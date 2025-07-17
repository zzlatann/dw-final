<?php 
    //conexion a la base de datos
    require_once "../lib/conexionDB.php";

    header('Content-Type: application/json; charset=utf-8');

    $conexion = new ConexionDB();
    $conexion->conectar();
    $con = $conexion->conexion;

    $id = $_GET['id'] ?? 0;

    if($id>0){
        $consulta = "SELECT * FROM jugadores WHERE id=".$id;
        $respuesta = $con->query($consulta);
        if($respuesta && $respuesta->num_rows > 0){
            $response = $respuesta->fetch_assoc();
            echo json_encode($response);
            exit;
        }
    }    
?>