<?php

namespace HichemTabTech\JetstreamRise\Tests;

use App\Actions\Jetstream\CreateTeam;
use HichemTabTech\JetstreamRise\Jetstream;
use HichemTabTech\JetstreamRise\Team;
use HichemTabTech\JetstreamRise\Tests\Fixtures\TeamPolicy;
use HichemTabTech\JetstreamRise\Tests\Fixtures\User;
use Illuminate\Support\Facades\Gate;
use Laravel\Sanctum\Sanctum;
use Laravel\Sanctum\TransientToken;

class TeamBehaviorTest extends OrchestraTestCase
{
    protected function defineEnvironment($app)
    {
        parent::defineEnvironment($app);

        Gate::policy(\App\Models\Team::class, TeamPolicy::class);
        Jetstream::useUserModel(User::class);
    }

    public function test_team_relationship_methods()
    {
        $action = new CreateTeam;

        $user = User::forceCreate([
            'name' => 'Taylor Otwell',
            'email' => 'taylor@laravel.com',
            'password' => 'secret',
        ]);

        $team = $action->create($user, ['name' => 'Test Team']);

        $this->assertInstanceOf(Team::class, $team);

        $this->assertTrue($user->belongsToTeam($team));
        $this->assertTrue($user->ownsTeam($team));
        $this->assertCount(1, $user->fresh()->ownedTeams);
        $this->assertCount(1, $user->fresh()->allTeams());

        $team->forceFill(['personal_team' => true])->save();

        $this->assertEquals($team->id, $user->fresh()->personalTeam()->id);
        $this->assertEquals($team->id, $user->fresh()->currentTeam->id);
        $this->assertTrue($user->hasTeamPermission($team, 'foo'));

        // Test with another user that isn't on the team...
        $otherUser = User::forceCreate([
            'name' => 'Adam Wathan',
            'email' => 'adam@laravel.com',
            'password' => 'secret',
        ]);

        $this->assertFalse($otherUser->belongsToTeam($team));
        $this->assertFalse($otherUser->ownsTeam($team));
        $this->assertFalse($otherUser->hasTeamPermission($team, 'foo'));

        // Add the other user to the team...
        Jetstream::role('editor', 'Editor', ['foo']);

        $otherUser->teams()->attach($team, ['role' => 'editor']);
        $otherUser = $otherUser->fresh();

        $this->assertTrue($otherUser->belongsToTeam($team));
        $this->assertFalse($otherUser->ownsTeam($team));

        $this->assertTrue($otherUser->hasTeamPermission($team, 'foo'));
        $this->assertFalse($otherUser->hasTeamPermission($team, 'bar'));

        $this->assertTrue($team->userHasPermission($otherUser, 'foo'));
        $this->assertFalse($team->userHasPermission($otherUser, 'bar'));

        $otherUser->withAccessToken(new TransientToken);

        $this->assertTrue($otherUser->belongsToTeam($team));
        $this->assertFalse($otherUser->ownsTeam($team));

        $this->assertTrue($otherUser->hasTeamPermission($team, 'foo'));
        $this->assertFalse($otherUser->hasTeamPermission($team, 'bar'));

        $this->assertTrue($team->userHasPermission($otherUser, 'foo'));
        $this->assertFalse($team->userHasPermission($otherUser, 'bar'));
    }

    public function test_has_team_permission_checks_token_permissions()
    {
        Jetstream::role('admin', 'Administrator', ['foo']);

        $action = new CreateTeam;

        $user = User::forceCreate([
            'name' => 'Taylor Otwell',
            'email' => 'taylor@laravel.com',
            'password' => 'secret',
        ]);

        $team = $action->create($user, ['name' => 'Test Team']);

        $adam = User::forceCreate([
            'name' => 'Adam Wathan',
            'email' => 'adam@laravel.com',
            'password' => 'secret',
        ]);

        $authToken = new Sanctum;
        $adam = $authToken->actingAs($adam, ['bar'], []);

        $team->users()->attach($adam, ['role' => 'admin']);

        $this->assertFalse($adam->hasTeamPermission($team, 'foo'));

        $john = User::forceCreate([
            'name' => 'John Doe',
            'email' => 'john@doe.com',
            'password' => 'secret',
        ]);

        $authToken = new Sanctum;
        $john = $authToken->actingAs($john, ['foo'], []);

        $team->users()->attach($john, ['role' => 'admin']);

        $this->assertTrue($john->hasTeamPermission($team, 'foo'));
    }

    public function test_user_does_not_need_to_refresh_after_switching_teams()
    {
        $action = new CreateTeam;

        $user = User::forceCreate([
            'name' => 'Taylor Otwell',
            'email' => 'taylor@laravel.com',
            'password' => 'secret',
        ]);

        $personalTeam = $action->create($user, ['name' => 'Personal Team']);

        $personalTeam->forceFill(['personal_team' => true])->save();

        $this->assertTrue($user->isCurrentTeam($personalTeam));

        $anotherTeam = $action->create($user, ['name' => 'Test Team']);

        $this->assertTrue($user->isCurrentTeam($anotherTeam));
    }
}
