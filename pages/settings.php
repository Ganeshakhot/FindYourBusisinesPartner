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
$bio = $row['bio'];
$firstname = $row['first_name'];
$lastname = $row['last_name'];
$linkedin = $row['linkedin'];
$twitter = $row['twitter'];
$phone = $row['phone'];
$email = $row['email'];

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




// Check if image file was uploaded
if(isset($_POST["submit"])) {
    $target_dir = "../assets/img/"; // Folder where the image will be saved
    $target_file = $target_dir . basename($_FILES["image"]["name"]); // File path of the image
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION)); // Get the file extension
    $error = 0;
    $error_msg = "";
    // Check if the image is a valid image
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if($check === false) {
        $error_msg = "File is not an image.";
        $error = 1;
    }

    

    // Check if the file size is within the allowed limit (in this case, 5MB)
    if ($_FILES["image"]["size"] > 5000000) {
        $error_msg = "Sorry, your file is too large.";
        $error = 2;
    }

    // Allow only certain file formats (in this case, only JPEG, PNG, and GIF)
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
        $error_msg ="Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $error = 3;
    }

    if($error != 0){
      header("Location: settings.php?erid=".$error."&ermsg=".$error_msg);

    }else{

    // If all checks pass, move the file to the specified folder
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        $filename = htmlspecialchars( basename( $_FILES["image"]["name"]));
        $query = "UPDATE user SET profile_link = '".$filename."' WHERE key_1 = ".$user_id;
        $result = mysqli_query($conn, $query);
        header("Location: settings.php");
    } else {
        $error_msg = "Sorry, there was an error uploading your file.";
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
        <div class="col-md-8">
          <div class="card">
            <div class="card-header pb-0">
              <div class="d-flex align-items-center">
                <p class="mb-0">Edit Profile</p>
              </div>
            </div>
            <div class="card-body">
              <div class="row">
              <div class="row">
              <div class="col-md-6">
                  <div class="form-group">
                    <label for="example-text-input" class="form-control-label">Profile Picture</label>
                    <br>
                    <label for="example-text-input" class="form-control-label" style="color : red"><?php if(isset($_GET['erid'])){ if($_GET['erid'] != 0){ echo $_GET['ermsg']; }} ?> </label>
                    <img src="../assets/img/<?php echo $profile_link; ?>" alt="profile_image" class="w-100 border-radius-lg shadow-sm">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
                        <input class="form-control" style="margin-top:10px;" type="file" name="image" id="image">
                        <input class="btn btn-primary btn-sm ms-auto" style="margin-top:10px;" type="submit" value="Upload Image" name="submit">
                    </form>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="example-text-input" class="form-control-label">About me</label>
                    <input id="bio" class="form-control" type="text" value="<?php echo $bio; ?>">
                  </div>
                </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="example-text-input" class="form-control-label">LinkedIn Link</label>
                    <input id="linkedin" class="form-control" type="text" value="<?php echo $linkedin; ?>">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="example-text-input" class="form-control-label">Twitter Link</label>
                    <input id="twitter" class="form-control" type="text" value="<?php echo $twitter; ?>">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                   <input id="saveabout" class="btn btn-primary btn-sm ms-auto" style="margin-top:10px;" type="submit" value="Save About Us" name="submitimages">
                  </div>
                </div>
              </div>
              <hr class="horizontal dark">
              <p class="text-uppercase text-sm">Contact Information</p>
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="example-text-input" class="form-control-label">Phone</label>
                    <input id="phone" class="form-control" type="tel" value="<?php echo $phone; ?>">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="example-text-input" class="form-control-label">Email</label>
                    <input class="form-control" type="text" value="<?php echo $email; ?>" disabled>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="example-text-input" class="form-control-label">Company Name</label>
                    <input id="company" class="form-control" type="text" value="<?php echo $company; ?>">
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                  <div class="form-group">
                   <input id="savecontactinfo" class="btn btn-primary btn-sm ms-auto" style="margin-top:10px;" type="submit" value="Save Contact Info" name="submitimages">
                  </div>
              </div>
              <hr class="horizontal dark">
              <p class="text-uppercase text-sm">Security Details</p>
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="example-text-input" class="form-control-label">Old Password</label>
                    <input id="oldpassword" class="form-control" type="password" value="">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="example-text-input" class="form-control-label">New Password</label>
                    <input id="newpassword" class="form-control" type="password" value="">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                   <input id="changepassword" class="btn btn-primary btn-sm ms-auto" style="margin-top:10px;" type="submit" value="Change Password" name="submitimages">
                  </div>
              </div>
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
<script>
$(document).ready(function() {
  // Attach click event listener to follow/unfollow button
  $('#changepassword').on('click', function() {
    // Get user ID from data attribute
    var oldpassword = $('#oldpassword').val();
    var newpassword = $('#newpassword').val();

    if (oldpassword == "") { // check if the password field is empty
        alert("Old Password field cannot be empty");
        exit();
    }

    if (newpassword == "") { // check if the password field is empty
        alert("New Password field cannot be empty");
        exit();
    } else if (!newpassword.match(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[a-zA-Z\d@$!%*?&]{8,}$/)) {
    alert("Password must contain at least one uppercase letter, one lowercase letter, one digit, and be at least 8 characters long");
    exit();
} else {}
   

    // Send user ID to nodes.php using AJAX
    $.ajax({
      type: 'POST',
      url: 'server.php',
      data: { oldpassword: oldpassword, newpassword: newpassword, changepassword: 1},
      success: function(data) {
        // Handle successful response from nodes.php
        console.log('Change Password');
        $('#changepassword').val(data);
      },
      error: function() {
        // Handle error response from nodes.php
        console.log('Something Went Wrong');
      }
    });
  });
});


$(document).ready(function() {
  // Attach click event listener to follow/unfollow button
  $('#savecontactinfo').on('click', function() {
    // Get user ID from data attribute
    var phone = $('#phone').val();
    var company = $('#company').val();

    if (phone == "") { // check if the password field is empty
        alert("Phone number cannot be empty");
        exit();
    }else if (phone.length < 10) {
        alert("Phone number must have at least 10 digits");
    }

    if (company == "") { // check if the password field is empty
        alert("Company Name cannot be empty");
        exit();
    }

    // Send user ID to nodes.php using AJAX
    $.ajax({
      type: 'POST',
      url: 'server.php',
      data: { phone: phone, company: company, savecontactinfo: 1},
      success: function(data) {
        // Handle successful response from nodes.php
        //console.log('Change Password');
        $('#savecontactinfo').val(data);
      },
      error: function() {
        // Handle error response from nodes.php
        console.log('Something Went Wrong');
      }
    });
  });
});

$(document).ready(function() {
  // Attach click event listener to follow/unfollow button
  $('#saveabout').on('click', function() {
    // Get user ID from data attribute
    var bio = $('#bio').val();
    var linkedin = $('#linkedin').val();
    var twitter = $('#twitter').val();

    if(twitter != ""){
        // Check if Twitter link is valid
        var twitterRegex = /^https?:\/\/(www\.)?twitter\.com\/[a-zA-Z0-9_]+\/?$/;
        if (!twitterRegex.test(twitter)) {
            alert("Twitter link is not valid");
            exit();
        }
    }

    if(linkedin != ""){
    // Check if LinkedIn link is valid
    var linkedinRegex = /^https?:\/\/(www\.)?linkedin\.com\/in\/[a-zA-Z0-9-]+\/?$/;
    if (!linkedinRegex.test(linkedin)) {
        alert("LinkedIn link is not valid");
        exit();
    }
    }

    

    // Send user ID to nodes.php using AJAX
    $.ajax({
      type: 'POST',
      url: 'server.php',
      data: { bio: bio, linkedin: linkedin, twitter: twitter, saveabout: 1},
      success: function(data) {
        // Handle successful response from nodes.php
        //console.log('Change Password');
        $('#saveabout').val(data);
      },
      error: function() {
        // Handle error response from nodes.php
        console.log('Something Went Wrong');
      }
    });
  });
});


    
</script>
</body>



</html>