<?php

use App\Http\Controllers\Api\ProductoController;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::apiResource('productos', ProductoController::class);

// Endpoint interno para el servicio gRPC: protegido por clave compartida.
Route::get('/interno/productos/{producto}', function (Request $request, Producto $producto) {
    $clave = (string) config('services.grpc.internal_key', '');

    abort_if(
        $clave === '' || ! hash_equals($clave, (string) $request->header('X-Internal-Key')),
        403
    );

    return response()->json($producto);
});
