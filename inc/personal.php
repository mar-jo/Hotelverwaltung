<?php
    session_start();
    require '../config/dbaccess.php';

    if(!isset($_SESSION['id'])) {
        header("Location: ../index.php");
    }

    // Variables
    $emailErr = $nameErr = $lname = $usernameErr = $passwordErr = $genderErr = "";
    $email = $name = $lnameErr = $username = $password = $gender = "";

    // Validation
    function test_input($data) {
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

    // update data when button is pressed
    if(isset($_POST['update'])) {
        $username = htmlspecialchars($_POST['username']);
        $email = htmlspecialchars($_POST['email']);
        $firstname = htmlspecialchars($_POST['name']);
        $lastname = htmlspecialchars($_POST['lname']);
        $salutation = htmlspecialchars($_POST['gender']);
        $id = $_SESSION['id'];

        $sql = "UPDATE registered SET username = ?, email = ?, firstname = ?, lastname = ?, salutation = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssssi', $username, $email, $firstname, $lastname, $salutation, $id);
        if($stmt->execute()) {
            header('Location: personal.php');
            exit();
        } else {
            echo "<script>alert('There has been an error updating your profile. Please try again.')</script>";
        }
    }
    
    // update password when button is pressed in modal
    if(isset($_POST['updatepass'])) {
        $old_password = hash('sha512', $_POST['oldpass']);
        $new_password = hash('sha512', htmlspecialchars($_POST['newpass']));
        $id = $_SESSION['id'];

        //only works once, user has to log in again for it to work again
        if($_SESSION['password'] === $old_password) {
            $sql = "UPDATE registered SET password = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('si', $new_password, $id);
            if($stmt->execute()) {
                header('Location: personal.php');
                exit();
            } else {
                echo "<script>alert('There has been an error updating your password. Please try again.')</script>";
            }
        } else {
            echo "<script>alert('Old password does not match. Please try again.')</script>";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="../res/css/style.css">
    <title>Personal</title>
</head>

<body>
    <!--Navbar-->
    <?php include 'nav.php'; ?>
    <!--Personal-->
    <div class="wrapper">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" class="form-register">
            <?php 
                    $sql = "SELECT * FROM registered WHERE id ='{$_SESSION['id']}'";
                    $result = mysqli_query($conn, $sql);
                    // check if data is available in database
                    if(mysqli_num_rows($result) > 0) {
                        while($row = mysqli_fetch_assoc($result)) {
                ?>
            <h2 class="form-signin-heading text-center pb-4 pt-2">Personal Info</h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" class="form-register">
                <select name="role" class="form-select text-danger border-0" id="rank" disabled>
                    <option name="role" disabled>Role</option>
                    <option value="Member" name="role" <?php if($row['role'] === "Member") {echo "selected";} ?>>Member</option>
                    <option value="Admin" name="role" <?php if($row['role'] === "Admin") {echo "selected";} ?>>Admin</option>
                    <option value="Technician" name="role" <?php if($row['role'] === "Technician") {echo "selected";} ?>>Technician</option>
                </select>
                <span class="error"><?php echo $genderErr;?></span>
                <select name="gender" class="form-select text-muted border-0 mb-3 id=" anrede">
                    <option name="gender" disabled selected>Salutation</option>
                    <option value="Herr" name="gender" <?php if($row['salutation'] === "Herr") {echo "selected";} ?>>Mr
                    </option> 
                    <option value="Frau" name="gender" <?php if($row['salutation'] === "Frau") {echo "selected";} ?>>Mrs
                    </option>
                    <option value="Sonstiges" name="gender"
                        <?php if($row['salutation'] === "Sonstiges") {echo "selected";} ?>>Diverse</option>
                </select>
                <span class="error"><?php echo $nameErr;?></span>
                <div class="row">
                    <div class=" col-md">
                        <div class="form-floating">
                            <input id="fname" type="name" class="form-control border-0" id="fname" name="name"
                                value="<?php echo $row['firstname'] ?>">
                            <label for="fname">First Name</label>
                        </div>
                    </div>
                    <span class="error"><?php echo $lnameErr;?></span>
                    <div class="col-md">
                        <div class="form-floating">
                            <input id="lname" type="name" class="form-control border-0" id="lname" name="lname"
                            value="<?php echo $row['lastname'] ?>">
                            <label for="lname">Last Name</label>
                        </div>
                    </div>
                    <span class="error"><?php echo $usernameErr;?></span>
                    <div class=" col-md">
                        <div class="form-floating">
                            <input id="username" type="name" class="form-control border-0" id="user" name="username"
                            value="<?php echo $row['username'] ?>">
                            <label for="user">Username</label>
                        </div>
                    </div>
                    <span class="error"><?php echo $emailErr;?></span>
                    <div class="col-md">
                        <div class="form-floating">
                            <input id="email" type="email" class="form-control border-0" id="email" name="email"
                            value="<?php echo $row['email'] ?>">
                            <label for="email">Email Address</label>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-lg col-12 mt-2 btn-secondary" data-bs-toggle="modal"
                    data-bs-target="#password">Change Password</button>
                <button type="submit" class="btn btn-lg btn-primary col-12 mt-2" name="update">Update</button>
                <div class="modal fade" id="password" tabindex="-1" aria-labelledby="label" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="label">Change Password</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form class="form-signin" method="post"
                                    action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                                    <div class="col-md form-floating">
                                        <input type="password" class="form-control mb-2" name="oldpass" id="oldp"
                                            placeholder="Old Password" autofocus>
                                        <label for="oldp">Old Password</label>
                                    </div>
                                    <div class="col-md form-floating">
                                        <input type="password" class="form-control mb-2" name="newpass" id="newp"
                                            placeholder="Password">
                                        <label for="newp">New Password</label>
                                    </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary" name="updatepass">Save</button>
                                    </div>
                                </form>
                        </div>
                    </div>
                </div>
            </form>
    </div>
    <?php
                        }
                    }
                ?>
    <!--Footer-->
    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
</body>

</html>