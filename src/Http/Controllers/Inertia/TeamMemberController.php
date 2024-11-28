<?php

namespace HichemTabTech\JetstreamRise\Http\Controllers\Inertia;

use HichemTabTech\JetstreamRise\Actions\UpdateTeamMemberRole;
use HichemTabTech\JetstreamRise\Contracts\AddsTeamMembers;
use HichemTabTech\JetstreamRise\Contracts\InvitesTeamMembers;
use HichemTabTech\JetstreamRise\Contracts\RemovesTeamMembers;
use HichemTabTech\JetstreamRise\Features;
use HichemTabTech\JetstreamRise\Jetstream;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class TeamMemberController extends Controller
{
    /**
     * Add a new team member to a team.
     *
     * @param  int  $teamId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, $teamId)
    {
        $team = Jetstream::newTeamModel()->findOrFail($teamId);

        if (Features::sendsTeamInvitations()) {
            app(InvitesTeamMembers::class)->invite(
                $request->user(),
                $team,
                $request->email ?: '',
                $request->role
            );
        } else {
            app(AddsTeamMembers::class)->add(
                $request->user(),
                $team,
                $request->email ?: '',
                $request->role
            );
        }

        return back(303);
    }

    /**
     * Update the given team member's role.
     *
     * @param  int  $teamId
     * @param  int  $userId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $teamId, $userId)
    {
        app(UpdateTeamMemberRole::class)->update(
            $request->user(),
            Jetstream::newTeamModel()->findOrFail($teamId),
            $userId,
            $request->role
        );

        return back(303);
    }

    /**
     * Remove the given user from the given team.
     *
     * @param  int  $teamId
     * @param  int  $userId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $teamId, $userId)
    {
        $team = Jetstream::newTeamModel()->findOrFail($teamId);

        app(RemovesTeamMembers::class)->remove(
            $request->user(),
            $team,
            $user = Jetstream::findUserByIdOrFail($userId)
        );

        if ($request->user()->id === $user->id) {
            return redirect(config('fortify.home'));
        }

        return back(303);
    }
}
