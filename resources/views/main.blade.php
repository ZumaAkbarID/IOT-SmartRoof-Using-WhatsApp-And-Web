<!doctype html>
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Smart Roof System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <h1 class="text-center">Smart Roof System</h1>
                <p class="text-center">
                    <span id="jam"></span>
                    <span id="tanggal"></span>
                </p>
            </div>
        </div>
    </div>

    <!-- <div class="container mt-3">
        <input type="hidden" name="statusMode" value="{{ $latest ? $latest->status_sistem : '' }}">
        <button class="btn btn-info" id="requestFirstData">Minta Data Pertama</button>
        <button class="btn btn-primary" id="changeMode">Ganti Mode</button>
        <button class="btn btn-success" id="changeRoof">Tutup</button>
    </div> -->

    <div class="container mt-3">
        <div class="row">
            <div class="col-md-4 mt-2">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Status Sistem</h5>
                        <div id="status-sistem">
                          @if ($latest && $latest->status_sistem == 'Otomatis')
                          <p class='card-text badge bg-success p-2'>Otomatis</p>
                          @elseif($latest && $latest->status_sistem == 'Manual')
                          <p class='card-text badge bg-primary p-2'>Manual</p>
                          @else
                          <p class='card-text badge bg-secondary p-2'>Tunggu data terupdate</p>
                          @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mt-2">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Status Hujan</h5>
                        <div id="status-hujan">
                          @if ($latest && $latest->status_hujan == 'Hujan')
                          <p class='card-text badge bg-warning p-2'>Hujan</p>
                          @elseif ($latest && $latest->status_hujan == 'Cerah')
                          <p class='card-text badge bg-primary p-2'>Cerah</p>
                          @else
                          <p class='card-text badge bg-secondary p-2'>Tunggu data terupdate</p>
                          @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mt-2">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Status Atap</h5>
                        <div id="status-atap">
                          @if ($latest && $latest->status_roof == 'Terbuka')
                          <p class='card-text badge bg-warning p-2'>Terbuka</p>
                          @elseif ($latest && $latest->status_roof == 'Tertutup')
                          <p class='card-text badge bg-primary p-2'>Tertutup</p>
                          @else
                          <p class='card-text badge bg-secondary p-2'>Tunggu data terupdate</p>
                          @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered" id="data-table">
                    <thead>
                        <tr>
                            <th>Status Hujan</th>
                            <th>Intensitas</th>
                            <th>Status Atap</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($all as $a)
                            @if ($a->status_hujan == 'Hujan')
                            <tr>
                              <td class="bg-secondary">{{ $a->status_hujan }}</td>
                              <td class="bg-secondary">{{ $a->intensity }}</td>
                              <td class="bg-secondary">{{ $a->status_roof }}</td>
                              <td class="bg-secondary">{{ date('H:i:s d/m/Y', strtotime($a->datetime)) }}</td>
                          </tr>
                            @else
                            <tr>
                              <td>{{ $a->status_hujan }}</td>
                              <td>{{ $a->intensity }}</td>
                              <td>{{ $a->status_roof }}</td>
                              <td>{{ date('H:i:s d/m/Y', strtotime($a->datetime)) }}</td>
                            </tr>
                            @endif
                        @empty
                        <tr id="oldtabledata">
                            <td class="bg-warning text-center" colspan="4">Tunggu data terupdate</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <script src="https://momentjs.com/downloads/moment.js"></script>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>
        // Enable pusher logging - don't include this in production
        Pusher.logToConsole = true;

        var pusher = new Pusher('3fcf6ae164687044ccd5', {
            cluster: 'ap1'
        });

        function formatDateTime(datetime) {
            return moment(datetime).format('H:m:s D/M/YYYY');
        }

        var channel = pusher.subscribe('esp-channel');
        channel.bind('esp-event', function(data) {
            console.log(data);
            $('#status-sistem').empty();
            $('#status-hujan').empty();
            $('#status-atap').empty();
            $('#oldtabledata').hide();
            if(data.status_sistem == 'Otomatis') {
              $('#status-sistem').html("<p class='card-text badge bg-success p-2'>Otomatis</p>");
            }else{
              $('#status-sistem').html("<p class='card-text badge bg-primary p-2'>Manual</p>");
            }

            if(data.status_hujan == 'Hujan') {
              $('#status-hujan').html("<p class='card-text badge bg-warning p-2'>Hujan</p>");
            }else{
              $('#status-hujan').html("<p class='card-text badge bg-primary p-2'>Cerah</p>");
            }
            
            if(data.status_roof == 'Terbuka') {
              $('#status-atap').html("<p class='card-text badge bg-warning p-2'>Terbuka</p>");
            }else{
              $('#status-atap').html("<p class='card-text badge bg-primary p-2'>Tertutup</p>");
            }

            var row = '<tr>';
    
            if (data.status_hujan == 'Hujan') {
                row += '<td class="bg-secondary">' + data.status_hujan + '</td>';
                row += '<td class="bg-secondary">' + data.intensitas + '</td>';
                row += '<td class="bg-secondary">' + data.status_roof + '</td>';
                row += '<td class="bg-secondary">' + formatDateTime(data.datetime) + '</td>';
            } else {
                row += '<td>' + data.status_hujan + '</td>';
                row += '<td>' + data.intensitas + '</td>';
                row += '<td>' + data.status_roof + '</td>';
                row += '<td>' + formatDateTime(data.datetime) + '</td>';
            }

            row += '</tr>';

            $('#data-table tbody').prepend(row);
        });
    </script>

    <!-- <script>
        $(document).ready(function () {
            if($('input[name="statusMode"]').val() == "Otomatis" || $('input[name="statusMode"]').val() == "") {
                $("#changeRoof").hide();
            }

            if($('input[name="statusMode"]').val() == "") {
                $("#changeMode").hide();
                $("#requestFirstData").show();
            } else {
                $("#changeMode").show();
                $("#requestFirstData").hide();
            }

            $("#changeMode").click(function () {
                if($("input[name='statusMode']").val() == '') {alert('Tunggu data diperbarui atau refresh halaman'); return;}
                $.ajax({
                    type: "GET",
                    url: "/change-mode",
                    dataType: "json", 
                    success: function (response) {
                        // do nothing
                    },
                    error: function (error) {
                        alert("Error fetching data:", error);
                    }
                });
            });
        });
    </script> -->

    <script>
        const tanggal = new Date();
        const jam = tanggal.getHours() + ":" + tanggal.getMinutes() + ":" + tanggal.getSeconds();

        document.getElementById("tanggal").textContent = tanggal.toLocaleDateString();
        document.getElementById("jam").textContent = jam;

        setInterval(() => {
            const tanggal = new Date();
            const jam = tanggal.getHours() + ":" + tanggal.getMinutes() + ":" + tanggal.getSeconds();

            document.getElementById("tanggal").textContent = tanggal.toLocaleDateString();
            document.getElementById("jam").textContent = jam;
        }, 1000);
    </script>

</body>

</html>
