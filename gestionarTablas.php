<?php
spl_autoload_register(function ($nombre_clase) {
    include $nombre_clase . '.php';
});


session_start();

if (isset($_SESSION['nombreTabla'])) {
    $datos = $_SESSION['bbdd'];
    $tabla = $_SESSION['nombreTabla'];

    $host = $datos['host'];
    $base = $datos['nombreBase'];
    $user = $datos['user'];
    $pass = $datos['pass'];

    $conexion = new BD($host, $user, $pass, $base);
    $sentencia1 = "SELECT * FROM " . $tabla;
    $sentencia2 = "desc " . $tabla;
}




if (isset($_POST['submit'])) {
    switch ($_POST['submit']) {
        case 'Insertar':
            header("Location:editar.php?campos=true");
            break;

        case 'Volver':
            header("Location:tablas.php");
            break;
    }
}
if (isset($_POST['editar'])) {
    $row = $_POST['editar'];
    $_SESSION['fila'] = $row;
    var_dump($row);
    //header("Location:editar.php?mostrarDatos=true");
}
if (isset($_POST['borrar'])) {
    $row = $_POST['borrar'];
    $_SESSION['fila'] = $row;
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
                $titulo = $conexion->select($sentencia2);
                foreach ($titulo as $columnas => $nombre) {

                    echo "<th>$nombre[0]</th>";
                }
                echo "<th>Opciones</th></tr>";

                $fila = $conexion->select($sentencia1);
                foreach ($fila as $dato => $columnas) {
                    echo "<tr>";
                    foreach ($columnas as $posi => $info) {
                        echo "<td>" . $info . "</td>";
                    }

                    echo "<td><button type='submit' name='editar' value='$columnas'>Editar</button><td>";
                    echo "<td><button type='submit' name='borrar' value=$columnas>Borrar</button><td></tr>";
                }
                ?>

            </table>
            <input type='submit' name='submit' value='Insertar'>
            <input type='submit' name='submit' value='Volver'>
        </form>
    </body>
</html>

