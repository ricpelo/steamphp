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
    require_once 'auxiliar.php';
    require_once 'Cliente.php';

    if (!esta_logueado()) {
        return;
    }

    $_csrf     = obtener_post('_csrf');
    $dni       = obtener_post('dni');
    $nombre    = obtener_post('nombre');
    $apellidos = obtener_post('apellidos');
    $direccion = obtener_post('direccion');
    $codpostal = obtener_post('codpostal');
    $telefono  = obtener_post('telefono');

    if (isset($_csrf, $dni, $nombre, $apellidos, $direccion, $codpostal, $telefono)) {
        if (!comprobar_csrf($_csrf)) {
            return volver_index();
        }
        $pdo = Cliente::pdo();
        $pdo->beginTransaction();
        $pdo->exec('LOCK TABLE clientes IN SHARE MODE;');
        $error = [];
        validar_dni($dni, $error);
        validar_nombre($nombre, $error);
        validar_sanear_apellidos($apellidos, $error);
        validar_sanear_direccion($direccion, $error);
        validar_sanear_codpostal($codpostal, $error);
        validar_sanear_telefono($telefono, $error);

        if (empty($error)) {
            $cliente = new Cliente([
                'dni'       => $dni,
                'nombre'    => $nombre,
                'apellidos' => $apellidos,
                'direccion' => $direccion,
                'codpostal' => $codpostal,
                'telefono'  => $telefono,
            ]);
            $cliente->guardar();
            $pdo->commit();
            $_SESSION['exito'] = 'El cliente se ha insertado correctamente';
            return volver_index();
        } else {
            $pdo->rollBack();
            // $_SESSION['fallo'] = 'No se ha podido insertar el cliente';
            cabecera();
            mostrar_errores($error);
        }
    } else {
        cabecera();
    }
    ?>
    <form action="" method="post">
        <?php campo_csrf() ?>
        <label for="dni">DNI:* </label>
        <input type="text" id="dni"       name="dni" value="<?= hh($dni) ?>"><br>
        <label for="nombre">Nombre:* </label>
        <input type="text" id="nombre"    name="nombre" value="<?= hh($nombre) ?>"><br>
        <label for="apellidos">Apellidos: </label>
        <input type="text" id="apellidos" name="apellidos" value="<?= hh($apellidos) ?>"><br>
        <label for="direccion">Dirección: </label>
        <input type="text" id="direccion" name="direccion" value="<?= hh($direccion) ?>"><br>
        <label for="codpostal">Código postal: </label>
        <input type="text" id="codpostal" name="codpostal" value="<?= hh($codpostal) ?>"><br>
        <label for="telefono">Teléfono: </label>
        <input type="text" id="telefono"  name="telefono" value="<?= hh($telefono) ?>"><br>
        <button type="submit">Insertar</button>
        <a href="index.php">Volver</a>
    </form>
</body>
</html>
