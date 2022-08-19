<?php
    session_start();
    require '../config/dbaccess.php';
    $fname = $lname = $email = $textfield = "";
    $fnameErr = $lnameErr = $emailErr = $textfieldErr = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // validation of input
        (empty($_POST["fname"]) ? $fnameErr = "First name is required" : $fname = htmlspecialchars($_POST["fname"]));
        (empty($_POST["lname"]) ? $lnameErr = "Last name is required" : $lname = htmlspecialchars($_POST["lname"]));
        (empty($_POST["email"]) ? $emailErr = "Email is required" : $email = htmlspecialchars($_POST["email"]));
        (empty($_POST["textfield"]) ? $textfieldErr = "Text is required" : $textfield = htmlspecialchars($_POST["textfield"]));
        
        // would usually work but SMTP server has not been set up
        /*if($fnameErr === "" && $lnameErr === "" && $emailErr === "" && $textfieldErr === "") {
            // the message
            $msg = $_POST['textfield'];

            // use wordwrap() if lines are longer than 70 characters
            $msg = wordwrap($msg,70);

            // send email
            mail("markx931@gmail.com","Contact Form - " . $_POST['fname'] ." ". $_POST['lname'],$msg);
        }*/
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
    <title>Contact Form</title>
</head>
    <body class="overflow-auto">
        <!--Navbar-->
        <?php include 'nav.php'; ?>
        <!--Contact Form-->
        <section class="contact">
            <div class="container py-5">
                <div class="row">
                    <div class="col-lg-6 mx-auto">
                        <div class="card">
                            <div class="card-body">
                                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                                    <h2 class="text-center">Contact Us</h2>
                                    <div class="row ms-auto">
                                        <div class="col form-floating">
                                            <span class="error"><?php echo $fnameErr;?></span>
                                            <input type="text" class="form-control border-0" name="fname" id="floatingInput" placeholder="First Name" value="<?php echo @$_POST["fname"] ?>" autofocus>
                                            <label for="floatingInput">First Name</label>
                                        </div>
                                        <div class="col form-floating">
                                            <span class="error"><?php echo $lnameErr;?></span>
                                            <input type="text" class="form-control border-0" name="lname" id="floatingInput" placeholder="Last Name" value="<?php echo @$_POST["lname"] ?>">
                                            <label for="floatingInput">Last Name</label>
                                        </div>
                                    </div>
                                    <div class="col mt-2 form-floating">
                                        <span class="error"><?php echo $emailErr;?></span>
                                        <input type="email" class="form-control border-0" name="email" id="floatingInput" placeholder="Email Address" value="<?php echo @$_POST["email"] ?>">
                                        <label for="floatingInput">Email Address</label>
                                    </div>
                                    <div class="col mt-2 form-floating">
                                        <span class="error"><?php echo $textfieldErr;?></span>
                                        <textarea class="form-control border-0" placeholder="Your Message" id="floatingInput" name="textfield" rows="5" cols="30" ></textarea>
                                        <label for="floatingInput">Message</label>
                                    </div>
                                    <div class="col mt-2">
                                        <button type="submit" name="sendmessage" class="btn btn-lg btn-success col-12 mb-1">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--Footer-->
        <?php include 'footer.php'; ?>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    </body>
</html>