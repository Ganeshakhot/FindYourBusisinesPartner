<?php 
require('database.php');
session_start();
error_reporting(0);
$fromm = $_SESSION['key_1'];
$too    = $_POST['profileId'];

if(isset($_POST['noderequest'])){

 if($_POST['isfollower'] == 1){
    $query = "DELETE FROM nodes WHERE fromm = $fromm AND too = $too";
    $result = mysqli_query($conn, $query);
    echo "Follow";
  }else
  {
    $query = "INSERT INTO nodes (fromm, too) VALUES ($fromm, $too)";
    $result = mysqli_query($conn, $query);
    echo 'Unfollow';
  }
    
}
?>