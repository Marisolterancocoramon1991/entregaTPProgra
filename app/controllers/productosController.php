<?php
    require_once "./clase/productos.php";

    class productoController{

        public function InsertarProducto($nombre,$precio,$tiempoElaboracion,$sector){
            $producto = new producto();
            $producto->nombre = $nombre;
            $producto->precio = $precio;
            $producto->tiempoElaboracion = $tiempoElaboracion;
            $producto->sector = $sector;

            return $producto->insertarProductoParametros();
        }

        public function modificarProducto($id,$nombre,$precio,$tiempoElaboracion,$sector){
            $producto = new producto();
            $producto->id = $id;
            $producto->nombre = $nombre;
            $producto->precio = $precio;
            $producto->tiempoElaboracion = $tiempoElaboracion;
            $producto->sector = $sector;

            return $producto->modificarProductoParametros();
        }

        public function borrarProducto($id){
            $producto = new producto();
            $producto->id = $id;

            return $producto->borrarProducto();
        }

        public function listarProductos(){
            return producto::traerTodosLosProductos();
        }

        public function buscarProductoPorId($id){
            $retorno = producto::TraerUnProducto($id);
            if($retorno === false) { // Validamos que exista y si no mostramos un error
                $retorno =  ['error' => 'No existe ese id'];
            }
            return $retorno;
        }

        //abm de productos directamente con los request slim

        public function CrearProducto($request, $response){
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
        }

        public function traerProductos($request, $response){
            $productosController = new productoController();
            $listaProductos = $productosController->listarProductos();
            $payload = json_encode($listaProductos);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function traerUnProducto($request, $response,array $args){
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
        }

        public function modificarUnProducto($request, $response,array $args){
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
        }

        public function eliminarUnProducto($request, $response,array $args){
            $id = $args['id'];
            $productoController = new productoController();
            $retorno = $productoController->borrarProducto($id);
            $payload = json_encode(array('Respuesta Eliminar' => "$retorno"));
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }
    }
?>