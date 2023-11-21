<?php
    require_once "./clase/pedidos.php";

    class pedidosController{

        public function InsertarPedido($codigoPedido,$productoId,$mesaId, $usuarioId,$estado,$tiempoOrden,$tiempoMaximo,$tiempoEntrega){
            $pedido = new pedido();
            $pedido->codigoPedido = $codigoPedido;
            $pedido->productoId = $productoId;
            $pedido->mesaId = $mesaId;
            $pedido->usuarioId = $usuarioId;
            $pedido->estado = $estado;
            $pedido->tiempoOrden = $tiempoOrden;
            $pedido->tiempoMaximo = $tiempoMaximo;
            $pedido->tiempoEntrega = $tiempoEntrega;

            return $pedido->insertarPedidoParametros();
        }

        public function modificarPedido($id,$codigoPedido,$productoId,$mesaId, $usuarioId,$estado,$tiempoOrden,$tiempoMaximo,$tiempoEntrega){
            $pedido = new pedido();
            $pedido->id = $id;
            $pedido->codigoPedido = $codigoPedido;
            $pedido->productoId = $productoId;
            $pedido->mesaId = $mesaId;
            $pedido->usuarioId = $usuarioId;
            $pedido->estado = $estado;
            $pedido->tiempoOrden = $tiempoOrden;
            $pedido->tiempoMaximo = $tiempoMaximo;
            $pedido->tiempoEntrega = $tiempoEntrega;

            return $pedido->modificarPedidosParametros();
        }

        public function borrarPedido($id){
            $pedido = new pedido();
            $pedido->id = $id;

            return $pedido->borrarPedido();
        }

        public function listarPedidos(){
            return pedido::traerTodosLosPedidos();
        }

        public function buscarPedidoPorId($id){
            $retorno = pedido::TraerUnPedido($id);
            if($retorno === false) { // Validamos que exista y si no mostramos un error
                $retorno =  ['error' => 'No existe ese id'];
            }
            return $retorno;
        }

        //abm de pedidos directamente con los request slim
        public function CrearPedido($request, $response){
            $data = $request->getParsedBody();
            $codigoPedido = $data['codigoPedido'];
            $productoId = $data['productoId'];
            $mesaId = $data['mesaId'];
            $usuarioId = $data['usuarioId'];
            $estado = $data['estado'];
            $tiempoOrden = $data['tiempoOrden'];
            $tiempoMaximo = $data['tiempoMaximo'];
            $tiempoEntrega = $data['tiempoEntrega'];
        
            $pedido = new pedido();
            $pedido->constructorParametros($codigoPedido,$productoId,$mesaId,$usuarioId,$estado,$tiempoOrden,$tiempoEntrega,$tiempoMaximo);
        
            $pedidosController = new pedidosController();
            $respuesta = $pedidosController->InsertarPedido($codigoPedido,$productoId,$mesaId,$usuarioId,$estado,$tiempoOrden,$tiempoMaximo,$tiempoEntrega);
            //retorno el id del usuario Ingresado
            $respuestaJson = json_encode(['resultado' => $respuesta]);
            $payload = json_encode($respuestaJson);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function traerPedidos($request, $response){
            $pedidosController = new pedidosController();
            $listaPedidos = $pedidosController->listarPedidos();
            $payload = json_encode($listaPedidos);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function traerUnPedido($request, $response,array $args){
            $id = $args['id'];
            $pedido = pedido::TraerUnPedido($id);
            if ($pedido != false) {
                $payload = json_encode($pedido);
                $response->getBody()->write($payload);
                return $response->withHeader('Content-Type', 'application/json');
            } else {
                $mensajeError = json_encode(array('Error' => 'Pedido No encontrado'));
                $response->getBody()->write($mensajeError);
                return $response->withHeader('Content-Type', 'application/json');
            }
        }

        public function modificarUnPedido($request, $response,array $args){
            $data = $request->getParsedBody();
            $id = $args['id'];
            $codigoPedido = $data['codigoPedido'];
            $productoId = $data['productoId'];
            $mesaId = $data['mesaId'];
            $usuarioId = $data['usuarioId'];
            $estado = $data['estado'];
            $tiempoOrden = $data['tiempoOrden'];
            $tiempoMaximo = $data['tiempoMaximo'];
            $tiempoEntrega = $data['tiempoEntrega'];
            
            $pedido = pedido::TraerUnPedido($id);
            if ($pedido != false) {
                $pedidoController = new pedidosController();
                $resultado = $pedidoController->modificarPedido($id,$codigoPedido,$productoId,$mesaId,$usuarioId,$estado,$tiempoOrden,$tiempoMaximo,$tiempoEntrega);
                $payload = json_encode(array("Resultado Modificar" => $resultado));
                $response->getBody()->write($payload);
                return $response->withHeader('Content-Type', 'application/json');
            } else {
                $mensajeError = json_encode(array('Error' => 'Pedido No encontrado'));
                $response->getBody()->write($mensajeError);
                return $response->withHeader('Content-Type', 'application/json');
            }
        }

        public function eliminarUnPedido($request, $response,array $args){
            $id = $args['id'];
            $pedidoController = new pedidosController();
            $retorno = $pedidoController->borrarPedido($id);
            $payload = json_encode(array('Respuesta Eliminar' => "$retorno"));
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }

        public static function parsearCsvPedidos($archivoRutaCSV)
        {
            $listaPedidos = [];
            $esPrimeraIteracion = true;

            if (($archivo = fopen($archivoRutaCSV, 'r')) !== false) {
                while (($data = fgetcsv($archivo, 1000, ',')) !== false) {
                    if ($esPrimeraIteracion) {
                        $esPrimeraIteracion = false;
                        continue;
                    }

                    $pedido = new pedido();
                    $pedido->constructorParametros( $data[0], $data[1], $data[2], $data[3], 
                    $data[4], $data[5], $data[6], $data[7]);
                   

                    $listaPedidos[] = $pedido;
                }

                fclose($archivo);
            }

            return $listaPedidos;
        }

        public function CargarPedidosCSV($request, $response)
        {
            $archivo = $request->getUploadedFiles()["pedidosCSV"];
            if ($archivo){
                $nombre = $archivo->getClientFileName();
                $destino = "./db/" . $nombre;
                $archivo->moveTo($destino);
        
                pedido::CargarPedidosCSV($destino);
                $payload = json_encode(array("Respuesta" => "pedidos cargados a la base de datos"));
                $response->getBody()->write($payload);
                return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
            } else {
                $payload = json_encode(array("Respuesta" => "El Archivo no esta"));
                $response->getBody()->write($payload);
                return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
            }
        
        }

        public function DescargaPedidosCSV($request, $response)
        {
            $pedidos = pedido::traerTodosLosPedidosArays();
            pedido::DescargaPedidosCSV($pedidos);

            readfile("./db/dataPedidos.csv");
            return $response->withHeader('Content-Type', 'text/csv')->withAddedHeader("Content-disposition", "attachment; filename=dataPedidos.csv")->withStatus(200);
        }
    }
?>