<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    public function test_string_form()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(env('APP_URL') . '/login')
                ->assertPathIs('/login')
                ->assertSeeIn('form', "Remember Me")
                ->assertSeeIn('form', "Don't have an account?")
                ->assertSeeIn('form', "Sign Up");
        });
    }

    public function test_login_fail()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(env('APP_URL') . '/login')
                ->type('email', 'stephon.mohr@yahoo.com')
                ->type('password', '123')
                ->press('#login-btn')
                ->assertPathIs('/login');
        });
    }

    public function test_click_register()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(env('APP_URL') . '/login')
                ->click('#sign-up')
                ->assertPathIs('/register');
        });
    }

    public function test_login_success()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(env('APP_URL') . '/login')
                ->type('email', 'minhquang31011998@gmail.com')
                ->type('password', '12345678')
                ->press('#login-btn')
                ->assertPathIs('/home');
        });
    }
}
