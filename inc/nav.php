<?php
include_once '../config/dbaccess.php';
?>

<nav class="navbar navbar-expand-lg navbar-dark py-3">
    <div class="container">
        <h1 class="navbar-brand">HOTEL GRAYSCALE</h1>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navmenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navmenu">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a href="../index.php" class="nav-link">Home</a>
                </li>
                <li class="nav-item">
                    <a href="help.php" class="nav-link">Help</a>
                </li>
                <?php 
                    if (isset($_SESSION['id'])) { 
                        if($_SESSION['role'] === 'Admin') {
                            echo'<li class="nav-item">
                                <a href="newsform.php" class="nav-link">News</a>
                            </li>';
                        }
                        echo'<li class="nav-item">
                            <a href="tickets.php" class="nav-link">Ticket</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Account</a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="personal.php">Personal</a></li>
                                <li><a class="dropdown-item" href="mytickets.php">My Tickets</a></li>';
                                if($_SESSION['role'] === 'Admin') {
                                    echo '<li><a class="dropdown-item" href="admin.php">Admin</a></li>';
                                }
                                if ($_SESSION['role'] === 'Admin' || $_SESSION['role'] === 'Technician') {
                                    echo '<li><a class="dropdown-item" href="technician.php">Tickets</a></li>';
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
                    <button class="btn btn-md btn-outline-primary me-2"><a href="login.php" class="text-white">Log In</a></button>
                    <button class="btn btn-md btn-primary me-2"><a href="register.php" class="text-white">Sign Up</a></button>
                </div>
            <?php } ?>
            <?php if (isset($_SESSION['id'])) { ?>
                    <div class="col-md-3 text-end">
                        <button class="btn btn-md btn-danger me-2"><a href="logout.php" class="text-white">Log Out</a></button>
                    </div>
            <?php } ?>
        </div>
    </div>
</nav>