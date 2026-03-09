<!DOCTYPE html>
<html>
<head>
    <title>Recuperación de Contraseña</title>
</head>
<body>
    <h1>Hola, {{ $user->persona->nombre }}</h1>
    <p>Has solicitado restablecer tu contraseña. Tu código de verificación (OTP) es:</p>
    <h2 style="font-size: 24px; font-weight: bold; letter-spacing: 5px;">{{ $otp }}</h2>
    <p>Este código es válido por 10 minutos.</p>
    <p>Si no solicitaste este cambio, por favor ignora este correo.</p>
</body>
</html>
