<?php
session_start();
require '../config/dbaccess.php';

$emailErr = $nameErr = $lname = $usernameErr = $passwordErr = $salutationErr = "";
$email = $name = $lnameErr = $username = $password = $salutation = "";

// Validation
function test_input($data) {
$data = trim($data);
$data = stripslashes($data);
$data = htmlspecialchars($data);
return $data;
}

if (isset($_POST['modify'])) {
    // Required Fields
    (empty($_POST["username"]) ? $usernameErr = "Username is required" : $username = test_input($_POST["username"]));
    (empty($_POST["email"]) ? $emailErr = "Email is required" : $email = test_input($_POST["email"]));
    (empty($_POST["name"]) ? $nameErr = "Name is required" : $name = test_input($_POST["name"])); 
    (empty($_POST["lname"]) ? $lnameErr = "Last name is required" : $lname = test_input($_POST["lname"]));
    (empty($_POST["password"]) ? $passwordErr = "Password is required" : $password = test_input($_POST["password"]));
    (!isset($_POST["salutation"]) ? $salutationErr = "Gender is required" : $salutation = test_input($_POST["salutation"]));

    // set role and id which are not from input
    $role = $_POST['role'];
    $id = $_POST['modifyID'];

    $sql = "UPDATE registered SET username = ?, email = ?, firstname = ?, lastname = ?, salutation = ?, role = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssssi', $username, $email, $name, $lname, $salutation, $role, $id);
    if ($stmt->execute()) {
        header('Location: admin.php');
        exit();
    } else {
        echo "<script>alert('There has been an error updating your profile. Please try again.')</script>";
    }
}

// delete user
if(isset($_POST['delete'])) {
    $id = $_POST['modifyID'];
    $sql = "DELETE FROM registered WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    if ($stmt->execute()) {
        header('Location: admin.php');
        exit();
    } else {
        echo "<script>alert('There was an error deleting the account. Please try again!')</script>";
    }
}

// change password (why?)
if(isset($_POST['updatepass'])) {
    $new_password = hash('sha512', htmlspecialchars($_POST['newpass']));
    $id = $_POST['modifyID'];

    $sql = "UPDATE registered SET password = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $new_password, $id);
    if($stmt->execute()) {
        header('Location: admin.php');
        exit();
    } else {
        echo "<script>alert('There has been an error updating your password. Please try again.')</script>";
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
    <!--Personal-->
    <?php
                if(isset($_POST['modifyButton'])) {
                    $id = $_POST['modifyID'];
                    $query = "SELECT * FROM registered WHERE id='$id'";
                    $query_run = mysqli_query($conn, $query);
                    foreach($query_run as $row) {
                       ?>
                <div class="wrapper">
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="form-register">
                        <h2 class="form-signin-heading text-center pb-4 pt-2"><?php echo $row['username']?>'s Info</h2>
                        <select name="role" class="form-select text-danger border-0" id="rank" <?php if($row['role'] === "Admin") {echo 'disabled';} ?>>
                            <option value="Member" name="role" <?php if ($row['role'] === "Member") {
                                                                    echo "selected";
                                                                } ?>>Member</option>
                            <option value="Admin" name="role" <?php if ($row['role'] === "Admin") {
                                                                    echo "selected";
                                                                } ?>>Admin</option>
                            <option value="Technician" name="role" <?php if ($row['role'] === "Technician") {
                                                                        echo "selected";
                                                                    } ?>>Technician</option>
                        </select>
                        <span class="error"><?php echo $salutationErr; ?></span>
                        <select name="salutation" class="form-select border-0" id="anrede">
                            <option name="salutation" disabled selected>Salutation</option>
                            <option value="Herr" name="salutation" <?php if ($row['salutation'] === "Herr") {
                                                                    echo "selected";
                                                                } ?>>Mr</option>
                            <option value="Frau" name="salutation" <?php if ($row['salutation'] === "Frau") {
                                                                    echo "selected";
                                                                } ?>>Mrs</option>
                            <option value="Sonstiges" name="salutation" <?php if ($row['salutation'] === "Sonstiges") {
                                                                        echo "selected";
                                                                    } ?>>Diverse
                            </option>
                        </select>
                        <span class="error"><?php echo $nameErr; ?></span>
                        <div class="form-floating">
                            <input id="fname" type="name" class="form-control border-0" id="floatingInput" name="name" placeholder="First name" value="<?php echo $row['firstname']; ?>">
                            <label for="floatingInput">First name</label>
                        </div>
                        <span class="error"><?php echo $lnameErr; ?></span>
                        <div class="form-floating">
                            <input type="name" class="form-control border-0" id="floatingInput" name="lname" placeholder="Last name" value="<?php echo $row['lastname']; ?>">
                            <label for="floatingInput">Last name</label>
                        </div>
                        <span class="error"><?php echo $usernameErr; ?></span>
                        <div class="form-floating">
                            <input type="name" class="form-control border-0" id="floatingInput" name="username" placeholder="Username" value="<?php echo $row['username']; ?>">
                            <label for="floatingInput">Username</label>
                        </div>
                        <span class="error"><?php echo $emailErr; ?></span>
                        <div class="form-floating">
                            <input id="email" type="email" class="form-control border-0" id="floatingInput" name="email" placeholder="Email Address" value="<?php echo $row['email']; ?>">
                            <label for="floatingInput">Email Address</label>
                        </div>
                        <span class="error"><?php echo $passwordErr; ?></span>
                        <button type="button" class="btn btn-lg col-12 mt-2 btn-primary" data-bs-toggle="modal" data-bs-target="#password">Change Password</button>
                        <button type="submit" class="btn btn-lg btn-success col-12 mt-2" name="modify">Update</button>
                        <?php
                            if($row['role'] !== "Admin") {
                                echo '<button type="submit" class="btn btn-lg btn-danger col-12 mt-2" name="delete">Delete</button>';
                            }
                        ?>
                        <a href="admin.php" class="text-white"><button type="button" class="btn btn-lg btn-secondary col-12 mt-2">Back</button></a>
                        <input type="hidden" name="modifyID" value="<?php echo $row['id']; ?>"> <!--user id-->
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
                    </div>
                </div>
                       <?php 
                    }
                }
            ?>
<!--Footer-->
<?php include 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
</script>
</body>

</html>