<?php 
    //conexion a la base de datos
    require_once "../lib/conexionDB.php";
    $conexion = new ConexionDB();
    $conexion->conectar();
    $con = $conexion->conexion;

    //iniciar sesion
    session_start();

    //verificar que se ingreso por medio medio de post o get
    if(!isset($_GET['accion'])){
        echo "here";
        exit;
        header("Location: ../index.php");
        exit;
    }

    //funcion para guardar imagenes
    function guardarImagen(){
        if(!isset($_FILES['imagen']) || $_FILES['imagen']['error'] !== 0){
            $_SESSION['estado'] = "error";
            $_SESSION['alerta'] = "Imagen vacia o no valida";
            header("Location: ../index.php");
            exit;
        }
        //formatos aceptados
        $formatos = ["image/jpeg","image/png", "image/gif"];
        if(!in_array($_FILES['imagen']['type'],$formatos)){
            $_SESSION['estado'] = "error";
            $_SESSION['alerta'] = "Formato de imagen no valida";
            header("Location: ../index.php");
            exit;
        }
        $temp = $_FILES['imagen']['tmp_name'];
        $nombreImagen = basename($_FILES['imagen']['name']);
        $salidaImagen = "../imagenes/".$nombreImagen;

        if(!move_uploaded_file($temp,$salidaImagen)){
            $_SESSION['estado'] = "error";
            $_SESSION['alerta'] = "No se pudo guardar la imagen en el servidor";
            header("Location: ../index.php");
            exit;
        }

        //finalizr retornando el nombre de la imagen guardada
        return $nombreImagen;
    }

    $accion = $_GET['accion'];
    //verificar accion del usuario
    switch($accion){
        case "agregar":
            $nombre = trim($_POST['nombre']);
            $edad = trim($_POST['edad']);
            $sueldo = trim($_POST['sueldo']);
            $posicion = trim($_POST['posicion']);
            $equipo = trim($_POST['equipo']);
            $biografia = trim($_POST['biografia']);
            $imagen = guardarImagen();

            //comprobar si los campos numericos son numericos
            if(!is_numeric($edad) || !is_numeric($sueldo)){
                $_SESSION['estado'] = "error";
                $_SESSION['alerta'] = "Los campos numericos deben ser numericos";
                header("Location: ../index.php");
                exit;
            }

            //comporbar campos vacios
            if(empty($nombre) || empty($edad) || empty($sueldo) || empty($posicion) || empty($equipo) || empty($biografia) ){
                $_SESSION['estado'] = "error";
                $_SESSION['alerta'] = "Ningun campo debe quedar vacio";
                header("Location: ../index.php");
                exit;
            }

            //agregar jugador a la base de datos
            $consulta = 'INSERT INTO jugadores (nombre, edad, sueldo, posicion, equipo, biografia, imagen) VALUES (?,?,?,?,?,?,?)';
            $declaracion = $con->prepare($consulta);
            $declaracion->bind_param("siissss",$nombre,$edad,$sueldo,$posicion,$equipo,$biografia,$imagen);
            if(!$declaracion->execute()){
                $_SESSION['estado'] = "error";
                $_SESSION['alerta'] = "La consulta no se realizo correctamente";
                header("Location: ../index.php");
                exit;  
            }

            //se ejecuta agregar correctamente y se redirige al usuario
            $_SESSION['estado'] = "correcto";
            $_SESSION['alerta'] = "Se agrego el jugador correctamente";
            header("Location: ../index.php");
            exit;  
            break;
        case "editar":
            if(!isset($_GET['id'])){
                header("Location: ../index.php");
                exit;   
            }
            $id = $_GET['id'];
            $nombre = trim($_POST['nombre']);
            $edad = trim($_POST['edad']);
            $sueldo = trim($_POST['sueldo']);
            $posicion = trim($_POST['posicion']);
            $equipo = trim($_POST['equipo']);
            $biografia = trim($_POST['biografia']);

            //comprobar si los campos numericos son numericos
            if(!is_numeric($edad) || !is_numeric($sueldo)){
                $_SESSION['estado'] = "error";
                $_SESSION['alerta'] = "Los campos numericos deben ser numericos";
                header("Location: ../index.php");
                exit;
            }

            //comporbar campos vacios
            if(empty($nombre) || empty($edad) || empty($sueldo) || empty($posicion) || empty($equipo) || empty($biografia) ){
                $_SESSION['estado'] = "error";
                $_SESSION['alerta'] = "Ningun campo debe quedar vacio";
                header("Location: ../index.php");
                exit;
            }

            $consulta = "UPDATE jugadores SET nombre=?,edad=?,sueldo=?,posicion=?,equipo=?,biografia=? WHERE id=?";
            $declaracion= $con->prepare($consulta);
            $declaracion->bind_param("siisssi",$nombre,$edad,$sueldo,$posicion,$equipo,$biografia,$id);
            if(!$declaracion->execute()){
                $_SESSION['estado'] = "error";
                $_SESSION['alerta'] = "No se pudo realizar la actualizacion del jugador";
                header("Location: ../index.php");
                exit;
            }

            //comprobar si subio una imagen
            if(isset($_FILES['imagen']) && $_FILES['imagen']['size']>0 && getimagesize($_FILES['imagen']['tmp_name'])){
                $consulta = "SELECT imagen FROM jugadores WHERE id=".$id;
                $resultado = $con->query($consulta);
                if($resultado && $resultado->num_rows > 0){
                    $fila = $resultado->fetch_assoc();
                    $antiguaImagen = $fila['imagen'];
                }

                $img = guardarImagen();

                $consulta = "UPDATE jugadores SET imagen=? WHERE id=?";
                $declaracion = $con->prepare($consulta);
                $declaracion->bind_param("si",$img,$id);
                if($declaracion->execute()){
                    unlink("../imagenes/".$antiguaImagen);
                }else{
                    $_SESSION['estado'] = "error";
                    $_SESSION['alerta'] = "No se pudo ejecutar la peticion en el servidor para almacenar la nueva imagen";
                    header("Location: ../index.php");
                    exit;
                }
                 
            }

            //se modifica correctamente y se redirige al usuario
            $_SESSION['estado'] = "correcto";
            $_SESSION['alerta'] = "Se edito el jugador correctamente";
            header("Location: ../index.php");
            exit;
            break;

        case "eliminar":
            if(!isset($_GET['id'])){
                header("Location: ../index.php");
                exit;   
            }
            //obtener id 
            $id = $_GET['id'];
            
            //consulta para obtener la imagen del jugador
            $consulta = "SELECT imagen FROM jugadores WHERE id=".$id;
            $resultado = $con->query($consulta);
            if($resultado && $resultado->num_rows > 0){
                $fila = $resultado->fetch_assoc();
                $img = $fila['imagen'];
            }

            //consulta a la BD para eliminar el jugador

            $consulta = "DELETE FROM jugadores WHERE id=".$id;
            if($con->query($consulta)){
                unlink("../imagenes/".$img);
                $_SESSION['estado'] = "correcto";
                $_SESSION['alerta'] = "Se elimino correctamente";
                header("Location: ../index.php");
                exit;
            }else{
                $_SESSION['estado'] = "correcto";
                $_SESSION['alerta'] = "No se pudo eliminar el jugador";
                header("Location: ../index.php");
                exit;
            }
    }
?>