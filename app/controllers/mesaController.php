<?php
    require_once "./clase/mesa.php";

    class mesaController{

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
    }
?>