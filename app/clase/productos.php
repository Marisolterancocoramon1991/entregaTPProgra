<?php
    include_once "./db/accesoDatos.php";

    class producto{
        public $id;
        public $nombre;
        public $precio;
        public $tiempoElaboracion;
        public $sector;

        public function __construct()
        {
            
        }

        public function constructorParametros($nombre,$precio,$tiempoElaboracion,$sector){
            $this->nombre = $nombre;
            $this->precio = $precio;
            $this->tiempoElaboracion = $tiempoElaboracion;
            $this->sector = $sector;
        }

        public function MostrarDatos(){
            return "Id {$this->id}<br>Nombre {$this->nombre}<br>Precio {$this->tiempoElaboracion}<br>Sector {$this->sector}";
        }

        public function InsertarProducto(){
            $objetoAccsesoDatos = AccesoDatos::dameUnObjetoAcceso();
            $consulta = $objetoAccsesoDatos->RetornarConsulta("INSERT INTO `productos`(`nombre`, `precio`, `tiempoElaboracion`, `sector`) 
            VALUES ('$this->nombre','$this->precio','$this->tiempoElaboracion','$this->sector')");
            $consulta->execute();
            return $objetoAccsesoDatos->RetornarUltimoIdInsertado();
        }

        public function insertarProductoParametros(){
            $objetoAccsesoDatos = AccesoDatos::dameUnObjetoAcceso();
            $consulta = $objetoAccsesoDatos->RetornarConsulta("INSERT INTO `productos`(`nombre`, `precio`, `tiempoElaboracion`, `sector`) 
            VALUES (:nombre,:precio ,:tiempoElaboracion, :sector)");
            $consulta->bindValue(':nombre',$this->nombre,PDO::PARAM_STR);
            $consulta->bindValue(':precio',$this->precio,PDO::PARAM_INT);
            $consulta->bindValue(':tiempoElaboracion',$this->tiempoElaboracion,PDO::PARAM_STR);
            $consulta->bindValue(':sector',$this->sector,PDO::PARAM_STR);
            $consulta->execute();
            return $objetoAccsesoDatos->RetornarUltimoIdInsertado();
        }


        public static function traerTodosLosProductos(){
            $objetoAccsesoDatos = AccesoDatos::dameUnObjetoAcceso();
            $consulta = $objetoAccsesoDatos->RetornarConsulta("SELECT `id`, `nombre`, `precio`, `tiempoEleboracion`, `sector` 
            FROM `productos` ");
            $consulta->execute();
            return $consulta->fetchAll(PDO::FETCH_CLASS,"producto");
        }

        public static function TraerUnProducto($id){
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $consulta = $objetoAccesoDato->RetornarConsulta("SELECT `id`, `nombre`, `precio`, `tiempoEleboracion`, `sector` 
            FROM `productos` 
            WHERE id = $id");
            $consulta->execute();
            $usuarioBuscado = $consulta->fetchObject("producto");
            return $usuarioBuscado;
        }

        public function modificarProducto(){
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $consulta = $objetoAccesoDato->RetornarConsulta("UPDATE `productos` 
            SET `nombre`='$this->nombre',`precio`= '$this->precio',`tiempoEleboracion`='$this->tiempoElaboracion',`sector`='$this->sector' 
            WHERE id = '$this->id'");
            return $consulta->execute();
        }

        public function modificarProductoParametros(){
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $consulta = $objetoAccesoDato->RetornarConsulta("UPDATE `productos` 
            SET `nombre`= :nombre,`precio`= :precio,`tiempoEleboracion`= :tiempoElaboracion,`sector`= :sector 
            WHERE id = :id");
    
            $consulta->bindValue(':id',$this->id,PDO::PARAM_INT);
            $consulta->bindValue(':nombre',$this->nombre,PDO::PARAM_STR);
            $consulta->bindValue(':precio',$this->precio,PDO::PARAM_INT);
            $consulta->bindValue(':tiempoElaboracion',$this->tiempoElaboracion,PDO::PARAM_STR);  
            $consulta->bindValue(':sector',$this->sector,PDO::PARAM_STR);
            return $consulta->execute();
        }

        public function borrarProducto(){
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $consulta = $objetoAccesoDato->RetornarConsulta("DELETE FROM `productos` 
            WHERE id = :id");
    
            $consulta->bindValue(':id',$this->id,PDO::PARAM_INT);
            $consulta->execute();
            return $consulta->rowCount();
        }
    }
?>