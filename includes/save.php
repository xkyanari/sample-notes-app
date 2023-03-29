<?php
ob_start(); // start output buffering

require_once 'db_conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $folder_id = $_POST['folder_id'];
    $new_folder_name = trim($_POST['new_folder_name']);
    $kayako_email = trim($_POST['kayako_email']);

    // Check if note_id is set
    if (isset($_POST['note_id'])) {
        $note_id = $_POST['note_id'];
    }

    // Check if folder name is filled out
    if (!empty($new_folder_name) && empty($title) && empty($content)) {
        $stmt = $conn->prepare("INSERT INTO folders (name, kayako_email) VALUES (?, ?)");
        $stmt->bind_param("ss", $new_folder_name, $kayako_email);
        $result = $stmt->execute();
        if ($result === FALSE) {
            $error = "Error creating folder: " . $conn->error;
        } else {
            $folder_id = $stmt->insert_id;
        }
    } else {
        // Check if title and content are filled out
        if (empty($title) || empty($content)) {
            $error = "Title and content fields are required.";
        } else {
            // Update note
            $stmt = $conn->prepare("UPDATE notes SET title=?, content=?, folder_id=?, kayako_email=? WHERE id=?");
            $stmt->bind_param("ssisi", $title, $content, $folder_id, $kayako_email, $note_id);
            $result = $stmt->execute();
            if ($result === FALSE) {
                $error = "Error updating note: " . $conn->error;
            } else {
                // Store the note ID in a cookie
                $cookieName = $kayako_email . '_notes';
                setcookie($cookieName, $note_id, time() + (86400 * 30), "/");
            }
        }
    }

    if (isset($error)) {
        echo "<script>alert('$error');</script>";
    } else {
        header('Location: ../widget.main.php?folder_id=' . $folder_id);
        exit();
    }
}

ob_end_flush(); // flush output buffer and send output
?>
