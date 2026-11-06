<?php

namespace Tests\Feature;

use App\Models\Producto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductoApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_lista_productos(): void
    {
        Producto::factory()->count(3)->create();

        $this->getJson('/api/productos')
            ->assertOk()
            ->assertJsonCount(3);
    }

    public function test_crea_producto(): void
    {
        $this->postJson('/api/productos', [
            'nombre' => 'Teclado',
            'precio' => 25000,
            'stock' => 12,
        ])->assertCreated()
            ->assertJsonFragment(['nombre' => 'Teclado']);

        $this->assertDatabaseHas('productos', ['nombre' => 'Teclado']);
    }

    public function test_validacion_falla_con_422(): void
    {
        $this->postJson('/api/productos', ['nombre' => ''])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['nombre', 'precio', 'stock']);
    }

    public function test_muestra_producto_inexistente_devuelve_404(): void
    {
        $this->getJson('/api/productos/999')->assertNotFound();
    }

    public function test_actualiza_stock_con_patch(): void
    {
        $producto = Producto::factory()->create(['stock' => 10]);

        $this->patchJson("/api/productos/{$producto->id}", ['stock' => 11])
            ->assertOk()
            ->assertJsonFragment(['stock' => 11]);
    }

    public function test_elimina_producto_devuelve_204(): void
    {
        $producto = Producto::factory()->create();

        $this->deleteJson("/api/productos/{$producto->id}")
            ->assertNoContent();

        $this->assertDatabaseMissing('productos', ['id' => $producto->id]);
    }
}
