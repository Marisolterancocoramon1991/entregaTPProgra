<?php
    include_once "./db/accesoDatos.php";

    class mesa{
        public $id;
        public $idMozo;
        public $codigoNumerico;
        public $estado;


        public function __construct()
        {
            
        }

        public function constructorParametros($idMozo,$codigoNumerico,$estado){
            $this->idMozo = $idMozo;
            $this->codigoNumerico = $codigoNumerico;
            $this->estado = $estado;
        }

        public function MostrarDatos(){
            return "Id {$this->id}<br>Id Mozo {$this->idMozo}<br>Codigo Alfa Numerico{$this->codigoNumerico}<br>Estado {$this->estado}";
        }

        public function InsertarMesa(){
            $objetoAccsesoDatos = AccesoDatos::dameUnObjetoAcceso();
            $consulta = $objetoAccsesoDatos->RetornarConsulta("INSERT INTO `mesas`(`idMozo`, `codigoNumerico`, `estado`) 
            VALUES ('$this->idMozo','$this->codigoNumerico','$this->estado')");
            $consulta->execute();
            return $objetoAccsesoDatos->RetornarUltimoIdInsertado();
        }


        public function insertarMesaParametros(){
            $objetoAccsesoDatos = AccesoDatos::dameUnObjetoAcceso();
            $consulta = $objetoAccsesoDatos->RetornarConsulta("INSERT INTO `mesas`(`idMozo`, `codigoNumerico`, `estado`) 
            VALUES (:idMozo, :codigoNumerico, :estado)");
            $consulta->bindValue(':idMozo',$this->idMozo,PDO::PARAM_INT);
            $consulta->bindValue(':codigoNumerico',$this->codigoNumerico,PDO::PARAM_INT);
            $consulta->bindValue(':estado',$this->estado,PDO::PARAM_STR);
            $consulta->execute();
            return $objetoAccsesoDatos->RetornarUltimoIdInsertado();
        }


        public static function traerTodosLasMesas(){
            $objetoAccsesoDatos = AccesoDatos::dameUnObjetoAcceso();
            $consulta = $objetoAccsesoDatos->RetornarConsulta("SELECT `id`, `idMozo`, `codigoNumerico` , `estado` 
            FROM `mesas` ");
            $consulta->execute();
            return $consulta->fetchAll(PDO::FETCH_CLASS,"mesa");
        }

        public static function TraerUnaMesa($id){
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $consulta = $objetoAccesoDato->RetornarConsulta("SELECT `id`, `idMozo`, `codigoNumerico`, `estado` 
            FROM `mesas` 
            WHERE id = $id");
            $consulta->execute();
            $usuarioBuscado = $consulta->fetchObject("mesa");
            return $usuarioBuscado;
        }

        public function modificarMesa(){
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $consulta = $objetoAccesoDato->RetornarConsulta("UPDATE `mesas` 
            SET `idMozo`= '$this->idMozo',`codigoNumerico`= '$this->codigoNumerico',`estado`='$this->estado' 
            WHERE id = '$this->id' ");
            return $consulta->execute();
        }
    
        public function modificarMesaParametros(){
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $consulta = $objetoAccesoDato->RetornarConsulta("UPDATE `mesas` 
            SET `idMozo`= :idMozo,`codigoNumerico`= :codigoNumerico,`estado`= :estado 
            WHERE id = :id ");
    
            $consulta->bindValue(':id',$this->id,PDO::PARAM_INT);
            $consulta->bindValue(':idMozo',$this->idMozo,PDO::PARAM_INT);
            $consulta->bindValue(':codigoNumerico',$this->codigoNumerico,PDO::PARAM_INT);  
            $consulta->bindValue(':estado',$this->estado,PDO::PARAM_STR);
            return $consulta->execute();
        }


        public function borrarMesa(){
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $consulta = $objetoAccesoDato->RetornarConsulta("DELETE FROM `mesas` 
            WHERE id = :id");
    
            $consulta->bindValue(':id',$this->id,PDO::PARAM_INT);
            $consulta->execute();
            return $consulta->rowCount();
        } 
    }
?>