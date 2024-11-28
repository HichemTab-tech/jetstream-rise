<?php

namespace App\Models;

use HichemTabTech\JetstreamRise\Jetstream;
use HichemTabTech\JetstreamRise\TeamInvitation as JetstreamTeamInvitation;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeamInvitation extends JetstreamTeamInvitation
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'role',
    ];

    /**
     * Get the team that the invitation belongs to.
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Jetstream::teamModel());
    }
}
