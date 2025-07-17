<?php 
    class ConexionDB{
        private $usuario = "root";
        private $servidor = "127.0.0.1";
        private $contrasena = "";
        private $baseDeDatos = "examen_final";
        public $conexion;
        
        public function conectar(){
            $this->conexion = new mysqli($this->servidor,$this->usuario,$this->contrasena,$this->baseDeDatos);
        }       
    }
?>