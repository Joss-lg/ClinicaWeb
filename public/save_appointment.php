<?php
// Permitir que Astro (en el puerto 4321) se comunique con XAMPP
header("Access-Control-Allow-Origin: http://localhost:4321");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

// 1. Configuración de conexión
$host = "localhost";
$user = "root";
$pass = ""; // En XAMPP suele estar vacío
$db   = "clinica_privada";

$conn = new mysqli($host, $user, $pass, $db);

// Verificar conexión
if ($conn->connect_error) {
    echo json_encode(["success" => false, "error" => "Fallo de conexión"]);
    exit;
}

// 2. Capturar datos del formulario (deben coincidir con el atributo 'name' del HTML)
$nombre = $_POST['nombre'] ?? '';
$telefono = $_POST['telefono'] ?? '';
$especialidad = $_POST['especialidad'] ?? '';
$mensaje = $_POST['mensaje'] ?? '';

// 3. Preparar la consulta SQL para evitar inyecciones
$stmt = $conn->prepare("INSERT INTO citas (nombre, telefono, especialidad, mensaje) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $nombre, $telefono, $especialidad, $mensaje);

// 4. Ejecutar y responder
if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => $stmt->error]);
}

$stmt->close();
$conn->close();
?>