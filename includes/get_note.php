<?php
require_once 'db_conn.php';

// Check if folder ID and/or note ID are provided in the POST data
$folderId = isset($_POST['folder_id']) ? $_POST['folder_id'] : null;
$noteId = isset($_POST['note_id']) ? $_POST['note_id'] : null;

if ($folderId !== null && $noteId !== null) {
  // If both folder ID and note ID are provided, retrieve the specific note
  $stmt = $conn->prepare('SELECT title, content, folder_id FROM notes WHERE id = ? AND folder_id = ?');
  $stmt->bind_param('ii', $noteId, $folderId);
  $stmt->execute();

  $result = $stmt->get_result();
  if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    echo json_encode($row);
  } else {
    echo json_encode(['error' => 'Note not found.']);
  }
} else if ($folderId === null && $noteId !== null) {
  // If folder ID is null and note ID is not null, load the form with the note details
  // This is used when the user clicks on a note in the notes list
  $stmt = $conn->prepare('SELECT id, title, content, folder_id FROM notes WHERE id = ?');
  $stmt->bind_param('i', $noteId);
  $stmt->execute();

  // Fetch the results and return them as a JSON-encoded string
  $result = $stmt->get_result();
  $note = $result->fetch_assoc();
  echo json_encode($note);
} else if ($folderId !== null && $noteId === null) {
  // If folder ID is not null and note ID is null, load the notes list for the selected folder
  // This is used when the user selects a folder from the folders list
  $stmt = $conn->prepare('SELECT id, title FROM notes WHERE folder_id = ?');
  $stmt->bind_param('i', $folderId);
  $stmt->execute();

  // Fetch the results and return them as a JSON-encoded array
  $result = $stmt->get_result();
  $notes = $result->fetch_all(MYSQLI_ASSOC);
  echo json_encode($notes);
} else if ($folderId === null && $noteId === null) {
  // If both folder ID and note ID are null, load all notes
  // This is used when the page is first loaded or when the "All Notes" folder is selected
  $stmt = $conn->prepare('SELECT id, title FROM notes');
  $stmt->execute();

  // Fetch the results and return them as a JSON-encoded array
  $result = $stmt->get_result();
  $notes = $result->fetch_all(MYSQLI_ASSOC);
  echo json_encode($notes);
}
?>