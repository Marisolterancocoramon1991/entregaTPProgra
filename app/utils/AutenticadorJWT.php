<?php
use Firebase\JWT\JWT;

class JwtUtil
{
    private static $claveSecreta = 'Kervin';
    private static $tipoEncriptacion = 'HS256';

    public static function CrearToken($datos)
    {
        $ahora = time();
        $payload = array(
            'iat' => $ahora,
            'exp' => $ahora + (600000),
            'aud' => self::Aud(),
            'data' => $datos,
        );
        return JWT::encode($payload, self::$claveSecreta, self::$tipoEncriptacion);
    }

    public static function CrearTokenUsuario($datos)
    {
        $datos->tipo = 'socio';
        $ahora = time();
        $payload = array(
            'iat' => $ahora,
            'exp' => $ahora + (600000),
            'aud' => self::Aud(),
            'data' => $datos,
        );
        return JWT::encode($payload, self::$claveSecreta, self::$tipoEncriptacion);
    }

    public static function VerificarToken($token)
    {
        if (empty($token)) {
            throw new Exception("El token esta vacio.");
        }
        try {
            $decodificado = JWT::decode(
                $token,
                self::$claveSecreta,
                array_keys(JWT::$supported_algs)
            );
        } catch (Exception $e) {
            throw $e;
        }
        if ($decodificado->aud !== self::Aud()) {
            throw new Exception("No Es un Usuario valido");
        }
    }


    public static function ObtenerPayLoad($token)
    {
        if (empty($token)) {
            throw new Exception("El token esta vacio");
        }
        return JWT::decode(
            $token,
            self::$claveSecreta,
            array_keys(JWT::$supported_algs)
        );
    }

    public static function ObtenerData($token)
    {
        return JWT::decode(
            $token,
            self::$claveSecreta,
            array_keys(JWT::$supported_algs)
        )->data;
    }

    private static function Aud()
    {
        $aud = '';

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $aud = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $aud = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $aud = $_SERVER['REMOTE_ADDR'];
        }

        $aud .= @$_SERVER['HTTP_USER_AGENT'];
        $aud .= gethostname();

        return sha1($aud);
    }
}
?>