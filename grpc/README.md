# gRPC — sistema de inventario

Servidor gRPC en Node.js que consulta el endpoint interno protegido de Laravel
(`GET /api/interno/productos/{id}`) usando la cabecera `X-Internal-Key`.

## Instalación

```sh
npm install
```

## Configuración

La clave se toma de la variable de entorno `GRPC_INTERNAL_KEY` y debe coincidir
con `GRPC_INTERNAL_KEY` del archivo `.env` de Laravel:

```
GRPC_INTERNAL_KEY=clave-local-larga-de-prueba
```

## Ejecución

```sh
# 1. Servidor Laravel (en otra terminal, desde la raíz del proyecto)
php artisan serve

# 2. Servidor gRPC (Linux/macOS)
GRPC_INTERNAL_KEY=clave-local-larga-de-prueba node server.cjs

# 3. Cliente Node
node client.cjs 18          # consulta el producto con id 18
```

## Cliente Python (opcional)

Requiere instalar `grpcio` y `grpcio-tools`:

```sh
python3 -m pip install grpcio grpcio-tools

# Generar los stubs dentro de grpc/
python3 -m grpc_tools.protoc -I. --python_out=. --grpc_python_out=. producto.proto

# Ejecutar el cliente
python3 client.py
```

## Estados de error

- `NOT_FOUND (5)`: el producto no existe en Laravel.
- `UNAVAILABLE (14)`: falló la conexión con el backend Laravel.
- `INTERNAL (13)`: otro error HTTP distinto de 404/200.
