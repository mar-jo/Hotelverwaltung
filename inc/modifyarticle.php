<?php
    session_start();
    require '../config/dbaccess.php';

    // update
    if (isset($_POST['update'])) {
        // modify data
        $title = htmlspecialchars($_POST['title']);
        $content = htmlspecialchars($_POST['content']);
        $id = $_POST['modifyID'];
    
        $sql = "UPDATE news SET title = ?, content = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssi', $title, $content, $id);
        if ($stmt->execute()) {
            header('Location: seeall.php');
            exit();
        } else {
            echo "<script>alert('There has been an error updating the article. Please try again.')</script>";
        }
    }

    // delete
    if(isset($_POST['delete'])) {
        $id = $_POST['modifyID'];
        $sql = "DELETE FROM news WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id);
        if($stmt->execute()) {
            $filePath = $_POST['imagepath'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            header('Location: seeall.php');
            exit();
        } else {
            echo "<script>alert('There was an error deleting the article. Please try again!')</script>";
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
        <?php 
            // getting id from previous page by using hidden textbox
            if(isset($_POST['modifyID'])) {
                $id = $_POST['modifyID'];
                $query = "SELECT * FROM news WHERE id = $id";
                $stmt = $conn->prepare($query);
                $stmt->execute();
                $result = $stmt->get_result();
                $article = $result->fetch_assoc(); 
                if($article) {
                    ?>
                    <div class="wrapper">
                        <form class="form-signin" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                            <input type="hidden" name="modifyID" value="<?php echo $_POST['modifyID']; ?>">
                            <input type="hidden" name="imagepath" value="<?php echo $article['imagepath']; ?>">
                            <div class="col mb-3">
                                <h2 class="text-center">Edit Article</h2>
                            </div>
                            <div class="col mb-3 mt-4 form-floating">
                                <input class="form-control border-0" type="text" name="title" id="title" placeholder="Title" value="<?php echo $article['title'] ?>" autofocus>
                                <label for="title">Title</label>
                            </div>
                            <div class="col mb-3 form-floating">
                                <textarea class="form-control border-0" name="content" id="textfield" placeholder="Text"  rows="5" cols="30"><?php echo $article['content'] ?></textarea>
                                <label for="textfield">Text</label>
                            </div>
                            <div class="col">
                                <button type="submit" class="btn btn-lg btn-primary col-12" name="update">Update</button>
                            </div>
                            <div class="col mt-1">
                                <button type="submit" class="btn btn-lg btn-danger col-12" name="delete">Delete</button>
                            </div>
                            <div class="col mt-1">
                                <a href="seeall.php" class="text-white"><button type="button" class="btn btn-lg btn-secondary col-12">Back</button></a>
                            </div>
                        </form>
                    </div>
                    <?php
                }
            }
        ?>
        <!--Footer-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        <?php include 'footer.php'; ?>
    </body>
</html>