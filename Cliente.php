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

    public static PDO $pdo;

    public static function buscar_por_id(string|int $id): ?Cliente
    {
        $pdo = Cliente::pdo();
        $sent = $pdo->prepare('SELECT * FROM clientes WHERE id = :id');
        $sent->execute([':id' => $id]);
        return $sent->fetchObject(Cliente::class);
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

    private static function pdo(): PDO
    {
        Cliente::$pdo = Cliente::$pdo ?? conectar();
        return Cliente::$pdo;
    }
}
