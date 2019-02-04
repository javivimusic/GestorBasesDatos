<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BD
 *
 * @author alumno
 */
class BD {

    //$conexion baldra null o un objeto de mysql
    private $conexion;
    private $error;
    private $h;
    private $u;
    private $p;
    private $db;

    public function __construct($h, $u, $p, $db) {
        $this->error = null;
        $this->h = $h;
        $this->u = $u;
        $this->p = $p;
        $this->db = $db;

        //dependiendo de si disponemos de la base de datos o no, hacemos unaç
        //conexión y otra
        if (is_null($this->db)) {
            $dsn = "mysql:host=" . $this->h . ";dbname=";
        } else {
            $dsn = "mysql:host=" . $this->h . ";dbname=$this->db";
        }

        try {
            //establecemos la conexión y en el que caso de que de error
            //lo guardamos en el atributo $error

            $atributos = [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8", PDO::ATTR_ERRMODE => true, PDO::ERRMODE_EXCEPTION => true];
            $this->conexion = new PDO($dsn, $this->u, $this->p, $atributos);
        } catch (PDOException $ex) {
            $this->error = "Error conectado a la base de datos " . $ex->getMessage();
        }
    }

    //funcion con la que devolvemos el error
    public function get_error() {
        return $this->error;
    }

    //devolvemos la conexión
    public function get_conect() {
        return $this->conexion;
    }

    /**
     *
     * @param string $consulta
     * @return array
     * realizamos una cosulta de tipo selec normal
     * devolvemos un array con las filas encontradas
     */
    public function select(string $consulta) {
        $fila = [];
        $rslt = $this->conexion->query($consulta);
        while ($f = $rslt->fetch(PDO::FETCH_NUM)) {
            $fila[] = $f;
        }
        return $fila;
    }

    /**
     *
     * @return boolean si estoy o no conectado a la base de datos
     *
     */
    public function conectado() {
        if (is_null($this->error))
            return true;
        return false;
    }

    /**
     *
     * @param array $datos
     * @param array $columnas
     * @param string $tabla
     * @return boolean
     * nos devuelve true si ha podido insertar la final
     */
    public function insert(array $datos, array $columnas, string $tabla) {
        $consulta = "INSERT INTO $tabla VALUES( ";
        foreach ($columnas as $nombre) {
            $consulta = $consulta . ":$nombre , ";
        }

        $sentencia = substr($consulta, 0, strlen($consulta) - 2);
        $sentencia = $sentencia . ")";

        $preparada = $this->conexion->prepare($sentencia);
        if ($preparada->execute($datos))
            return true;

        return false;
    }

    /**
     *
     * @param array $fila
     * @param array $titulo
     * @param string $tabla
     * @return string
     * devolvemos un valor que es el número de filas borradas
     */
    public function borrar(array $fila, array $titulo, string $tabla) {
        $consulta = "DELETE FROM $tabla WHERE ";
        $c = 0;
        foreach ($fila as $dato) {

            $consulta = $consulta . $titulo[$c] . " = '$dato' AND ";
            $c++;
        }

        $sentencia = substr($consulta, 0, strlen($consulta) - 4);

        $registros = $this->conexion->exec($consulta);
        return $registros;
//        return $sentencia;
    }

    public function cerrarCon() {
        if (is_null($this->error))
            $this->conexion->close();
    }

}
