<?php

namespace Tests\Feature;

use App\Models\Producto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductoGrpcInternoTest extends TestCase
{
    use RefreshDatabase;

    public function test_endpoint_interno_rechaza_sin_clave(): void
    {
        $producto = Producto::factory()->create();

        $this->getJson("/api/interno/productos/{$producto->id}")
            ->assertForbidden();
    }

    public function test_endpoint_interno_rechaza_clave_incorrecta(): void
    {
        $producto = Producto::factory()->create();

        $this->getJson("/api/interno/productos/{$producto->id}", [
            'X-Internal-Key' => 'clave-erronea',
        ])->assertForbidden();
    }

    public function test_endpoint_interno_responde_con_clave_valida(): void
    {
        config(['services.grpc.internal_key' => 'clave-local-larga-de-prueba']);

        $producto = Producto::factory()->create();

        $this->getJson("/api/interno/productos/{$producto->id}", [
            'X-Internal-Key' => 'clave-local-larga-de-prueba',
        ])->assertOk()
            ->assertJsonFragment(['nombre' => $producto->nombre]);
    }
}
