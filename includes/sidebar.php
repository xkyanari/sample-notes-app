<?php
require_once 'db_conn.php';

// Get list of folders
$sql = "SELECT * FROM folders";
$result = $conn->query($sql);
$folders = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $folders[] = $row;
    }
}

// Get list of notes
$sql = "SELECT * FROM notes";
$result = $conn->query($sql);
$notes = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $notes[] = $row;
    }
}
?>

<div class="sidebar">
    <div class="sidebar-header">
        <h2>Folders</h2>
    </div>
    <div class="folder-list">
        <?php foreach ($folders as $folder) { ?>
        <a href="?folder_id=<?= $folder['id']; ?>" class="folder"><?= $folder['name']; ?></a>
        <?php } ?>
    </div>
</div>
<div class="sidebar">
    <div class="sidebar-header">
        <h2>Notes</h2>
    </div>
    <div class="note-list">
    <?php
        // Get list of notes for selected folder or all notes
        $sql = "SELECT * FROM notes";
        if (isset($_GET['folder_id'])) {
            $folder_id = $_GET['folder_id'];
            $sql .= " WHERE folder_id = $folder_id";
        }
        $result = $conn->query($sql);
        $notes = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $notes[] = $row;
            }
        }
    ?>
    <div class="notes-sidebar">
        <?php foreach ($notes as $note) { ?>
            <a href="#" class="note" data-id="<?= $note['id']; ?>"><?= $note['title']; ?></a>
        <?php } ?>
    </div>
</div>
</div>
