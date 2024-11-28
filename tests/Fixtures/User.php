<?php

namespace HichemTabTech\JetstreamRise\Tests\Fixtures;

use App\Models\User as BaseUser;
use HichemTabTech\JetstreamRise\HasProfilePhoto;
use HichemTabTech\JetstreamRise\HasTeams;
use Laravel\Sanctum\HasApiTokens;

class User extends BaseUser
{
    use HasApiTokens, HasTeams, HasProfilePhoto;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
}
