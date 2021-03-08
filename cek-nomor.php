<?php

if (isset($_POST["nomor"])) {
    $nomor = $_POST["nomor"];
    $data = [
        "nomor" => $nomor
    ];
    $data_string = json_encode($data);
    $url = 'http://localhost:3000/cek-nomor';
    $proxy = 'localhost:3000';
    //$proxyauth = 'user:password';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_PROXY, $proxy);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    // curl_setopt($ch, CURLOPT_HEADER, 1);
    $start_time = time();
    $result = curl_exec($ch);
    curl_close($ch);
    $result = json_decode($result);
    $end_time = time();
    $hasil = $end_time - $start_time;
    // var_dump(json_decode($result));
}
?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="favicon.ico" type="image/gif" sizes="16x16">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" integrity="sha512-HK5fgLBL+xu6dm/Ii3z4xhlSUyZgTT9tuc/hSrtw6uzJOvgRr2a9jyxxT1ely+B+xFAmJKVSTbpM/CuL7qxO8w==" crossorigin="anonymous" />
    <title>WhatsApp API</title>
    <style>
        .noselect {
            -webkit-touch-callout: none;
            /* iOS Safari */
            -webkit-user-select: none;
            /* Safari */
            -khtml-user-select: none;
            /* Konqueror HTML */
            -moz-user-select: none;
            /* Old versions of Firefox */
            -ms-user-select: none;
            /* Internet Explorer/Edge */
            user-select: none;
            /* Non-prefixed version, currently supported by Chrome, Edge, Opera and Firefox */
        }
    </style>
</head>

<body>
    <div class="container row mt-5">
        <div class="col-md-6 mb-5">
            <form method="post">
                <div class="form-floating">
                    <label for="floatingTextarea2">Input Nomor</label>
                    <textarea name="nomor" class="form-control" placeholder="Masukkan nomor disini" id="floatingTextarea2" style="height: 400px" autofocus></textarea>
                </div>
                <button type="submit" class="btn btn-primary mt-4">Cek Nomor</button>
            </form>
        </div>
        <div class="col-md-6 mb-5">
            <?php
            if (isset($result)) {
            ?>
                <label><span class="text-info"><?= count($result->hasil); ?></span> nomor berhasil dieksekusi dalam <?= $hasil; ?> detik.</label>
                <ul class="list-group" style="max-height: 400px;overflow-y: scroll;">
                    <?php
                    foreach ($result->hasil as $row) : ?>
                        <li class="list-group-item <?= $row->status == true ? '' : 'bg-danger text-white' ?>">
                            <?= $row->nomor; ?><i class="fas <?= $row->status == true ? 'fa-check' : 'fa-times' ?> ml-2 noselect"></i>
                        </li>
                    <?php
                    endforeach;
                    ?>
                </ul>
            <?php
            }
            ?>
        </div>
    </div>


    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script> -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js" integrity="sha384-q2kxQ16AaE6UbzuKqyBE9/u/KzioAlnx2maXQHiDX9d4/zp8Ok3f+M7DPm+Ib6IU" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.min.js" integrity="sha384-pQQkAEnwaBkjpqZ8RU1fF1AKtTcHJwFl3pblpTlHXybJjHpMYo79HY3hIi4NKxyj" crossorigin="anonymous"></script>
    -->
</body>

</html>