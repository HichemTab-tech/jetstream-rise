<?php

namespace HichemTabTech\JetstreamRise\Actions;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use HichemTabTech\JetstreamRise\Events\TeamMemberUpdated;
use HichemTabTech\JetstreamRise\Jetstream;
use HichemTabTech\JetstreamRise\Rules\Role;

class UpdateTeamMemberRole
{
    /**
     * Update the role for the given team member.
     *
     * @param  mixed  $user
     * @param  mixed  $team
     * @param mixed $teamMemberId
     * @param  string  $role
     * @return void
     */
    public function update(mixed $user, mixed $team, mixed $teamMemberId, string $role): void
    {
        Gate::forUser($user)->authorize('updateTeamMember', $team);

        Validator::make([
            'role' => $role,
        ], [
            'role' => ['required', 'string', new Role],
        ])->validate();

        $team->users()->updateExistingPivot($teamMemberId, [
            'role' => $role,
        ]);

        TeamMemberUpdated::dispatch($team->fresh(), Jetstream::findUserByIdOrFail($teamMemberId));
    }
}
