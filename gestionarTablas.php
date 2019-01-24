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
$datos = $_SESSION['tabla'];

/*
  $miConexion = new BD($datos['host'], $datos['user'], $datos['pass']);
  $miConexion->setBaseDatos($datos['nombreBase']);

  $consulta = "selec * from " . $datos['tabla'];
  $result = $miConexion->modificar($consulta);
  $consulta2 = "desc " . $datos['tabla'];
  $result2 = $miConexion->modificar($consulta);
  var_dum($result);
  var_dum($result2);
 */


$host = "mysql:host=" . $datos['host'] . ";dbname=" . $datos['nombreBase'];
$user = $datos['user'];
$passwd = $datos['pass'];

var_dump($datos);

try {

    $miConexion = new PDO($host, $user, $passwd);
} catch (PDOException $ex) {
    die("Error conectado a la base de datos " . $ex->getMessage());
}
$tabla = $datos['tabla'];

$sentencia1 = "SELECT * FROM :table";
$consulta = $miConexion->prepare($sentencia1);

$consulta->execute(array("table" => $tabla));
var_dump($consulta);
while ($f = $consulta->fetch()) {
    var_dump($f);
}
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Document</title>
    </head>
    <body>
        <table>
            <?php
//            foreach ($resultado2 as $index => $nombre) {
//                echo "<th>$nombre[0]</th>";
//            }
            foreach ($fila as $nombre => $dato) {
                echo "<tr>";
                foreach ($dato as $columna => $info) {
                    echo "<td>" . $info . "</td>";
                }
                echo "</tr>";
            }
            ?>

        </table>

    </body>
</html>

