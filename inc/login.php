<?php
    session_start();
    require '../config/dbaccess.php';
    $username = $password = $usernameErr = $passwordErr = $error = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // validation of input
        (empty($_POST["username"]) ? $usernameErr = "Username is required" : $username = htmlspecialchars($_POST["username"]));
        (empty($_POST["password"]) ? $passwordErr = "Password is required" : $password = htmlspecialchars($_POST["password"]));


        //login feature by using prepared statements to avoid sql-injections
        if($usernameErr === "" && $passwordErr === "") {
            $sql = "SELECT * FROM registered WHERE username=? LIMIT 1";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            $password = hash('sha512', $password);
            // check if password matches with the one stored in the db by comparing hashes aka hashing the inputted password first
            if(isset($user['password']) && $password == $user['password']) {
                if($user['status'] == 1) {
                    // setting session variables to have global access to all variables associated with the user logged in
                    $_SESSION['id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['password'] = $user['password']; // remove?
                    $_SESSION['name'] = $user['firstname'];
                    $_SESSION['lname'] = $user['lastname'];
                    $_SESSION['salutation'] = $user['salutation'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['status'] = $user['status'];

                    header('Location: ../index.php');
                    exit();
                } else {
                    $error = "Your account is not active, please contact our technicians for support!";
                }
            } else {
                $error = "Username or password is wrong, please try again!";
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
    <title>Login Hotel</title>
</head>
    <body class="">
        <!--Navbar-->
        <?php include 'nav.php'; ?>
        <!--Login-->
        <div class="wrapper">
            <form class="form-signin" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                <h2 class="form-signin-heading text-center">Login</h2>
                <span class="error"><?php echo $error;?></span>
                <span class="error"><?php echo $usernameErr;?></span>
                <div class="form-floating">
                    <input type="name" class="form-control border-0 mb-2" name="username" id="floatingInput" placeholder="Username"  value="<?php echo @$_POST["username"] ?>" autofocus>
                    <label for="floatingInput">Username</label>
                </div>
                <span class="error"><?php echo $passwordErr;?></span>
                <div class="form-floating">
                    <input type="password" class="form-control border-0 mb-2" name="password" id="floatingInput" placeholder="Password"  value="<?php echo @$_POST["password"] ?>">
                    <label for="floatingInput">Password</label>
                </div>
                <br><button type="submit" class="btn btn-lg btn-primary col-12" name="submit">Login</button>
            </form>
        </div>
        <!--Footer-->
        <?php include 'footer.php'; ?>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    </body>
</html>