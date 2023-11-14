<?php
    use Slim\Psr7\Response;
    require_once "./clase/usuario.php";

    class usuarioController{
        public function InsertarUsuario($nombre,$apellido,$dni,$estadoLaboral,$edad,$sector,$clave,$mail){
            $usuario = new Usuario();
            $usuario->nombre = $nombre;
            $usuario->apellido = $apellido;
            $usuario->dni = $dni;
            $usuario->estadoLaboral = $estadoLaboral;
            $usuario->edad = $edad;
            $usuario->sector = $sector;
            $usuario->clave = $clave;
            $usuario->mail = $mail;

            return $usuario->insertarUsuarioParametros();
        }

        public function modificarUsuario($id,$nombre,$apellido,$dni,$estadoLaboral,$edad,$sector,$clave,$mail){
            $usuario = new Usuario();
            $usuario->id = $id;
            $usuario->nombre = $nombre;
            $usuario->apellido = $apellido;
            $usuario->dni = $dni;
            $usuario->estadoLaboral = $estadoLaboral;
            $usuario->edad = $edad;
            $usuario->sector = $sector;
            $usuario->clave = $clave;
            $usuario->mail = $mail;            
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
            if($retorno === false) { 
                $retorno =  ['error' => 'No existe ese id'];
            }
            return $retorno;
        }

        public function buscarUsuarioPorMailClave($mail,$clave){
            $usuario = usuario::TraerUnUsuarioMailClave($mail,$clave);
            if($usuario === false) { 
                $retorno =  ['error' => 'No existe ese Usuario'];
            } else {
                $retorno = ['Bienvenido' => "{$usuario->nombre} {$usuario->apellido}"];;
            }
            return $retorno;
        }

        public function LoggearUsuario($request, $response){
            $data = $request->getParsedBody();
            $nombre = $data["nombre"];
            $apellido = $data["apellido"];
            $mail = $data['mail'];
            $clave = $data['clave'];
        
            $usuarioController = new usuarioController();
            $retorno = $usuarioController->buscarUsuarioPorMailClave($mail,$clave);
            $payload = $payload = json_encode(array($retorno));;
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }

        
        public function verificarMailClaveUsuarioAbmMw($request,$handler): Response{
            $data = $request->getParsedBody();
            $nombre = $data["nombre"];
            $apellido = $data["apellido"];
            $mail = $data['mail'];
            $clave = $data['clave'];

            $usuarioController = new usuarioController();
            $retorno = $usuarioController->buscarUsuarioPorMailClave($mail,$clave);

            if($retorno == ['error' => 'No existe ese Usuario']) {
                $response = new Response();
                $response->getBody()->write(json_encode(['Error' => 'Usuario No Registrado']));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
            }

            return $handler->handle($request);
        }


      

        public function CrearUsuario($request, $response){
            $data = $request->getParsedBody();
            $nombre = $data['nombre'];
            $apellido = $data['apellido'];
            $dni = $data['dni'];
            $estadoLaboral = $data['estadoLaboral'];
            $edad = $data['edad'];
            $sector = $data['sector'];
            $clave = $data['clave'];
            $mail = $data['mail'];
            $usuario = new usuario();
            $usuario->constructorParametros($nombre,$apellido,$dni,$estadoLaboral,$edad,$sector,$clave,$mail);
        
            $usuarioController = new usuarioController();
            $respuesta = $usuarioController->InsertarUsuario($nombre,$apellido,$dni,$estadoLaboral,$edad,$sector,$clave,$mail);
            //retorno el id del usuario Ingresado
            $respuestaJson = json_encode(['resultado' => $respuesta]);
            $payload = json_encode($respuestaJson);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function traerUsuarios($request, $response){
            $usuarioController = new usuarioController();
            $listaUsuarios = $usuarioController->listarUsuarios();
            $payload = json_encode($listaUsuarios);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function traerUnUsuario($request, $response,array $args){
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
        }

        public function modificarUnUsuario($request, $response,array $args){
            $data = $request->getParsedBody();
            $id = $args['id'];
            $nombre = $data['nombre'];
            $apellido = $data['apellido'];
            $dni = $data['dni'];
            $estadoLaboral = $data['estadoLaboral'];
            $edad = $data['edad'];
            $sector = $data['sector'];
            $clave = $data['clave'];
            $mail = $data['mail'];
        
            $usuario = usuario::TraerUnUsuario($id);
            if ($usuario != false) {
                $usuarioController = new usuarioController();
                $resultado = $usuarioController->modificarUsuario($id,$nombre,$apellido,$dni,$estadoLaboral,$edad,$sector,$clave,$mail);
                $payload = json_encode(array("Resultado Modificar" => $resultado));
                $response->getBody()->write($payload);
                return $response->withHeader('Content-Type', 'application/json');
            } else {
                $mensajeError = json_encode(array('Error' => 'Usuario No encontrado'));
                $response->getBody()->write($mensajeError);
                return $response->withHeader('Content-Type', 'application/json');
            }
        }

        public function eliminarUnUsuario($request, $response,array $args){
            $id = $args['id'];
            $usuarioController = new usuarioController();
            $retorno = $usuarioController->borrarUsuario($id);
            $payload = json_encode(array('Respuesta Eliminar' => "$retorno"));
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }
     
    }
?>