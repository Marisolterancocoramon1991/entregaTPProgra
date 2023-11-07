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

require __DIR__ . '/../vendor/autoload.php';

// Instantiate App
$app = AppFactory::create();

//comando de consola para abrilo en el puerto 8080
//php -S localhost:8080 -t app


// Add error middleware
$app->addErrorMiddleware(true, true, true);

// Add parse body
$app->addBodyParsingMiddleware();

// Routes
//usuarios
$app->post('/crearUsuario', function (Request $request, Response $response) {
    $data = $request->getParsedBody();
    $nombre = $data['nombre'];
    $apellido = $data['apellido'];
    $dni = $data['dni'];
    $estadoLaboral = $data['estadoLaboral'];
    $edad = $data['edad'];
    $sector = $data['sector'];

    $usuario = new usuario();
    $usuario->constructorParametros($nombre,$apellido,$dni,$estadoLaboral,$edad,$sector);

    $usuarioController = new usuarioController();
    $respuesta = $usuarioController->InsertarUsuario($nombre,$apellido,$dni,$estadoLaboral,$edad,$sector);
    //retorno el id del usuario Ingresado
    $respuestaJson = json_encode(['resultado' => $respuesta]);
    $payload = json_encode($respuestaJson);
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});


$app->get('/usuarios', function (Request $request, Response $response) {
    $usuarioController = new usuarioController();
    $listaUsuarios = $usuarioController->listarUsuarios();
    $payload = json_encode($listaUsuarios);
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});


$app->get('/usuario/{id}', function (Request $request, Response $response, array $args) {
    $id = $args['id'];
    $usuario = usuario::TraerUnUsuario($id);
    if ($usuario != false) {
        $payload = json_encode($usuario);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    } else {
        $mensajeError = json_encode(array('Error' => 'Usuario No encontrado'));
        $response->getBody()->write($mensajeError);
        return $response->withHeader('Content-Type', 'application/json');
    }
});

$app->post('/modificarUsuario/{id}', function (Request $request, Response $response,array $args) {
    $data = $request->getParsedBody();
    $id = $args['id'];
    $nombre = $data['nombre'];
    $apellido = $data['apellido'];
    $dni = $data['dni'];
    $estadoLaboral = $data['estadoLaboral'];
    $edad = $data['edad'];
    $sector = $data['sector'];
    
    $usuario = usuario::TraerUnUsuario($id);
    if ($usuario != false) {
        $usuarioController = new usuarioController();
        $resultado = $usuarioController->modificarUsuario($id,$nombre,$apellido,$dni,$estadoLaboral,$edad,$sector);
        $payload = json_encode(array("Resultado Modificar" => $resultado));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    } else {
        $mensajeError = json_encode(array('Error' => 'Usuario No encontrado'));
        $response->getBody()->write($mensajeError);
        return $response->withHeader('Content-Type', 'application/json');
    }
});

$app->delete('/usuario/{id}', function (Request $request, Response $response, array $args) {
    $id = $args['id'];
    $usuarioController = new usuarioController();
    $retorno = $usuarioController->borrarUsuario($id);
    $payload = json_encode(array('Respueta Eliminar' => "$retorno"));
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});


//mesas
$app->post('/crearMesa', function (Request $request, Response $response) {
    $data = $request->getParsedBody();
    $idMozo = $data['idMozo'];
    $codigoNumerico = $data['codigoNumerico'];
    $estado = $data['estado'];

    $mesa = new mesa();
    $mesa->constructorParametros($idMozo,$codigoNumerico,$estado);

    $mesaController = new mesaController();
    $respuesta = $mesaController->InsertarMesa($idMozo,$codigoNumerico,$estado);
    //retorno el id del usuario Ingresado
    $respuestaJson = json_encode(['resultado' => $respuesta]);
    $payload = json_encode($respuestaJson);
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});


$app->get('/mesas', function (Request $request, Response $response) {
    $mesasController = new mesaController();
    $listaMesas = $mesasController->listarMesas();
    $payload = json_encode($listaMesas);
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});


$app->get('/mesas/{id}', function (Request $request, Response $response, array $args) {
    $id = $args['id'];
    $mesa = mesa::TraerUnaMesa($id);
    if ($mesa != false) {
        $payload = json_encode($mesa);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    } else {
        $mensajeError = json_encode(array('Error' => 'Mesa No encontrada'));
        $response->getBody()->write($mensajeError);
        return $response->withHeader('Content-Type', 'application/json');
    }
});

$app->post('/modificarMesa/{id}', function (Request $request, Response $response,array $args) {
    $data = $request->getParsedBody();
    $id = $args['id'];
    $idMozo = $data['idMozo'];
    $codigoNumerico = $data['codigoNumerico'];
    $estado = $data['estado'];
    
    $mesa = mesa::TraerUnaMesa($id);
    if ($mesa != false) {
        $mesaController = new mesaController();
        $resultado = $mesaController->modificarMesa($id,$idMozo,$codigoNumerico,$estado);
        $payload = json_encode(array("Resultado Modificar" => $resultado));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    } else {
        $mensajeError = json_encode(array('Error' => 'Mesa No encontrada'));
        $response->getBody()->write($mensajeError);
        return $response->withHeader('Content-Type', 'application/json');
    }
});

$app->delete('/mesa/{id}', function (Request $request, Response $response, array $args) {
    $id = $args['id'];
    $mesaController = new mesaController();
    $retorno = $mesaController->borrarMesa($id);
    $payload = json_encode(array('Respueta Eliminar' => "$retorno"));
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});

//productos
$app->post('/crearProducto', function (Request $request, Response $response) {
    $data = $request->getParsedBody();
    $nombre = $data['nombre'];
    $precio = $data['precio'];
    $tiempoElaboracion = $data['tiempoElaboracion'];
    $sector = $data['sector'];

    $producto = new producto();
    $producto->constructorParametros($nombre,$precio,$tiempoElaboracion,$sector);

    $productoController = new productoController();
    $respuesta = $productoController->InsertarProducto($nombre,$precio,$tiempoElaboracion,$sector);
    //retorno el id del usuario Ingresado
    $respuestaJson = json_encode(['resultado' => $respuesta]);
    $payload = json_encode($respuestaJson);
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});


$app->get('/productos', function (Request $request, Response $response) {
    $productosController = new productoController();
    $listaProductos = $productosController->listarProductos();
    $payload = json_encode($listaProductos);
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});


$app->get('/productos/{id}', function (Request $request, Response $response, array $args) {
    $id = $args['id'];
    $producto = producto::TraerUnProducto($id);
    if ($producto != false) {
        $payload = json_encode($producto);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    } else {
        $mensajeError = json_encode(array('Error' => 'Producto No encontrado'));
        $response->getBody()->write($mensajeError);
        return $response->withHeader('Content-Type', 'application/json');
    }
});

$app->post('/modificarProducto/{id}', function (Request $request, Response $response,array $args) {
    $data = $request->getParsedBody();
    $id = $args['id'];
    $nombre = $data['nombre'];
    $precio = $data['precio'];
    $tiempoElaboracion = $data['tiempoElaboracion'];
    $sector = $data['sector'];
    
    $producto = producto::TraerUnProducto($id);
    if ($producto != false) {
        $productoController = new productoController();
        $resultado = $productoController->modificarProducto($id,$nombre,$precio,$tiempoElaboracion,$sector);
        $payload = json_encode(array("Resultado Modificar" => $resultado));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    } else {
        $mensajeError = json_encode(array('Error' => 'Producto No encontrado'));
        $response->getBody()->write($mensajeError);
        return $response->withHeader('Content-Type', 'application/json');
    }
});

$app->delete('/producto/{id}', function (Request $request, Response $response, array $args) {
    $id = $args['id'];
    $productoController = new productoController();
    $retorno = $productoController->borrarProducto($id);
    $payload = json_encode(array('Respueta Eliminar' => "$retorno"));
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});

//pedidos falta hacer
/*
$app->post('/crearPedido', function (Request $request, Response $response) {
    $data = $request->getParsedBody();
    $nombre = $data['nombre'];
    $precio = $data['precio'];
    $tiempoElaboracion = $data['tiempoElaboracion'];
    $sector = $data['sector'];

    $producto = new producto();
    $producto->constructorParametros($nombre,$precio,$tiempoElaboracion,$sector);

    $productoController = new productoController();
    $respuesta = $productoController->InsertarProducto($nombre,$precio,$tiempoElaboracion,$sector);
    //retorno el id del usuario Ingresado
    $respuestaJson = json_encode(['resultado' => $respuesta]);
    $payload = json_encode($respuestaJson);
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});


$app->get('/pedidos', function (Request $request, Response $response) {
    $productosController = new productoController();
    $listaProductos = $productosController->listarProductos();
    $payload = json_encode($listaProductos);
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});


$app->get('/pedidos/{id}', function (Request $request, Response $response, array $args) {
    $id = $args['id'];
    $producto = producto::TraerUnProducto($id);
    if ($producto != false) {
        $payload = json_encode($producto);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    } else {
        $mensajeError = json_encode(array('Error' => 'Producto No encontrado'));
        $response->getBody()->write($mensajeError);
        return $response->withHeader('Content-Type', 'application/json');
    }
});

$app->post('/modificarPedidos/{id}', function (Request $request, Response $response,array $args) {
    $data = $request->getParsedBody();
    $id = $args['id'];
    $nombre = $data['nombre'];
    $precio = $data['precio'];
    $tiempoElaboracion = $data['tiempoElaboracion'];
    $sector = $data['sector'];
    
    $producto = producto::TraerUnProducto($id);
    if ($producto != false) {
        $productoController = new productoController();
        $resultado = $productoController->modificarProducto($id,$nombre,$precio,$tiempoElaboracion,$sector);
        $payload = json_encode(array("Resultado Modificar" => $resultado));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    } else {
        $mensajeError = json_encode(array('Error' => 'Producto No encontrado'));
        $response->getBody()->write($mensajeError);
        return $response->withHeader('Content-Type', 'application/json');
    }
});

$app->delete('/pedido/{id}', function (Request $request, Response $response, array $args) {
    $id = $args['id'];
    $productoController = new productoController();
    $retorno = $productoController->borrarProducto($id);
    $payload = json_encode(array('Respueta Eliminar' => "$retorno"));
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});
*/

$app->run();