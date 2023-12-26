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
  
 
  <div class="container-fluid py-4" id="post" style="">
      <div class="row">
    <div class="col-md-3" style="position: fixed;text-align:right;position: fixed; height:580px;overflow-y: scroll;">
          <div class="card">
            <div class="card-header pb-0">
            </div>
            <div class="card-body">
<?php 
  $u = 0;
  $query = "SELECT * from chat_master where user_1 = ".$_SESSION['key_1']." OR user_2 = ".$_SESSION['key_1'];
  $result = mysqli_query($conn, $query);
  if (mysqli_num_rows($result) > 0) {
      while ($row = mysqli_fetch_array($result)) {
        if($row['user_1'] == $_SESSION['key_1']){
          $u = $row['user_2'];
        }else
        {
          $u = $row['user_1'];
        } 
        $chatid = $row['chat_id'];
        $requeststatus = $row['request_accepted'];
        $user2 = $row['user_2'];
     
        $q = "SELECT * from user WHERE key_1 = ".$u;
        $r = mysqli_query($conn, $q);
        $row = mysqli_fetch_assoc($r);

?>
            <!-- start-here -->
            <div class="col-md-6" >
                    <div class="form-group">
                    <div class="row gx-4">
                    <div class="col-auto">
                    <div class="avatar-sm position-relative">
                    <a href="#" onclick="gotochat(<?php echo $chatid; ?>)">
                    <img id="chatimage_<?php echo $chatid; ?>" src="../assets/img/<?php echo $row['profile_link']; ?>" alt="profile_image" class="w-100 border-radius-lg shadow-sm">
                    </div>
                    <label id="chatname_<?php echo $chatid; ?>" for="example-text-input" class="form-control-label"><?php echo ucfirst($row['first_name'])." ".ucfirst($row['last_name']); if(($requeststatus == 0) && ($user2 == $_SESSION['key_1'])){echo "<br>(Accept Request)";}else if($requeststatus == 0){echo "<br> (Request Sent)";} ?></label>
                    </div>
                    </div>
                  </div>
                </div>
                </a>
                <hr class="horizontal dark">
              <!--stop here -->
<?php 
      }}
?>
          <div class="nav-wrapper position-relative end-0 border-curved" style="margin-top:2%;">
          </div>
        </div>
      </div>
    </div>
    

       <!--post section starts here -->
        <div class="col-md-9" style="margin-left:25%;position: fixed; height:70%;overflow-y: scroll;">
        <input type="hidden" name="" id="chatidwindow" value="">
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
                    <img id="chatwindowimage" src="../assets/img/<?php echo $profile_link; ?>" alt="profile_image" class="w-100 border-radius-lg shadow-sm">
                    </a>
                    </div>
                    <label id="chatwindowname" for="example-text-input" class="form-control-label"></label>
                    </div>
                    </div>
                  </div>
                </div>
                <hr class="horizontal dark">
                <div>
                <p id="template_body" ></p>
          <div class="nav-wrapper position-relative end-0 border-curved" style="margin-top:2%; position:fixed;">
            <div class="">
              <p id="template" type="hidden" id="template" style=""></p>
            </div>
          </div>
        </div>
      </div>
      </div>
      <!--post section ends here -->
       <!--go right --> 
    <div class="col-md-3">
    </div>
    
    <!-- Core -->
    <script src="../assets/js/core/popper.min.js"></script>
    <script src="../assets/js/core/bootstrap.min.js"></script>

    <!-- Theme JS -->
    <script src="../assets/js/myproject.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>


    function gotochat(chatid){
      
      chatid = chatid;
      label1 = document.getElementById("chatname_"+chatid);
      chatname = label1.textContent;

      label2 = document.getElementById("chatimage_"+chatid);
      chatimage = label2.src;


      label3 = document.getElementById("chatwindowname");
      label3.textContent = chatname;

      label4 = document.getElementById("chatwindowimage");
      label4.src         = chatimage;

      label5 = document.getElementById("chatidwindow");
      label5.value = chatid;

      label5 = document.getElementById("template_body");
      label5.innerHTML = "";

     
      $.ajax({
      type: 'POST',
      url: 'server.php',
      data: { chatid: chatid, gotochat:1},
      success: function(data) {
        console.log(data);
        // Handle successful response from nodes.php
        if(data == "acptrqstfrst"){
          //give accept request button
          requestbtn = '<button id="acptbtn" class="btn btn-sm" style="color:red;" onclick="acceptrequest('+chatid+');">Accept Request</button>';
          var myDiv = document.getElementById("template");
          console.log(myDiv);
          myDiv.innerHTML = requestbtn;

        }else if(data == "rqntacpt"){
          //request not yet accepted
            var myDiv = document.getElementById("template");
            myDiv.innerHTML = "Request not accepted yet";
        }else if(data == "nomsgs"){
          // request accepted but no msgs present yet
          var myDiv = document.getElementById("template_body");
          myDiv.innerHTML = "No Old Messages Say Hi..";

          srchbar = '<input id="sendmessage" type="text" class="form-control" placeholder="Press enter to send message..">';
          var myDiv = document.getElementById("template");
          console.log(myDiv);
          myDiv.innerHTML = srchbar;

        }else {
            srchbar = '<input id="sendmessage" type="text" class="form-control" placeholder="Press enter to send message..">';
            var myDiv = document.getElementById("template");
            console.log(myDiv);
            myDiv.innerHTML = srchbar;

            //latest messages will be added from ehere 
            var myDiv = document.getElementById("template_body");
            myDiv.innerHTML = data;

            var sm = document.getElementById("sendmessage");
            sm.scrollIntoView({ behavior: "smooth" });
        }
       
      },
      error: function() {
        // Handle error response from nodes.php
        console.log('Something went wrong');
      }
    });
     
    }


    //accept request
    function acceptrequest(chatid){
      chatid = chatid;
      $.ajax({
        url: "server.php",
        method: "POST",
        data: { chatid: chatid, approvemsgrqst: 1},
        success: function(data) {
            if(data == "requestaccepted"){
              srchbar = '<input id="sendmessage" type="text"    class="form-control" placeholder="Press enter to send message..">';
              var myDiv = document.getElementById("template");
              myDiv.innerHTML = srchbar;
            }
        },
        error: function() {
            console.log("something went wrong");
        }
    });
    }


    $(document).on('keydown', '#sendmessage', function(e) {
      if (e.keyCode == 13) {
        chatid = $('#chatidwindow').val();
        e.preventDefault(); // prevent the form from submitting
        message = $('#sendmessage').val();
        if(message == "" || message == " "){
          alert("Message cannot be empty");
        }else{
          $.ajax({
          url: "server.php",
          method: "POST",
          data: { chatid: chatid, message: message, sendmessage: 1, gotochat: 1},
          success: function(data) {
            console.log(data);
              if(data.includes("messagesent")){

                original_string = data;
                new_string = original_string.replace("messagesent", "")
               

                //call function to update chat box here 
                console.log("here");
                var sendmessagevalue = document.getElementById("sendmessage");
                console.log(sendmessagevalue);
                sendmessagevalue.value = " ";

                var myDiv = document.getElementById("template_body");
                myDiv.innerHTML = new_string;

                

                var sm = document.getElementById("sendmessage");
                sm.scrollIntoView({ behavior: "smooth" });
               
                
              }else
              {
                //console.log("asdasd");
              }
          },
          error: function() {
              console.log("something went wrong");
          }
      });
        }
      }
    });


    setInterval(function() {
    // Your code here
    chatid = $('#chatidwindow').val();
    if(chatid != null){

      $.ajax({
        url: "server.php",
        method: "POST",
        data: { chatid: chatid, gotochat:1},
        success: function(data) {
                // Handle successful response from nodes.php
        if(data == "acptrqstfrst"){
          //give accept request button
          requestbtn = '<button id="acptbtn" class="btn btn-sm" style="color:red;" onclick="acceptrequest('+chatid+');">Accept Request</button>';
          var myDiv = document.getElementById("template");
          console.log(myDiv);
          myDiv.innerHTML = requestbtn;

        }else if(data == "rqntacpt"){
          //request not yet accepted
            var myDiv = document.getElementById("template");
            myDiv.innerHTML = "Request not accepted yet";
        }else if(data == "nomsgs"){
          // request accepted but no msgs present yet
          var myDiv = document.getElementById("template_body");
          myDiv.innerHTML = "No Old Messages Say Hi..";


        }else {

            //latest messages will be added from ehere 
            var myDiv = document.getElementById("template_body");
            myDiv.innerHTML = data;
        }
      
    },
        error: function() {
            console.log("something went wrong");
        }
    });

    }

  }, 2000);
    </script>
   





