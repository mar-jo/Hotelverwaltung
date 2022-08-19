<?php
session_start();
require '../config/dbaccess.php';
// Variables
$emailErr = $nameErr = $lname = $usernameErr = $passwordErr = $genderErr = "";
$email = $name = $lnameErr = $username = $password = $gender = "";

// Validation
function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Required Fields
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    (empty($_POST["username"]) ? $usernameErr = "Username is required" : $username = test_input($_POST["username"]));
    (empty($_POST["email"]) ? $emailErr = "Email is required" : $email = test_input($_POST["email"]));
    (empty($_POST["name"]) ? $nameErr = "Name is required" : $name = test_input($_POST["name"]));
    (empty($_POST["lname"]) ? $lnameErr = "Last name is required" : $lname = test_input($_POST["lname"]));
    (empty($_POST["password"]) ? $passwordErr = "Password is required" : $password = test_input($_POST["password"]));
    (!isset($_POST["gender"]) ? $genderErr = "Gender is required" : $gender = test_input($_POST["gender"]));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="../res/css/style.css">
    <title>Personal</title>
</head>

<body>
    <!--Navbar-->
    <?php include 'nav.php'; ?>
    <!--Create user button-->
    <div class="wrapper">
        <!--User Data-->
        <div class="container mt-4">
            <div class="card w-75 px-0 py-0">
                <div class="card-header">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm">
                                <h2 class="float-start">Helpdesk</h2>
                            </div>
                            <div class="col-sm">
                                <div class="input-group form-control-sm">
                                    <span class="input-group-text" id="addon-wrapping">Status</span>
                                    <input type="text" class="form-control" placeholder="Search by Status" aria-label="ID" aria-describedby="button-addon1">
                                    <button class="btn btn-outline-primary" type="button" id="button-addon1">Search</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col" class="border-0">Ticket ID</th>
                                <th scope="col" class="border-0">Timestamp</th>
                                <th scope="col" class="border-0">Username</th>
                                <th scope="col" class="border-0">Email Address</th>
                                <th scope="col" class="border-0">Status</th>
                                <th scope="col" class="border-0 float-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row" class="border-0">#69420</th>
                                <td scope="row" class="border-0">3.01.2022, 13:33</td> <!-- DISPLAY TIME HERE -->
                                <td scope="row" class="border-0">Mark</td>
                                <td scope="row" class="border-0">mark@gmail.gay</td>
                                <td scope="row" class="border-0">Status</td> <!-- DISPLAY STATUS HERE-->
                                <td scope="row" class="border-0">
                                    <!--Change-->
                                    <button type="button" class="btn btn-md btn-secondary text-center float-end" data-bs-toggle="modal" data-bs-target="#changeentry">View</button>
                                    <div class="modal fade" id="changeentry" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Ticket #ID</h5> <!-- DISPLAY ID HERE-->
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="card border-0 float-start">
                                                        <img src="../res/img/hotel-login.jpg" class="card-img-top" alt="Ticket">
                                                        <div class="card-body">
                                                            <h5 class="card-title">Ticket header</h5>
                                                            <p class="card-text">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Excepturi iure voluptatem harum quo sequi. Nisi!</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <?php if($_SESSION['role'] === 'Technician') {
                                                    echo
                                                    '<div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                                        <button type="button" class="btn btn-danger">Not Resolved</button>
                                                        <button type="button" class="btn btn-warning">Pending</button>
                                                        <button type="button" class="btn btn-success">Resolved</button>
                                                    </div>
                                                    <button type="button" class="btn btn-primary">Send Message</button>';
                                                    } else {
                                                    echo '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>';
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!--Footer-->
    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>