<?php

namespace HichemTabTech\JetstreamRise\Tests;

use App\Actions\Jetstream\CreateTeam;
use App\Actions\Jetstream\DeleteTeam;
use App\Models\Team;
use HichemTabTech\JetstreamRise\Actions\ValidateTeamDeletion;
use HichemTabTech\JetstreamRise\Jetstream;
use HichemTabTech\JetstreamRise\Tests\Fixtures\TeamPolicy;
use HichemTabTech\JetstreamRise\Tests\Fixtures\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class DeleteTeamTest extends OrchestraTestCase
{
    protected function defineEnvironment($app)
    {
        parent::defineEnvironment($app);

        Gate::policy(Team::class, TeamPolicy::class);
        Jetstream::useUserModel(User::class);
    }

    public function test_team_can_be_deleted()
    {
        $team = $this->createTeam();

        $action = new DeleteTeam;

        $action->delete($team);

        $this->assertNull($team->fresh());
    }

    public function test_team_deletion_can_be_validated()
    {
        Jetstream::useUserModel(User::class);

        $team = $this->createTeam();

        $action = new ValidateTeamDeletion;

        $action->validate($team->owner, $team);

        $this->assertTrue(true);
    }

    public function test_personal_team_cant_be_deleted()
    {
        $this->expectException(ValidationException::class);

        Jetstream::useUserModel(User::class);

        $team = $this->createTeam();

        $team->forceFill(['personal_team' => true])->save();

        $action = new ValidateTeamDeletion;

        $action->validate($team->owner, $team);
    }

    public function test_non_owner_cant_delete_team()
    {
        $this->expectException(AuthorizationException::class);

        Jetstream::useUserModel(User::class);

        $team = $this->createTeam();

        $action = new ValidateTeamDeletion;

        $action->validate(User::forceCreate([
            'name' => 'Adam Wathan',
            'email' => 'adam@laravel.com',
            'password' => 'secret',
        ]), $team);
    }

    protected function createTeam()
    {
        $action = new CreateTeam;

        $user = User::forceCreate([
            'name' => 'Taylor Otwell',
            'email' => 'taylor@laravel.com',
            'password' => 'secret',
        ]);

        return $action->create($user, ['name' => 'Test Team']);
    }
}
