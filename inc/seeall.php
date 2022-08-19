<?php 
    session_start();
    require '../config/dbaccess.php';
    $searchbox = "";
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $searchbox = htmlspecialchars($_POST["searchbox"]);

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
    <!--Article Gallery-->
    <div class="container">
        <div class="text-center my-5">
            <h3 class="text-white">All Articles</h3>
            <?php if($_SESSION['role'] === 'Admin') { ?>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="input-group form-control-sm">
                        <span class="input-group-text" id="addon-wrapping">@Title</span>
                        <input type="text" class="form-control" placeholder="Search by Title" name="searchbox" aria-label="ID" aria-describedby="button-addon1">
                        <button class="btn btn-primary" type="submit" name="searchbutton" id="button-addon1">Search</button>
                    </div>
                </form>
            <?php } ?>
            <hr class="text-white" />
        </div>
        <div class="row">
            <?php
                $sql = "SELECT * FROM news";
                $result = $conn->query($sql);

            while($row = $result->fetch_row()){
                if (isset($_POST["searchbox"])){
                    $searchbox = $_POST["searchbox"];
                }
                if (str_contains($row[1], $searchbox)){
            ?>
                <div class="col-12 col-md-6 col-lg-4 mt-3">
                    <div class="card border-0 text-center">
                        <img src="<?php echo $row[2]?>" class="card-img-top" alt="Featured">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $row[1] ?></h5>
                            <p class="text-muted text-sm">Released <?php echo $row[4] ?>, <?php echo $row[5] ?> by <?php echo $row[6] ?></p>
                            <p class="card-text"><?php echo substr($row[3],0, 50) ?></p>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal<?php echo $row[0]; ?>">Read</button>
                            <div class="modal fade" id="exampleModal<?php echo $row[0]; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel"><?php echo $row[1] ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <img src="<?php echo $row[2]?>" class="card-img-top" alt="Featured">
                                            <p class="mt-3"><?php echo $row[3] ?></p>
                                        </div>
                                        <div class="modal-footer">
                                            <form method="post" action="modifyarticle.php">
                                                <input type="hidden" name="modifyID" value="<?php echo $row[0]; ?>">
                                                <button type="submit" class="btn btn-primary">Edit</button>
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
                }
            }
            ?>
        </div>
    </div>
    <!--Footer-->
    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
</body>

</html>