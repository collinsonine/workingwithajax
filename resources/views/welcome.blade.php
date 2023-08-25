<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Car</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
    <style>
        .overlay {
            width: 100%;
            height: 300px;
            position: relative;
            background: rgb(255, 254, 254);
            margin-left: auto;
            margin-right: auto;
        }

        .overlay__inner {
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            position: absolute;
        }

        .overlay__content {
            left: 50%;
            position: absolute;
            top: 50%;
            transform: translate(-50%, -50%);
        }

        .spinner {
            width: 75px;
            height: 75px;
            display: inline-block;
            border-width: 2px;
            border-color: rgba(255, 255, 255, 0.05);
            border-top-color: #000000;
            animation: spin 1s infinite linear;
            border-radius: 100%;
            border-style: solid;
        }

        @keyframes spin {
        100% {
            transform: rotate(360deg);
        }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="col-8 mx-auto">
           <div class="card mt-5">
                <div class="card-header text-center h4">
                    Leaning Ajax
                </div>
                <div class="card-body">
                    <div class="mb-4 h6 text-center">Create Car</div>
                    <form action="" id="carsform" method="post">
                        <div class="form-group mb-3">
                            <label for="brand" class="form-label"> Brand: </label>
                            <input type="text" id="brand" name="brand" class="form-control brand" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="description" class="form-label"> Description: </label>
                            <textarea name="description" id="description" rows="4" class="form-control description" required></textarea>
                        </div>
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-secondary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="table-responsive mt-4 mx-auto" style="width: 80%">
            <div class="card">
                <div class="card-body">
                    <div class="overlay">
                        <div class="overlay__inner">
                            <div class="overlay__content"><span class="spinner"></span><p class="mt-2">Loading...</p></div>
                        </div>
                    </div>
                    <div class="tableclass d-none">
                        <div class="h4 text-center mb-3">
                            List of Cars
                        </div>
                        <table class="table table-striped" id="table">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Brand</th>
                                    <th>Description</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody class="tablebody">

                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js" integrity="sha512-uKQ39gEGiyUJl4AI6L+ekBdGKpGw4xJ55+xyJG7YFlJokPNYegn9KwQ3P8A7aFQAUtUsAQHep+d/lrGqrbPIDQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <script>
        $(document).ready(function () {
            loaddata();
        });

        //LOAD DATA FROM DB
        function loaddata(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "get",
                url: "{{route('car.index')}}",
                success: function (response) {
                    if (response.cars.length < 1) {
                        $('.tablebody').html('<tr><td colspan="4" class="text-center">No Data Found</td></tr>');
                        $('.tableclass').removeClass('d-none');
                        $('.overlay').addClass('d-none');
                    }else{
                        $(".tablebody > tr").remove();
                        $j = 1;
                        $.each(response.cars, function (i, value) {
                            $("#table").find('tbody')
                            .append($('<tr>')
                                .append($('<td>')
                                    .append($j++))
                                .append($('<td>')
                                    .append(value['brand']))

                                .append($('<td>')
                                    .append(value['description']))

                                .append($('<td>')
                                    .append('<div class="btn-group"><button class="btn btn-info"> <i class="fas fa-edit"></i></button>&nbsp;<button class="btn btn-danger" onclick="deleteCar(value='+value['id']+');"> <i class="fas fa-trash"></i></button></div>'))

                                )
                                });


                        $('.tableclass').removeClass('d-none');
                        $('.overlay').addClass('d-none');

                    }
                }
            });
        }

        function deleteCar(value){
            $.confirm({
                title: 'Delete!',
                content: 'Are you sure?',
                buttons: {
                    confirm: function () {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.ajax({
                            type: "delete",
                            url: "{{route('car.destroy', '')}}/" + value,
                            success: function (response) {
                                loaddata();
                                Swal.fire({
                                    title: 'Deleted!',
                                    html: response.message,
                                    icon: 'success'
                                })
                            }
                        });
                    },
                    cancel: function () {

                    },
                }
            });
            console.log(value);
        }

        //SAVE DATA TO DB
        $("#carsform").submit(function (e) {
            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "post",
                url: "{{route('car.store')}}",
                data: {
                    'brand' : $('.brand').val(),
                    'description' : $('.description').val(),
                },
                success: function (response) {
                    loaddata();
                    $('.brand').val('');
                    $('.description').val('');
                    Swal.fire({
                        title: 'Saved!',
                        html: response.message,
                        icon: 'success'
                    })
                }
            });
        });


    </script>
</body>
</html>
