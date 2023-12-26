<?php
session_start();
error_reporting(0);
if(isset($_POST['signup'])){
    // Get values from form
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $company = $_POST['company'];
    $passsword = $_POST['password'];
    $birthdate = $_POST['birthdate'];
    $investor = $_POST['investor'];

    $inv = 0;
    if($investor == "true"){
        $inv = 1;
    }else{
        $inv = 0;
    }

    $gender = "0";

    // Hash password using MD5
    $passsword = md5($passsword);

    require('database.php');

    //check if user is existing by checking email id 
    $stmt = $conn->prepare("SELECT * FROM user WHERE email = ?");
    // Bind the parameter
    $stmt->bind_param("s", $email);

    // Execute the statement
    $stmt->execute();

    // Store the result
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        // User exists
    echo "User already Exists";
    } else {
        // User does not exist
        // Insert values into database
        $sql = "INSERT INTO user (first_name, last_name, email, passwordd, company, birth_date, user_type, gender)
        VALUES ('$firstname', '$lastname', '$email', '".$passsword."','$company', '$birthdate', '$inv', $gender)";

        if (mysqli_query($conn, $sql)) {
        echo "New record created successfully";
        } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
    }

    mysqli_close($conn);
}

if(isset($_POST['changepassword'])){
    require('database.php');
    $oldpassword = $_POST['oldpassword'];
    $newpassword = $_POST['newpassword'];

    $oldpassword = md5($oldpassword);
    //$newpassword = md5($newpassword);
    $userid = $_SESSION['key_1'];

    $query = "SELECT passwordd FROM user WHERE key_1 = $userid";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        if($row['passwordd'] != $oldpassword){
            //echo $row['passwordd'];
            echo 'Old password Wrong';
        }
        else
        {
            $query = "UPDATE user SET passwordd = MD5('".$newpassword."') WHERE key_1 = ".$userid;
            $result = mysqli_query($conn, $query);
            echo 'Password Changed';
        }
    }
    mysqli_close($conn);
}

if(isset($_POST['savecontactinfo'])){
    require('database.php');
    $phone = $_POST['phone'];
    $company = $_POST['company'];

    $userid = $_SESSION['key_1'];
    $query = "UPDATE user SET phone = '".$phone."', company = '".$company."' WHERE key_1 = ".$userid;
    $result = mysqli_query($conn, $query);
    echo 'Info Saved';
  
    mysqli_close($conn);
}

if(isset($_POST['saveabout'])){
    require('database.php');
    $bio = $_POST['bio'];
    $linkedin = $_POST['linkedin'];
    $twitter = $_POST['twitter'];

    $userid = $_SESSION['key_1'];
    $query = "UPDATE user SET linkedin = '".$linkedin."', twitter = '".$twitter."', bio = ' ".$bio."' WHERE key_1 = ".$userid;
    $result = mysqli_query($conn, $query);
    echo 'Info Saved';
  
    mysqli_close($conn);
}

if(isset($_POST['postnow'])){
    require('database.php');
    $title = $_POST['title'];
    $body = $_POST['body'];
    $image = $_POST['image'];
    $tags = $_POST['tags'];

    $words = explode(", ", $tags);
    $var1 = isset($words[0]) ? $words[0] : "";
    $var2 = isset($words[1]) ? $words[1] : "";
    $var3 = isset($words[2]) ? $words[2] : "";
    $var4 = isset($words[3]) ? $words[3] : "";
    $var5 = isset($words[4]) ? $words[4] : "";

    $userid = $_SESSION['key_1'];
    $query = "INSERT INTO posts (post_by, post_title, post_body, tag_1, tag_2, tag_3, tag_4, tag_5)
    VALUES ('$userid' , '$title', '$body', '$var1', '$var2', '$var3', '$var4', '$var5')";
    $result = mysqli_query($conn, $query);
    echo "Posted";

  
    mysqli_close($conn);
}



if(isset($_POST['postfetch'])){
require('database.php');
$user_id = $_POST['userid'];
$height = $_POST['height'];
$explore = $_POST['explore'];
$query = "";

if($explore == 1){
    //SELECT * FROM posts ORDER BY RAND() LIMIT 1 OFFSET 0;
    if(isset($_POST['postid'])){
        $query0 = "SELECT post_by,post_id from posts LIMIT 50 OFFSET ".$_POST['postid'];
    }
    else
    {
        $query0 = "SELECT post_by,post_id from posts LIMIT 50";
    }
    $postdata0 = mysqli_query($conn, $query0);
    if (mysqli_num_rows($postdata0) > 0){
        $row0 = mysqli_fetch_assoc($postdata0);
        $user_id = $row0['post_by'];
        $post_id = $row0['post_id'];
    }
}

$query = "SELECT * from user WHERE key_1 = $user_id";
$result = $conn->query($query);
$row = $result->fetch_assoc();
$company = ucfirst($row['company']);
$username = ucfirst($row['first_name']);
$lastname = ucfirst($row['last_name']);
$profile_link = $row['profile_link'];
$user_type = $row['user_type'];

if(isset($_POST['postid'])){
    $postid = $_POST['postid'];
    if($postid == 0){
        $query = "SELECT * FROM posts WHERE post_by = ".$user_id." ORDER BY post_time DESC LIMIT 1";
    }
    else
    {
        $query = "SELECT * FROM posts WHERE post_by = ".$user_id." ORDER BY post_time DESC LIMIT 1 OFFSET ".$postid;
    }
    if (isset($_POST['saved'])) {
        # code...
    
    if($explore == 1){
        if($_POST['saved'] == 1){
            $query = "SELECT * FROM posts INNER JOIN saves ON posts.post_id = saves.post_id WHERE saves.user_id = ".$_SESSION['key_1']." ORDER BY post_time DESC LIMIT 1 OFFSET ".$postid;
        }else
        {
            $query = "SELECT * FROM posts WHERE post_by = ".$user_id." AND post_id = ".$post_id." ORDER BY post_time DESC LIMIT 1";
        }
        
    }
    }
}

$postdata = mysqli_query($conn, $query);


if (mysqli_num_rows($postdata) > 0){
    $postsfound = 1;
    $row = mysqli_fetch_assoc($postdata);

    $query1 = "SELECT * FROM post_likes WHERE post_id = ".$row["post_id"]." AND liked_by = ".$_SESSION['key_1'];
    // Execute SQL statement and check for result
    $result1 = mysqli_query($conn, $query1);
    $liked = 0;
    //echo mysqli_num_rows($result1);
    if (mysqli_num_rows($result1) > 0){ $liked = 1; } else{ $liked = 0; }

    $query2 = "SELECT * FROM saves WHERE post_id = ".$row["post_id"]." AND user_id = ".$_SESSION['key_1'];
    // Execute SQL statement and check for result
    $result2 = mysqli_query($conn, $query2);
    $saved = 0;
    //echo mysqli_num_rows($result1);
    if (mysqli_num_rows($result2) > 0){ $saved = 1; } else{ $saved = 0; }




 echo '
 <!--go left --> 
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
             <img src="../assets/img/';?><?php echo $profile_link; ?><?php echo '" alt="profile_image" class="w-100 border-radius-lg shadow-sm">
             </a>
             </div>
             <label for="example-text-input" class="form-control-label">'; ?><?php echo $username." ".$lastname; echo '</label>
             <label for="example-text-input" class="form-control-label">'; ?><?php if($user_type == 1){echo "(investor)";}else{echo "(owner)";} ?><?php echo ' </label>
             '; ?> <?php if($user_id == $_SESSION['key_1'] || $_SESSION['user_type'] == 3){ echo '<label style="text-align:right;" onclick="delpost('.$row["post_id"].')"><i class="fa fa-trash"></i></label>'; }?> 
             <?php echo '</div>
             </div>
           </div>
         </div>
         <hr class="horizontal dark">
       <p class="text-uppercase text-sm">'; ?><?php echo $row['post_title']; ?>
       <?php echo '
        </p>
       <p class="text-sm">'; ?><?php echo $row['post_body']; ?>
       <?php echo '
        </p>' ?>
       <?php if($row['post_link'] != NULL){ ?>
       <?php echo '
        <div class="col-auto">
         <a href="home.php">
         <img src="../assets/img/'; ?><?php echo $row['post_link']; ?><?php echo '" alt="profile_image" class="w-100 border-radius-lg shadow-sm">
         </a>
      </div>'; ?>
         <?php } ?>
    <?php echo '
   <div class="nav-wrapper position-relative end-0 border-curved" style="margin-top:2%;">
     <ul class="nav nav-pills nav-fill p-1" role="tablist">
     <li class="nav-item">
            <a class=" mb-0 px-0 py-1 active d-flex align-items-center justify-content-center " data-bs-toggle=""  onclick="like('.$row["post_id"].');"';?><?php echo '" role="tab" aria-selected="true">
            <span class="ms-2"><i class="fa fa-thumbs-up py-2" style="';?><?php if($liked == 1){echo 'color:red"';}else{echo '"';} ?> <?php echo 'id="thumbsup_'.$row["post_id"].'"></i><label id="likenumber_'.$row["post_id"].'">'.$row['post_likes'].'</label></span>
            </a>
       </li>
       <li class="nav-item">
         <a class=" mb-0 px-0 py-1 active d-flex align-items-center justify-content-center " role="button" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="comments('.$row["post_id"].');">
           <span class="ms-2"><i class="fa fa-comment py-2"> </i><label>';?><?php echo $row['post_comments']; ?><? echo '</label></span>
         </a>
       </li>
       <!--
       <li class="nav-item">
         <a class=" mb-0 px-0 py-1 active d-flex align-items-center justify-content-center " data-bs-toggle="" href="feed.php'; ?><?php if(isset($_GET['userid'])){echo '?userid='.$_GET['userid'].'';} ?><?php echo '" role="tab" aria-selected="true">
           <span class="ms-2"><i class="fa fa-share py-2"> </i></span>
         </a>
       </li>
       -->
       <li class="nav-item">
        <a class=" mb-0 px-0 py-1 active d-flex align-items-center justify-content-center " data-bs-toggle=""  onclick="save('.$row["post_id"].');"';?><?php echo '" role="tab" aria-selected="true">
        <span class="ms-2"><i class="fa fa-save py-2" style="';?><?php if($saved == 1){echo 'color:red"';}else{echo 'color:"';} ?> <?php echo 'id="saved_'.$row["post_id"].'"></i></span>
        </a>
       </li>
     </ul>
   </div>
 </div>
</div>
</div>
<div class="col-md-3" style="text-align:right;">
</div>
<!--post section ends here -->';
}
else
{
    $postsfound = 0;
    echo "No More Posts";
}

mysqli_close($conn);

}

if(isset($_POST['like'])){
  
require('database.php');
$user_id = $_POST['userid'];
$post_id = $_POST['postid'];


$query = "SELECT * FROM post_likes WHERE post_id = ".$post_id." AND liked_by = ".$user_id;
// Execute SQL statement and check for result
$result = mysqli_query($conn, $query);

  if (mysqli_num_rows($result) > 0) {
    $query = "UPDATE posts SET post_likes = (post_likes-1) WHERE post_id = ".$post_id;
    $result = mysqli_query($conn, $query);
    

    $query = "DELETE FROM post_likes WHERE post_id = ".$post_id." AND liked_by = ".$user_id;
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    
    $query = "SELECT post_likes FROM posts WHERE post_id = ".$post_id;
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    echo $row['post_likes'].",disliked";
   
}
else
{
    $query = "UPDATE posts SET post_likes = (post_likes+1) WHERE post_id = ".$post_id;
    $result = mysqli_query($conn, $query);

    $query = "INSERT INTO post_likes (post_id, liked_by)
    VALUES ('$post_id' ,'$user_id')";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    $query = "SELECT post_likes FROM posts WHERE post_id = ".$post_id;
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    echo $row['post_likes'].",liked";
}


mysqli_close($conn);    
}


if(isset($_POST['like'])){
  
    require('database.php');
    $user_id = $_SESSION['key_1'];
    $post_id = $_POST['postid'];
    
    
    $query = "SELECT * FROM saves WHERE post_id = ".$post_id." AND user_id = ".$user_id;
    // Execute SQL statement and check for result
    $result = mysqli_query($conn, $query);
    
      if (mysqli_num_rows($result) > 0) {
    
        $query = "DELETE FROM saves WHERE post_id = ".$post_id." AND user_id = ".$user_id;
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);
        
        echo "unsaved";
       
    }
    else
    {
    
        $query = "INSERT INTO saves (post_id, user_id)
        VALUES ('$post_id' ,'$user_id')";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);
    
        echo "saved";
    }
    
    
    mysqli_close($conn);    
}



// view comments 
if(isset($_POST['viewcomments'])){
    $postid = $_POST['postid'];
  
    require('database.php');
    $query = "SELECT * FROM comments WHERE post_id = ".$postid;
    $result = mysqli_query($conn, $query);
    $string = "";
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo $string = "<label>".$row['user_name']."</label><br>".$row['comment']."<br><br>";
    }
        
    }
    else
    {
        echo "nocomm";    
    }
    
    mysqli_close($conn);    
}

// add comments 
if(isset($_POST['submitcomment'])){
    $postid = $_POST['postid'];
    $comment = $_POST['comment'];
    $fname = $_SESSION['first_name'];
    
  
    require('database.php');
    $query = "UPDATE posts SET post_comments = (post_comments+1) WHERE post_id =".$postid;
    $result = mysqli_query($conn, $query);

    $query = "INSERT INTO comments (post_id, user_name, comment)
              VALUES ('$postid' ,'$fname', '$comment')";
    $result = mysqli_query($conn, $query);
    
    
    $query = "SELECT * FROM comments WHERE post_id = ".$postid;
    $result = mysqli_query($conn, $query);
    $string = "";
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo $string = "<label>".ucfirst($row['user_name'])."</label><br>".$row['comment']."<br><br>";
    }
        
    }
    else
    {
        echo "nocomm";    
    }
    
    mysqli_close($conn);    
}

// search anything 
if(isset($_POST['search'])){
    $inputValue = $_POST['inputValue'];
  
    require('database.php');
    $query = "SELECT * FROM user WHERE first_name like '%".$inputValue."%' OR last_name like '%".$inputValue."%' OR email like '%".$inputValue."%' OR company like '%".$inputValue."%'";
    $result = mysqli_query($conn, $query);
   
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo '
            <a href=home.php?userid='.$row['key_1'].'>
            <div id="searchrow" style="padding:1%;position: ;z-index:100; background-color:white;" >
            <p class="mb-0 font-weight-bold text-sm">'.ucfirst($row['first_name']).' '.ucfirst($row['last_name']).'<p>
            <label class="" style="margin-left:-0.8%;">'.ucfirst($row['company']).'</label>
          </div> </a>';
    }
        
    }
    else
    {
        echo "no search results..";    
    }
    
    mysqli_close($conn);    
}


// block user 
if(isset($_POST['block'])){
    $userid = $_POST['userid'];
    $myuserid = $_SESSION['key_1'];
  
    require('database.php');

    $query = "SELECT * FROM reported where reported_user = ".$userid." AND reported_by = ".$myuserid;
    $result = mysqli_query($conn, $query);
   
    if (mysqli_num_rows($result) > 0) {
        $query = "DELETE FROM reported WHERE reported_user = ".$userid." AND reported_by = ".$myuserid;
        $result = mysqli_query($conn, $query);
        echo "Block";
    }
    else
    {   
        $query = "INSERT INTO reported (reported_user, reported_by)
        VALUES ('$userid' ,'$myuserid')";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);
        echo "UnBlock";
    
    }
    mysqli_close($conn);    
}


if(isset($_POST['chat'])){
    require('database.php');
    $userid = $_POST['userid'];
    $myuserid = $_SESSION['key_1'];

    //check if chat exists already
    $query = "SELECT * FROM chat_master WHERE user_1 = ".$myuserid." AND user_2 = ".$userid;   
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        if($row['request_accepted'] == 0){
            echo "Request has already been sent";
        }
        else{
            echo "goto";
        }
    }
    else
    {
        $query = "SELECT * FROM chat_master WHERE user_1 = ".$userid." AND user_2 = ".$myuserid;
        $result = mysqli_query($conn, $query);
        if (mysqli_num_rows($result) > 0) {
            echo "goto";
        }
        else
        {
             //if not create a new chat thread
            $query = "INSERT INTO chat_master (user_1, user_2, request_accepted)
            VALUES ('".$myuserid."' ,'".$userid."','0')";
            $result = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($result);
            echo "Request Sent Succesfully";
        }
    }
    mysqli_close($conn);    
}




//approve msg rqst 
if(isset($_POST['approvemsgrqst'])){
    $chatid = $_POST['chatid'];
    require('database.php');
    $query = "UPDATE chat_master SET request_accepted = '1' WHERE chat_id = ".$chatid;
    $result = mysqli_query($conn, $query);
    echo "requestaccepted";

    mysqli_close($conn); 
}

//sendmessage
if(isset($_POST['sendmessage'])){
    $chatid = $_POST['chatid'];
    $message = $_POST['message'];
    $myuserid = $_SESSION['key_1'];

    require('database.php');
    $query = "INSERT INTO chat_messages (chat_id, msg_maker, message1, seen)
    VALUES ('".$chatid."', '".$myuserid."','".$message."','0')";

    $result = mysqli_query($conn, $query);
    echo "messagesent";

    mysqli_close($conn); 
}





if(isset($_POST['gotochat'])){
    $chatid = $_POST['chatid'];
    require('database.php');
    $query = "SELECT * FROM chat_master WHERE chat_id = ".$chatid;
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        if($row['request_accepted'] == 0 && $row['user_2'] == $_SESSION['key_1']){
            // you need to accept request
            echo "acptrqstfrst";
        }
        else if ($row['request_accepted'] == 1)
        {
            $query1 = "SELECT * FROM chat_messages WHERE chat_id = ".$chatid;
            $result1 = mysqli_query($conn, $query1);
            if (mysqli_num_rows($result1) > 0) {
                    while ($row1 = mysqli_fetch_array($result1)) {
                        if($row1['msg_maker'] == $_SESSION['key_1']){
                            echo ' <p class="text-uppercase text-sm" style="text-align:right;">'.$_SESSION['first_name'].' '.$_SESSION['last_name'].'</p>
                            <p class="text-sm" style="text-align:right;">'.$row1['message1'].'</p> 
                            <hr class="horizontal dark">';
                        }else
                        {
                            $q = "SELECT first_name,last_name from user WHERE key_1 =".$row1['msg_maker'];
                            $resu = mysqli_query($conn, $q);
                            $rw = mysqli_fetch_array($resu);
                            
                            echo '
                            <p class="text-uppercase text-sm" >'.$rw['first_name'].' '.$rw['last_name'].'</p>
                            <p class="text-sm">'.$row1['message1'].'</p>
                            <hr class="horizontal dark">';
                        }
                }
            }
            else
            {
                echo "nomsgs";
            }

        }
        else{
            //request to be accepted by person ahead
            echo "rqntacpt";
        }
    }
    else{}
    //
    mysqli_close($conn);    
}


//del post
if(isset($_POST['delpost'])){
    $postid = $_POST['postid'];
    require('database.php');
    $query1 = "DELETE FROM posts WHERE  post_id = ".$postid;
    $result1 = mysqli_query($conn, $query1);
    $row = mysqli_fetch_assoc($result1);

    $query2 = "DELETE FROM post_likes WHERE post_id = ".$postid;
    $result2 = mysqli_query($conn, $query2);
    $row = mysqli_fetch_assoc($result2);

    $query3 = "DELETE FROM post_comments WHERE post_id = ".$postid;
    $result3 = mysqli_query($conn, $query3);
    $row = mysqli_fetch_assoc($result3);

    echo "Post Deleted";
    mysqli_close($conn);   
}

// report user 
if(isset($_POST['report'])){
    $userid = $_POST['userid'];
    $myuserid = $_SESSION['key_1'];
  
    require('database.php');

    $query = "SELECT * FROM admin_reported where admin_reported_to = ".$userid;
    $result = mysqli_query($conn, $query);
   
    if (mysqli_num_rows($result) > 0) {
        echo "User Already Reported";
    }
    else
    {   
        $query = "INSERT INTO admin_reported (admin_reported_to, admin_reported_by)
        VALUES ('$userid' ,'$myuserid')";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);
        echo "Reported";
    
    }
    mysqli_close($conn);    
}

//admin ban
if(isset($_POST['admin_ban'])){
    $userid = $_POST['userid'];
  
    require('database.php');

    $query = "SELECT admin_ban FROM user where key_1 = ".$userid;
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $row['admin_ban'];

    if ($row['admin_ban'] == 1) {
        $query = "UPDATE user SET admin_ban = '0' WHERE key_1 = ".$userid;
        $result = mysqli_query($conn, $query);

        echo "BAN";
    }
    else
    {   
        $query = "UPDATE user SET admin_ban = '1' WHERE key_1 = ".$userid;
        $result = mysqli_query($conn, $query);

        echo "UNBAN";
    
    }
    mysqli_close($conn);    
}
?>