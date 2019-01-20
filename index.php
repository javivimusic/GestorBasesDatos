<?php
spl_autoload_register(function ($nombre_clase) {
    include $nombre_clase . '.php';
});

if (isset($_POST['submit'])) {
    $host = $_POST['host'];
    $user = $_POST['user'];
    $pass = $_POST['pass'];
    $conexion = new BD($host, $user, $pass);

    switch ($_POST['sumbit']) {
        case 'Conectar':
            if ($conexion->connect_errno == 0) {
                $con = true;
                $consulta = "show databases";
                $bd = $conexion->select($consulta);
            } else {
                $msj = "No se ha podido establecer la conexion" . $conexion->connect_error;
            }
            break;
        case 'Gestionar':
            break;
    }


    $conexion->cerrarCon();
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
        <form action="index.php" method="POST">
            <fieldset>
                <legend>Datos de conexión</legend>
                <label>Host</label>
                <input type="text" name="host" value="localhost">
                <label>Usuario</label>
                <input type="text" name="user" value="root">
                <label>Password</label>
                <input type="text" name="pass" value="">
                <input type="submit" name="submit" value="Conectar">
            </fieldset>
        </form>

        <?php if ($con) : ?>
            <form action="index.php" method="POST">
                <fieldset>
                    <legend>Gestion de las bases de datos del host <?php echo $host ?></legend>
                    <?php
                    foreach ($bd as $lista => $nombre) {
                        foreach ($nombre as $dato => $info) {
                            echo"<input type='radio' name='bd' value='$info'>" . $info . "<br>";
                        }
                    }
                    echo"<input type='submit' name='submit' value='Gestionar'>";
                    ?>
                </fieldset>
            </form>
        <?php endif; ?>
        <h1><?php echo $msj ?></h1>
    </body>
</html>
