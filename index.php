<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilos/login.css">
    <title>Iniciar Sesión</title>
</head>
<body>

    <form action="includes/login_verification.php" method="post">
        <h1>INICIAR SESIÓN</h1>
        <h2>- Portal de Base de Datos -</h2>

        <label for="email">Correo Electrónico:</label>
        <input type="email" name="email" required>

        <label for="password">Contraseña:</label>
        <input type="password" name="password" required>

        <button type="submit">ACCEDER</button>
    </form>

</body>
</html>