<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');

// Desactivar reporte de errores visuales de MySQL para que no rompan el JSON
mysqli_report(MYSQLI_REPORT_OFF); 

$conn = new mysqli("localhost", "root", "", "clinica_db");

// FUNDAMENTAL: Configurar UTF-8 para las tildes de las especialidades
$conn->set_charset("utf8");

if ($conn->connect_error) {
    echo json_encode(["error" => "Conexión fallida"]);
    exit;
}

$especialidad = $_GET['esp'] ?? '';

try {
    // Asegúrate que tu tabla se llame 'citas' y tenga la columna 'especialidad'
    $stmt = $conn->prepare("SELECT paciente, fecha, hora, motivo, tel FROM citas WHERE especialidad = ?");
    $stmt->bind_param("s", $especialidad);
    $stmt->execute();
    $result = $stmt->get_result();

    $citas = [];
    while($row = $result->fetch_assoc()) {
        $citas[] = $row;
    }

    echo json_encode($citas);

} catch (Exception $e) {
    // Si hay error de SQL, enviamos un JSON vacío en lugar de un error HTML
    echo json_encode([]);
}

$conn->close();