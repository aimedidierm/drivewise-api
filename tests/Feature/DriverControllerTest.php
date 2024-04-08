<?php

namespace Tests\Feature;

use App\Enums\UserType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class DriverControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('passport:install');
    }

    /**
     * Test fetching all drivers.
     *
     * @return void
     */
    public function testListDrivers()
    {
        $user = User::factory()->create(['type' => UserType::DRIVER->value]);

        $response = $this->actingAs($user, 'api')->getJson(route('admin.driver.index'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['drivers']);
    }

    /**
     * Test creating a new driver.
     *
     * @return void
     */
    public function testStoreDriver()
    {
        $userData = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'password' => 'password',
        ];

        $response = $this->postJson(route('admin.driver.store'), $userData);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'message' => 'Driver created',
                'driver' => [
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'phone' => $userData['phone'],
                ],
            ]);
    }

    /**
     * Test updating a driver.
     *
     * @return void
     */
    public function testUpdateDriver()
    {
        $user = User::factory()->create(['type' => UserType::DRIVER->value]);
        $userData = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'password' => 'password',
        ];

        $response = $this->actingAs($user, 'api')->putJson(route('admin.driver.update') . $user->id, $userData);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'message' => 'Driver updated',
                'driver' => [
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'phone' => $userData['phone'],
                ],
            ]);
    }

    /**
     * Test deleting a driver.
     *
     * @return void
     */
    public function testDeleteDriver()
    {
        $user = User::factory()->create(['type' => UserType::DRIVER->value]);

        $response = $this->actingAs($user, 'api')->deleteJson(route('admin.driver.destroy') . $user->id);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'message' => 'Driver deleted',
            ]);
    }
}
