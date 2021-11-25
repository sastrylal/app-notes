<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Home - Login</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="/assets/css/sb-admin-2.min.css" rel="stylesheet">
    <style>
    .note .note-date {
        float: right;
    }
    </style>
</head>

<body class="bg-gradient-primary">
    <div class="container">
        <!-- Outer Row -->
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div id="alert_block" style="margin-top: 20px;">
                    <?php getMessage(); ?>
                </div>
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body">
                        <a href="/home/notes/">Notes</a> | <a href="/home/tags/">Tags</a>
                    </div>
                </div>
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body">
                        <div class="row" style="margin-bottom: 40px;">
                            <input type="button" class="btn btn-primary" id="add_note_btn" value="Add" />
                        </div>
                        <div id="notes_block">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="noteModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form id="note_form" onsubmit="return noteFormSubmit();">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Note</h5>
                        <!-- <button class="btn-close close-modal" type="button" data-bs-dismiss="modal"></button> -->
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="note_title">Title</label>
                            <input class="form-control" id="note_title" name="note_title" type="text" placeholder=""
                                required="true" />
                        </div>
                        <div class="mb-0">
                            <label for="note_description">Description</label>
                            <textarea class="form-control" id="note_description" name="note_description"
                                rows="3"></textarea>
                        </div>
                        <div class="mb-0">
                            <label for="note_tags">Tags</label>
                            <div id="tags_block">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="note_tags[]" value="" />
                                    <label class="form-check-label">Default checkbox</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary close-modal" type="button" data-bs-dismiss="modal">Close</button>
                        <button class="btn btn-primary" type="submit" data-bs-dismiss="modal">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Bootstrap core JavaScript-->
    <script src="/assets/vendor/jquery/jquery.min.js"></script>
    <script src="/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="/assets/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="/assets/js/sb-admin-2.min.js"></script>
    <script src="/assets/js/main.js"></script>
    <script>
    function noteFormSubmit() {
        var formData = $('#note_form').serialize();
        $.ajax({
            url: '/api/note/',
            type: "POST",
            data: formData,
            dataType: "json",
            success: function(data) {
                console.log(data);
                $('#noteModal').modal('hide');
                checkAlerts(data);
            }
        });
        return false;
    }
    $(function() {
        $(".close-modal").on("click", function(event) {
            $('#noteModal').modal('hide');
        });
        $("#add_note_btn").on("click", function(event) {
            $('#noteModal').modal('show');
        });

        function fetchNotes() {
            $.ajax({
                url: '/api/notes/',
                type: "GET",
                dataType: "json",
                success: function(data) {
                    if (data.notes) {
                        $("#notes_block").html("");
                        $.each(data.notes, function(index, note) {
                            console.log(note);
                            note.note_tags = '';
                            $.each(note.tags, function(index, tag) {
                                note.note_tags +=
                                    ` <span class="btn btn-primary">${tag.tag_name}</span> `;
                            });
                            $("#notes_block").append(
                                `<div class="row note">
                                <h4 class="note-title">${note.note_title}</h4>
                                <span class="note-date right">${note.created_on}</span>
                                <p class="note-description">${note.note_description}</p>
                                <div class="note-tags"> ${note.note_tags} </div>
                            </div>
                            <hr />`
                            );
                        });
                    }
                    checkAlerts(data);
                }
            });
        }

        function fetchTags() {
            $.ajax({
                url: '/api/tags/',
                type: "GET",
                dataType: "json",
                success: function(data) {
                    if (data.tags) {
                        $("#tags_block").html("");
                        $.each(data.tags, function(index, tag) {
                            $("#tags_block").append(
                                `<div class="form-check">
                                <label class="form-check-label">
                                <input class="form-check-input" type="checkbox" name="note_tags[]" value="${tag.tag_id}" />
                                ${tag.tag_name}</label>
                                </div>`
                            );
                        });
                    }
                }
            });
        }
        fetchTags();
        fetchNotes();
    });
    </script>
</body>

</html>