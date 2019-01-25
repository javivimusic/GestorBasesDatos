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
     * @param string $consulta
     * devolvemos un valor que es el numero de filas afectadas
     */
    public function insert(string $consulta) {

    }

    public function cerrarCon() {
        if (is_null($this->error))
            $this->conexion->close();
    }

}
