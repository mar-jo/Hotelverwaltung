<?php
session_start();
require '../config/dbaccess.php';
// Variables
$emailErr = $nameErr = $lname = $usernameErr = $passwordErr = $salutationErr = $dberror = "";
$email = $name = $lnameErr = $username = $password = $salutation = "";

// Validation
function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// required fields by validating input and checking if box is empty -> outputs error
if (isset($_POST['createuser'])) {
    (empty($_POST["username"]) ? $usernameErr = "Username is required" : $username = test_input($_POST["username"]));
    (empty($_POST["email"]) ? $emailErr = "Email is required" : $email = test_input($_POST["email"]));
    (empty($_POST["name"]) ? $nameErr = "Name is required" : $name = test_input($_POST["name"]));
    (empty($_POST["lname"]) ? $lnameErr = "Last name is required" : $lname = test_input($_POST["lname"]));
    (empty($_POST["password"]) ? $passwordErr = "Password is required" : $password = test_input($_POST["password"]));
    (!isset($_POST["salutation"]) ? $salutationErr = "Salutation is required" : $salutation = test_input($_POST["salutation"]));
    $role = $_POST["role"];

    // Check if username already exists by fetching username and seeing if it exists (LIMIT 1)
    $emailQuery = "SELECT * FROM registered WHERE username=? LIMIT 1";
    $stmt = $conn->prepare($emailQuery);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $userCount = $result->num_rows;
    $stmt->close();

    // error output if data has been fetched and result is 1
    if ($userCount > 0) {
        $usernameErr = "Username already exists";
    }

    // Check if email already exists by using same method of searching username
    $emailQuery = "SELECT * FROM registered WHERE email=? LIMIT 1";
    $stmt = $conn->prepare($emailQuery);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $emailCount = $result->num_rows;
    $stmt->close();
    
    // error output if data has been fetched and result is 1
    if ($emailCount > 0) {
        $emailErr = "Email already exists";
    }

    // cheking if no errors have been outputted, then proceed to insert data into db
    if ($usernameErr === "" && $emailErr === "" && $nameErr === "" && $lnameErr === "" && $passwordErr === "" && $salutationErr === "") {
        $password = hash('sha512', $password);
        $status = 1;

        // data insertion by using prepared statements to avoid sql-injection
        $sql = "INSERT INTO registered (username, email, password, firstname, lastname, salutation, role, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssssssi', $username, $email, $password, $name, $lname, $salutation, $role, $status);
        if ($stmt->execute()) {
            // after insertion is complete, user will be relocated to index to indicate successful completion of creating a user
            header('Location: ../index.php');
            exit();
        } else {
            // otherwise error message will be outputted
            $dberror = "Database Error: There was an unexpected error.";
            var_dump($conn);
        }
    }
}
// checking if button has been pressed to activate account
if(isset($_POST['activate'])) {
    // fetching id from hidden label that includes id from user
    $id = $_POST['modifyID'];

    //using prepared statements to update status
    $sql = "UPDATE registered SET status = 1 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    if ($stmt->execute()) {
        header('Location: admin.php');
        exit();
    } else {
        echo "<script>alert('There has been an error updating your profile. Please try again.')</script>";
    }
}
// checking if button has been pressed to deactivate account
if(isset($_POST['deactivate'])) {
    // fetching id from hidden label that includes id from user
    $id = $_POST['modifyID'];

    //using prepared statements to update status
    $sql = "UPDATE registered SET status = 0 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    if ($stmt->execute()) {
        header('Location: admin.php');
        exit();
    } else {
        echo "<script>alert('There has been an error updating your profile. Please try again.')</script>";
    }
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
        <div class="container">
            <div class="col-md-12 text-center">
                <button type="button" class="btn btn-lg btn-success text-center" name="createuser" data-bs-toggle="modal" data-bs-target="#changedata">Create User</button>
            </div>
        </div>
        <div class="modal fade" id="changedata" tabindex="-1" aria-labelledby="label" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="label">Create User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="form-register">
                            <span class="error"><?php echo $dberror; ?></span>
                            <select name="role" class="form-select text-danger border-0" id="rank">
                                <option value="Member" name="role">Member</option>
                                <option value="Admin" name="role">Admin</option>
                                <option value="Technician" name="role">Technician</option>
                            </select>
                            <span class="error"><?php echo $salutationErr; ?></span>
                            <select name="salutation" class="form-select border-0" id="anrede">
                                <option name="salutation" disabled selected>Salutation</option>
                                <option value="Herr" name="salutation" <?php if (@$_POST['salutation'] === "Herr") {
                                                                            echo "selected";
                                                                        } ?>>Mr</option>
                                <option value="Frau" name="salutation" <?php if (@$_POST['salutation'] === "Frau") {
                                                                            echo "selected";
                                                                        } ?>>Mrs</option>
                                <option value="Sonstiges" name="salutation" <?php if (@$_POST['salutation'] === "Sonstiges") {
                                                                                echo "selected";
                                                                            } ?>>Diverse</option>
                            </select>
                            <span class="error"><?php echo $nameErr; ?></span>
                            <div class="form-floating">
                                <input id="fname" type="name" class="form-control border-0" id="floatingInput" name="name" placeholder="First name" value="<?php echo @$_POST["name"] ?>">
                                <label for="floatingInput">First name</label>
                            </div>
                            <span class="error"><?php echo $lnameErr; ?></span>
                            <div class="form-floating">
                                <input id="lname" type="name" class="form-control border-0" id="floatingInput" name="lname" placeholder="Last name" value="<?php echo @$_POST["lname"] ?>">
                                <label for="floatingInput">Last name</label>
                            </div>
                            <span class="error"><?php echo $usernameErr; ?></span>
                            <div class="form-floating">
                                <input id="username" type="name" class="form-control border-0" id="floatingInput" name="username" placeholder="Username" value="<?php echo @$_POST["username"] ?>">
                                <label for="floatingInput">Username</label>
                            </div>
                            <span class="error"><?php echo $emailErr; ?></span>
                            <div class="form-floating">
                                <input id="email" type="email" class="form-control border-0" id="floatingInput" name="email" placeholder="Email Address" value="<?php echo @$_POST["email"] ?>">
                                <label for="floatingInput">Email Address</label>
                            </div>
                            <span class="error"><?php echo $passwordErr; ?></span>
                            <div class="form-floating">
                                <input id="password" type="password" class="form-control border-0" id="floatingInput" name="password" placeholder="Password">
                                <label for="floatingInput">Password</label>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" name="createuser">Create</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <!--User Data-->
        <div class="container mt-4">
            <div class="card w-75 px-0 py-0">
                <div class="card-header">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm">
                                <h2 class="float-start">User Data</h2>
                            </div>
                            <div class="col-sm">
                                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                    <div class="input-group form-control-sm">
                                        <span class="input-group-text" id="addon-wrapping">@</span>
                                        <input type="text" class="form-control" placeholder="Search by User" name="searchbox" aria-label="ID" aria-describedby="button-addon1">
                                        <button class="btn btn-outline-primary" type="submit" name="searchbutton" id="button-addon1">Search</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <?php
                    // search box
                    if(isset($_POST['searchbutton'])) {
                        $username = htmlspecialchars($_POST['searchbox']);
                        $query = "SELECT * FROM registered WHERE username = ?";
                        $stmt = $conn->prepare($query);
                        $stmt->bind_param('s', $username);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $stmt->store_result();
                        //$query_run = mysqli_query($conn, $stmt);
                    } else {
                        $query = "SELECT * FROM registered";
                        $stmt = $conn->prepare($query);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        //$query_run = mysqli_query($conn, $query);
                    }

                    ?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col" class="border-0">ID</th>
                                <th scope="col" class="border-0">Username</th>
                                <th scope="col" class="border-0">Email Address</th>
                                <th scope="col" class="border-0">Status</th>
                                <th scope="col" class="border-0 float-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($stmt->affected_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                            ?>
                                    <tr>
                                        <th scope="row" class="border-0"><?php echo $row['id']; ?></th>
                                        <td class="border-0"><?php echo $row['username']; ?></td>
                                        <td class="border-0"><?php echo $row['email']; ?></td>
                                        <td class="border-0 align-middle">
                                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                                <input type="hidden" name="modifyID" value="<?php echo $row['id']; ?>">
                                                <?php if($row['role'] !== 'Admin' && $row['status'] == '0') {
                                                echo '<button type="submit" name="activate" class="btn btn-success">Activate</button>';
                                                } if($row['role'] !== 'Admin' && $row['status'] == '1') {
                                                echo '<button type="submit" name="deactivate" class="btn btn-danger">Deactivate</button>';
                                                }
                                                ?>
                                            </form>
                                        </td>
                                        <td class="border-0">
                                            <!--Change-->
                                            <form action="modify.php" method="post">
                                                <input type="hidden" name="modifyID" value="<?php echo $row['id']; ?>">
                                                <button type="submit" class="btn btn-md btn-secondary text-center float-end" name="modifyButton">Modify</button>
                                            </form>
                                        </td>
                                    </tr>
                            <?php
                                }
                            } else {
                                echo '<tr><td>No records found.</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!--Footer-->
    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
</body>

</html>