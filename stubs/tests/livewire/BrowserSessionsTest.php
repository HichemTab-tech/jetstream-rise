<?php

namespace Tests\Feature;

use App\Models\User;
use HichemTabTech\JetstreamRise\Http\Livewire\LogoutOtherBrowserSessionsForm;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class BrowserSessionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_other_browser_sessions_can_be_logged_out(): void
    {
        $this->actingAs(User::factory()->create());

        Livewire::test(LogoutOtherBrowserSessionsForm::class)
            ->set('password', 'password')
            ->call('logoutOtherBrowserSessions')
            ->assertSuccessful();
    }
}
