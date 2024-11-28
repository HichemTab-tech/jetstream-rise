<?php

use HichemTabTech\JetstreamRise\Features;

// config for HichemTabTech/JetstreamRise
return [
    'stack' => 'inertia',
    'middleware' => ['web'],
    'features' => [Features::accountDeletion()],
    'profile_photo_disk' => 'public',
];
