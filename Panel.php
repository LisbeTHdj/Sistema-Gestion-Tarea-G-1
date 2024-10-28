<?php 

session_start();


if (!isset($_SESSION['id']) || !isset($_SESSION['nombre'])) {
    
    header("Location: Iniciar seccion.php");
    exit();
}


include 'SRC/db.php';


$id_usuario = $_SESSION['id'];
$tareas = []; 


if ($conn) {
   
    $query = "SELECT Titulo, Descripcion, FechaVencimiento, Prioridad FROM Tareas WHERE IdUsuario = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_usuario); 
    $stmt->execute();
    $result = $stmt->get_result();

 
    while ($row = $result->fetch_assoc()) {
        $tareas[] = $row; 
    }
    
    $stmt->close(); 
} else {
    
    header("Location: error.php"); 
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema Gestor de Tareas</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 800px;
        }
        .list-group-item {
            border: 1px solid #dee2e6;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .navbar {
            padding: 10px 20px;
        }
        .welcome-title {
            font-size: 2rem;
            font-weight: bold;
            color: #343a40;
        }
        .btn-add-task {
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <!-- Barra de Navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">Gestor de Tareas</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#">Mis Tareas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Notificaciones</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="CerrarSesion.php">Cerrar Sesión</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Contenedor Principal -->
    <div class="container mt-5">
        <div class="text-center mb-4">
            <h2 class="welcome-title">Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre']); ?>!</h2>
            <p>Visualiza y organiza tus tareas</p>
        </div>

        <!-- Sección de Tareas -->
        <div class="row">
            <div class="col-md-12">
                <h4>Tus Tareas</h4>
                <div class="list-group">
                    <?php if ($tareas): ?>
                        <?php foreach ($tareas as $tarea): ?>
                            <a href="#" class="list-group-item list-group-item-action">
                                <h5 class="mb-1"><?php echo htmlspecialchars($tarea['Titulo']); ?></h5>
                                <p class="mb-1"><?php echo htmlspecialchars($tarea['Descripcion']); ?></p>
                                <small>Vencimiento: <?php echo htmlspecialchars($tarea['FechaVencimiento']); ?> | Prioridad: <?php echo htmlspecialchars($tarea['Prioridad']); ?></small>
                            </a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-center">No tienes tareas pendientes.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Botón para Agregar Tarea -->
        <div class="text-center">
            <a href="nueva_tarea.html" class="btn btn-primary btn-lg btn-add-task">Añadir Nueva Tarea</a>
        </div>
    </div>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>

