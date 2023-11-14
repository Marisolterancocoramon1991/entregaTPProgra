<?php
    require_once "./clase/pedidos.php";

    class pedidosController{

        public function InsertarPedido($codigoMesa,$dniMozo,$estado,$tiempoOrden,$tiempoMaximo,$tiempoEntrega){
            $pedido = new pedido();
            $pedido->codigoMesa = $codigoMesa;
            $pedido->dniMozo = $dniMozo;
            $pedido->estado = $estado;
            $pedido->tiempoOrden = $tiempoOrden;
            $pedido->tiempoMaximo = $tiempoMaximo;
            $pedido->tiempoEntrega = $tiempoEntrega;

            return $pedido->insertarPedidoParametros();
        }

        public function modificarPedido($id,$codigoMesa,$dniMozo,$estado,$tiempoOrden,$tiempoMaximo,$tiempoEntrega){
            $pedido = new pedido();
            $pedido->id = $id;
            $pedido->codigoMesa = $codigoMesa;
            $pedido->dniMozo = $dniMozo;
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
            $codigoMesa = $data['codigoMesa'];
            $dniMozo = $data['dniMozo'];
            $estado = $data['estado'];
            $tiempoOrden = $data['tiempoOrden'];
            $tiempoMaximo = $data['tiempoMaximo'];
            $tiempoEntrega = $data['tiempoEntrega'];
        
            $pedido = new pedido();
            $pedido->constructorParametros($codigoMesa,$dniMozo,$estado,$tiempoOrden,$tiempoMaximo,$tiempoEntrega);
        
            $pedidosController = new pedidosController();
            $respuesta = $pedidosController->InsertarPedido($codigoMesa,$dniMozo,$estado,$tiempoOrden,$tiempoMaximo,$tiempoEntrega);
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
            $codigoMesa = $data['codigoMesa'];
            $dniMozo = $data['dniMozo'];
            $estado = $data['estado'];
            $tiempoOrden = $data['tiempoOrden'];
            $tiempoMaximo = $data['tiempoMaximo'];
            $tiempoEntrega = $data['tiempoEntrega'];
            
            $pedido = pedido::TraerUnPedido($id);
            if ($pedido != false) {
                $pedidoController = new pedidosController();
                $resultado = $pedidoController->modificarPedido($id,$codigoMesa,$dniMozo,$estado,$tiempoOrden,$tiempoMaximo,$tiempoEntrega);
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
    }
?>