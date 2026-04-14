<?php

namespace Tests\Unit;

use App\Http\Middleware\CheckRole;
use App\Models\User;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class InventoryControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(new User([
            'id' => 1,
            'name' => 'Test Admin',
            'email' => 'admin@example.com',
            'rolename' => 'admin',
            'password' => 'password',
        ]));

        $this->withoutMiddleware([
            Authenticate::class,
            EnsureEmailIsVerified::class,
            CheckRole::class,
        ]);
    }

    public function test_index_returns_inventory_view()
    {
        DB::shouldReceive('select')
            ->with('CALL GetInventory()')
            ->once()
            ->andReturn([
                (object) [
                    'InventoryId' => 1,
                    'ProductName' => 'Test Product',
                    'Barcode' => '123456789',
                    'Category' => 'Vegetables',
                    'Supplier' => 'Local Supplier',
                    'Quantity' => 50,
                    'ExpirationDate' => '2025-12-31',
                    'InventoryNote' => 'Test note',
                    'ProductNote' => 'Test product note',
                ],
            ]);

        $response = $this->get(route('inventory.index'));

        $response->assertStatus(200);
        $response->assertViewIs('Inventory.inventory');
    }

    public function test_index_shows_success_flash_message()
    {
        DB::shouldReceive('select')
            ->with('CALL GetInventory()')
            ->once()
            ->andReturn([]);

        $response = $this->withSession([
            'success' => 'Inventory item updated successfully.',
        ])->get(route('inventory.index'));

        $response->assertStatus(200);
        $response->assertSee('alert alert-success', false);
        $response->assertSee('Inventory item updated successfully.');
    }

    public function test_create_returns_create_view()
    {
        $response = $this->get(route('inventory.create'));

        $response->assertStatus(200);
        $response->assertViewIs('Inventory.create');
    }

    public function test_store_creates_inventory_item()
    {
        DB::shouldReceive('connection')->zeroOrMoreTimes()->andReturnSelf();
        DB::shouldReceive('table')->zeroOrMoreTimes()->andReturnSelf();
        DB::shouldReceive('useWritePdo')->zeroOrMoreTimes()->andReturnSelf();
        DB::shouldReceive('where')->zeroOrMoreTimes()->andReturnSelf();
        DB::shouldReceive('whereNull')->zeroOrMoreTimes()->andReturnSelf();
        DB::shouldReceive('count')->zeroOrMoreTimes()->andReturn(0);

        DB::shouldReceive('statement')
            ->with('CALL InsertInventory(?, ?, ?, ?, ?, ?, ?, ?)', \Mockery::any())
            ->once();

        $data = [
            'ProductName' => 'New Product',
            'Barcode' => '123456789',
            'Category' => 'Vegetables',
            'Supplier' => 'Local Supplier',
            'Quantity' => 50,
            'ExpirationDate' => '2025-12-31',
            'InventoryNote' => 'Test note',
            'ProductNote' => 'Test product note',
        ];

        $response = $this->post(route('inventory.store'), $data);

        $response->assertRedirect(route('inventory.index'));
        $response->assertSessionHas('success');
    }

    public function test_destroy_deletes_inventory_item()
    {
        DB::shouldReceive('statement')
            ->with('CALL DeleteInventoryById(?)', [1])
            ->once();

        $response = $this->delete(route('inventory.destroy', 1));

        $response->assertRedirect(route('inventory.index'));
        $response->assertSessionHas('success');
    }
}
