<?php

namespace HichemTabTech\JetstreamRise\Tests;

use App\Actions\Jetstream\CreateTeam;
use App\Models\Team;
use HichemTabTech\JetstreamRise\Jetstream;
use HichemTabTech\JetstreamRise\Tests\Fixtures\TeamPolicy;
use HichemTabTech\JetstreamRise\Tests\Fixtures\User;
use Illuminate\Support\Facades\Gate;

class CurrentTeamControllerTest extends OrchestraTestCase
{
    protected function defineEnvironment($app)
    {
        parent::defineEnvironment($app);

        $app['config']->set([
            'jetstream.stack' => 'livewire',
            'jetstream.features' => ['teams'],
        ]);

        Gate::policy(Team::class, TeamPolicy::class);
        Jetstream::useUserModel(User::class);
    }

    public function test_can_switch_to_team_the_user_belongs_to()
    {
        $action = new CreateTeam;

        $user = User::forceCreate([
            'name' => 'Taylor Otwell',
            'email' => 'taylor@laravel.com',
            'password' => 'secret',
        ]);

        $team = $action->create($user, ['name' => 'Test Team']);

        $response = $this->actingAs($user)->put('/current-team', ['team_id' => $team->id]);

        $response->assertRedirect('/home');

        $this->assertEquals($team->id, $user->fresh()->currentTeam->id);
        $this->assertTrue($user->isCurrentTeam($team));
    }

    public function test_cant_switch_to_team_the_user_does_not_belong_to()
    {
        $action = new CreateTeam;

        $user = User::forceCreate([
            'name' => 'Taylor Otwell',
            'email' => 'taylor@laravel.com',
            'password' => 'secret',
        ]);

        $team = $action->create($user, ['name' => 'Test Team']);

        $otherUser = User::forceCreate([
            'name' => 'Adam Wathan',
            'email' => 'adam@laravel.com',
            'password' => 'secret',
        ]);

        $response = $this->actingAs($otherUser)->put('/current-team', ['team_id' => $team->id]);

        $response->assertStatus(403);
    }
}
