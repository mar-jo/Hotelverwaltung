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
    <title>News</title>
</head>
    <body class="overflow-auto">
        <!--Navbar-->
        <?php include 'nav.php'; ?>
        <!--Help-->
        <div class="wrapper">
            <section class="form-help">
                <h2 class="text-center mb-4">Help Page</h2>
                <div class="col mb-3">
                    <h4 class="text-center">General info for Guests</h4>
                    <p class="text-center">Guests are the ones whomst have no account. You will be able to comfortably skim through our news feed and read through all of our articles, which have been published by our moderation team. We strongly advise you to create an account to enjoy the full feature set our website has to offer. For any further questions, please use our contact form which is located in the footer section of this page.</p>
                    <h4 class="text-center">General info for Members</h4>
                    <p class="text-center">Registered accounts have a wider range of features than Guests. In addition to all the features a regular Guest has, you'll be able to have quick response times in terms of inquiries or questions by using our ticket form. You can create a ticket by going to the "Ticket" section in the navbar. All your past tickets will be displayed under "My Tickets", you'll see a status indicating on whether the issue you have reported has been resolved or not. Furthermore you'll be able to change your own data in the "Personal" section.</p>
                </div>
            </section>
        </div>
        <!--Footer-->
        <?php include 'footer.php'; ?>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    </body>
</html>