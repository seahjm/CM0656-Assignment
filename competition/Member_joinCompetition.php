<?php
// ini_set("session.save_path", ""); //TODO: comment out
session_start();
include '../db/database_conn.php';
require_once('../controls.php');
require_once('../functions.php');
echo makePageStart("Competition");
echo makeWrapper("../");
echo "<form method='post'>" . makeLoginLogoutBtn("../") . "</form>";
echo makeProfileButton("../");
echo makeNavMenu("../");
echo makeHeader("Welcome to join competition");
?>

<script src="../scripts/jquery.js"></script>
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="https://fonts.googleapis.com/css?family=Lora:400,400i,700" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Lato:300,300i,400,400i,700,700i" rel="stylesheet">
<script src="../scripts/bootstrap.min.js"></script>
<link rel="stylesheet" href="../css/stylesheet.css" type="text/css" />
<link href="../css/bootstrap.css" rel="stylesheet">

<div class="content test">
  <div class ="container">
    <?php //Only show content to junior members
    if((isset($_SESSION['logged-in']) && $_SESSION['logged-in'] == true) &&
    (isset($_SESSION['userType']) && ($_SESSION['userType'] == "admin" || $_SESSION['userType'] == "mainAdmin" || $_SESSION['userType'] == "junior"))) {
      if (checkUserStatus($conn, $_SESSION['userID']) == "active") { //Only allow if user status is active
        ?>
        <td><a href= "Member_test13.php"><button id="button">10-13</button></a></td>
        <td><a href= "Member_test16.php"><button id="button">13-16</button></a></td>
        <td><a href= "Member_test18.php"><button id="button">16-18</button></a></td>
        <?php
      }
      else { //User has been banned; Redirect to home page
        setCookie(session_name(), "", time() - 1000, "/");
        $_SESSION = array();
        session_destroy();
        echo "<script>alert('You are not allowed here!')</script>";
        header("Refresh:0;url=../index.php");
      }
    }
    else { //Redirect user to home page
      echo "<script>alert('You are not allowed here!')</script>";
      header("Refresh:0;url=../index.php");
    }
    ?>
  </div>
</div>

<?php
echo makeFooter("../");
echo makePageEnd();
?>
