<?php
require 'config.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $sql = "SELECT * FROM todo_list WHERE id = $id";
        } else {
            $sql = "SELECT * FROM todo_list";
        }
        $result = mysqli_query($conn, $sql);
        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        echo json_encode($data);
        break;

    case 'POST':
        $input = json_decode(file_get_contents('php://input'), true);
        $title = mysqli_real_escape_string($conn, $input['title']);
        $description = mysqli_real_escape_string($conn, $input['description']);
        $status = mysqli_real_escape_string($conn, $input['status']);

        $sql = "INSERT INTO todo_list (title, description, status) VALUES ('$title', '$description', '$status')";
        if (mysqli_query($conn, $sql)) {
            echo json_encode(['message' => 'Task created successfully']);
        } else {
            echo json_encode(['error' => mysqli_error($conn)]);
        }
        break;

    case 'PUT':
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $input = json_decode(file_get_contents('php://input'), true);
            $title = mysqli_real_escape_string($conn, $input['title']);
            $description = mysqli_real_escape_string($conn, $input['description']);
            $status = mysqli_real_escape_string($conn, $input['status']);

            $sql = "UPDATE todo_list SET title = '$title', description = '$description', status = '$status' WHERE id = $id";
            if (mysqli_query($conn, $sql)) {
                echo json_encode(['message' => 'Task updated successfully']);
            } else {
                echo json_encode(['error' => mysqli_error($conn)]);
            }
        } else {
            echo json_encode(['error' => 'ID is required']);
        }
        break;

    case 'DELETE':
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $sql = "DELETE FROM todo_list WHERE id = $id";
            if (mysqli_query($conn, $sql)) {
                echo json_encode(['message' => 'Task deleted successfully']);
            } else {
                echo json_encode(['error' => mysqli_error($conn)]);
            }
        } else {
            echo json_encode(['error' => 'ID is required']);
        }
        break;

    default:
        echo json_encode(['error' => 'Method not allowed']);
        break;
}

mysqli_close($conn);
?>
