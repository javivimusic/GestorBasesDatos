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
        $this->h = $h;
        $this->u = $u;
        $this->p = $p;
        $this->db = $db;
        $this->conexion = $this->conexion();
    }

    private function conexion() {

        if (is_null($this->db)) {
            $dsn = "mysql:host=" . $this->h . ";dbname=";
        } else {
            $dsn = "mysql:host=" . $this->h . ";dbname=$this->db";
        }

        try {
            $atributos = [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8", PDO::ATTR_ERRMODE => true, PDO::ERRMODE_EXCEPTION => true];
            $con = new PDO($dsn, $this->u, $this->p, $atributos);
        } catch (PDOException $ex) {
            $this->error = "Error conectado a la base de datos " . $ex->getMessage();
        }

        return $con;
    }

    public function get_error() {
        return $this->error;
    }

    public function get_conect() {
        return $this->conexion;
    }

    public function select(string $consulta) {
        $fila = [];
        $rslt = $this->conexion->query($consulta);
        while ($f = $rslt->fetch(PDO::FETCH_NUM)) {
            $fila[] = $f;
        }
        return $fila;
    }

    public function insert(string $consulta) {

    }

}
