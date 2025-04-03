<?php
header("Content-Type: application/json");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "comentarios_db";

// Crear conexión segura
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die(json_encode(["success" => false, "error" => "Conexión fallida: " . $conn->connect_error]));
}

// Obtener el comentario del cuerpo de la solicitud
$data = json_decode(file_get_contents("php://input"), true);
$comentario = $data['comentario'] ?? '';

// Validar que el comentario no esté vacío
if (empty(trim($comentario))) {
    echo json_encode(["success" => false, "error" => "Comentario vacío"]);
    exit;
}

// Usar consulta preparada para evitar inyección SQL
$stmt = $conn->prepare("INSERT INTO comentarios (comentario, fecha) VALUES (?, NOW())");
$stmt->bind_param("s", $comentario);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => $stmt->error]);
}

$stmt->close();
$conn->close();
?>