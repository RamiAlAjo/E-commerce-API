<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

$request_method = $_SERVER["REQUEST_METHOD"];
switch ($request_method) {
    case 'GET':
        if (!empty($_GET["id"])) {
            $id = intval($_GET["id"]);
            get_user($id);
        } else {
            get_users();
        }
        break;
    case 'POST':
        insert_user();
        break;
    case 'PUT':
        $id = intval($_GET["id"]);
        update_user($id);
        break;
    case 'DELETE':
        $id = intval($_GET["id"]);
        delete_user($id);
        break;
    default:
        header("HTTP/1.0 405 Method Not Allowed");
        break;
}

function get_users()
{
    global $db;
    $query = "SELECT * FROM users";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($users);
}

function get_user($id)
{
    global $db;
    $query = "SELECT * FROM users WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->bindParam(1, $id);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($user);
}

function insert_user()
{
    global $db;
    $data = json_decode(file_get_contents("php://input"));
    $query = "INSERT INTO users(name, email, password) VALUES(:name, :email, :password)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':name', $data->name);
    $stmt->bindParam(':email', $data->email);
    $stmt->bindParam(':password', password_hash($data->password, PASSWORD_BCRYPT));
    if ($stmt->execute()) {
        $response = array('status' => 201, 'message' => 'User created successfully.');
        header("HTTP/1.1 201 Created");
    } else {
        $response = array('status' => 500, 'message' => 'User creation failed.');
        header("HTTP/1.1 500 Internal Server Error");
    }
    echo json_encode($response);
}

function update_user($id)
{
    global $db;
    $data = json_decode(file_get_contents("php://input"));
    $query = "UPDATE users SET name = :name, email = :email, password = :password WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':name', $data->name);
    $stmt->bindParam(':email', $data->email);
    $stmt->bindParam(':password', password_hash($data->password, PASSWORD_BCRYPT));
    $stmt->bindParam(':id', $id);
    if ($stmt->execute()) {
        $response = array('status' => 200, 'message' => 'User updated successfully.');
        header("HTTP/1.1 200 OK");
    } else {
        $response = array('status' => 500, 'message' => 'User update failed.');
        header("HTTP/1.1 500 Internal Server Error");
    }
    echo json_encode($response);
}

function delete_user($id)
{
    global $db;
    $query = "DELETE FROM users WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->bindParam(1, $id);
    if ($stmt->execute()) {
        $response = array('status' => 200, 'message' => 'User deleted successfully.');
        header("HTTP/1.1 200 OK");
    } else {
        $response = array('status' => 500, 'message' => 'User deletion failed.');
        header("HTTP/1.1 500 Internal Server Error");
    }
    echo json_encode($response);
}
