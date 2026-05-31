<?php
include "config.php";

// Check user login or not
if (!isset($_SESSION['uname'])) {
    header('Location: index.php');
}

$id = $_GET['id']; // get id through query string
$query = "SELECT * FROM movietable WHERE movieID = '$id'";
$result = mysqli_query($con, $query);
$data = mysqli_fetch_array($result);

if (isset($_POST['update'])) {
    $title = $_POST['movieTitle'];
    $genre = $_POST['movieGenre'];
    $duration = $_POST['movieDuration'];
    $reldate = $_POST['movieRelDate'];
    $director = $_POST['movieDirector'];
    $actors = $_POST['movieActors'];
    $mainhall = $_POST['mainhall'];
    $viphall = $_POST['viphall'];
    $privatehall = $_POST['privatehall'];
    $img = $_POST['movieImg'] ? "img/" . $_POST['movieImg'] : $data['movieImg'];

    $update_query = "UPDATE movietable SET 
        movieTitle = '$title',
        movieGenre = '$genre',
        movieDuration = '$duration',
        movieRelDate = '$reldate',
        movieDirector = '$director',
        movieActors = '$actors',
        mainhall = '$mainhall',
        viphall = '$viphall',
        privatehall = '$privatehall',
        movieImg = '$img'
        WHERE movieID = '$id'";

    $edit = mysqli_query($con, $update_query);

    if ($edit) {
        header("location:addmovie.php");
        exit;
    } else {
        echo "Error updated record: " . mysqli_error($con);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Edit Movie</title>
    <link rel="icon" type="image/png" href="../img/logo.png">
    <link rel="stylesheet" href="../style/styles.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>

<body>
    <?php include('header.php'); ?>

    <div class="admin-container">
        <?php include('sidebar.php'); ?>
        <div class="admin-section admin-section2">
            <div class="admin-section-column">
                <div class="admin-section-panel admin-section-panel2">
                    <div class="admin-panel-section-header">
                        <h2>EDIT MOVIE: <?php echo $data['movieTitle']; ?></h2>
                        <i class="fas fa-film" style="background-color: #4547cf"></i>
                    </div>
                    <form action="" method="POST">
                        <label>Movie Title</label>
                        <input placeholder="Title" type="text" name="movieTitle" value="<?php echo $data['movieTitle']; ?>" required>
                        <label>Genre</label>
                        <input placeholder="Genre" type="text" name="movieGenre" value="<?php echo $data['movieGenre']; ?>" required>
                        <label>Duration (min)</label>
                        <input placeholder="Duration" type="number" name="movieDuration" value="<?php echo $data['movieDuration']; ?>" required>
                        <label>Release Date</label>
                        <input placeholder="Release Date" type="date" name="movieRelDate" value="<?php echo $data['movieRelDate']; ?>" required>
                        <label>Director</label>
                        <input placeholder="Director" type="text" name="movieDirector" value="<?php echo $data['movieDirector']; ?>" required>
                        <label>Actors</label>
                        <input placeholder="Actors" type="text" name="movieActors" value="<?php echo $data['movieActors']; ?>" required>
                        <label>Price (Main Hall)</label>
                        <input placeholder="Main Hall" type="text" name="mainhall" value="<?php echo $data['mainhall']; ?>" required>
                        <label>Price (VIP Hall)</label>
                        <input placeholder="VIP Hall" type="text" name="viphall" value="<?php echo $data['viphall']; ?>" required>
                        <label>Price (Private Hall)</label>
                        <input placeholder="Private Hall" type="text" name="privatehall" value="<?php echo $data['privatehall']; ?>" required>
                        <br>
                        <label>Change Poster (optional - current: <?php echo $data['movieImg']; ?>)</label>
                        <input type="file" name="movieImg" accept="image/*">
                        <button type="submit" value="update" name="update" class="form-btn">Update Movie</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="../scripts/jquery-3.3.1.min.js "></script>
    <script src="../scripts/script.js "></script>
</body>

</html>
