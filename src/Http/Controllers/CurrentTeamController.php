<?php

namespace HichemTabTech\JetstreamRise\Http\Controllers;

use HichemTabTech\JetstreamRise\Jetstream;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CurrentTeamController extends Controller
{
    /**
     * Update the authenticated user's current team.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $team = Jetstream::newTeamModel()->findOrFail($request->team_id);

        if (! $request->user()->switchTeam($team)) {
            abort(403);
        }

        return redirect(config('fortify.home'), 303);
    }
}
