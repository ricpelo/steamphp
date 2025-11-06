<?php

namespace App\AR;

class Cliente extends ActiveRecord
{
    public $dni;
    public $nombre;
    public $apellidos;
    public $direccion;
    public $codpostal;
    public $telefono;

    #[\Override]
    protected static string $tabla = 'clientes';

    public static function buscar_por_dni(string $dni): ?Cliente
    {
        $pdo = Cliente::pdo();
        $tabla = Cliente::$tabla;
        $sent = $pdo->prepare("SELECT * FROM $tabla WHERE dni = :dni");
        $sent->execute([':dni' => $dni]);
        return $sent->fetchObject(Cliente::class) ?: null;
    }

    #[\Override]
    public function guardar(): void
    {
        if (isset($this->id)) {
            $this->modificar();
        } else {
            $this->insertar();
        }
    }

    private function modificar()
    {
        $pdo = Cliente::pdo();
        $tabla = Cliente::$tabla;
        $sent = $pdo->prepare("UPDATE $tabla
                                  SET dni = :dni,
                                      nombre = :nombre,
                                      apellidos = :apellidos,
                                      direccion = :direccion,
                                      codpostal = :codpostal,
                                      telefono = :telefono
                                WHERE id = :id");
        $sent->execute([
            ':id'        => $this->id,
            ':dni'       => $this->dni,
            ':nombre'    => $this->nombre,
            ':apellidos' => $this->apellidos,
            ':direccion' => $this->direccion,
            ':codpostal' => $this->codpostal,
            ':telefono'  => $this->telefono,
        ]);
    }

    private function insertar()
    {
        $pdo = Cliente::pdo();
        $tabla = Cliente::$tabla;
        $sent = $pdo->prepare("INSERT INTO $tabla (dni, nombre, apellidos, direccion, codpostal, telefono)
                               VALUES (:dni, :nombre, :apellidos, :direccion, :codpostal, :telefono)
                               RETURNING (id)");
        $sent->execute([
            ':dni'       => $this->dni,
            ':nombre'    => $this->nombre,
            ':apellidos' => $this->apellidos,
            ':direccion' => $this->direccion,
            ':codpostal' => $this->codpostal,
            ':telefono'  => $this->telefono,
        ]);
        $this->id = $sent->fetchColumn() ?: null;
    }
}
