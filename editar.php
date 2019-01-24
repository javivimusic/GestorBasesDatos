<?php
spl_autoload_register(function ($nombre_clase) {
    include $nombre_clase . '.php';
});

session_start();
$campos=$_GET['campos'];
$mostrarDatos=$_GET['mostrarDatos'];
$borrar=$_GET['borrar'];

$datos=$_SESSION['bbdd'];

$host="mysql:host=".$datos['host'].";dbname=".$datos['nombreBase'];
$user=$datos['user'];
$pass=$datos['pass'];

    try{
    //creamos una nueva conexion con PDO y realizamos las consultas
    $conexion=new PDO($host,$user,$pass);
    $fila=[];
    $titulo=[];
    
    $sentencia="desc ".$datos['tabla'];
    $consulta=$conexion->query($sentencia);
    while($fe=$consulta->fetch(PDO::FETCH_NUM)){
        $titulo[]=$fe;
    }
    
} catch (PDOException $ex) {
    echo "ERROR".$ex->getMessage();
}
if(isset($_POST['submit'])){    
    switch($_POST['submit']){
        case 'Actualizar':
            $sentencia="UPDATE ".$datos['tabla']." WHERE ".$titulo[0][0]." = '".$cod."'";
            $consulta=$conexion->exec($sentencia);
            break;
        case 'Cancelar':
            header("Location:gestionarTablas.php?msj=Una fila actualizada");
            break;
    }
}
if($mostrarDatos){
    $cod=$datos['cod'];
    $sentencia="SELECT * FROM ".$datos['tabla']." WHERE ".$titulo[0][0]." = '".$cod."'";
    $consulta=$conexion->query($sentencia);
    while ($f=$consulta->fetch(PDO::FETCH_NUM)){
        $fila[]=$f;
    }
}
if($borrar){
    $cod=$datos['cod'];
    $sentencia="DELETE FROM ".$datos['tabla']." WHERE ".$titulo[0][0]." = '".$cod."'";
    $consulta=$conexion->exec($sentencia);
    if($consulta !=0){
        header("Location:gestionarTablas?msj=true");
    }else{
        header("Location:gestionarTablas?msj=false");
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
                <legende><?php echo "Nueva fila en la tabla ".$datos['tabla']."<br>" ?></legende>
                <?php
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
                <legende><?php echo "Editar producto ".$cod." de la tabla ".$datos['tabla']."<br>" ?></legende>
                <?php
                    foreach($fila as $dato => $colum){
                        //como puede haber varias filas con el mismo codigo
                        //inicializamos el contar a cero cada vez que encuentra una nueva fila
                        $pos=0;
                        foreach ($colum as $posi => $info){
                            //el titulo de cada textarea lo mostramos sacando la posicion de cada campo
                            //con el contador y en la posicion cero que es el nombre de cada campo
                            echo "<br>".$titulo[$pos][0]."<br><textarea name=".$titulo[$pos][0].">".$info."</textarea><br>";
                            $pos++;
                        }
                    }
                
                ?>
                
            </fieldset>
            <input type="submit" name="submit" value="Actualizar">
            <input type="submit" name="submit" value="volver">
        </form>
        <?php endif; ?>
    </body>
</html>

