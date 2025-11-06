<?php

namespace App\AR;

class Usuario extends ActiveRecord
{
    public $nick;
    public $password;

    #[\Override]
    protected static string $tabla = 'usuarios';

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
        $pdo = Usuario::pdo();
        $tabla = Usuario::$tabla;
        $sent = $pdo->prepare("UPDATE $tabla
                                  SET nick = :nick,
                                      password = :password,
                                WHERE id = :id");
        $sent->execute([
            ':id'       => $this->id,
            ':nick'     => $this->nick,
            ':password' => $this->password,
        ]);
    }

    private function insertar()
    {
        $pdo = Usuario::pdo();
        $tabla = Usuario::$tabla;
        $sent = $pdo->prepare("INSERT INTO $tabla (nick, password)
                               VALUES (:nick, :password)
                               RETURNING (id)");
        $sent->execute([
            ':nick'     => $this->nick,
            ':password' => $this->password,
        ]);
        $this->id = $sent->fetchColumn() ?: null;
    }
}
