<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Household;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CustomerRegistrationFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_first_customer_registration_redirects_to_dashboard(): void
    {
        $user = User::factory()->create([
            'rolename' => 'customer',
        ]);

        $token = 'test-csrf-token';

        $response = $this->actingAs($user)->withSession(['_token' => $token])->post(route('customersregistration.store'), [
            '_token' => $token,
            'first_name' => 'Klant',
            'last_name' => 'Tester',
            'phone' => '0612345678',
            'street' => 'Teststraat',
            'house_number' => '12',
            'postal_code' => '1234AB',
            'city' => 'Utrecht',
            'total_members' => 3,
        ]);

        $response->assertRedirect(route('dashboard'));
    }

    public function test_existing_customer_update_redirects_to_registration_index(): void
    {
        $user = User::factory()->create([
            'rolename' => 'customer',
        ]);

        $address = Address::create([
            'Street' => 'Oude Straat',
            'HouseNumber' => '1',
            'PostalCode' => '1111AA',
            'City' => 'Amsterdam',
            'is_active' => true,
        ]);

        DB::table('Client')->insert([
            'Id' => $user->id,
            'FirstName' => 'Bestaand',
            'LastName' => 'Klant',
            'Phone' => '0600000000',
            'AddressId' => $address->Id,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Household::create([
            'ClientId' => $user->id,
            'TotalMembers' => 2,
            'RegistrationDate' => now(),
            'is_active' => true,
        ]);

        $token = 'test-csrf-token';

        $response = $this->actingAs($user)->withSession(['_token' => $token])->post(route('customersregistration.store'), [
            '_token' => $token,
            'first_name' => 'Nieuw',
            'last_name' => 'Naam',
            'phone' => '0699999999',
            'street' => 'Nieuwe Straat',
            'house_number' => '22',
            'postal_code' => '2222BB',
            'city' => 'Rotterdam',
            'total_members' => 4,
        ]);

        $response->assertRedirect(route('customersregistration.index'));
    }
}
