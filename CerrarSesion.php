<?php

session_start();

$serverName = "localhost"; 
$database = "Sistema_Gestion_Tareas01";
$username = "root";
$password = "12345Abc@"; 

try {
    $conn = new PDO("mysql:host=$serverName;dbname=$database;charset=utf8", $username, $password);
    
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $mensaje_error = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
       
        $email = $_POST['email'];
        $contrasena = $_POST['contrasena'];

       
        $sql = "SELECT IdUsuario, Nombre, Contrasena FROM Usuarios WHERE Email = :email"; 

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();


        if ($stmt->rowCount() > 0) {
          
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

  
            if (password_verify($contrasena, $usuario['Contrasena'])) {
   
                $_SESSION['id'] = $usuario['IdUsuario'];
                $_SESSION['nombre'] = $usuario['Nombre'];
                header("Location: Panel.php");
                exit();
            } else {
          
                $mensaje_error = "Contraseña incorrecta. Por favor, intenta de nuevo.";
            }
        } else {
       
            $mensaje_error = "No se encontró ninguna cuenta con ese correo electrónico.";
        }
    }
} catch (PDOException $e) {
    die("Conexión fallida: " . $e->getMessage());
}


$logoutMessage = "";
if (isset($_GET['logout']) && $_GET['logout'] === 'success') {
    $logoutMessage = "Has cerrado sesión exitosamente.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Sistema Gestor de Tareas</title>
    <link rel="stylesheet" href="css/bootstrap.min.css"> <
    <style>
        body {
            background-color: #f8f9fa;
        }
        h1 {
            margin-bottom: 30px; 
        .form-control {
            border-radius: 0.25rem; 
        }
        .btn {
            border-radius: 0.25rem; 
        }
        .btn-primary {
            background-color: #007bff;
            border: none; 
        }
        .btn-primary:hover {
            background-color: #0056b3; 
        }
        .btn-secondary {
            background-color: #6c757d; 
            border: none; 
        }
        .btn-secondary:hover {
            background-color: #5a6268;
        }
        .container {
            max-width: 600px; 
            padding: 20px;
            border: 1px solid #ced4da; 
            border-radius: 0.25rem; 
            background-color: #ffffff; 
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); 
        }
        .error-message {
            color: red; 
            margin-bottom: 15px;
        }
        .success-message {
            color: green; 
            margin-bottom: 15px;
        }
    </style>
</head>
<body class="d-flex flex-column justify-content-center align-items-center vh-100 bg-light">
    <div class="container mt-5">
        <h1 class="text-center">Iniciar Sesión</h1>

        <!-- Mostrar el mensaje de cierre de sesión si existe -->
        <?php if ($logoutMessage): ?>
            <div class="success-message text-center"><?php echo htmlspecialchars($logoutMessage); ?></div>
        <?php endif; ?>

        <!-- Mostrar el mensaje de error si existe -->
        <?php if (!empty($mensaje_error)): ?>
            <div class="error-message text-center"><?php echo htmlspecialchars($mensaje_error); ?></div>
        <?php endif; ?>

        <form action="" method="POST"> 
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="contrasena">Contraseña</label>
                <input type="password" class="form-control" id="contrasena" name="contrasena" required>
            </div>
            <button type="submit" class="btn btn-primary btn-lg btn-block">Iniciar Sesión</button>
            <a href="Registrase.php" class="btn btn-secondary btn-lg btn-block mt-2">Registrarse</a>
            <a href="index.html" class="btn btn-secondary btn-lg btn-block mt-2">Volver a Inicio</a>
        </form>
    </div>

    <script src="js/bootstrap.bundle.min.js"></script> 
</body>
</html>

