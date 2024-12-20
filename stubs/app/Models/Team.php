<?php

namespace App\Models;

use HichemTabTech\JetstreamRise\Events\TeamCreated;
use HichemTabTech\JetstreamRise\Events\TeamDeleted;
use HichemTabTech\JetstreamRise\Events\TeamUpdated;
use HichemTabTech\JetstreamRise\Team as JetstreamTeam;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Team extends JetstreamTeam
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'personal_team',
    ];

    /**
     * The event map for the model.
     *
     * @var array<string, class-string>
     */
    protected $dispatchesEvents = [
        'created' => TeamCreated::class,
        'updated' => TeamUpdated::class,
        'deleted' => TeamDeleted::class,
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'personal_team' => 'boolean',
        ];
    }
}
