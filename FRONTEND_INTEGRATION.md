# LÚMINA — guía de integración frontend

## Alcance completado

- Sistema visual responsive en `resources/css/app.css`.
- Interactividad accesible en `resources/js/app.js`: navegación móvil, sidebar, búsqueda local, mostrar contraseña, alertas y confirmación de eliminación.
- Layout público y layout de panel reutilizables.
- Componentes Blade para marca, iconos, alertas, estados vacíos y tarjetas de producto.
- Página principal, login y panel por rol.
- CRUD visual completo de categorías y productos, conectado a las rutas y campos existentes.
- Prototipos adaptables para catálogo, detalle, carrito, checkout e inventario.
- CRUD visual de clientes conectado a las rutas y campos incorporados en el backend.

## Integraciones existentes respetadas

| Módulo | Vista | Ruta/datos actuales |
| --- | --- | --- |
| Inicio | `welcome.blade.php` | `GET /` |
| Login | `auth/login.blade.php` | `login`, `login.submit`; campos `correo`, `contrasena` |
| Panel | `panel.blade.php` | `GET /panel`; recibe `nombre`; usa `id_rol` solo para presentación |
| Categorías | `categorias/*` | Rutas `categorias.*`; campos `nombre`, `descripcion` |
| Productos | `productos/*` | Rutas `productos.*`; campos `nombre`, `description`, `precio`, `stock`, `id_categoria` |
| Clientes | `clientes/*` | Rutas `clientes.*`; campos `nombre`, `correo`, `telefono` |

No se modificaron controladores, modelos, rutas, migraciones, middleware, configuración de autenticación, consultas ni validaciones.

## Contratos pendientes para Zahid

### Catálogo público

Vista: `resources/views/store/catalog.blade.php`

Necesita una ruta GET y estas variables:

- `$productos`: colección de productos con relación `categoria`.
- `$categorias`: colección de categorías.
- `$catalogAction`: URL del formulario de filtros.
- `$productUrls`: arreglo indexado por ID de producto con la URL de detalle.

Filtros visuales esperados: `buscar`, `categorias[]` y `orden`. El backend define su lógica y validación.

### Detalle de producto

Vista: `resources/views/store/product.blade.php`

Necesita `$producto` y, cuando exista el carrito, `$cartAction`. El formulario visual usa `id_producto` y `cantidad`; confirma estos nombres al implementar el backend.

### Carrito

Vista: `resources/views/store/cart.blade.php`

Necesita `$items`, `$subtotal`, `$total` y `$checkoutUrl`. Cada ítem visual espera `producto`, `cantidad` y `subtotal`. No hay rutas ni operaciones creadas desde Blade.

### Checkout

Vista: `resources/views/store/checkout.blade.php`

Los controles están deshabilitados intencionalmente. Antes de activarlos se requieren la ruta de envío, los nombres reales de campos de cliente/dirección y las reglas de validación.

### Inventario

Vista: `resources/views/admin/inventario.blade.php`.

Es un estado de integración, sin rutas inventadas. Debe conectarse cuando existan su controlador, permisos y estructura de datos.

### Imagen de producto

El modelo `Producto` incluye `imagen`, y los listados ya la muestran cuando tiene valor. Los formularios no incluyen carga de archivo porque `ProductoController` todavía no valida ni almacena imágenes.

## Ejecución

1. `composer install`
2. Copiar `.env.example` a `.env` y configurar la base existente.
3. `php artisan key:generate`
4. `npm install`
5. `npm run build`
6. Ejecutar con Laragon o `php artisan serve`.

En el equipo de revisión, Composer no pudo completar descargas por un certificado interceptado por Avast/Laragon (`curl error 60`). No se cambió la configuración global del equipo para evitar una solución insegura.
