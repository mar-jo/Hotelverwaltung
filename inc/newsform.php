<?php
    session_start();
    require '../config/dbaccess.php';
    $title = $textfield = $fileErr = $titleErr = $textfieldErr = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        (empty($_POST["title"]) ? $titleErr = "Title is required" : $title = htmlspecialchars($_POST["title"]));
        (empty($_POST["textfield"]) ? $textfieldErr = "Text is required" : $textfield = htmlspecialchars($_POST["textfield"]));

        $target_dir = "../uploads/news/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $fileErr = $target_file;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        // Check if image file is a actual image or fake image
        if(isset($_POST["submit"])) {
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if($check !== false) {
                $fileErr =  "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                $fileErr = "File is not an image.";
                $uploadOk = 0;
            }
        }
        // Check if file already exists
        if (file_exists($target_file)) {
            $fileErr = "Sorry, file already exists.";
            $uploadOk = 0;
        }
        // Check file size
        if ($_FILES["fileToUpload"]["size"] > 50000000) {
            $fileErr = "Sorry, your file is too large.";
            $uploadOk = 0;
        }
        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" ) {
            $fileErr = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            // $fileErr = "Sorry, your file was not uploaded.";
            // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                $fileErr = "The file " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " has been uploaded.";
                $percent = 0.7;
                list($width, $height) = getimagesize($target_file);
                $newwidth = $width * $percent;
                $newheight = $height * $percent;
                $thumb = imagecreatetruecolor($newwidth, $newheight);
                $source = imagecreatefromjpeg($target_file);
                imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
                imagejpeg($thumb, $target_file);
                $sql = "INSERT INTO news (title, imagepath, content, date, time, user) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $date = date("Y-m-d");
                $time = date("H:i:s");
                $user = $_SESSION['username'];
                $stmt->bind_param('ssssss', $title, $target_file, $textfield, $date, $time, $_SESSION['username']);
                $stmt->execute();
                header('Location: newsform.php');
                exit();
            } else {
                $fileErr = "Sorry, there was an error uploading your file.";
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
    <title>News</title>
</head>
    <body>
        <!--Navbar-->
        <?php include 'nav.php'; ?>
        <!--News Form-->
        <div class="wrapper">
            <form class="form-signin" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
                <div class="col mb-3">
                    <h2 class="text-center">Upload Form</h2>
                </div>
                <div class="col mb-3 mt-4 form-floating">
                <span class="error"><?php echo $titleErr;?></span>
                    <input class="form-control border-0" type="text" name="title" id="title" placeholder="Title" value="<?php echo @$_POST["title"] ?>" autofocus>
                    <label for="title">Title</label>
                </div>
                <div class="col mb-3">
                    <span class="error"><?php echo $fileErr;?></span>
                    <input type="file" class="form-control-file" name="fileToUpload" id="fileToUpload" required/>
                </div>
                <div class="col mb-3 form-floating">
                <span class="error"><?php echo $textfieldErr;?></span>
                    <textarea class="form-control border-0" name="textfield" id="textfield" placeholder="Text" rows="5" cols="30"></textarea>
                    <label for="textfield">Text</label>
                </div>
                <div class="col">
                    <button type="submit" class="btn btn-lg btn-success col-12">Submit</button>
                </div>
                <div class="col mt-4">
                    <a href="../index.php" class="returnNews nav-link text-center text-dark">Return to Homepage</a>
                </div>
            </form>
        </div>
        <!--Footer-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        <?php include 'footer.php'; ?>
    </body>
</html>