<?php

use App\Models\User;
use HichemTabTech\JetstreamRise\Features;
use HichemTabTech\JetstreamRise\Http\Livewire\ApiTokenManager;
use Illuminate\Support\Str;
use Livewire\Livewire;

test('api tokens can be deleted', function () {
    if (Features::hasTeamFeatures()) {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());
    } else {
        $this->actingAs($user = User::factory()->create());
    }

    $token = $user->tokens()->create([
        'name' => 'Test Token',
        'token' => Str::random(40),
        'abilities' => ['create', 'read'],
    ]);

    Livewire::test(ApiTokenManager::class)
        ->set(['apiTokenIdBeingDeleted' => $token->id])
        ->call('deleteApiToken');

    expect($user->fresh()->tokens)->toHaveCount(0);
})->skip(function () {
    return ! Features::hasApiFeatures();
}, 'API support is not enabled.');
