<?php

use App\Models\User;
use HichemTabTech\JetstreamRise\Http\Livewire\LogoutOtherBrowserSessionsForm;
use Livewire\Livewire;

test('other browser sessions can be logged out', function () {
    $this->actingAs(User::factory()->create());

    Livewire::test(LogoutOtherBrowserSessionsForm::class)
        ->set('password', 'password')
        ->call('logoutOtherBrowserSessions')
        ->assertSuccessful();
});
