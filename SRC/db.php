<?php

// Configuración de la conexión
$serverName = "localhost"; 
$username = "root";
$password = "12345Abc@"; 
$database = "Sistema_Gestion_Tareas01"; 

// Crear conexión 
$conn = new mysqli($serverName, $username, $password, $database);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}




