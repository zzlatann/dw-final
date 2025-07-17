<?php 
    //conectar con la base de datos
    require_once "lib/conexionDB.php";
    $conexion = new ConexionDB();
    $conexion->conectar();
    $con = $conexion->conexion;

    session_start();

    $consulta = "SELECT * FROM jugadores ORDER BY sueldo DESC";
    $resultado = $con->query($consulta);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Examen Final</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link rel="stylesheet" href="CSS/estilo.css">
</head>
<body>
    <!--Header-->
    <?php include "Fragmentos/header.php";?>
    <!--Formulario para gestionar jugadores-->
    <?php if(isset($_SESSION['estado'])):?>
        <?php if($_SESSION['estado'] == "error"):?>
            <script>alert("<?=$_SESSION['alerta'];?>")</script>
        <?php else:?>
            <script>alert("<?=$_SESSION['alerta'];?>")</script>
        <?php endif;?>
        <?php unset($_SESSION['estado']);?>
        <?php unset($_SESSION['alerta']);?>
        <?php endif;?>
        
    <form class="p-4 mx-auto mt-4 border rounded-2 border-3 border-primary  oculto formulario" action="jugadores/gestionar_jugador.php?accion=agregar" method="POST" enctype="multipart/form-data">
        <label class="form-label text-primary fw-bolder" for="nombre">Nombre</label>
        <input class="form-control" type="text" id="nombre" name="nombre" required>
        <label class="form-label text-primary fw-bolder" for="edad">Edad</label>
        <input class="form-control" type="number" id="edad" name="edad" required>
        <label class="form-label text-primary fw-bolder" for="sueldo">Sueldo anual(USD)</label>
        <input class="form-control" type="number" id="sueldo" name="sueldo" required>
        <label class="form-label text-primary fw-bolder" for="posicion">Posicion</label>
        <input class="form-control" type="text" id="posicion" name="posicion" required>
        <label class="form-label text-primary fw-bolder" for="equipo">Equipo</label>
        <input class="form-control" type="text" id="equipo" name="equipo">
        <label class="form-label text-primary fw-bolder" for="biografia">Biografia</label>
        <textarea class="form-control" rows="5" name="biografia" id="biografia" required></textarea>
        <label class="form-label text-primary fw-bolder" for="imagen">Seleccione una imagen</label>
        <input class="form-control-file mb-4" type="file" id="imagen" name="imagen" required>

        <button class="btn btn-primary" type="submit" id="boton_enviar">Agregar Jugador</button>
        <button class="btn btn-outline-secondary" type="button" id="boton_cancelar" >Cancelar</button>
    </form>

    <!--Boton para mostrar/ocultar formulario-->
    <div class="text-center py-4">
        <button class="btn btn-outline-primary fs-5" type="button" id="boton_nuevo">Nuevo</button>
    </div>
    <!--Tabla para mostrar todos los jugadores-->
    <?php if($resultado && $resultado->num_rows > 0): ?>
    <div class="container">
        <table class="table table-bordered table-sm-responsive table-md-responsive table-lg-responsive table-xl-responsive table-striped table-sm text-center ">
            <thead>
                <tr>
                    <th>Imagen</th>
                    <th>Nombre</th>
                    <th>Edad</th>
                    <th>Sueldo Anual (USD)</th>
                    <th>Posicion</th>
                    <th>Equipo</th>
                    <th style="width: 400px;">Biografia</th>
                    <th style="width: 170px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while($fila = $resultado->fetch_assoc()):?>
                <tr>
                    <td><img class="foto_jugador" src="imagenes/<?=$fila['imagen']?>" alt="<?=$fila['nombre']?>"></td>
                    <td class="id_container" data-id="<?=$fila['id']?>"><?=htmlspecialchars($fila['nombre'])?></td>
                    <td><?=htmlspecialchars($fila['edad'])?></td>
                    <td><?=htmlspecialchars($fila['sueldo'])?></td>
                    <td><?=htmlspecialchars($fila['posicion'])?></td>
                    <td><?=htmlspecialchars($fila['equipo'])?></td>
                    <td><?=htmlspecialchars($fila['biografia'])?></td>
                    <td><button class="btn btn-warning editar" type="button">Editar</button> <a class="btn btn-danger" href="jugadores/gestionar_jugador.php?accion=eliminar&id=<?=$fila['id'];?>">Eliminar</a></td>
                </tr>
                <?php endwhile;?>
            </tbody>
        </table>
    </div>
    <?php else:?>
        <p class="p-4 text-center fs-5 text-secondary">Aun no fueron registrados jugadores</p>
    <?php endif;?>

    <!--Footer-->
    <?php include "Fragmentos/footer.php";?>
    <!--Funcionalidades del lado del cliente-->
    <script src="javaScript/index.js"></script>
</body>
</html>