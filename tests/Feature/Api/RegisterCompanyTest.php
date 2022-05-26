<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterCompanyTest extends TestCase
{

    /**
     * test
     */
    public function test_company_register()
    {

    }

    /**
     * test
     */

    public function test_user_can_login()
    {
        $userData = ['email' => 'admin@gmail.com','password' => 'password'];

        $this->postJson('api/v1/login', $userData)->assertStatus(200);

    }

    /**
     * test
     */

    public function test_user_login_if_wrong_email_or_password()
    {
        $userData = ['email' => 'admin@test.com','password' => 'test'];

        $this->postJson('api/v1/login', $userData)->assertStatus(401);
    }


}
