<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *"); // Permite solicitudes desde cualquier origen (útil si la API y el frontend están en diferentes servidores)
header("Access-Control-Allow-Methods: GET");

// Datos de conexión
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "comentarios_db";

// Crear conexión segura
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die(json_encode(["error" => "Conexión fallida: " . $conn->connect_error]));
}

// Consultar los comentarios de la base de datos
$sql = "SELECT comentario, fecha FROM comentarios ORDER BY fecha DESC";
$result = $conn->query($sql);

$comentarios = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Asegurar que los datos se codifiquen correctamente en JSON
        $comentarios[] = [
            "comentario" => htmlspecialchars($row["comentario"], ENT_QUOTES, 'UTF-8'),
            "fecha" => $row["fecha"]
        ];
    }
}

// Devolver los comentarios en formato JSON
echo json_encode($comentarios, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

$conn->close();
?>