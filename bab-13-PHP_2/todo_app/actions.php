<?php
require_once 'config/db.php';

$action = $_REQUEST['action'] ?? '';

switch ($action) {

    // ── CREATE
    case 'add':
        $task     = trim($conn->real_escape_string($_POST['task'] ?? ''));
        $due_date = trim($conn->real_escape_string($_POST['due_date'] ?? ''));
        $priority = trim($conn->real_escape_string($_POST['priority'] ?? 'medium'));

        if ($task === '') {
            header('Location: index.php?error=Task+tidak+boleh+kosong');
            exit;
        }

        $due_val = $due_date !== '' ? "'$due_date'" : 'NULL';
        $sql = "INSERT INTO todos (task, due_date, priority) VALUES ('$task', $due_val, '$priority')";
        $conn->query($sql);
        header('Location: index.php?success=Task+berhasil+ditambahkan');
        exit;

    // ── UPDATE STATUS (toggle)
    case 'toggle':
        $id = (int)($_GET['id'] ?? 0);
        $sql = "UPDATE todos SET status = IF(status='pending','completed','pending') WHERE id = $id";
        $conn->query($sql);
        header('Location: index.php');
        exit;

    // ── UPDATE TASK (edit)
    case 'edit':
        $id       = (int)($_POST['id'] ?? 0);
        $task     = trim($conn->real_escape_string($_POST['task'] ?? ''));
        $due_date = trim($conn->real_escape_string($_POST['due_date'] ?? ''));
        $priority = trim($conn->real_escape_string($_POST['priority'] ?? 'medium'));

        if ($task === '') {
            header('Location: index.php?error=Task+tidak+boleh+kosong');
            exit;
        }

        $due_val = $due_date !== '' ? "'$due_date'" : 'NULL';
        $sql = "UPDATE todos SET task='$task', due_date=$due_val, priority='$priority' WHERE id=$id";
        $conn->query($sql);
        header('Location: index.php?success=Task+berhasil+diupdate');
        exit;

    // ── DELETE
    case 'delete':
        $id = (int)($_GET['id'] ?? 0);
        $conn->query("DELETE FROM todos WHERE id = $id");
        header('Location: index.php?success=Task+berhasil+dihapus');
        exit;

    // ── DELETE ALL COMPLETED
    case 'clear_completed':
        $conn->query("DELETE FROM todos WHERE status = 'completed'");
        header('Location: index.php?success=Semua+task+selesai+dihapus');
        exit;

    default:
        header('Location: index.php');
        exit;
}
