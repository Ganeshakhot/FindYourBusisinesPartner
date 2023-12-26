<?php $blockedhideall=""; ?>
<?php error_reporting(0); ?>
<div class="position-absolute w-100 min-height-300 top-0" style="background-image: url('https://raw.githubusercontent.com/creativetimofficial/public-assets/master/myproject-pro/assets/img/profile-layout-header.jpg'); background-position-y: 50%;">
  <span class="mask bg-primary opacity-6"></span>
</div>
<input type="hidden" id="is_follower" value="<?php echo $isfollower; ?>" >
<input type="hidden" id="user_id" value="<?php echo $_SESSION['key_1']; ?>" >
<input type="hidden" id="profileId" value= "<?php echo $_GET['userid']; ?>" >
<div class="main-content position-relative max-height-vh-100 h-100">
  <div class="card shadow-lg mx-4 card-profile-bottom">
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
              <?php echo $username; ?><?php if( $self == 0 ){ echo ' <a href="#" onclick="chat('.$user_id.');"><i class="fa fa-comments py-2"></i></a>';} ?>
            </h5>
            <p class="mb-0 font-weight-bold text-sm">
            <?php echo $company; if($user_type == 1){echo " (investor)";}?>
            <p class="text-sm" ><?php if($followsyou == 1){echo "follows you";} ?></p>
            </p>
          </div>
        </div>
        <div class="col-auto my-auto">
        <div class="input-group">
            <span class="input-group-text text-body"><i class="fas fa-search" aria-hidden="true"></i></span>
            <input id="searchbar" type="text" class="form-control" placeholder="Search Anything.">
        </div>

        <div id="searchrow" style="padding:1%;position: absolute;z-index:100; background-color:white;">
        </div>

        </div>
        <div class="col-lg-6 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
          <div class="nav-wrapper position-relative end-0">
            <ul class="nav nav-pills nav-fill p-1" role="tablist">
              <?php if($blockedhideall == 0) { ?>
            <li class="nav-item">
                <a class=" mb-0 px-0 py-1 active d-flex align-items-center justify-content-center " data-bs-toggle="" href="explore.php" role="tab" aria-selected="true">
                  <span class="ms-2" >Explore</span>
                </a>
              </li>
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
              <?php } ?>
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
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  // Bind keyup event to input element
$('#searchbar').on('keyup', function() {
  // Get input value
  var inputValue = $(this).val();
  console.log(inputValue);
  if(inputValue == " " || inputValue == ""){
   
  }else
  {
    var stringSize = inputValue.length;
    if(stringSize > 2){
      // Perform AJAX call
    $.ajax({
      url: 'server.php',
      type: 'POST',
      data: {inputValue: inputValue, search:1},
      success: function(response) {
        // Display AJAX response
        if(response != "nosearch"){
          $('#searchrow').html(response);
        }
        else
        {
          $('#searchrow').html("");
        }
      }
    });
  }else
  {
    $('#searchrow').html("");
  }
}

});

//block user 
function chat(userid){
  userid = userid;
  console.log(userid);
  $.ajax({
      type: 'POST',
      url: 'server.php',
      data: { userid: userid, chat:1},
      success: function(data) {
        // Handle successful response from nodes.php
     
        if(data == "goto"){
          window.location.href = "chat.php";
        }else{
          alert(data);
        }
       
      },
      error: function() {
        // Handle error response from nodes.php
        console.log('Something went wrong');
      }
    });
}
</script>

        