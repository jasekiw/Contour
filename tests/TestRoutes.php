<?php

use App\Models\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TestRoutes extends TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testBasicExample()
    {
        $user = User::first();
        $this->be($user);
        $this->visit('/')
             ->see('Welcome Back');

    }
    public function testLogin()
    {
        $this->visit('/')
            ->see('Login');
    }
    public function testProfile()
    {
        $user = User::first();
        $this->be($user);
        $this->visit('/profile')
            ->assertResponseOk();
    }
    public function testMenu()
    {
        $user = User::first();
        $this->be($user);
        $this->visit('/menu')
            ->assertResponseOk();
    }
    public function testGroups()
    {
        $user = User::first();
        $this->be($user);
        $this->visit('/menu')
            ->assertResponseOk();
    }
    public function testTags()
    {
        $user = User::first();
        $this->be($user);
        $this->visit('/tags')
            ->assertResponseOk();
    }
    public function testSheet()
    {
        $user = User::first();
        $this->be($user);
        $this->visit('/sheets/1473')
            ->assertResponseOk();
    }
    public function testImport()
    {
        $user = User::first();
        $this->be($user);
        $this->visit('/import')
            ->assertResponseOk();
    }
}
