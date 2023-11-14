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
            if (empty($params['codigoMesa']) || empty($params['dniMozo']) || empty($params["estado"]) || empty($params["tiempoOrden"]) || empty($params["tiempoMaximo"]) || empty($params["tiempoEntrega"])) {
                $response = new Response();
                $response->getBody()->write(json_encode(['Error' => 'Parametros Vacios']));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
            }
        }

        return $handler->handle($request);
    }

    public function verificarParametrosVaciosLoginUsuario($request,$handler): Response
    {
        $params = $request->getParsedBody();
        $requestType = $request->getMethod();
        if($requestType == 'POST'){
            if (empty($params['nombre']) || empty($params['apellido']) || empty($params["mail"]) || empty($params["clave"])) {
                $response = new Response();
                $response->getBody()->write(json_encode(['Error' => 'Parametros Vacios']));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
            }
        }

        return $handler->handle($request);
    }
}