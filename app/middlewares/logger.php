<?php

use Slim\Psr7\Response;
class Logger
{

    public function verificarParametrosVaciosUsuario($request, $handler): Response
    {
        $params = $request->getParsedBody();
        $requestType = $request->getMethod();

        if ($requestType == 'POST') {
            if (empty($params['nombre']) || empty($params['apellido']) || empty($params["dni"]) || empty($params["estadoLaboral"]) || empty($params["edad"]) || empty($params["sector"]) || empty($params["clave"]) || empty($params["mail"])) {
                $response = new Response();
                $response->getBody()->write(json_encode(['Error' => 'Parametros Vacios']));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
            }
        }

        return $handler->handle($request);
    }


    public function verificarParametrosVaciosMesa($request, $handler): Response
    {
        $params = $request->getParsedBody();
        $requestType = $request->getMethod();

        if ($requestType == 'POST') {
            if (empty($params['idMozo']) || empty($params['codigoNumerico']) || empty($params["estado"])) {
                $response = new Response();
                $response->getBody()->write(json_encode(['Error' => 'Parametros Vacios']));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
            }
        }

        return $handler->handle($request);
    }

    public function verificarParametrosVaciosProducto($request, $handler): Response
    {
        $params = $request->getParsedBody();
        $requestType = $request->getMethod();

        if ($requestType == 'POST') {
            if (empty($params['nombre']) || empty($params['precio']) || empty($params["tiempoElaboracion"]) || empty($params["sector"])) {
                $response = new Response();
                $response->getBody()->write(json_encode(['Error' => 'Parametros Vacios']));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
            }
        }

        return $handler->handle($request);
    }

    public function verificarParametrosVaciosPedido($request, $handler): Response
    {
        $params = $request->getParsedBody();
        $requestType = $request->getMethod();
        if ($requestType == 'POST') {
            if (empty($params['codigoPedido']) || empty($params['productoId']) || empty($params["mesaId"]) || empty($params["usuarioId"]) || empty($params["estado"]) || empty($params["tiempoOrden"]) || empty($params["tiempoMaximo"]) || empty($params["tiempoEntrega"])) {
                $response = new Response();
                $response->getBody()->write(json_encode(['Error' => 'Parametros Vacios']));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
            }
        }

        return $handler->handle($request);
    }


    public function verificarParametrosVaciosLoginUsuario($request, $response, $handler): Response
    {
        $params = $request->getParsedBody();
        $mail = $params["mail"];
        $clave = $params["clave"];

        $usuario = usuario::TraerUnUsuarioMailClave($mail, $clave);

        // Obtén una nueva instancia de Response
        $response = $handler->handle($request); 

        if ($usuario != null) {
            $data = JwtUtil::CrearToken($usuario);
            $payload = json_encode(array("Datos usuario" => $data));
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } else {
            $payload = json_encode(array("Respuesta" => "No existe el Usuario"));
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
    }

}