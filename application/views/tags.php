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
                        <div class="row">
                            <input type="text" id="tag_name" name="tag_name" />
                            <input type="button" id="add_item" name="add_item" value="Add" />
                        </div>
                        <div class="container" id="list_items" style="margin-top: 20px;"></div>
                    </div>
                </div>

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
    $(function() {
        $("#add_item").on("click", function(event) {
            if ($("#item_name").val() != "") {
                $.ajax({
                    url: '/api/tag/',
                    type: "POST",
                    data: {
                        'tag_name': $("#tag_name").val()
                    },
                    dataType: "json",
                    success: function(data) {
                        if (data.tags) {
                            $("#list_items").html("");
                            $.each(data.tags, function(index, tag) {
                                $("#list_items").append(
                                    `<div class="row list_item"><span> ${tag.tag_name} </span> - <a href="#" data-id="${tag.tag_id}" class="item_remove">X</a></div>`
                                );
                            });
                        }
                        checkAlerts(data);
                        $("#item_name").val();
                    }
                });
            } else {
                alert("Please enter tag name");
            }
        });
        $("#list_items").on("click", ".item_remove", function(event) {
            console.log($(this).data("id"));
            $.ajax({
                url: '/api/tag/',
                type: "DELETE",
                data: {
                    'tag_id': $(this).data("id")
                },
                dataType: "json",
                success: function(data) {
                    checkAlerts(data);
                    fetchTags();
                }
            });
        });

        function fetchTags() {
            $.ajax({
                url: '/api/tags/',
                type: "GET",
                dataType: "json",
                success: function(data) {
                    if (data.tags) {
                        $("#list_items").html("");
                        $.each(data.tags, function(index, tag) {
                            $("#list_items").append(
                                `<div class="row list_item"><span> ${tag.tag_name} </span> - <a href="#" data-id="${tag.tag_id}" class="item_remove">X</a></div>`
                            );
                        });
                    }
                    checkAlerts(data);
                }
            });
        }
        fetchTags();
    });
    </script>
</body>

</html>