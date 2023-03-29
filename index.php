<!DOCTYPE html>
<html>
    <head>
        <title>Sample Notes App</title>
        <link rel="stylesheet" href="css/style.css">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    </head>
    <body>
        <div class="wrapper">
            <?php require_once 'includes/sidebar.php'; ?>
            <div class="main">
              <form class="note-form" method="POST" action="includes/save.php">
                  <input type="hidden" name="note_id" value="<?= $notes[0]['id'] ?? '' ?>">
                  <input type="hidden" name="kayako_email" value="<?= htmlspecialchars($kayako_email, ENT_QUOTES) ?>">
                  <input type="text" name="title" placeholder="Title" />
                  <textarea name="content" class="large-textarea" placeholder="Note"></textarea>
                  <input type="text" name="new_folder_name" id="new_folder_name" placeholder="New folder name" style="display: none">
                  <select name="folder_id" onchange="showNewFolderInput(this)">
                      <option value="">--Select a folder--</option>
                      <?php foreach ($folders as $folder) { ?>
                      <option value="<?= $folder['id']; ?>"><?= $folder['name']; ?></option>
                      <?php } ?>
                      <option value="new_folder">Create new folder</option>
                  </select>
                  <input type="submit" value="Submit" />
                  <div id="success-msg" style="display: none;">Note has been saved.</div>
              </form>
            </div>
        </div>
        <script src="js/script.js"></script>
    </body>
</html>
