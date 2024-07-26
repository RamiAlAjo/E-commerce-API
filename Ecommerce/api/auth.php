<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

$request_method = $_SERVER["REQUEST_METHOD"];
if ($request_method == 'POST') {
    login();
} else {
    header("HTTP/1.0 405 Method Not Allowed");
}

function login()
{
    global $db;
    $data = json_decode(file_get_contents("php://input"));
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $db->prepare($query);
    $stmt->bindParam(1, $data->email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($data->password, $user['password'])) {
        $response = array('status' => 200, 'message' => 'Authenticated');
        header("HTTP/1.1 200 OK");
    } else {
        $response = array('status' => 401, 'message' => 'Authentication failed');
        header("HTTP/1.1 401 Unauthorized");
    }
    echo json_encode($response);
}
