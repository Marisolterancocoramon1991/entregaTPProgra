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
    }
?>