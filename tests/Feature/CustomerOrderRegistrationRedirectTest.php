<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerOrderRegistrationRedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_unregistered_customer_is_redirected_to_registration_when_opening_order_create(): void
    {
        $user = User::factory()->create([
            'rolename' => 'customer',
        ]);

        $response = $this->actingAs($user)->get(route('customersorders.create', $user->id));

        $response->assertRedirect(route('customersregistration.index'));
        $response->assertSessionHas('error');
    }
}
