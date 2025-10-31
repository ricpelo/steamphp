<?php

require_once 'auxiliar.php';

class Cliente
{
    public $id;
    public $dni;
    public $nombre;
    public $apellidos;
    public $direccion;
    public $codpostal;
    public $telefono;

    private static PDO $pdo;

    public function __construct(array $fila = [])
    {
        foreach ($fila as $k => $v) {
            $this->$k = $v;
        }
    }

    public static function buscar_por_id(string|int $id): ?Cliente
    {
        $pdo = Cliente::pdo();
        $sent = $pdo->prepare('SELECT * FROM clientes WHERE id = :id');
        $sent->execute([':id' => $id]);
        return $sent->fetchObject(Cliente::class) ?: null;
    }

    public static function buscar_por_dni(string $dni): ?Cliente
    {
        $pdo = Cliente::pdo();
        $sent = $pdo->prepare('SELECT * FROM clientes WHERE dni = :dni');
        $sent->execute([':dni' => $dni]);
        return $sent->fetchObject(Cliente::class) ?: null;
    }

    public static function borrar_por_id(string|int $id): void
    {
        Cliente::buscar_por_id($id)?->borrar();
    }

    /**
     * Devuelve todos los clientes.
     *
     * @return Cliente[]
     */
    public static function todos(): array
    {
        $pdo = Cliente::pdo();
        $sent = $pdo->query('SELECT * FROM clientes');
        return $sent->fetchAll(PDO::FETCH_CLASS, Cliente::class);
    }

    public function borrar(): void
    {
        $pdo = Cliente::pdo();
        $sent = $pdo->prepare("DELETE FROM clientes WHERE id = :id");
        $sent->execute([':id' => $this->id]);
    }

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
        $sent = $pdo->prepare('UPDATE clientes
                                  SET dni = :dni,
                                      nombre = :nombre,
                                      apellidos = :apellidos,
                                      direccion = :direccion,
                                      codpostal = :codpostal,
                                      telefono = :telefono
                                WHERE id = :id');
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
        $sent = $pdo->prepare('INSERT INTO clientes (dni, nombre, apellidos, direccion, codpostal, telefono)
                               VALUES (:dni, :nombre, :apellidos, :direccion, :codpostal, :telefono)
                               RETURNING (id)');
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

    public static function pdo(): PDO
    {
        Cliente::$pdo = Cliente::$pdo ?? conectar();
        return Cliente::$pdo;
    }
}
