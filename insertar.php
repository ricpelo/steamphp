<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insertar un nuevo cliente</title>
</head>
<body>
    <?php
    require 'auxiliar.php';

    if (!esta_logueado()) {
        return;
    }

    $dni       = obtener_post('dni');
    $nombre    = obtener_post('nombre');
    $apellidos = obtener_post('apellidos');
    $direccion = obtener_post('direccion');
    $codpostal = obtener_post('codpostal');
    $telefono  = obtener_post('telefono');

    if (isset($dni, $nombre, $apellidos, $direccion, $codpostal, $telefono)) {
        $pdo = conectar();
        $error = [];
        validar_dni($dni, $error, $pdo);
        validar_nombre($nombre, $error);
        validar_sanear_apellidos($apellidos, $error);
        validar_sanear_direccion($direccion, $error);
        validar_sanear_codpostal($codpostal, $error);
        validar_sanear_telefono($telefono, $error);

        if (empty($error)) {
            $sent = $pdo->prepare('INSERT INTO clientes (dni, nombre, apellidos, direccion, codpostal, telefono)
                                   VALUES (:dni, :nombre, :apellidos, :direccion, :codpostal, :telefono)');
            $sent->execute([
                ':dni'       => $dni,
                ':nombre'    => $nombre,
                ':apellidos' => $apellidos,
                ':direccion' => $direccion,
                ':codpostal' => $codpostal,
                ':telefono'  => $telefono,
            ]);
            return volver_index();
        } else {
            mostrar_errores($error);
        }
    }
    ?>
    <?php cabecera() ?>
    <form action="" method="post">
        <label for="dni">DNI:* </label>
        <input type="text" id="dni"       name="dni" value="<?= $dni ?>"><br>
        <label for="nombre">Nombre:* </label>
        <input type="text" id="nombre"    name="nombre" value="<?= $nombre ?>"><br>
        <label for="apellidos">Apellidos: </label>
        <input type="text" id="apellidos" name="apellidos" value="<?= $apellidos ?>"><br>
        <label for="direccion">Dirección: </label>
        <input type="text" id="direccion" name="direccion" value="<?= $direccion ?>"><br>
        <label for="codpostal">Código postal: </label>
        <input type="text" id="codpostal" name="codpostal" value="<?= $codpostal ?>"><br>
        <label for="telefono">Teléfono: </label>
        <input type="text" id="telefono"  name="telefono" value="<?= $telefono ?>"><br>
        <button type="submit">Insertar</button>
        <a href="index.php">Volver</a>
    </form>
</body>
</html>
