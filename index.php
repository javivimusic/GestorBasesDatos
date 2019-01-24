<?php
spl_autoload_register(function ($nombre_clase) {
    include $nombre_clase . '.php';
});

session_start();

if (isset($_SESSION['bbdd'])) {
    $existe = $_SESSION['bbdd'];
    $host = $existe['host'];
    $user = $existe['user'];
    $pass = $existe['pass'];
    $conexion = new BD($host, $user, $pass, null);
    if (is_null($conexion)) {
        $msj = $conexion->get_error();
    } else {
        $con = true;
        $sentencia = "show databases";
    }
}
if (isset($_POST['submit'])) {
    $host = $_POST['host'];
    $user = $_POST['user'];
    $pass = $_POST['pass'];

    switch ($_POST['submit']) {
        case 'Conectar':
            $conexion = new BD($host, $user, $pass, null);
            if (is_null($conexion->get_conect())) {
                $msj = $conexion->get_error();
            } else {
                $con = true;
                $sentencia = "show databases";
            }

            break;
        case 'Gestionar':
            $host = $_POST['host'];
            $user = $_POST['user'];
            $pass = $_POST['pass'];
            $baseDatos = $_POST['bd'];
            $datos = ["host" => $host, "user" => $user, "pass" => $pass, "nombreBase" => $baseDatos];
            $_SESSION['bbdd'] = $datos;
            header("Location:tablas.php");
            break;
        case 'Desconectar':
            $con = false;
            session_destroy();
            $conexion = null;
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
        <style>
            table, tr{
                border: 1px solid #ddd;
                padding: 8px;
            }
            tr:nth-child(even){background-color: #f2f2f2;}

            tr:hover {background-color: #ddd;}

        </style>
    </head>
    <body>
        <form action="index.php" method="POST">
            <fieldset>
                <legend>Datos de conexión</legend>
                <label>Host</label>
                <input type="text" name="host" value="">
                <label>Usuario</label>
                <input type="text" name="user" value="root">
                <label>Password</label>
                <input type="text" name="pass" value="">
                <input type="submit" name="submit" value="Conectar">

            </fieldset>
        </form>

        <?php
        //echo $msj;
        if ($con) :
            ?>
            <form action="index.php" method="POST">
                <fieldset>
                    <legend>Gestion de las bases de datos del host <?php echo $host ?></legend>
                    <?php
                    $fila = $conexion->select($sentencia);
                    foreach ($fila as $lista => $nombre) {
                        foreach ($nombre as $dato => $info) {
                            echo"<input type='radio' name='bd' value='$info'>" . $info . "<br>";
                        }
                    }

                    echo"<input type='hidden' name='host' value='$host'>";
                    echo"<input type='hidden' name='user' value='$user'>";
                    echo"<input type='hidden' name='pass' value='$pass'>";
                    echo"<input type='submit' name='submit' value='Gestionar'>";
                    ?>
                </fieldset>
                <input type='submit' name='submit' value='Desconectar'>
            </form>
        <?php endif; ?>
        <h1><?php echo $msj ?></h1>
    </body>
</html>
