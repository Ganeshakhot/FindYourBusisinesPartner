<?php
if(isset($_POST['submit']))
{
    require('database.php');
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password_hash = md5($password);

    // Prepare SQL statement to select user with matching email and password
    $sql = "SELECT * FROM user WHERE email='$email' AND passwordd='$password_hash'";

    // Execute SQL statement and check for result
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
    $row =mysqli_fetch_assoc($result);
    // If user with matching email and password is found, login is successful
    // Generate a random session ID
    $session_id = bin2hex(random_bytes(16));
    
    // Store user data in session variables
    session_start();
    $_SESSION['key_1'] = $row['key_1'];
    $_SESSION['session_id'] = $session_id;
    $_SESSION['first_name'] = $row['first_name'];
    $_SESSION['last_name'] = $row['last_name'];
    $_SESSION['company'] = $row['company'];
    $_SESSION['profile_link'] = $row['profile_link'];
    $_SESSION['user_type'] = $row['user_type'];
    
    if($row['admin_ban'] == 0){
        echo "success";
    }else if ($row['admin_ban'] == 1)
    {
        echo "adminban";
    }

    } else {
    // If no matching user is found, login is unsuccessful
    echo "error";
    }
}

?>
