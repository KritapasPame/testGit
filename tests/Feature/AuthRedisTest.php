<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Service\AuthRedis;

class AuthRedisTest extends TestCase
{
    public function testLoginSuccess()
    {
        $email = 'test1@example.com';
        $password = 'password123';

        // Mock the User model
        $user = User::factory()->create([
            'email' => $email,
            'password' => bcrypt($password), // Ensure the password is hashed
        ]);
        $this->assertModelExists($user);
        // Create instance of class that interacts with Redis
        $authRedis = new AuthRedis();

        // Perform login
        $result = $authRedis->login($email, $password);
        $this->assertTrue($result);

        // Perform logout
        $result = $authRedis->logout();
        $this->assertNull($result);
    }

    public function testLoginFail()
    {
        $email = 'test1@example.com';
        $password = 'wrongpassword';

        // Create instance of class that interacts with Redis
        $authRedis = new AuthRedis();
        // Perform login
        $result = $authRedis->login($email, $password);
        $this->assertFalse($result);
    }

    public function testLoginNotFoundUser()
    {
        $email = 'test1@example.com';
        $password = 'password123';
        $user = User::where('email', $email)->first();
        $user->delete(); // Clean up the test user
        $this->assertModelMissing($user);

        // Create instance of class that interacts with Redis
        $authRedis = new AuthRedis();
        // Perform login
        $result = $authRedis->login($email, $password);
        $this->assertFalse($result);
    }
}
