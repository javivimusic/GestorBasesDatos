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
    if (is_null($conexion->get_conect())) {
        $msj = $conexion->get_error();
    } else {
        $sentencia1 = "SELECT * FROM " . $tabla;
        $sentencia2 = "desc " . $tabla;
    }
}



if (isset($_POST['submit'])) {
    switch ($_POST['submit']) {
        case 'Insertar':
            header("Location:editar.php?campos=true");
            break;

        case 'Volver':
            header("Location:tablas.php");
            break;
        case 'Editar':
            $row = $_POST['datos'];
            $_SESSION['fila'] = $row;
            header("Location:editar.php?mostrarDatos=true");
            break;
        case 'Borrar':
            $row = $_POST['datos'];
            $_SESSION['fila'] = $row;
            header("Location:editar.php?borrar=true");
            break;
    }
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
        <h1><?php echo $msj ?></h1>
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

                    $c = 0;
                    echo "<form action='gestionarTablas.php' method='POST'>";
                    foreach ($columnas as $info) {
                        echo "<td>" . $info . "</td>";
                        echo "<input type='hidden' name=datos[" . $titulo[$c][0] . "] value=$info>";
                        $c++;
                    }


                    echo "<td><input type='submit' name='submit' value=Editar>";
                    echo "<input type='submit' name='submit' value=Borrar></td></form>";
                }
                ?>

            </table>
            <input type='hidden' name='posicion' value='Insertar'>
            <input type='submit' name='submit' value='Insertar'>
            <input type='submit' name='submit' value='Volver'>
        </form>

    </body>
</html>

