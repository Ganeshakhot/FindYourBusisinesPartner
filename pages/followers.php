<?php
session_start();
error_reporting(0);
if (!isset($_SESSION['session_id'])) {
  // If session ID is not set, user is not logged in. Redirect to index.php
  header("Location: index.php");
  exit();
}

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

//get followers 
$query = "SELECT nodes.*, user.key_1, user.first_name, user.last_name, user.profile_link, user.company FROM nodes JOIN user ON nodes.fromm = user.key_1 WHERE nodes.too = $user_id";
$follower_result = mysqli_query($conn, $query);

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
<?php require('header.php'); ?>
    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-md-12  ">
          <div class="card">
            <div class="card-header pb-0">
              <div class="d-flex align-items-center">
                <p class="mb-0">Followers</p>
              </div>
            </div>
            <div class="card-body">
              <div class="row">
                <?php
                 // Display user profile links and names
                if (mysqli_num_rows($follower_result) > 0) {
                  while ($row = mysqli_fetch_assoc($follower_result)) {
                    echo '
                    <a href="home.php?userid='.$row['key_1'].'">
                    <div class="col-md-6">
                    <div class="card-body p-3">
                    <div class="row gx-4">
                      <div class="col-auto">
                        <div class="avatar avatar-xl position-relative">
                          <img src="../assets/img/'; echo $row['profile_link']; ?> <?php echo '" alt="profile_image" class="w-100 border-radius-lg shadow-sm">
                        </div>
                      </div>
                      <div class="col-auto my-auto">
                        <div class="h-100">
                          <h5 class="mb-1">' ?>
                        <?php echo ucfirst($row['first_name']) . ' ' . ucfirst($row['last_name']); ?>
                      <?php echo '
                      </h5>
                      <p class="mb-0 font-weight-bold text-sm">'; ?>
                      <?php echo $company; ?>
                      <p class="text-sm" ><?php if($followsyou == 1){echo "follows you";} ?></p>
                      <?php echo '</p>
                    </div>
                  </div>
                  </div>
                </div>
                </div></a>';
                }
              }
                else {
                  echo 'No Followers Found';
                }
                ?>
              </div>
            </div>
          </div>
        </div>
        <?php require('footer.php'); ?>
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
  $('#follow-button').on('click', function() {
    // Get user ID from data attribute
    var profileId = $('#profileId').val();
    var isfollower = $('#is_follower').val();

    
    // Send user ID to nodes.php using AJAX
    $.ajax({
      type: 'POST',
      url: 'nodes.php',
      data: { profileId: profileId, noderequest: 1, isfollower: isfollower},
      success: function(data) {
        // Handle successful response from nodes.php
        console.log('Follow/unfollow successful');
        $('#follow-button').text(data);
        if (isfollower == 1){$('#is_follower').val(0); } else{$('#is_follower').val(1);}
      },
      error: function() {
        // Handle error response from nodes.php
        console.log('Follow/unfollow failed');
      }
    });
  });
});
</script>
</body>



</html>