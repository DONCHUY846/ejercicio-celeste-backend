<!DOCTYPE html>
<html>
<head>
    <title>Verificación de Correo</title>
</head>
<body>
    <h1>Hola, {{ $user->persona->nombre }}</h1>
    <p>Gracias por registrarte. Por favor, verifica tu dirección de correo electrónico haciendo clic en el siguiente enlace:</p>
    <a href="{{ $url }}">Verificar Correo Electrónico</a>
    <p>Si no creaste esta cuenta, puedes ignorar este correo.</p>
</body>
</html>