<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Laravel\Passport\Passport;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call('key:generate');
        Artisan::call('passport:install');
    }

    /**
     * Test login with valid credentials.
     *
     * @return void
     */
    public function testLoginWithValidCredentials()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'phone' => '0788888888',
            'password' => bcrypt('password'),
        ]);

        $response = $this->postJson(route('login'), [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'user' => [
                    'id',
                    'name',
                    'email',
                    'phone',
                    'type',
                    'email_verified_at',
                ],
                'token',
            ]);
    }

    /**
     * Test login with invalid credentials.
     *
     * @return void
     */
    public function testLoginWithInvalidCredentials()
    {
        $response = $this->postJson(route('login'), [
            'email' => 'invalid@example.com',
            'password' => 'invalidpassword',
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJson([
                'error' => 'Invalid Email and Password',
            ]);
    }

    /**
     * Test login with missing fields.
     *
     * @return void
     */
    public function testLoginWithMissingFields()
    {
        $response = $this->postJson(route('login'), []);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                'message' => 'Validation error',
                'errors' => [
                    'email',
                    'password',
                ],
            ]);
    }
}
