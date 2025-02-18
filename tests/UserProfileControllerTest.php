<?php

namespace HichemTabTech\JetstreamRise\Tests;

use HichemTabTech\JetstreamRise\Jetstream;
use HichemTabTech\JetstreamRise\Tests\Fixtures\User;
use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;
use Laravel\Fortify\Features;
use Mockery as m;

class UserProfileControllerTest extends OrchestraTestCase
{
    protected function defineEnvironment($app)
    {
        parent::defineEnvironment($app);

        $app['config']->set([
            'jetstream.stack' => 'inertia',
            'fortify.features' => [
                Features::registration(),
                Features::resetPasswords(),
                // Features::emailVerification(),
                Features::updateProfileInformation(),
                Features::updatePasswords(),
                Features::twoFactorAuthentication([
                    'confirm' => true,
                    'confirmPassword' => true,
                ]),
            ],
        ]);

        Jetstream::useUserModel(User::class);
    }

    public function test_empty_two_factor_state_is_noted()
    {
        $disable = $this->mock(DisableTwoFactorAuthentication::class);
        $disable->shouldReceive('__invoke')->once();

        Jetstream::$inertiaManager = $inertia = m::mock();
        $inertia->shouldReceive('render')->once();

        $user = User::forceCreate([
            'name' => 'Taylor Otwell',
            'email' => 'taylor@laravel.com',
            'password' => 'secret',
        ]);

        $response = $this->actingAs($user)->get('/user/profile');

        $response->assertSessionHas('two_factor_empty_at');

        $response->assertStatus(200);
    }

    public function test_two_factor_is_not_disabled_if_was_previously_empty_and_currently_confirming()
    {
        $disable = $this->mock(DisableTwoFactorAuthentication::class);
        $disable->shouldReceive('__invoke')->never();

        Jetstream::$inertiaManager = $inertia = m::mock();
        $inertia->shouldReceive('render')->once();

        $user = User::forceCreate([
            'name' => 'Taylor Otwell',
            'email' => 'taylor@laravel.com',
            'password' => 'secret',
            'two_factor_secret' => 'test-secret',
        ]);

        $response = $this->actingAs($user)
            ->withSession(['two_factor_empty_at' => time()])
            ->get('/user/profile');

        $response->assertStatus(200);
    }

    public function test_two_factor_is_disabled_if_was_previously_confirming_and_page_is_reloaded()
    {
        $disable = $this->mock(DisableTwoFactorAuthentication::class);
        $disable->shouldReceive('__invoke')->once();

        Jetstream::$inertiaManager = $inertia = m::mock();
        $inertia->shouldReceive('render')->once();

        $user = User::forceCreate([
            'name' => 'Taylor Otwell',
            'email' => 'taylor@laravel.com',
            'password' => 'secret',
            'two_factor_secret' => 'test-secret',
        ]);

        $response = $this->actingAs($user)
            ->withSession([
                'two_factor_empty_at' => time(),
                'two_factor_confirming_at' => time() - 10,
            ])
            ->get('/user/profile');

        $response->assertStatus(200);
    }
}
