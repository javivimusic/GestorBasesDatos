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

    public function __construct($h, $u, $p) {
        $this->h = $h;
        $this->u = $u;
        $this->p = $p;
        $this->conexion = $this->conexion();
    }

    private function conexion() {
        $con = new mysqli($this->h, $this->u, $this->p);
        if ($con->connect_errno) {
            $this->error = "Error de conexion:" . $con->connect_error;
        }

        return $con;
    }

    public function select(string $c): array {
        $fila = [];

        if ($this->conexion == null) {
            $this->conexion = $this->conexion();
        }

        $resutlado = $this->conexion->query($c);
        /*
         *  while ($f = $resutlado->fetch_row()) es igual que
         *  $f = $resutlado->fetch_row();
         *  while ($f != null)
         */
        while ($f = $resutlado->fetch_row()) {
            $fila[] = $f;
        }
        return $fila;
    }

    public function modificar(string $c) {
        if ($rtn = $this->conexion->query($c) === true) {
            return $rtn;
        }
        return $this->conexion->error;
    }

    public function cerrarCon() {
        $this->conexion->close();
    }

}
