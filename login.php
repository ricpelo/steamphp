<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <?php
    require 'auxiliar.php';

    $nick = obtener_post('nick');
    $password = obtener_post('password');

    if (isset($nick, $password)) {
        $pdo = conectar();
        $sent = $pdo->prepare('SELECT * FROM usuarios WHERE nick = :nick');
        $sent->execute([':nick' => $nick]);
        $fila = $sent->fetch();
        if ($fila && password_verify($password, $fila['password'])) {
            $_SESSION['nick'] = $nick;
            return volver_index();
        } else {
            echo "<h2>Error de credenciales incorrectas</h2>";
        }
    }
    ?>
    <form action="" method="post">
        <label for="nick">Nombre de usuario:</label>
        <input type="text" id="nick" name="nick"><br>
        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password"><br>
        <button type="submit">Iniciar sesión</button>
    </form>
</body>
</html>
