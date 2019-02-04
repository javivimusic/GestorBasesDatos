<?php
spl_autoload_register(function ($nombre_clase) {
    include $nombre_clase . '.php';
});

session_start();
$campos = $_GET['campos'];
$mostrarDatos = $_GET['mostrarDatos'];
$borrar = $_GET['borrar'];

$datos = $_SESSION['bbdd'];
$tabla = $_SESSION['nombreTabla'];
$fila = $_SESSION['fila'];

$host = $datos['host'];
$base = $datos['nombreBase'];
$user = $datos['user'];
$pass = $datos['pass'];
$conexion = new BD($host, $user, $pass, $base);
if (is_null($conexion->get_conect())) {
    $msj = $conexion->get_error();
} else {
    $sentencia2 = "desc " . $tabla;
    $consulta = $conexion->select($sentencia2);
    foreach ($consulta as $columnas => $nombre) {
        $titulo[] = $nombre[0];
    }
}

if ($borrar) {
    $resultado = $conexion->borrar($fila, $titulo, $tabla);
    if (resutlado != 0) {
        $msj = "Fila borrada con exito";
    } else {
        $msj = "No se ha podido borrar la fila";
    }
    header("Location:gestionarTablas.php?msj=$msj");
    exit();
}

if (isset($_POST['submit'])) {
    switch ($_POST['submit']) {
        case 'Insertar':
            foreach ($titulo as $nombre) {
                $insertar[":$nombre"] = $_POST[$nombre];
            }
            $c = $conexion->insert($insertar, $titulo, $tabla);
            if ($c) {
                $msj = "fila insertada";
            } else {
                $msj = "no se ha podido insertar la fila";
            }

            header("Location:gestionarTablas.php?msj=$msj");
            break;
        case 'Actualizar':
            $sentencia = "UPDATE $tabla SET";
            header("Location:gestionarTablas.php?msj=Datos borrados");
            break;
        case 'Cancelar':
            header("Location:gestionarTablas.php");
            exit();
            break;
    }
}
?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <h1><?php echo $msj ?></h1>
        <?php if ($campos) : ?>
            <form action="editar.php" method="POST">
                <fieldset>
                    <legende><?php echo "Nueva fila en la tabla " . $tabla . "<br>" ?></legende>
                    <?php
                    foreach ($titulo as $nombre) {
                        echo "<br>" . $nombre . "<br><textarea name=" . $nombre . "></textarea><br>";
                    }
                    ?>

                </fieldset>
                <input type="submit" name="submit" value="Insertar">
                <input type="submit" name="submit" value="Cancelar">
            </form>
        <?php endif; ?>
        <?php if ($mostrarDatos) : ?>
            <form action="editar.php" method="POST">
                <fieldset>
                    <legende><?php echo "Editar elemento de la tabla " . $tabla ?></legende>
                    <?php
                    $cont = 0;
                    //$titulo[$cont][0]=> $con 0 nos permite sacar el titulo de las columnas y asignarlos a los campos
                    foreach ($fila as $info) {
                        echo "<br>" . $titulo[$cont] . "<br><textarea name=" . $titulo[$cont] . ">$info</textarea><br>";
                        $cont++;
                    }
                    ?>

                </fieldset>
                <input type="submit" name="submit" value="Actualizar">
                <input type="submit" name="submit" value="Cancelar">
            </form>
        <?php endif; ?>
    </body>
</html>

