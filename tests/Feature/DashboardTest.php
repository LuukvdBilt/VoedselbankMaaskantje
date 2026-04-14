<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_the_login_page(): void
    {
        $response = $this->get(route('dashboard'));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_users_can_visit_the_dashboard(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('dashboard'));
        $response->assertOk();
    }

    public function test_customer_sees_only_customer_actions_in_navigation(): void
    {
        $user = User::factory()->create([
            'rolename' => 'customer',
        ]);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertOk();
        $response->assertSee('registratie');
        $response->assertSee('Naar bestellen');
        $response->assertDontSee('Magazijn');
        $response->assertDontSee('Leverancier Overzicht');
    }

    public function test_employee_sees_employee_actions_in_navigation(): void
    {
        $user = User::factory()->create([
            'rolename' => 'manager',
        ]);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertOk();
        $response->assertSee('Magazijn');
        $response->assertSee('Leverancier Overzicht');
        $response->assertDontSee('Klant Bestellen');
        $response->assertDontSee('Klantregistratie');
    }

    public function test_supplier_sees_employee_actions_in_navigation(): void
    {
        $user = User::factory()->create([
            'rolename' => 'supplier',
        ]);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertOk();
        $response->assertSee('Magazijn');
        $response->assertSee('Leverancier Overzicht');
        $response->assertDontSee('Klant Bestellen');
        $response->assertDontSee('Klantregistratie');
    }
}
