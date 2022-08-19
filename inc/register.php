<?php
session_start();
require '../config/dbaccess.php';
// Variables
$emailErr = $nameErr = $lname = $usernameErr = $passwordErr = $salutationErr = $dberror = "";
$email = $name = $lnameErr = $username = $password = $salutation = "";

// user cannot see this page if already logged in
if(isset($_SESSION['id'])) {
    header("Location: ../index.php");
}

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
    (!isset($_POST["salutation"]) ? $salutationErr = "salutation is required" : $salutation = test_input($_POST["salutation"]));

    // Check if username already exists
    $emailQuery = "SELECT * FROM registered WHERE username=? LIMIT 1";
    $stmt = $conn->prepare($emailQuery);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $userCount = $result->num_rows;
    $stmt->close();

    if ($userCount > 0) {
        $usernameErr = "Username already exists";
    }

    // Check if email already exists
    $emailQuery = "SELECT * FROM registered WHERE email=? LIMIT 1";
    $stmt = $conn->prepare($emailQuery);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $emailCount = $result->num_rows;
    $stmt->close();

    if ($emailCount > 0) {
        $emailErr = "Email already exists";
    }

    if ($usernameErr === "" && $emailErr === "" && $nameErr === "" && $lnameErr === "" && $passwordErr === "" && $salutationErr === "") {
        $password = hash('sha512', $password);
        $role = "Member";
        $status = 1;

        $sql = "INSERT INTO registered (username, email, password, firstname, lastname, salutation, role, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssssssi', $username, $email, $password, $name, $lname, $salutation, $role, $status);
        if ($stmt->execute()) {
            // automatic login
            $user_id = $conn->insert_id;
            $_SESSION['id'] = $user_id;
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;
            $_SESSION['password'] = $password;
            $_SESSION['name'] = $name;
            $_SESSION['lname'] = $lname;
            $_SESSION['salutation'] = $salutation;
            $_SESSION['role'] = $role;
            $_SESSION['status'] = $status;

            header('Location: ../index.php');
            exit();
        } else {
            $dberror = "Database Error: There was an unexpected error.";
            var_dump($conn);
        }
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
    <title>Registration</title>
</head>

<body class="overflow-auto">
    <!--Navbar-->
    <?php include 'nav.php'; ?>
    <!--Registration-->
    <div class="wrapper">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="form-register">
            <h2 class="form-signin-heading text-center pb-4 pt-2">Registration</h2>
            <span class="error"><?php echo $dberror; ?></span>
            <span class="error"><?php echo $salutationErr; ?></span>
            <select name="salutation" class="form-select border-0" id="anrede">
                <option name="salutation" disabled selected>Salutation</option>
                <option value="Herr" name="salutation" <?php if (@$_POST['salutation'] === "Mr") {
                                                        echo "selected";
                                                    } ?>>Mr</option> <!-- fix required-->
                <option value="Frau" name="salutation" <?php if (@$_POST['salutation'] === "Mrs") {
                                                        echo "selected";
                                                    } ?>>Mrs</option> <!-- fix required-->
                <option value="Sonstiges" name="salutation" <?php if (@$_POST['salutation'] === "Diverse") {
                                                            echo "selected";
                                                        } ?>>Diverse</option> <!-- fix required-->
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
            <button type="submit" class="btn btn-lg btn-primary col-12 mt-2" name="submit">Register</button>
        </form>
    </div>
    <!--Footer-->
    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>