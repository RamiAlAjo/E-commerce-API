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
        if (!empty($_GET["name"])) {
            $name = $_GET["name"];
            search_products($name);
        } else {
            get_products();
        }
        break;
    case 'POST':
        insert_product();
        break;
    default:
        header("HTTP/1.0 405 Method Not Allowed");
        break;
}

function get_products()
{
    global $db;
    $query = "SELECT * FROM products";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($products);
}

function search_products($name)
{
    global $db;
    $query = "SELECT * FROM products WHERE name LIKE ?";
    $stmt = $db->prepare($query);
    $search = "%{$name}%";
    $stmt->bindParam(1, $search);
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($products);
}

function insert_product()
{
    global $db;
    $data = json_decode(file_get_contents("php://input"));
    $query = "INSERT INTO products(name, description, price) VALUES(:name, :description, :price)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':name', $data->name);
    $stmt->bindParam(':description', $data->description);
    $stmt->bindParam(':price', $data->price);
    if ($stmt->execute()) {
        $response = array('status' => 201, 'message' => 'Product created successfully.');
        header("HTTP/1.1 201 Created");
    } else {
        $response = array('status' => 500, 'message' => 'Product creation failed.');
        header("HTTP/1.1 500 Internal Server Error");
    }
    echo json_encode($response);
}
