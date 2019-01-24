<?php
spl_autoload_register(function ($nombre_clase) {
    include $nombre_clase . '.php';
});

session_start();
$campos=$_GET['campos'];
$mostrarDatos=$_GET['mostrarDatos'];
$borrar=$_GET['borrar'];

$datos=$_SESSION['bbdd'];
$tabla = $_SESSION['nombreTabla'];
$fila=$_SESSION['fila'];

    $host = $datos['host'];
    $base = $datos['nombreBase'];
    $user = $datos['user'];
    $pass = $datos['pass'];
$conexion=new BD($host, $user,$pass,$base);
$sentencia2 = "desc " . $tabla;

if (isset($_POST['submit'])){
    switch($_POST['submit']){
        case 'Actualizar':
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
        <?php if($campos) : ?>
        <form action="editar.php" method="POST">
            <fieldset>
                <legende><?php echo "Nueva fila en la tabla ".$tabla."<br>" ?></legende>
                <?php
                $titulo=$conexion->select($sentencia2);
                foreach($titulo as $columnas){
                    echo "<br>".$columnas[0]."<br><textarea name=".$columnas[0]."></textarea><br>";
                    
                }
                ?>
                
            </fieldset>
            <input type="submit" name="submit" value="Actualizar">
            <input type="submit" name="submit" value="volver">
        </form>
        <?php endif; ?>
        <?php if($mostrarDatos) : ?>
        <form action="editar.php" method="POST">
            <fieldset>
                <legende><?php echo "Editar elemento de la tabla ".$tabla."<br>" ?></legende>
                <?php
                    $titulo=$conexion->select($sentencia2);
                    $con=0;
                    foreach($fila as $info){
                        echo "<br>".$titulo[$cont][0]."<br><textarea name=".$titulo[$cont][0].">$info</textarea><br>";
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

