<?php 
class Conexion{	  
    public static function Conectar() {        
        define('servidor', 'localhost');
        define('nombre_bd', 'web');
        define('usuario', 'root');
        define('password', 'root');		

        /*
        Bo
        define('servidor', '89.46.111.52');
        define('nombre_bd', 'Sql1142897_5');
        define('usuario', 'Sql1142897');
        define('password', 'o20556190q');	
        
        define('servidor', 'localhost');
        define('nombre_bd', 'web');
        define('usuario', 'root');
        define('password', 'root');		
        */
        $opciones = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');			
        try{
            $conexion = new PDO("mysql:host=".servidor."; dbname=".nombre_bd, usuario, password, $opciones);			
            return $conexion;
        }catch (Exception $e){
            die("El error de Conexión es: ". $e->getMessage());
        }
    }
}