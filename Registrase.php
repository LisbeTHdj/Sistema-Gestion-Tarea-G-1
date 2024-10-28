<?php

include 'SRC/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    if ($conn) {
    
        $nombre = $conn->real_escape_string($_POST['nombre']);
        $email = $conn->real_escape_string($_POST['email']);
        $contrasena = $_POST['contrasena'];
        $confirmarContrasena = $_POST['confirmarContrasena'];

     
        if ($contrasena !== $confirmarContrasena) {
            echo "<script>alert('Las contraseñas no coinciden. Por favor, intenta de nuevo.');</script>";
            exit();
        }

      
        $sql = "SELECT IdUsuario FROM Usuarios WHERE Email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            echo "<script>alert('El correo electrónico ya está registrado.');</script>";
            $stmt->close();
            exit();
        }

        
        $contrasenaEncriptada = password_hash($contrasena, PASSWORD_DEFAULT);


        $sql = "INSERT INTO Usuarios (Nombre, Email, Contrasena) VALUES (?, ?, ?)";

        
        try {
            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                die("Error en la preparación de la consulta: " . $conn->error);
            }

            $stmt->bind_param("sss", $nombre, $email, $contrasenaEncriptada);

            if ($stmt->execute()) {
               
                header("Location: IniciarSeccion.php");
                exit();
            } else {
                echo "Error en el registro: " . $stmt->error;
            }

           
            $stmt->close();
        } catch (Exception $e) {
            echo "Error en el registro: " . $e->getMessage();
        }
    } else {
   
        exit();
    }

    
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Sistema Gestor de Tareas</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        h1 {
            margin-bottom: 30px;
        }
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
    </style>
</head>
<body class="d-flex flex-column justify-content-center align-items-center vh-100 bg-light">

    <div class="container mt-5">
        <h1 class="text-center">Registro</h1>
        <form action="Registrase.php" method="POST">
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="contrasena">Contraseña</label>
                <input type="password" class="form-control" id="contrasena" name="contrasena" required>
            </div>
            <div class="form-group">
                <label for="confirmarContrasena">Confirmar Contraseña</label>
                <input type="password" class="form-control" id="confirmarContrasena" name="confirmarContrasena" required>
            </div>
            <button type="submit" class="btn btn-primary btn-lg btn-block">Registrar</button>
            <a href="index.html" class="btn btn-secondary btn-lg btn-block mt-2">Volver a Inicio</a>
        </form>
    </div>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
