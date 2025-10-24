<?php

function conectar()
{
    return new PDO('pgsql:host=localhost;dbname=steam', 'steam', 'steam');
}

function obtener_get(string $par): ?string
{
    return isset($_GET[$par]) ? trim($_GET[$par]) : null;
}

function obtener_post(string $par): ?string
{
    return isset($_POST[$par]) ? trim($_POST[$par]) : null;
}

function volver_index()
{
    header('Location: index.php');
}

function validar_dni($dni, &$error, ?PDO $pdo = null)
{
    if ($dni === '') {
        $error[] = 'El DNI es obligatorio';
    } elseif (mb_strlen($dni) > 9) {
        $error[] = 'El DNI es demasiado largo';
    } else {
        if (buscar_cliente_por_dni($dni, $pdo)) {
            $error[] = 'Ya existe un cliente con ese DNI';
        }
    }
}

function validar_nombre($nombre, &$error)
{
    if ($nombre === '') {
        $error[] = 'El nombre es obligatorio';
    } elseif (mb_strlen($nombre) > 255) {
        $error[] = 'El nombre es demasiado largo';
    }
}

function validar_sanear_apellidos(&$apellidos, &$error)
{
    if ($apellidos === '') {
        $apellidos = null;
    } elseif (mb_strlen($apellidos) > 255) {
        $error[] = 'Los apellidos son demasiado largos';
    }
}

function validar_sanear_direccion(&$direccion, &$error)
{
    if ($direccion === '') {
        $direccion = null;
    } elseif (mb_strlen($direccion) > 255) {
        $error[] = 'La direccion es demasiado larga';
    }
}

function validar_sanear_codpostal(&$codpostal, &$error)
{
    if ($codpostal === '') {
        $codpostal = null;
    } elseif (!ctype_digit($codpostal)) {
        $error[] = 'El código postal no es válido';
    } elseif (mb_strlen($codpostal) > 5) {
        $error[] = 'El código postal es demasiado largo';
    }
}

function validar_sanear_telefono(&$telefono, &$error)
{
    if ($telefono === '') {
        $telefono = null;
    } elseif (mb_strlen($telefono) > 255) {
        $error[] = 'El teléfono es demasiado largo';
    }
}

function mostrar_errores(array $error): void
{
    foreach ($error as $mensaje) { ?>
        <h3>Error: <?= $mensaje ?></h3><?php
    }
}

function buscar_cliente($id, ?PDO $pdo = null): array|false
{
    $pdo = $pdo ?? conectar();
    $sent = $pdo->prepare('SELECT * FROM clientes WHERE id = :id');
    $sent->execute([':id' => $id]);
    return $sent->fetch();
}

function buscar_cliente_por_dni($dni, ?PDO $pdo = null): array|false
{
    $pdo = $pdo ?? conectar();
    $sent = $pdo->prepare('SELECT * FROM clientes WHERE dni = :dni');
    $sent->execute([':dni' => $dni]);
    return $sent->fetch();
}

function validar_dni_update($dni, $id, &$error, ?PDO $pdo = null)
{
    if ($dni === '') {
        $error[] = 'El DNI es obligatorio';
    } elseif (mb_strlen($dni) > 9) {
        $error[] = 'El DNI es demasiado largo';
    } else {
        $pdo = $pdo ?? conectar();
        $sent = $pdo->prepare('SELECT *
                                 FROM clientes
                                WHERE dni = :dni AND id != :id');
        $sent->execute([':dni' => $dni, ':id' => $id]);
        if ($sent->fetch()) {
            $error[] = 'Ya existe un cliente con ese DNI';
        }
    }
}

function cabecera()
{ ?>
    <div align="right">
        <?= $_SESSION['nick'] ?>
        <a href="logout.php">Logout</a>
    </div>
    <hr><?php
}

function esta_logueado()
{
    if (!isset($_SESSION['nick'])) {
        header('Location: login.php');
        return false;
    }
    return true;
}
