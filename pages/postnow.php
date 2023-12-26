<?php 
session_start();
error_reporting(0);
if (!isset($_SESSION['session_id'])) {
    // If session ID is not set, user is not logged in. Redirect to index.php
    header("Location: index.php");
    exit();
}

require('database.php');
$user_id = 0;
if(!isset($_GET['userid']))
{
  $self = 1;
  $user_id = $_SESSION['key_1'];
}
else
{
  $self = 0;
  $user_id = $_GET['userid'];
}
//company
$query = "SELECT * from user WHERE key_1 = $user_id";
$result = $conn->query($query);
$row = $result->fetch_assoc();
$company = ucfirst($row['company']);
$username = ucfirst($row['first_name']);
$profile_link = $row['profile_link'];
$user_type = $row['user_type'];

require('database.php');
//followers
// Count number of followers
$query = "SELECT COUNT(*) as follower_count FROM nodes WHERE too = $user_id ";
$result = $conn->query($query);
$row = $result->fetch_assoc();
$followers = $row['follower_count'];


//following
// Count number of followers
$query = "SELECT COUNT(*) as follower_count FROM nodes WHERE fromm = $user_id ";
$result = $conn->query($query);
$row = $result->fetch_assoc();
$following = $row['follower_count'];

//company
$query = "SELECT * from user WHERE key_1 = $user_id";
$result = $conn->query($query);
$row = $result->fetch_assoc();
$company = ucfirst($row['company']);
$username = ucfirst($row['first_name']);
$profile_link = $row['profile_link'];
$user_type = $row['user_type'];

//is the user following 
$fromm = $_SESSION['key_1'];
$query = "SELECT COUNT(*) as is_follower FROM nodes WHERE fromm = $fromm AND too = $user_id";
$result = $conn->query($query);
$row = $result->fetch_assoc();
if($row['is_follower'] != 0)
{
  $isfollower = 1;
}
else
{
  $isfollower = 0;
}

//follows you 
$fromm = $_SESSION['key_1'];
$query = "SELECT COUNT(*) as followsyou FROM nodes WHERE fromm = $user_id AND too = $fromm";
$result = $conn->query($query);
$row = $result->fetch_assoc();
if($row['followsyou'] != 0)
{
  $followsyou = 1;
}
else
{
  $followsyou = 0;
}



if(isset($_POST["postnow"])) {
  //check posts made today 

   echo $posts = "SELECT count(*) as poststoday FROM posts WHERE post_by = ".$_SESSION['key_1']." AND DATE(`post_time`) = CURRENT_DATE";
   $postno = mysqli_query($conn, $posts);
   $nos = mysqli_fetch_array($postno);

   if($nos['poststoday'] >= 3){

    header('Location: postnow.php?error=10');

   }else
   {
   
 

    $title = $_POST['title'];
    $body  = $_POST['body'];
    $tags  = $_POST['tags'];

    $words = explode(",", $tags);
    $var1 = isset($words[0]) ? $words[0] : "";
    $var2 = isset($words[1]) ? $words[1] : "";
    $var3 = isset($words[2]) ? $words[2] : "";
    $var4 = isset($words[3]) ? $words[3] : "";
    $var5 = isset($words[4]) ? $words[4] : "";

    /*$var1 = "1";
    $var2 = "2";
    $var3 = "3";
    $var4 = "4";
    $var5 = "5";*/


    $post_id = rand(100000, 999999);

    $userid = $_SESSION['key_1'];
    $query = "INSERT INTO posts (post_id, post_by, post_title, post_body, tag_1, tag_2, tag_3, tag_4, tag_5)
    VALUES ('$post_id' ,'$userid' , '$title', '$body', '$var1', '$var2', '$var3', '$var4', '$var5')";
    $result = mysqli_query($conn, $query);

    $target_dir = "../assets/img/"; // Folder where the image will be saved
    $file_name = $post_id . '.' . pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION); // Generate a unique file name
    $target_file = $target_dir . basename($file_name); // File path of the image
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION)); // Get the file extension

    // Check if the image is a valid image
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if($check === false) {
        //header('Location: postnow.php?error=1');
    }

    // Check if the file already exists
    if (file_exists($target_file)) {
       
        header('Location: postnow.php?error=2');
    }

    // Check if the file size is within the allowed limit (in this case, 5MB)
    if ($_FILES["image"]["size"] > 5000000) {
       
        header('Location: postnow.php?error=3');
    }

    // Allow only certain file formats (in this case, only JPEG, PNG, and GIF)
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
        
        header('Location: postnow.php?error=4');
    }

    // If all checks pass, move the file to the specified folder with the new name
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        $filename = htmlspecialchars($file_name);
        $query = "UPDATE posts SET post_link = '".$filename."' WHERE post_id =".$post_id;
        $postdata = mysqli_query($conn, $query);
        header('Location: postnow.php?posted=1');
    } else {
      header('Location: postnow.php?posted=1');
        //header('Location: postnow.php?error=5');
    }
  }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>
    Profile Page
  </title>
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <!-- Nucleo Icons -->
  <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- CSS Files -->
  <link id="pagestyle" href="../assets/css/myproject.css?v=2.0.4" rel="stylesheet" />
</head>
<body class="g-sidenav-show bg-gray-100">

<?php 
require('header.php');
?>

<div class="container-fluid py-4">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header pb-0">
              <div class="d-flex align-items-center">
                <p class="mb-0">Create a Post</p>
              </div>
              <label for="example-text-input" class="form-control-label"><?php 
              if(isset($_GET['error'])){
              if($_GET['error'] == 1){
                echo "File is not an image.";
              }
              else if($_GET['error'] == 2){
                echo "Sorry, file already exists.";
              }
              else if($_GET['error'] == 3){
                echo "Sorry, your file is too large.";
              }
              else if($_GET['error'] == 4){
                echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
              }
              else if($_GET['error'] == 5){
                echo "Sorry, there was an error uploading your file.";
              }
              else if($_GET['error'] == 10){
                echo "Sorry you cannot post more than 3 posts per day.";
              }
            }
              ?></label>
            </div>
            <div class="card-body">
              <div class="row">
              <div class="row">
              <div class="col-md-6">
                </div>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="example-text-input" class="form-control-label">Title</label>
                    <input id="title" class="form-control" name="title" type="text" value="" placeholder="This is the title of your post" required>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="example-text-input" class="form-control-label">Body</label>
                    <input id="body" class="form-control" type="text" name="body" value="" placeholder="This is the body of your post" required>
                  </div>
                </div>
                </div>
                <div class="col-md-6" style="margin-top:0.7%;">
                  <div class="form-group">
                    <label for="example-text-input" class="form-control-label">Tags</label>
                    <input id="tags" class="form-control" type="text" name="tags" value="" placeholder="IT, BUSINESS, FUNDING, SHARK TANK" required>
                  </div>
                </div>
                <div class="col-md-6">
                <div class="form-group">
                    <label for="example-text-input" class="form-control-label">Add Image</label>
                    <input class="form-control" style="margin-top:10px;" type="file" name="image" id="image" optional>
                  </div>
                </div>
              </div>
                <div class="col-md-6">
                  <div class="form-group">
                   <input name="postnow" id="postnow" class="btn btn-primary btn ms-auto" style="margin-top:10px;" type="submit" value="<?php if (!(isset($_GET['posted']))) { echo 'Post'; } if(isset($_GET['posted'])){ if($_GET['posted'] == 1){echo 'Posted';} else{echo 'Post'; } } ?>"  <?php  if(isset($_GET['posted'])){ if($_GET['posted'] == 1){echo 'disabled';} else{echo ''; } } if (!(isset($_GET['posted']))) { echo 'Post'; }
                    # code...
                   ?>>
                  </div>
              </div>
              </form>
              </div>
            </div>
          </div>
        </div>
<?php require('post.php'); ?>
  <!--   Core JS Files   -->
  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="../assets/js/myproject.min.js?v=2.0.4"></script>
  <!-- jQuery script to handle button click event -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>



</html>