<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <title>E-Book Web Service</title>
</head>
<body>
    <div class="container" style="margin-top: 50px;">
        <h4 class="text-center">E-Book</h4><br>
        <h5>Tambahkan Buku</h5>
        <div class="card card-default">
            <div class="card-body">
                <form id="addBook" class="form-inline" method="POST" action="">
                    <div class="form-group mb-2">
                        <label for="judul" class="sr-only">Judul</label>
                        <input id="judul" type="text" class="form-control" name="judul" placeholder="Judul"
                            required autofocus>
                    </div>
                    <div class="form-group mx-sm-3 mb-2">
                        <label for="pengarang" class="sr-only">Pengarang</label>
                        <input id="pengarang" type="text" class="form-control" name="pengarang" placeholder="Pengarang"
                            required autofocus>
                    </div>
                    <button id="submitBook" type="button" class="btn btn-primary mb-2">Submit</button>
                </form>
            </div>
        </div>

        <br>

        <h5>Daftar Buku</h5>
        <table class="table table-bordered">
            <tr>
                <th>Judul</th>
                <th>Pengarang</th>
                <th width="180" class="text-center">Action</th>
            </tr>
            <tbody id="tbody">

            </tbody>
        </table>
    </div>

    <!-- Update Model -->
    <form action="" method="POST" class="users-update-record-model form-horizontal">
        <div id="update-modal" data-backdrop="static" data-keyboard="false" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" style="width:55%;">
                <div class="modal-content" style="overflow: hidden;">
                    <div class="modal-header">
                        <h4 class="modal-title" id="custom-width-modalLabel">Edit</h4>
                        <button type="button" class="close" data-dismiss="modal"
                                aria-hidden="true">×
                        </button>
                    </div>
                    <div class="modal-body" id="updateBody">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light"
                                data-dismiss="modal">Tutup
                        </button>
                        <button type="button" class="btn btn-success updateBook">Edit
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Delete Model -->
    <form action="" method="POST" class="users-remove-record-model">
        <div id="remove-modal" data-backdrop="static" data-keyboard="false" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel"
            aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-dialog-centered" style="width:55%;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="custom-width-modalLabel">Hapus</h4>
                        <button type="button" class="close remove-data-from-delete-form" data-dismiss="modal"
                                aria-hidden="true">×
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menghapus data ini?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect remove-data-from-delete-form"
                                data-dismiss="modal">Tutup
                        </button>
                        <button type="button" class="btn btn-danger waves-effect waves-light deleteRecord">Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>


    <!-- {{--Firebase Tasks--}} -->
    <script src="https://code.jquery.com/jquery-3.4.0.min.js"></script>
    <script src="https://www.gstatic.com/firebasejs/5.10.1/firebase.js"></script>
    <script>
    // Initialize Firebase
    var config = {
        apiKey: "{{ config('services.firebase.api_key') }}",
        authDomain: "{{ config('services.firebase.auth_domain') }}",
        databaseURL: "{{ config('services.firebase.database_url') }}",
        storageBucket: "{{ config('services.firebase.storage_bucket') }}",
    };
    firebase.initializeApp(config);

    var database = firebase.database();

    var lastIndex = 0;

    // Get Data
    firebase.database().ref('books/').on('value', function (snapshot) {
        var value = snapshot.val();
        var htmls = [];
        $.each(value, function (index, value) {
            if (value) {
                htmls.push('<tr>\
                <td>' + value.judul + '</td>\
                <td>' + value.pengarang + '</td>\
                <td><button data-toggle="modal" data-target="#update-modal" class="btn btn-info updateData" data-id="' + index + '">Edit</button>\
                <button data-toggle="modal" data-target="#remove-modal" class="btn btn-danger removeData" data-id="' + index + '">Hapus</button></td>\
            </tr>');
            }
            lastIndex = index;
        });
        $('#tbody').html(htmls);
        $("#submitUser").removeClass('desabled');
    });

    // Add Data
    $('#submitBook').on('click', function () {
        var values = $("#addBook").serializeArray();
        var judul = values[0].value;
        var pengarang = values[1].value;
        var userID = lastIndex + 1;

        console.log(values);

        firebase.database().ref('books/' + userID).set({
            judul: judul,
            pengarang: pengarang,
        });

        // Reassign lastID value
        lastIndex = userID;
        $("#addBook input").val("");
    });

    // Update Data
    var updateID = 0;
    $('body').on('click', '.updateData', function () {
        updateID = $(this).attr('data-id');
        firebase.database().ref('books/' + updateID).on('value', function (snapshot) {
            var values = snapshot.val();
            var updateData = '<div class="form-group">\
                <label for="first_name" class="col-md-12 col-form-label">Judul</label>\
                <div class="col-md-12">\
                    <input id="first_name" type="text" class="form-control" name="judul" value="' + values.judul + '" required autofocus>\
                </div>\
            </div>\
            <div class="form-group">\
                <label for="last_name" class="col-md-12 col-form-label">Pengarang</label>\
                <div class="col-md-12">\
                    <input id="last_name" type="text" class="form-control" name="pengarang" value="' + values.pengarang + '" required autofocus>\
                </div>\
            </div>';

            $('#updateBody').html(updateData);
        });
    });

    $('.updateBook').on('click', function () {
        var values = $(".users-update-record-model").serializeArray();
        var postData = {
            judul: values[0].value,
            pengarang: values[1].value,
        };

        var updates = {};
        updates['/books/' + updateID] = postData;

        firebase.database().ref().update(updates);

        $("#update-modal").modal('hide');
    });

    // Remove Data
    $("body").on('click', '.removeData', function () {
        var id = $(this).attr('data-id');
        $('body').find('.users-remove-record-model').append('<input name="id" type="hidden" value="' + id + '">');
    });

    $('.deleteRecord').on('click', function () {
        var values = $(".users-remove-record-model").serializeArray();
        var id = values[0].value;
        firebase.database().ref('books/' + id).remove();
        $('body').find('.users-remove-record-model').find("input").remove();
        $("#remove-modal").modal('hide');
    });
    $('.remove-data-from-delete-form').click(function () {
        $('body').find('.users-remove-record-model').find("input").remove();
    });
    </script>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>