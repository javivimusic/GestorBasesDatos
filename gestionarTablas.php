<?php

spl_autoload_register(function ($nombre_clase) {
    include $nombre_clase . '.php';
});


session_start();
//recuperamos los datos de la variable de session y lo guardamos en una variable
$datos = $_SESSION['bbdd'];

$host="mysql:host=".$datos['host'].";dbname=".$datos['nombreBase'];
$user=$datos['user'];
$pass=$datos['pass'];

try{
    //creamos una nueva conexion con PDO y realizamos las consultas
    $conexion=new PDO($host,$user,$pass);
    $fila=[];
    $titulo=[];
    $sentencia="SELECT * FROM ".$datos['tabla'];
    $consulta=$conexion->query($sentencia);
    
    /*
     * las consultas no están parametrizadas
     * esto es ya que al intentar hacerlo no hago bien y rompe
     */
    //$sentencia="SELECT * FROM :table";
    //$result=$conexion->query($sentencia);
    
    //$result=$conexion->prepare('SELECT * FROM ?');
    //$tabla=$datos['tabla'];
    //$result->bindParam(1 ,$tabla );
    //$result->execute($parametros);
    
    
    while ($f=$consulta->fetch(PDO::FETCH_NUM)){
        //var_dump($f);
        $fila[]=$f;
    }
    //$colsulta=null;
    /*
     * creamos la consulta para coseguir la descripción de la tabla
     * y asi sacar los nombres de la columnas
     */
    $sentencia="desc ".$datos['tabla'];
    $consulta=$conexion->query($sentencia);
    while($fe=$consulta->fetch(PDO::FETCH_NUM)){
        $title[]=$fe;
    }
    
    //$colsulta=null;
    //$conexion=null;
} catch (PDOException $ex) {
    echo "ERROR".$ex->getMessage();
}

if(isset($_POST['submit'])){
    switch($_POST['submit']){
        case 'Insertar':
            header("Location:editar.php?campos=true");
            break;
        
        case 'Volver':
            header("Location:tablas.php");
            break;
    }
}
if(isset($_POST['editar'])){
    $datos['cod']=$_POST['editar'];
    $_SESSION['bbdd']=$datos;
    var_dump($datos);
    header("Location:editar.php?mostrarDatos=true");
}
if(isset($_POST['borrar'])){
    $datos['cod']=$_POST['borrar'];
    $_SESSION['bbdd']=$datos;
    var_dump($datos);
    header("Location:editar.php?borrar=true");
}


?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Document</title>
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
        <form method="POST" action="gestionarTablas.php">
        <table>
            <?php
            echo "<tr>";
            foreach($title as $columnas){
                
                echo "<th>$columnas[0]</th>";
            }
            echo "<th>Opciones</th></tr>";
            
            foreach ($fila as $dato =>$columnas){
                echo "<tr>";
                foreach ($columnas as $posi => $info){
                        echo "<td>" . $info . "</td>";
                }
                
                echo "<td><button type='submit' name='editar' value='$columnas[0]'>Editar</button><td>";
                echo "<td><button type='submit' name='borrar' value=$columnas[0]>Borrar</button><td></tr>";
                
            }
            
            
            ?>

        </table>
            <input type='submit' name='submit' value='Insertar'>
            <input type='submit' name='submit' value='Volver'>
        </form>
    </body>
</html>

