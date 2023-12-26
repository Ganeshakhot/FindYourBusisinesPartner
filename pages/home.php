<?php
session_start();
error_reporting(0);
require('database.php');
$blocked = 0;
$blockedhideall = 0;
if (!isset($_SESSION['session_id'])) {
  // If session ID is not set, user is not logged in. Redirect to index.php
  header("Location: index.php");
  exit();
}

$self = 0;
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
  //check block or unblock 
  $query = "SELECT * FROM reported where reported_user = ".$user_id." AND reported_by = ".$_SESSION['key_1'];
  $result = mysqli_query($conn, $query);
   
    if (mysqli_num_rows($result) > 0) {
        $query = "DELETE FROM reported WHERE reported_user = ".$userid." AND reported_by = ".$_SESSION['key_1'];
        $result = mysqli_query($conn, $query);
        $blocked = 1;
    }
    else
    {
      $blocked = 0;
    }
}

//let user know that he / she has blocked you
if($self == 0){
  //check block or unblock 
  $query = "SELECT * FROM reported where reported_user = ".$_SESSION['key_1']." AND reported_by = ".$user_id;
  $result = mysqli_query($conn, $query);
  if (mysqli_num_rows($result) > 0) {
    $blockedhideall = 1;
  }
}
else
{

}

 

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
$bio = $row['bio'];
$firstname = $row['first_name'];
$lastname = $row['last_name'];
$linkedin = $row['linkedin'];
$twitter = $row['twitter'];
$admin_ban = $row['admin_ban'];

if($admin_ban == 1){
 echo "<script>alert('User has been banned by Admin');</script>";
 echo "<script>window.location.href='home.php';</script>";
}


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


//total number of investors 
$query = "SELECT COUNT(*) as investors FROM user WHERE user_type = '1'";
$result = $conn->query($query);
$row = $result->fetch_assoc();
$investors = $row['investors'];



//total number of businesses
$query = "SELECT COUNT(*) as business FROM user WHERE user_type = '0'";
$result = $conn->query($query);
$row = $result->fetch_assoc();
$business = $row['business'];


//total posts made today 
$query = "SELECT COUNT(*) as postsmade FROM posts WHERE DATE(`post_time`) = CURRENT_DATE";
$result = $conn->query($query);
$row = $result->fetch_assoc();
$postsmade = $row['postsmade'];


//total reported users
$query = "SELECT COUNT(*) as reported FROM admin_reported";
$result = $conn->query($query);
$row = $result->fetch_assoc();
$reported = $row['reported'];




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
        <div class="col-md-12 ">
          <div class="card">
            <div class="card-header pb-0">
              <div class="d-flex align-items-center">
              <?php if($blockedhideall == 0) { ?>
                <p class="mb-0">Profile Info</p>
                <?php if($self ==  1 ){echo '<a class="btn btn-primary btn-sm ms-auto" href="settings.php">Settings</a>';}else{echo '<a id="block-button" class="btn btn-danger btn-sm ms-auto" href="#" onclick="blockuser('.$_GET['userid'].')">' ?><?php if($blocked == 1){echo "UnBlock"; }else{echo "Block"; } ?><?php echo '</a>'; } ?>
              </div>
              <?php if($self !=  1 ){echo '<a id="report-button" class="btn btn-danger btn-sm ms-auto" href="#" onclick="reportuser('.$_GET['userid'].')">Report</a>'; } ?>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="example-text-input" class="form-control-label">Fullname</label>
                    <p class="mb-0"><?php echo ucfirst($firstname)." ".ucfirst($lastname); ?></p>
                    <br>
                    <label for="example-text-input" class="form-control-label">About me</label>
                    <p class="mb-0"><?php echo ucfirst($bio); ?></p>
                    <br>
                    <label for="example-text-input" class="form-control-label">Social Network</label>
                    <a target="_blank" href="<?php echo $linkedin; ?>"><i class="fab fa-linkedin"></i></a>
                    <a target="_blank" href="<?php echo $twitter;  ?>"><i class="fab fa-twitter"></i></a>
                  </div>
                </div>
              </div>
            </div>
            <?php }else{echo "YOU HAVE BEEN BLOCKED BY THE USER";} ?>
            <?php  if($_SESSION['user_type'] == 3 ){  ?>
              <div class="container-fluid py-4">

            
        <div class="row">
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-uppercase font-weight-bold">TOTAL INVESTORS</p>
                    <h5 class="font-weight-bolder">
                    <?php echo $investors; ?>
                    </h5>
                    <p class="mb-0">
                      <a href="admin.php?investors=1">View More</a>
                    </p>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                    <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        

        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-uppercase font-weight-bold">TOTAL BUSINESSES</p>
                    <h5 class="font-weight-bolder">
                    <?php echo  $business; ?> 
                    </h5>
                    <p class="mb-0">
                    <a href="admin.php?businesses=1">View More</a>
                    </p>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                    <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>


        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-uppercase font-weight-bold">TODAYS POSTS</p>
                    <h5 class="font-weight-bolder">
                    <?php echo $postsmade; ?> 
                    </h5>
                    <p class="mb-0">
                      Made today
                    </p>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                    <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        

        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-uppercase font-weight-bold">REPORTED USERS</p>
                    <h5 class="font-weight-bolder">
                      <?php echo $reported; ?>
                    </h5>
                    <p class="mb-0">
                    <a href="admin.php?reported=1">View Reported Users</a>
                    </p>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                    <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>


            <?php }  ?>
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


//block user 
function blockuser(userid){
  userid = userid;
  $.ajax({
      type: 'POST',
      url: 'server.php',
      data: { userid: userid, block:1},
      success: function(data) {
        // Handle successful response from nodes.php
        console.log('blocked succesfully');
        $('#block-button').text(data);
      },
      error: function() {
        // Handle error response from nodes.php
        console.log('Something went wrong');
      }
    });
}

function reportuser(userid){
  userid = userid;
  $.ajax({
      type: 'POST',
      url: 'server.php',
      data: { userid: userid, report:1},
      success: function(data) {
        // Handle successful response from nodes.php
        console.log('reported succesfully');
        $('#report-button').text(data);
      },
      error: function() {
        // Handle error response from nodes.php
        console.log('Something went wrong');
      }
    });
}
</script>
</body>



</html>