const getKayakoEmail = () => {
    let email = '';
    // attempts to get the email address from API
    $.getJSON('https://example.com/api/get_kayako_email.php', function(response) {
        email = response.kayako_email;
    })
    .fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown);
    });
    return email;
};

let kayako_email = getKayakoEmail();

export function showNewFolderInput(select) {
    let input = document.getElementById("new_folder_name");
    if (select.value === "new_folder") {
      input.style.display = "block";
    } else {
      input.style.display = "none";
    }
};

function clearCookie(name) {
    document.cookie = name + '=;expires=Thu, 01 Jan 1970 00:00:01 GMT;';
};

function setCookie(name, value, days) {
    let expires = "";
    if (days) {
        let date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + value + expires + "; path=/";
};

function getCookie(name) {
    const value = "; " + document.cookie;
    const parts = value.split("; " + name + "=");
    if (parts.length === 2) {
        return parts.pop().split(";").shift();
    }
};

$(document).ready(function() {
    // Check if there are any URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    if (!urlParams.has('folder_id')) {
        clearCookie('note_id');
    }

    // Set the kayako_email cookie if it doesn't exist -- this is for dev purposes only
    if (!getCookie('kayako_email')) {
    setCookie('kayako_email', kayako_email, 1); // Expires in 1 day
    }

    let cookieValue = getCookie('kayako_email');
    if (cookieValue) {
        let kayako_email = cookieValue;
        let noteId = parseInt(getCookie('note_id')); // attempts to get the note_id from cookie

        // if noteId is present
        if (!isNaN(noteId)) {
            $('input[name=note_id]').val(noteId);
            $('.note[data-id=' + noteId + ']').trigger('click');
            refreshNotesList(null, noteId, kayako_email);
        } else {
            // if noteId is missing, then just load the notes list for the selected folder
            // Get all folder elements and listen for click events
            $('.folder').on('click', function() {
                // Clear the note ID and the form
                $('input[name=note_id]').val('');
                $('input[name=title]').val('');
                $('textarea[name=content]').val('');

                // Get the folder ID from the clicked element's data-id attribute
                const folderId = $(this).data('id');

                // Call refreshNotesList() with the folder ID
                refreshNotesList(folderId, null, kayako_email);
            });
        }
    } else {
    console.log("cookieValue not found.");
    }
});

function refreshNotesList(folderId, noteId, kayako_email) {
    $.ajax({
        url: 'includes/get_notes.php',
        type: 'POST',
        data: { folder_id: folderId },
        dataType: 'json',
        success: function(response) {
            let $notesList = $('#notes-list');
            $notesList.empty(); // Remove all existing notes
            
            // Loop through the response and add each note to the list
            for (let i = 0; i < response.length; i++) {
            let note = response[i];
            let $note = $('<div class="note" data-id="' + note.id + '">' + note.title + '</div>');
            $notesList.append($note);
            }
            
            // If a note ID was passed in, select the corresponding note in the list
            if (noteId !== null) {
            $('.note').removeClass('active');
            $('.note[data-id=' + noteId + ']').addClass('active');
            }
            
            $notesList.show(); // Show the notes list again now that the filtered data has been added
            hideLoadingIndicator();
        },
        error: function(xhr, status, error) {
            console.log(error);
            hideLoadingIndicator();
        }
    });
};

function showLoadingIndicator() {
    $('.loading').show();
};

function hideLoadingIndicator() {
    $('.loading').hide();
};

$(document).on('click', '.note', function(e) {
    e.preventDefault();
    $('.note').removeClass('active');
    $(this).addClass('active');
    let noteId = $(this).data('id');
    $('input[name=note_id]').val(noteId); // set the value of the hidden input field
    showLoadingIndicator();
    $.ajax({
        url: 'includes/get_note.php',
        type: 'POST',
        data: { note_id: noteId },
        dataType: 'json',
        success: function(response) {
        console.log(response);
        $('input[name=title]').val(response.title);
        $('textarea[name=content]').val(response.content);
        $('select[name=folder_id]').val(response.folder_id);
        hideLoadingIndicator();
        },
        error: function(xhr, status, error) {
        console.log(error);
        hideLoadingIndicator();
        }
    });
});

// Handle form submission
$('.note-form').on('submit', function(e) {
    e.preventDefault();
    let $form = $(this);
    if ($form.length > 0) {
        let formData = $form.serialize();
        let folderId = $('select[name=folder_id]').val();
        let noteId = parseInt($('input[name=note_id]').val());
        
        if (!noteId || isNaN(noteId)) {
        noteId = null;
        }
        
        if (noteId !== null) {
        // Update the existing note
        formData += '&note_id=' + noteId;
        } else {
        // Create a new note
        folderId = $('select[name=folder_id]').val();
        formData += '&folder_id=' + folderId;
        }
        
        // Add kayako_email to formData
        formData += '&kayako_email=' + encodeURIComponent(kayako_email);
        
        $.ajax({
        url: 'includes/save.php',
        type: 'POST',
        data: formData,
        success: function(response) {
            // Show success message
            $('#success-msg').show();
            
            // Hide success message after 5 seconds
            setTimeout(function() {
            $('#success-msg').hide();
            }, 5000);
        },
        error: function(xhr, status, error) {
            console.log(error);
            alert('There was an error saving the note. Please try again.');
        }
        });
    } else {
        console.log('Form not found');
    }
});
