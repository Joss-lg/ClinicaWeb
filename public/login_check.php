<?php
// 1. Permitir que Astro se conecte (CORS)
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json; charset=utf-8');

// 2. Si Astro hace una "pre-consulta" (OPTIONS), responder OK y salir
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// 3. Conexión a la base de datos (XAMPP Estándar)
// host, usuario, contraseña (vacia), nombre de BD que creaste en Shell
$host = "localhost";
$user = "root";
$pass = ""; 
$db   = "clinica_db";

$conn = new mysqli($host, $user, $pass, $db);

// Verificar si la conexión falló
if ($conn->connect_error) {
    echo json_encode([
        "success" => false,
        "message" => "Error de conexión MySQL: " . $conn->connect_error
    ]);
    exit;
}

// 4. Procesar el Login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar que lleguen los datos
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if (empty($email) || empty($password)) {
        echo json_encode(["success" => false, "message" => "Faltan datos en el formulario"]);
        exit;
    }

    // Consulta segura
    $stmt = $conn->prepare("SELECT id, nombre, especialidad, password FROM doctores WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // Comprobación de contraseña (Texto plano según lo que pusimos en la Shell)
        if ($password === $row['password']) {
            echo json_encode([
                "success" => true,
                "nombre" => $row['nombre'],
                "especialidad" => $row['especialidad']
            ]);
        } else {
            echo json_encode(["success" => false, "message" => "La contraseña es incorrecta"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "El correo no está registrado"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Método no permitido"]);
}

$conn->close();
?>