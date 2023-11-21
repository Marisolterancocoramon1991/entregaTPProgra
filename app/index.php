<?php
// Error Handling
error_reporting(-1);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;


require_once "./clase/usuario.php";
require_once "./controllers/usuariosController.php";

require_once "./clase/mesa.php";
require_once "./controllers/mesaController.php";

require_once "./clase/productos.php";
require_once "./controllers/productosController.php";

require_once "./clase/pedidos.php";
require_once "./controllers/pedidosController.php";

require_once "./middlewares/Logger.php";
require_once "./middlewares/AuthJWT.php";

require __DIR__ . '/../vendor/autoload.php';

// Instantiate App
$app = AppFactory::create();

//comando de consola para abrilo en el puerto 8080aas
//php -S localhost:8080 -t app
//usuarios : añadir mail, clave
//composer require firebase/php-jwt:^4.0
// crear archivos de csv en el link https://mockaroo.com/
// Add error middleware
$app->addErrorMiddleware(true, true, true);

// Add parse body
$app->addBodyParsingMiddleware();


//Login de Usuarios
$app->post('/LoggearUsuario', [\UsuarioController::class, 'LoggearUsuario'])
->add(\Logger::class . ':verificarParametrosVaciosLoginUsuario');


//mesas
$app->group('/mesas', function (RouteCollectorProxy $group) {
    $group->post('/crear', \mesaController::class . ':CrearMesa');
    $group->get('/traerTodas', \mesaController::class . ':traerMesas');
    $group->get('/traerUna/{id}', \mesaController::class . ':traerUnaMesa');
    $group->post('/modificar/{id}', \mesaController::class . ':modificarUnaMesa');
    $group->delete('/eliminar/{id}', \mesaController::class . ':eliminarUnaMesa');
})->add(\Logger::class . ':verificarParametrosVaciosMesa')->add(\AuthJWT::class . ':VerificarTokenValido'); 

//productos
$app->group('/productos', function (RouteCollectorProxy $group) {
    $group->post('/crear', \productoController::class . ':CrearProducto');
    $group->get('/traerTodos', \productoController::class . ':traerProductos');
    $group->get('/traerUno/{id}', \productoController::class . ':traerUnProducto');
    $group->post('/modificar/{id}', \productoController::class . ':modificarUnProducto');
    $group->delete('/eliminar/{id}', \productoController::class . ':eliminarUnProducto');
})->add(\Logger::class . ':verificarParametrosVaciosProducto')->add(\AuthJWT::class . ':VerificarTokenValido'); 


//pedidos 
$app->group('/pedidos', function (RouteCollectorProxy $group) {
    $group->post('/crear', \pedidosController::class . ':CrearPedido');
    $group->get('/traerTodos', \pedidosController::class . ':traerPedidos');
    $group->get('/traerUno/{id}', \pedidosController::class . ':traerUnPedido');
    $group->post('/modificar/{id}', \pedidosController::class . ':modificarUnPedido');
    $group->delete('/eliminar/{id}', \pedidosController::class . ':eliminarUnPedido');
})->add(\Logger::class . ':verificarParametrosVaciosPedido')->add(\AuthJWT::class . ':VerificarTokenValido'); 

//usuarios
$app->group('/usuarios', function (RouteCollectorProxy $group) {
    $group->post('/crear', \usuarioController::class . ':CrearUsuario');
    $group->get('/traerTodos', \usuarioController::class . ':traerUsuarios');
    $group->get('/traerUno/{id}', \usuarioController::class . ':traerUnUsuario');
    $group->post('/modificarUsuario/{id}', \usuarioController::class . ':modificarUnUsuario');
    $group->delete('/eliminar/{id}', \usuarioController::class . ':eliminarUnUsuario');
})->add(\Logger::class . ':verificarParametrosVaciosUsuario')->add(\AuthJWT::class . ':VerificarTokenValido'); 

$app->group('/CSV', function (RouteCollectorProxy $group) {
    $group->post("/cargarPedidos", \pedidosController::class . ':CargarPedidosCSV');
    $group->post("/descargaPedidos", \pedidosController::class . ':DescargaPedidosCSV');
})->add(\AuthJWT::class . ':VerificarTokenValido');


$app->run();