<!DOCTYPE HTML>
<html lang="fr">
<head>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" >
    <link rel="stylesheet" href="assets/css/style.css?v=2">
    <script type="text/javascript" src="assets/js/jquery.min.js"></script>
    <script type="text/javascript" src="assets/js/Chart.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/javascript.util/0.12.12/javascript.util.min.js" integrity="sha512-oHBLR38hkpOtf4dW75gdfO7VhEKg2fsitvHZYHZjObc4BPKou2PGenyxA5ZJ8CCqWytBx5wpiSqwVEBy84b7tw==" crossorigin="anonymous"></script>
    <title>Smartwine</title>
</head>
<body>


<nav class="navbar navbar-dark bg-dark">
    <a class="navbar-brand d-flex align-items-center" href="#">
        <img src="assets/img/logo.png"  class="d-inline-block align-top" id="logo" alt="">
        <span>DASHBOARD</span>
    </a>
</nav>

<div class="row">
    <div class="col card">
        <h1 class="title-card">Temperature</h1>
        <h2 id="temp"></h2>
        <canvas id="graphCanvas" ></canvas>
    </div>
    <div class="col card">
        <h1 class="title-card">Hygrometrie</h1>
        <h2 id="hygro"></h2>
        <canvas id="graphCanvas2"></canvas>*
    </div>
</div>

<div class="card">
    <h1 class="title-card" id="stock">Stock</h1>
    <table class="table table-dark bg-dark justify-content-center">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Nom</th>
            <th scope="col">Variété</th>
            <th scope="col">Quantité</th>
            <th scope="col">Température idéale</th>
        </tr>
        </thead>
        <tbody id="tbody">
        <?php
        $connect = new mysqli("localhost", "smartwine", "raspberry", "smartwine");

        if ($connect->connect_errno) {

            echo "Error: Échec d'établir une connexion MySQL, voici pourquoi : \n";
            echo "Errno: " . $connect->connect_errno . "\n";
            echo "Error: " . $connect->connect_error . "\n";
            exit;
        }


        $req = "SELECT * FROM `wines`";

        $res = $connect->query($req);
        $data=array();
        $quantityTot = 0;
        foreach ($res as $row): ?>

            <tr>
                <th scope="row"><?=$row['id']?></th>
                <td><?=$row['name']?></td>
                <td><?=$row['variety']?></td>
                <td><?=$row['quantity']?></td>
                <td><?=$row['idealTemp']?>°C</td>
            </tr>

            <?php
            $quantityTot += $row['quantity'];
        endforeach;



        mysqli_close($connect);
        ?>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td id="total">Total bouteilles : <?=$quantityTot?></td>
        </tr>



        </tbody>
    </table>
</div>


<script>
    window.onload = function() {
        refresh()
    };

    function refresh() {
        $.post("temperature.php",
            function (data) {
                let json = JSON.parse(data);
                console.log(data);
                let date = [];
                let temp = [];

                for (let i in json) {
                    date.push(json[i].date);
                    temp.push(json[i].temp);
                }


                let configTemp = {
                    type: 'line',

                    data: {
                        labels: date,
                        datasets: [
                            {

                                label: 'Temperature',
                                borderColor: 'rgb(239, 41, 150)',
                                backgroundColor: 'rgb(239, 41, 150, 0.2)',
                                hoverBackgroundColor: '#ffffff',
                                hoverBorderColor: '#ffffff',
                                data: temp
                            },
                        ],
                        fill: false,
                    },
                    option: {

                        responsive: true,
                        plugins: {
                            title: {
                                display: true,
                                text: 'Chart.js Line Chart'
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false,
                            }
                        },
                        hover: {
                            mode: 'nearest',
                            intersect: true
                        },
                        scales: {
                            x: {
                                display: true,
                            },
                            y: {
                                display: true,
                                min: 0,
                                max: 100,
                                ticks: {
                                    stepSize: 5
                                }
                            }
                        }
                    }
                };


                let ctx = document.getElementById('graphCanvas').getContext('2d');
                window.myLine = new Chart(ctx, configTemp);

                document.getElementById('temp').innerHTML = temp[temp.length - 1]+"°C";

            });

        $.post("hygrometrie.php",
            function (data) {
                let json = JSON.parse(data);
                console.log(data);
                let date = [];
                let hygro = [];

                for (let i in json) {
                    date.push(json[i].date);
                    hygro.push(json[i].hygro);
                }


                let configHygro = {
                    type: 'line',

                    data: {
                        labels: date,
                        datasets: [
                            {

                                label: 'Hygrometrie',
                                borderColor: 'rgb(239, 41, 150)',
                                backgroundColor: 'rgb(239, 41, 150, 0.2)',
                                hoverBackgroundColor: '#ffffff',
                                hoverBorderColor: '#ffffff',
                                data: hygro
                            },
                        ],
                        fill: false,
                    },
                    option: {

                        responsive: true,
                        plugins: {
                            title: {
                                display: true,
                                text: 'Chart.js Line Chart'
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false,
                            }
                        },
                        hover: {
                            mode: 'nearest',
                            intersect: true
                        },
                        scales: {
                            x: {
                                display: true,
                            },
                            y: {
                                display: true,
                                min: 0,
                                max: 100,
                                ticks: {
                                    stepSize: 5
                                }
                            }
                        }
                    }
                };


                let ctx = document.getElementById('graphCanvas2').getContext('2d');
                window.myLine = new Chart(ctx, configHygro);
                document.getElementById('hygro').innerHTML = hygro[hygro.length - 1] + "%";
            });
        setTimeout(refresh, 5000);
    }
    $.post("stock.php")
    {
        let obj = JSON.parse(data);

    }
</script>


</body>

</html>
