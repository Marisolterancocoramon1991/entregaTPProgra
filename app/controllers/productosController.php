<?php
    require_once "./clase/productos.php";

    class productoController{

        public function InsertarProducto($nombre,$precio,$tiempoElaboracion,$sector){
            $producto = new producto();
            $producto->nombre = $nombre;
            $producto->precio = $precio;
            $producto->tiempoElaboracion = $tiempoElaboracion;
            $producto->sector = $sector;

            return $producto->insertarProductoParametros();
        }

        public function modificarProducto($id,$nombre,$precio,$tiempoElaboracion,$sector){
            $producto = new producto();
            $producto->id = $id;
            $producto->nombre = $nombre;
            $producto->precio = $precio;
            $producto->tiempoElaboracion = $tiempoElaboracion;
            $producto->sector = $sector;

            return $producto->modificarProductoParametros();
        }

        public function borrarProducto($id){
            $producto = new producto();
            $producto->id = $id;

            return $producto->borrarProducto();
        }

        public function listarProductos(){
            return producto::traerTodosLosProductos();
        }

        public function buscarProductoPorId($id){
            $retorno = producto::TraerUnProducto($id);
            if($retorno === false) { // Validamos que exista y si no mostramos un error
                $retorno =  ['error' => 'No existe ese id'];
            }
            return $retorno;
        }
    }
?>