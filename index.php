<?php
session_start();
require './config/dbaccess.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="res/css/style.css">
    <title>News</title>
</head>

<body class="overflow-auto body-home d-flex flex-column min-vh-100">
    <!--Navbar-->
    <nav class="navbar navbar-expand-lg navbar-dark py-3">
        <div class="container">
            <h1 class="navbar-brand">HOTEL GRAYSCALE</h1>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navmenu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navmenu">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a href="index.php" class="nav-link">Home</a>
                    </li>
                    <li class="nav-item">
                        <a href="inc/help.php" class="nav-link">Help</a>
                    </li>
                    <?php 
                    if (isset($_SESSION['id'])) { 
                        if($_SESSION['role'] === 'Admin') {
                            echo '<li class="nav-item">
                            <a href="inc/newsform.php" class="nav-link">News</a>
                        </li>';
                        }
                        echo'<li class="nav-item">
                            <a href="inc/tickets.php" class="nav-link">Ticket</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Account</a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="inc/personal.php">Personal</a></li>
                                <li><a class="dropdown-item" href="inc/mytickets.php">My Tickets</a></li>';
                                if($_SESSION['role'] === 'Admin') {
                                    echo '<li><a class="dropdown-item" href="inc/admin.php">Admin</a></li>';
                                }
                                if ($_SESSION['role'] === 'Admin' || $_SESSION['role'] === 'Technician') {
                                    echo '<li><a class="dropdown-item" href="inc/technician.php">Tickets</a></li>';
                                }
                            echo '
                            </ul>
                        </li>
                        ';
                    }
                ?>
                </ul>
                <?php if (!isset($_SESSION['id'])) { ?>
                    <div class="col-md-3 text-end">
                        <button class="btn btn-md btn-outline-primary me-2"><a href="inc/login.php" class="text-white">Log In</a></button>
                        <button class="btn btn-md btn-primary me-2"><a href="inc/register.php" class="text-white">Sign Up</a></button>
                    </div>
                <?php } ?>
                <?php if (isset($_SESSION['id'])) { ?>
                    <div class="col-md-3 text-end">
                        <button class="btn btn-md btn-danger me-2"><a href="inc/logout.php" class="text-white">Log Out</a></button>
                    </div>
                <?php } ?>
            </div>
        </div>
    </nav>
    <!--Home-->
    <div class="wrapper">
        <h1 class="text-center text-white">HOTEL GRAYSCALE</h1>
        <h3 class="text-center text-white">Welcome, <?php echo (isset($_SESSION['id']) ? $_SESSION['name'] . " " . $_SESSION['lname'] : 'Guest') ?>!</h3> <!-- Insert USER here -->
        <!--News-->
        <!-- ONLY DISPLAY THREE ARTICLES HERE-->
        <div class="row">
            <?php
                $sql = "SELECT * FROM `news` ORDER BY id DESC LIMIT 3";
                $result = $conn->query($sql);

                while($news = $result->fetch_assoc()) {
            ?>
            <div class="col-12 col-md-6 col-lg-4 mt-3">
                <div class="card border-0 text-center">
                    <img src="<?php echo substr($news['imagepath'], 3, 100)?>" class="card-img-top" alt="Featured">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $news['title'] ?></h5>
                        <p class="text-muted text-sm">Released <?php echo $news['date'] ?>, <?php $news['time'] ?> by <?php echo $news['user'] ?></p>
                        <p class="card-text"><?php echo substr($news['content'], 0, 50)?></p>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">Read</button>
                        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel"><?php echo $news['title'] ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <img src="<?php echo substr($news['imagepath'], 3, 100)?>" class="card-img-top" alt="Featured">
                                        <p class="mt-3"><?php echo $news['content']?></p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php 
            }
            ?>
        <div class="text-center mt-4">
            <a href="inc/seeall.php" class="btn btn-lg btn-primary" role="button">All articles</a>
        </div>
    </div>
    <!--Footer-->
    <footer class="footer mt-auto py-3">
        <ul class="nav justify-content-center">
            <li class="nav-item">
                <a href="inc/contact.php" class="nav-link text-muted">Contact</a>
            </li>
            <li class="nav-item">
                <a href="inc/imprint.php" class="nav-link text-muted">Imprint</a>
            </li>
        </ul>
        <p class="text-center text-muted">Copyright &copy; 2022 Hotel Grayscale</p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>