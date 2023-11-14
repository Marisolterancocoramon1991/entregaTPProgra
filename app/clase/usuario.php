<?php
    include_once "./db/accesoDatos.php";
class usuario{
    public $id;
    public $nombre;
    public $apellido;
    public $dni;
    public $estadoLaboral;
    public $edad;
    public $sector;
    public $mail;
    public $clave;

    public function __construct()
    {
        
    }

    public function constructorParametros($nombre,$apellido,$dni,$estadoLaboral,$edad,$sector,$mail,$clave)
    {
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->dni = $dni;
        $this->estadoLaboral = $estadoLaboral;
        $this->edad = $edad;
        $this->sector = $sector;
        $this->mail = $mail;
        $this->clave = $clave;
    }

    public function MostarDatos(){
        return "id {$this->id}<br>Nombre {$this->nombre}<br>Apellido {$this->apellido}<br>Dni {$this->dni}<br>Estado Laboral {$this->estadoLaboral}<br>Edad {$this->edad}<br>Sector {$this->sector}<br>Clave {$this->clave}<br>Mail {$this->mail}";
    }

    public function InsertarUsuario(){
        $objetoAccsesoDatos = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccsesoDatos->RetornarConsulta("INSERT INTO `usuarios`(`nombre`, `apellido`, `dni`, `estadoLaboral`, `edad`, `sector`, `clave`, `mail`) 
        VALUES ('$this->nombre','$this->apellido','$this->dni','$this->estadoLaboral','$this->edad','$this->sector', '$this->clave', '$this->mail')");
        $consulta->execute();
        return $objetoAccsesoDatos->RetornarUltimoIdInsertado();
    }

    public function insertarUsuarioParametros(){
        $objetoAccsesoDatos = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccsesoDatos->RetornarConsulta("INSERT INTO `usuarios`(`nombre`, `apellido`, `dni`, `estadoLaboral`, `edad`, `sector`, `clave`, `mail`) 
        VALUES (:nombre,:apellido,:dni,:estadoLaboral,:edad,:sector,:clave,:mail)");
        $consulta->bindValue(':nombre',$this->nombre,PDO::PARAM_STR);
        $consulta->bindValue(':apellido',$this->apellido,PDO::PARAM_STR);
        $consulta->bindValue(':dni',$this->dni,PDO::PARAM_INT);
        $consulta->bindValue(':estadoLaboral',$this->estadoLaboral,PDO::PARAM_STR);
        $consulta->bindValue(':edad',$this->edad,PDO::PARAM_INT);
        $consulta->bindValue(':sector',$this->sector,PDO::PARAM_STR);
        $consulta->bindValue(':clave',$this->clave,PDO::PARAM_STR);
        $consulta->bindValue(':mail',$this->mail,PDO::PARAM_STR);
        $consulta->execute();
        return $objetoAccsesoDatos->RetornarUltimoIdInsertado();
    }

    public static function traerTodosLosUsuarios(){
        $objetoAccsesoDatos = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccsesoDatos->RetornarConsulta("SELECT id, nombre, apellido, dni, estadoLaboral as 'estadoLaboral', edad , sector , clave , mail
        FROM `usuarios`");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS,"usuario");
    }

    public static function TraerUnUsuario($id){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT id, nombre, apellido, dni, estadoLaboral, edad , sector , clave , mail
        FROM `usuarios` 
        WHERE id = $id");
        $consulta->execute();
        $usuarioBuscado = $consulta->fetchObject("usuario");
        return $usuarioBuscado;
    }

    public static function TraerUnUsuarioMailClave($mail,$clave){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT id, nombre, apellido, dni, estadoLaboral, edad , sector , clave , mail
        FROM `usuarios` 
        WHERE mail = '$mail' AND clave = '$clave'");
        $consulta->execute();
        $usuarioBuscado = $consulta->fetchObject("usuario");
        return $usuarioBuscado;
    }

    public function modificarUsuario(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("UPDATE `usuarios` 
        SET `nombre`='$this->nombre',`apellido`='$this->apellido',`dni`= $this->dni,`estadoLaboral`='$this->estadoLaboral',`edad`= $this->edad,`sector`='$this->sector' ,`clave`='$this->clave',`mail`='$this->mail'
        WHERE id = '$this->id'");
        return $consulta->execute();
    }

    public function modificarUsuarioParametros(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("UPDATE `usuarios` 
        SET `nombre`= :nombre,`apellido`= :apellido,`dni`= :dni,`estadoLaboral`= :estadoLaboral,`edad`= :edad,`sector`= :sector ,`clave`= :clave,`mail`= :mail
        WHERE id = :id");

        $consulta->bindValue(':id',$this->id,PDO::PARAM_INT);
        $consulta->bindValue(':nombre',$this->nombre,PDO::PARAM_STR);
        $consulta->bindValue(':apellido',$this->apellido,PDO::PARAM_STR);  
        $consulta->bindValue(':dni',$this->dni,PDO::PARAM_INT);
        $consulta->bindValue(':estadoLaboral',$this->estadoLaboral,PDO::PARAM_STR);
        $consulta->bindValue(':edad',$this->edad,PDO::PARAM_INT);
        $consulta->bindValue(':sector',$this->sector,PDO::PARAM_STR);
        $consulta->bindValue(':clave',$this->clave,PDO::PARAM_STR);
        $consulta->bindValue(':mail',$this->mail,PDO::PARAM_STR);
        return $consulta->execute();
    }

    public function borrarUsuario(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("DELETE FROM `usuarios` 
        WHERE id = :id");

        $consulta->bindValue(':id',$this->id,PDO::PARAM_INT);
        $consulta->execute();
        return $consulta->rowCount();
    } 
}
?>