<?php

namespace App\GraphQL\Mutations;

use App\Models\Producto;
use App\Services\ProductoService;

class ProductoMutator
{
    public function __construct(private ProductoService $servicio) {}

    public function crear(mixed $root, array $args): Producto
    {
        return $this->servicio->crear($args);
    }

    public function stock(mixed $root, array $args): Producto
    {
        $producto = $this->servicio->buscar((int) $args['id']);

        return $this->servicio->cambiarStock($producto, (int) $args['stock']);
    }
}