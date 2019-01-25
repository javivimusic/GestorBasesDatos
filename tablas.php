<?php
spl_autoload_register(function ($nombre_clase) {
    include $nombre_clase . '.php';
});

session_start();
$datos = $_SESSION['bbdd'];
$host = $datos['host'];
$user = $datos['user'];
$pass = $datos['pass'];
if (!isset($_SESSION['nombreTabla'])) {
    $base = $datos['nombreBase'];
} else {
    $base = $datos['nombreBase'];
    echo$base;
}
$conexion = new BD($host, $user, $pass, $base);
if (is_null($conexion->get_conect())) {
    $msj = $conexion->get_error();
} else {
    $sentencia = "show tables from $base";
    $result = $conexion->select($sentencia);
}

if (isset($_POST['submit'])) {
    switch ($_POST['submit']) {
        case 'volver':
            $_SESSION['bbdd'] = $datos;
            header("Location:index.php");
            break;
        default:
            $tabla = $_POST['submit'];
            $_SESSION['nombreTabla'] = $tabla;
            header("Location:gestionarTablas.php");
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
        <?php echo $msj ?>

        <form actio="tablas.php" method="POST">
            <fieldset>
                <legend>Tablas de las base de datos de <?php echo$datos['nombreBase'] ?></legend>
                <?php
                foreach ($result as $tablas => $nombre) {
                    echo "<button type='submit' name='submit' value='$nombre[0]'>$nombre[0]</button>";
                }
                ?>

            </fieldset>
            <input type="submit" name="submit" value="volver">
        </form>
    </body>
</html>