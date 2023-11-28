<?php
    require_once "./clase/mesa.php";

    class mesaController
    {

       
        public function InsertarMesa($idMozo,$codigoNumerico,$estado){
            $mesa = new mesa();
            $mesa->idMozo = $idMozo;
            $mesa->codigoNumerico = $codigoNumerico;
            $mesa->estado = $estado;

            return $mesa->insertarMesaParametros();
        }

        public function modificarMesa($id,$idMozo,$codigoNumerico,$estado){
            $mesa = new mesa();
            $mesa->id = $id;
            $mesa->idMozo = $idMozo;
            $mesa->codigoNumerico = $codigoNumerico;
            $mesa->estado = $estado;
            return $mesa->modificarMesaParametros();
        }

        public function borrarMesa($id){
            $mesa = new mesa();
            $mesa->id = $id;
            return $mesa->borrarMesa();
        }

        public function listarMesas(){
            return mesa::traerTodosLasMesas();
        }

        public function buscarMesaPorId($id){
            $retorno = mesa::TraerUnaMesa($id);
            if($retorno === false) { // Validamos que exista y si no mostramos un error
                $retorno =  ['error' => 'No existe ese id'];
            }
            return $retorno;
        }

        //abm de mesas directamente con los request slim

        public function CrearMesa($request, $response){
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
        }


        public function traerMesas($request, $response){
            $mesasController = new mesaController();
            $listaMesas = $mesasController->listarMesas();
            $payload = json_encode($listaMesas);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function traerUnaMesa($request, $response,array $args){
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
        }

        public function modificarUnaMesa($request, $response,array $args){
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
        }

        public function eliminarUnaMesa($request, $response,array $args){
            $id = $args['id'];
            $mesaController = new mesaController();
            $retorno = $mesaController->borrarMesa($id);
            $payload = json_encode(array('Respuesta Eliminar' => "$retorno"));
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }
    }
    
?>