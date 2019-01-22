<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
spl_autoload_register(function ($nombre_clase) {
    include $nombre_clase . '.php';
});

session_start();
$datos=$_SESSION['bbdd'];
$conexion=new BD($datos['host'],$datos['user'],$datos['pass']);
$conexion->setBaseDatos($datos['nombreBase']);

if ($conexion->connect_errno == 0) {
    $con = true;
    $consulta = "show tables from ".$datos['nombreBase'];
    $bd = $conexion->select($consulta);
} else {
    $msj = "No se ha podido establecer la conexion" . $conexion->connect_error;
}

if(isset($_POST['tabla'])){
    $tabla=$_POST['tabla'];
    $datos['tabla']=$tabla;
    $_SESSION['tabla']=$datos;
    header("Location:gestionarTablas.php");
}
$conexion->cerrarCon();
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
        <?php echo $msj?>
        
        <form actio="tablas.php" method="POST">
            <fieldset>
                <legend>Tablas de las base de datos de <?php echo$datos['nombreBase'] ?></legend>
                <?php
                foreach($bd as $tablas => $nombre){
                    echo "<input type='submit' name='tabla' value='$nombre[0]'>";
                }
                ?>
            </fieldset>
        </form>
    </body>
</html>