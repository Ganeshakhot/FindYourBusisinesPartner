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
$lastname = ucfirst($row['last_name']);
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


$postid = 0;
$postsfound = 0;
$query = "";

$totalposts = 0;
$query = "SELECT count(*) as totalposts FROM posts WHERE post_by = ".$user_id;
$postdata = mysqli_query($conn, $query);


// post data fetch starts here 
if( !isset($_GET['firstpost']) ){
    // get latest post

    if(isset($_GET['postid'])){
        $postid = $_GET['postid'];
        $query = "SELECT * FROM posts WHERE post_by = ".$user_id." LIMIT 1 OFFSET ".$postid;
    }
    else
    {
        $query = "SELECT * FROM posts WHERE post_by = ".$user_id." LIMIT 1";
    }
    $postdata = mysqli_query($conn, $query);

    if (mysqli_num_rows($postdata) > 0){
        $postsfound = 1;
        $row = mysqli_fetch_assoc($postdata);
    }
    else
    {
        $postsfound = 0;
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
<body class="g-sidenav-show bg-gray-100" style="background-color: coral;">


<input type="hidden" id="is_follower" value="<?php echo $isfollower; ?>" >
<input type="hidden" id="user_id" value="<?php echo $_SESSION['key_1']; ?>" >
<input type="hidden" id="profileId" value= "<?php echo $_GET['userid']; ?>" >
<input type="hidden" id="user_id_as_per_php" value="<?php echo $user_id; ?>" >
<input type="hidden" id="postidcounter" value= "0" >

<div class="main-content position-relative max-height-vh-100 h-100">
  <div class="card shadow-lg mx-4" style="margin-top:2%;">
    <div class="card-body p-3">
      <div class="row gx-4">
        <div class="col-auto">
          <div class="avatar avatar-xl position-relative">
          <a href="home.php">
          <img src="../assets/img/<?php echo $profile_link; ?>" alt="profile_image" class="w-100 border-radius-lg shadow-sm">
          </a>
         </div>
        </div>
        <div class="col-auto my-auto">
          <div class="h-100">
            <h5 class="mb-1">
              <?php echo $username; ?>
            </h5>
            <p class="mb-0 font-weight-bold text-sm">
            <?php echo $company; if($user_type == 1){echo " (investor)";}?>
            <p class="text-sm" ><?php if($followsyou == 1){echo "follows you";} ?></p>
            </p>
          </div>
        </div>
        <div class="col-auto my-auto">
        </div>
        <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
          <div class="nav-wrapper position-relative end-0">
            <ul class="nav nav-pills nav-fill p-1" role="tablist">
            <li class="nav-item">
                <a class=" mb-0 px-0 py-1 active d-flex align-items-center justify-content-center " data-bs-toggle="" href="feed.php<?php if(isset($_GET['userid'])){echo '?userid='.$_GET['userid'].'';} ?>" role="tab" aria-selected="true">
                  <span class="ms-2">MyFeed</span>
                </a>
              </li>
              <li class="nav-item">
                <a class=" mb-0 px-0 py-1 active d-flex align-items-center justify-content-center " data-bs-toggle="" href="following.php<?php if(isset($_GET['userid'])){echo '?userid='.$_GET['userid'].'';} ?>" role="tab" aria-selected="true">
                  <span class="ms-2"><?php echo $following; ?> Following</span>
                </a>
              </li>
              <li class="nav-item">
                <a class=" mb-0 px-0 py-1 d-flex align-items-center justify-content-center " data-bs-toggle="" href="followers.php<?php if(isset($_GET['userid'])){echo '?userid='.$_GET['userid'].'';} ?>" role="tab" aria-selected="false">
                  <span class="ms-2"><?php echo $followers; ?> Followers</span>
                </a>
              </li>
              <?php if($self == 0 && $_GET['userid'] != $_SESSION['key_1'])
              { if($isfollower == 1){
              echo 
              '
              <li class="nav-item">
                <a id="follow-button" class="mb-0 px-0 py-1 d-flex align-items-center justify-content-center " data-bs-toggle="tab" href="javascript:;" role="tab" aria-selected="false">
                  <span class="ms-2">Unfollow</span>
                </a>
              </li> ';
              }
              else
              {
              echo
              '
              <li class="nav-item">
              <a id="follow-button" class=" mb-0 px-0 py-1 d-flex align-items-center justify-content-center " data-bs-toggle="tab" href="javascript:;" role="tab" aria-selected="false">
                <span class="ms-2">Follow</span>
              </a>
              </li> ';
              }
              }
              ?>
              <li class="nav-item">
                <a class=" mb-0 px-0 py-1 active d-flex align-items-center justify-content-center " data-bs-toggle="" href="logout.php" role="tab" aria-selected="true">
                  <span class="ms-2">Logout</span>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>

<!-- Modal -->
<div class="modal fade " id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Comments</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ...
      </div>
      
      <div class="modal-footer">
      <div class="col-md-9" style="padding:1%;">
        <div class="form-group">
        <input id="comment_data" class="form-control" type="text" value="" placeholder="Add a comment.." required> 
        <input type="hidden" id="comment_post_id" class="form-control" type="text" value="">
       </div>
      </div> 
        <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="" onclick="add_comment(<?php echo $_SESSION['key_1']; ?>)" >Send</button>
      </div>
    </div>
  </div>
</div>

  <div class="container-fluid py-4" id="post">
  </div>
  <?php if($postsfound == 1) { /* ?>  
 
  <div class="container-fluid py-4" id="post">
      <div class="row">
        <!--go left --> 
    <div class="col-md-3" style="text-align:right;">
    </div>

       <!--post section starts here -->
        <div class="col-md-6">
          <div class="card">
            <div class="card-header pb-0">
            </div>
            <div class="card-body">
            <div class="col-md-6">
                    <div class="form-group">
                    <div class="row gx-4">
                    <div class="col-auto">
                    <div class="avatar position-relative">
                    <a href="home.php">
                    <img src="../assets/img/<?php echo $profile_link; ?>" alt="profile_image" class="w-100 border-radius-lg shadow-sm">
                    </a>
                    </div>
                    <label for="example-text-input" class="form-control-label"><?php echo $username." ".$lastname; ?></label>
                    </div>
                    </div>
                  </div>
                </div>
                <hr class="horizontal dark">
              <p class="text-uppercase text-sm"><?php echo $row['post_title']; ?>
              </p>
              <p class="text-sm"><?php echo $row['post_body']; ?>
              </p>
              <?php if($row['post_link'] != NULL){ ?>
              <div class="col-auto">
                <a href="home.php">
                <img src="../assets/img/<?php echo $row['post_link']; ?>" alt="profile_image" class="w-100 border-radius-lg shadow-sm">
                </a>
             </div>
                <?php } ?>
           
          <div class="nav-wrapper position-relative end-0 border-curved" style="margin-top:2%;">
            <ul class="nav nav-pills nav-fill p-1" role="tablist">
            <li class="nav-item">
                <a class=" mb-0 px-0 py-1 active d-flex align-items-center justify-content-center " data-bs-toggle="" href="feed.php<?php if(isset($_GET['userid'])){echo '?userid='.$_GET['userid'].'';} ?>" role="tab" aria-selected="true">
                  <span class="ms-2"><i class="fa fa-thumbs-up py-2"> </i></span>
                </a>
              </li>
              <li class="nav-item">
                <a class=" mb-0 px-0 py-1 active d-flex align-items-center justify-content-center " data-bs-toggle="" href="feed.php<?php if(isset($_GET['userid'])){echo '?userid='.$_GET['userid'].'';} ?>" role="tab" aria-selected="true">
                  <span class="ms-2"><i class="fa fa-comment py-2"> </i></span>
                </a>
              </li>
              <li class="nav-item">
                <a class=" mb-0 px-0 py-1 active d-flex align-items-center justify-content-center " data-bs-toggle="" href="feed.php<?php if(isset($_GET['userid'])){echo '?userid='.$_GET['userid'].'';} ?>" role="tab" aria-selected="true">
                  <span class="ms-2"><i class="fa fa-share py-2"> </i></span>
                </a>
              </li>
              <li class="nav-item">
                <a class=" mb-0 px-0 py-1 active d-flex align-items-center justify-content-center " data-bs-toggle="" href="feed.php<?php if(isset($_GET['userid'])){echo '?userid='.$_GET['userid'].'';} ?>" role="tab" aria-selected="true">
                  <span class="ms-2"><i class="fa fa-save py-2"> </i></span>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
      </div>
      <!--post section ends here -->
       <!--go right --> 
    <div class="col-md-3">
    </div>

              <?php */ } else{ echo ""; }?>
    <?php require('post.php'); ?>
    
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    <!-- Core -->
    <script src="../assets/js/core/popper.min.js"></script>
    <script src="../assets/js/core/bootstrap.min.js"></script>

    <!-- Theme JS -->
    <script src="../assets/js/myproject.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>

$(document).ready(function() {
    var postid = $('#postidcounter').val();
    var userid = $('#user_id_as_per_php').val();
  $.ajax({
        url: "server.php",
        method: "POST",
        data: { postid: postid, userid: userid, postfetch: 1 },
        success: function(data) {
            $('#post').append(data);
        },
        error: function() {
        // Code to handle error in retrieval of data
        }
    });
});




$(document).ready(function() {
       $(window).scroll(function() {
        //console.log($(window).scrollTop() + $(window).height());
       if ($(window).scrollTop() + $(window).height() > 300) {
        postid1 = $('#postidcounter').val();
        var postid = parseInt(postid1);
        var height = $(document).height();

        if(postid == 0){
            postid = 1;
        }else
        {
            postid = postid + 1;
        }
        var userid = $('#user_id_as_per_php').val();
    $.ajax({
        url: "server.php",
        method: "POST",
        data: { postid: postid, userid: userid, postfetch: 1 },
        success: function(data) {
                if(data == "No More Posts"){
                    $('#post').val(data);
                }
                else
                {
                    $('#post').append(data);
                    $('#postidcounter').val(postid);
                }
                
            
        },
        error: function() {
            $('#post').append("Fin");
        }
    });
   }     
    });
    });


    function like(postid){
        console.log("once");
        postid = postid;
        userid = $('#user_id').val();
        $.ajax({
        url: "server.php",
        method: "POST",
        data: { postid: postid, userid: userid, like: 1 },
        success: function(data) {
                    let nameArr = data.split(",");
                    let numb = nameArr[0];
                    let stat = nameArr[1];
                    $('#likenumber_'+postid).text(numb);

                    if(stat == "liked"){
                        var element = document.getElementById('thumbsup_'+postid);
                        var style = "color:red;";
                        element.setAttribute("style", style);
                    }
                    else
                    {
                        var element = document.getElementById('thumbsup_'+postid);
                        var style = "color:;";
                        element.setAttribute("style", style);
                    }
                    
        },
        error: function() {
            $('#post').append("Fin");
        }
    });
    }

    function save(postid){
        console.log("inside save");
        postid = postid;
        $.ajax({
        url: "server.php",
        method: "POST",
        data: { postid: postid, save: 1 },
        success: function(data) {
                    if(data == "saved"){
                        var element = document.getElementById('saved_'+postid);
                        var style = "color:red;";
                        element.setAttribute("style", style);
                    }
                    else
                    {
                        var element = document.getElementById('saved_'+postid);
                        var style = "color:;";
                        element.setAttribute("style", style);
                    }
                    
        },
        error: function() {
            $('#post').append("Fin");
        }
    });
    }


    function comments(postid){
        console.log("inside view comments");
        postid = postid;
        $.ajax({
        url: "server.php",
        method: "POST",
        data: { postid: postid, viewcomments: 1},
        success: function(data) {
                    if(data == "nocomm"){
                        // there are no comments 
                        const myElement = document.querySelector('.modal-body');
                        myElement.textContent = 'No Comments Yet';

                        const myElement2 = document.querySelector('#comment_post_id');
                        myElement2.value = postid;

                        
                        
                    }
                    else
                    {
                        // there are comments 
                        const myElement = document.querySelector('.modal-body');
                        myElement.innerHTML = data;

                        const myElement2 = document.querySelector('#comment_post_id');
                        myElement2.value = postid;
                    }
                    
        },
        error: function() {
            $('#post').append("Fin");
        }
    });

    }

    function add_comment(postid){
        comment = $('#comment_data').val();
        postid = $('#comment_post_id').val();

        if(comment == ""){
            alert("Please enter a comment first.");
            exit();
        }

        $.ajax({
        url: "server.php",
        method: "POST",
        data: { postid: postid, comment: comment, submitcomment: 1},
        success: function(data) {
                        // there are comments 
                        const myElement = document.querySelector('.modal-body');
                        myElement.innerHTML = data;
        },
        error: function() {
            //$('#post').append("");
            console.log("Something Went Wrong");
        }
    });
    }


    function delpost(postid){
      postid = postid;
      $.ajax({
        url: "server.php",
        method: "POST",
        data: { postid: postid, delpost:1},
        success: function(data) {
                        // there are comments 
                        alert(data);
        },
        error: function() {
            //$('#post').append("");
            console.log("Something Went Wrong");
        }
    });
    }
    </script>
   





