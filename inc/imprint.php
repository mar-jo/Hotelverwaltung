<?php 
    session_start();
    require '../config/dbaccess.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="../res/css/style.css">
    <title>Imprint</title>
</head>
    <body class="">
        <!--Navbar-->
        <?php include 'nav.php'; ?>
        <!--Imprint-->
        <div class="wrapper text-center form-register imprint mt-4">
            <div>        
                <h2>Impressum</h2>
                <p>Max Mustermann</p>
                <P>4753 Musterdorf</P>
                <p>Musterstrasse 15 | Austria</p>
            </div>
            <div>
                <h3>Hotel Verwaltung</h3>
                <p>Tel: +43 XXX XXXX</p>
                <p>Fax: +43 XXX XXXX XX</p>
                <p>E-Mail: email@server.domain</p>
                <p>UID-NR: ATU12345678</p>
                <p>Mitglied der WKÖ, Ladesinnung Hotel, Bundesinnung Hotel</p>
            </div>
            <div>
                <h3>Berufsrecht</h3>
                <p>Gewerbeordnung:</p>
                <a href="http://www.ris.bka.gv.at">www.ris.bka.gv.at</a>
                <p>Bezirkshauptmannschaft Musterstadt</p>
                <p>Meisterbetrieb, Meisterprüfung abgelegt in Österreich</p>
                <p>Verbraucher haben die Möglichkeit, Beschwerden an<br>die Online-Streitbeilegungsplattform der EU zu richten</p>
                <p><a href="http://ec.europa.eu/odr">ec.europa.eu/odr</a></p>
                <p>Sie können allfälligen Beschwerden auch an die oben angegebene E-Mail-Adresse richten.</p>
            </div>
            <div>
                <h4>Developers of this webpage</h4>
                <img src="../res/img/Michi.jpeg" class="card-img-top" alt="Michael">
                <p>Developer Michael</p>
                <img src="../res/img/Mark.jpeg" class="card-img-top" alt="Mark">
                <p>Developer Mark</p>
            </div>
        </div>
        <!--Footer-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        <?php include 'footer.php'; ?>
    </body>
</html>