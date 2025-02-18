<?php

namespace HichemTabTech\JetstreamRise\Tests;

use App\Actions\Jetstream\CreateTeam;
use App\Models\Team;
use HichemTabTech\JetstreamRise\Contracts\AddsTeamMembers;
use HichemTabTech\JetstreamRise\Jetstream;
use HichemTabTech\JetstreamRise\Tests\Fixtures\TeamPolicy;
use HichemTabTech\JetstreamRise\Tests\Fixtures\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;

class TeamInvitationControllerTest extends OrchestraTestCase
{
    protected function defineEnvironment($app)
    {
        parent::defineEnvironment($app);

        $app['config']->set('jetstream.stack', 'inertia');
        $app['config']->set('jetstream.features', ['teams']);

        Gate::policy(Team::class, TeamPolicy::class);
        Jetstream::useUserModel(User::class);
    }

    public function test_team_invitations_can_be_accepted()
    {
        $this->mock(AddsTeamMembers::class)->shouldReceive('add')->once();

        Jetstream::role('admin', 'Admin', ['foo', 'bar']);
        Jetstream::role('editor', 'Editor', ['baz', 'qux']);

        $team = $this->createTeam();

        $invitation = $team->teamInvitations()->create(['email' => 'adam@laravel.com', 'role' => 'admin']);

        $url = URL::signedRoute('team-invitations.accept', ['invitation' => $invitation]);

        $response = $this->actingAs($team->owner)->get($url);

        $response->assertRedirect();
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
