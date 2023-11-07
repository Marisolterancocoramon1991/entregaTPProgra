<?php
    require_once "./clase/usuario.php";

    class usuarioController{

        public function InsertarUsuario($nombre,$apellido,$dni,$estadoLaboral,$edad,$sector){
            $usuario = new Usuario();
            $usuario->nombre = $nombre;
            $usuario->apellido = $apellido;
            $usuario->dni = $dni;
            $usuario->estadoLaboral = $estadoLaboral;
            $usuario->edad = $edad;
            $usuario->sector = $sector;

            return $usuario->insertarUsuarioParametros();
        }

        public function modificarUsuario($id,$nombre,$apellido,$dni,$estadoLaboral,$edad,$sector){
            $usuario = new Usuario();
            $usuario->id = $id;
            $usuario->nombre = $nombre;
            $usuario->apellido = $apellido;
            $usuario->dni = $dni;
            $usuario->estadoLaboral = $estadoLaboral;
            $usuario->edad = $edad;
            $usuario->sector = $sector;
            return $usuario->modificarUsuarioParametros();
        }

        public function borrarUsuario($id){
            $usuario = new Usuario();
            $usuario->id = $id;
            return $usuario->borrarUsuario();
        }

        public function listarUsuarios(){
            return usuario::traerTodosLosUsuarios();
        }

        public function buscarUsuarioPorId($id){
            $retorno = usuario::TraerUnUsuario($id);
            if($retorno === false) { // Validamos que exista y si no mostramos un error
                $retorno =  ['error' => 'No existe ese id'];
            }
            return $retorno;
        }
    }
?>