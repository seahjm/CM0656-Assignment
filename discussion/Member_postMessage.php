<?php
// ini_set("session.save_path", "");
session_start();
include '../db/database_conn.php';
include_once '../config.php';
require_once('../controls.php');
require_once('../functions.php');
echo makePageStart("Discussion Forum");
echo makeWrapper("../");
echo "<form method='post'>" . makeLoginLogoutBtn("") . "</form>";
echo makeProfileButton("../");
echo makeNavMenu("../");
$environment = WEB;
?>
<!-- CSS style -->
<link rel='stylesheet' href='../css/bootstrap.css' />
<link rel="stylesheet" href="../css/jquery-ui.min.css" />
<link rel="stylesheet" href="../css/jquery.dataTables.min.css" />
<link rel="stylesheet" href="../css/stylesheet.css" />
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="../css/parsley.css" />
<!-- <i class='material-icons'>reply</i></input> -->
<script src='../scripts/bootstrap.js'></script>
<script src='../scripts/jquery.dataTables.min.js'></script>
<script src="../scripts/jquery.js"></script>
<script src="../scripts/parsley.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

<style>
.submitLink {
background-color: transparent;
text-decoration: underline;
border: none;
cursor: pointer;
cursor: hand;
}

</style>
<div class='content'>
<div class='container'>
<!-- Reply Message -->
<script>
function myFunction(msgID,threadID,userID) {
    var msgID = msgID;
    var threadID = threadID;
    var userID = userID;
    var replyMsg = prompt("Reply to this Message?");

    if (replyMsg == null){
    	alert("Empty reply....");
    }else{
      $.ajax({
              type: "POST",
              url: "../discussion/Member_replyMessage.php",
              data: { msgID : msgID, threadID : threadID, replyMsg : replyMsg, userID : userID} //pass data through this variable
            }).done(function( dataReturn ) {
                alert(dataReturn);
                location.reload();
            });
    }
}
</script>

<?php
    //Display Thread Title
    if(isset($_GET['threadID'])){
      $threadID = $_GET['threadID'];
    }

      $sqlGetThread = "SELECT threadName, threadDescription
                       FROM discussion_thread WHERE threadID = $threadID";
      $result = mysqli_query($conn, $sqlGetThread) or die(mysqli_error($conn));
      if (mysqli_num_rows($result) > 0){
  					while($rows = mysqli_fetch_assoc($result)){
                $threadName = $rows['threadName'];
                $threadDesc = $rows['threadDescription'];

                echo makeHeader($threadName);
                echo "<pre><h3>&nbsp;&nbsp;".$threadDesc."</h3></pre>";
            }
      }
?>

<!--*******************************************************************************************************************************************************
      DISCUSSION BOARD: Messages
*******************************************************************************************************************************************************-->

<div class='message-group'>
<table id="t01">

<?php
    /*******************************************************************************************************************************************************
          DISCUSSION BOARD: (POST MESSAGE) Display Posted Message Content
    *******************************************************************************************************************************************************/
    $sqlGetMessage = "SELECT user.username, discussion_message.messageContent, discussion_message.messageID,
                              discussion_message.messageDateTime,discussion_message.userID,discussion_message.threadID
                        FROM discussion_message
                        INNER JOIN discussion_thread
                        ON discussion_message.threadID=discussion_thread.threadID
                        INNER JOIN user
                        ON discussion_message.userID=user.userID
                        WHERE discussion_thread.threadID = $threadID AND discussion_message.replyTo IS NULL
                        ORDER BY discussion_message.messageDateTime DESC";
    $Msgresult = mysqli_query($conn, $sqlGetMessage) or die(mysqli_error($conn));
    if (mysqli_num_rows($Msgresult) > 0){
        while($rows = mysqli_fetch_assoc($Msgresult)){
              $messageID = $rows['messageID'];
              $userID = $rows['userID'];
              $threadID = $rows['threadID'];
              $username = $rows['username'];
              $MsgDateTime= $rows['messageDateTime'];
              $MsgContent= $rows['messageContent'];

              echo "<b>Post Msg : $MsgContent </b> by $username - $MsgDateTime";
              echo "&nbsp;";

              /*****************************************************************************************************************************************************
                            DISCUSSION BOARD: (REPORT POST MESSAGE) Display "Report" button
              *******************************************************************************************************************************************************/
              //Validation - Only member can view "Report" button
              if((isset($_SESSION['logged-in']) && $_SESSION['logged-in'] == true) && (isset($_SESSION['userID'])) &&
              (isset($_SESSION['userType']) && ($_SESSION['userType'] == "junior" || $_SESSION['userType'] == "senior"))) {
                  echo
                  "<form method='post' action='Member_reportMessage.php'>
                    <input type='hidden' name='msgID' value='$messageID'>
                    <input type='hidden' name='threadID' value='$threadID'>
                    <input type='hidden' name='PostedUserID' value='$userID'>
                    <input type='submit' value='ReportMsg' name='ReportMsg' />
                  </form>";
              }
              /*****************************************************************************************************************************************************
                            DISCUSSION BOARD: (REPLY MESSAGE) Display Replied Message Content
              *******************************************************************************************************************************************************/
              $sqlGetReply = "SELECT discussion_message.messageID, user.username, discussion_message.messageContent,
                                     discussion_thread.threadID,discussion_message.messageDateTime, discussion_message.replyTo
                                FROM discussion_message
                                INNER JOIN discussion_thread
                                ON discussion_message.threadID=discussion_thread.threadID
                                INNER JOIN user
                                ON discussion_message.userID=user.userID
                                WHERE discussion_thread.threadID = $threadID AND discussion_message.replyTo=$messageID
                                ORDER BY discussion_message.messageID DESC";
              $MsgReply = mysqli_query($conn, $sqlGetReply) or die(mysqli_error($conn));
              $spacing = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
              if (mysqli_num_rows($MsgReply) > 0){
                  while($rows = mysqli_fetch_assoc($MsgReply)){
                    $messageID = $rows['replyTo'];
                    $threadID = $rows['threadID'];
                    $username = $rows['username'];
                    $MsgDateTime= $rows['messageDateTime'];
                    $MsgContent= $rows['messageContent'];
                    echo "</br>$spacing
                          <b>Reply Msg : $MsgContent </b> by $username - $MsgDateTime";
                          /*****************************************************************************************************************************************************
                                        DISCUSSION BOARD: (REPORT REPLY MESSAGE) Display "Report" submit
                          *******************************************************************************************************************************************************/
                          //Validation - Only member can view "Report" submit
                          if((isset($_SESSION['logged-in']) && $_SESSION['logged-in'] == true) && (isset($_SESSION['userID'])) &&
                          (isset($_SESSION['userType']) && ($_SESSION['userType'] == "junior" || $_SESSION['userType'] == "senior"))) {
                              echo
                              "<form method='post' action='Member_reportMessage.php'>
                                <input type='hidden' name='msgID' value='$messageID'>
                                <input type='hidden' name='threadID' value='$threadID'>
                                <input type='hidden' name='PostedUserID' value='$userID'>
                                <input type='submit' value='ReportReply' name='ReportMsg' />
                              </form>";
                          }
                }
              }
              //Validation - Only member can view "Reply" textfield
              if((isset($_SESSION['logged-in']) && $_SESSION['logged-in'] == true) && (isset($_SESSION['userID'])) &&
              (isset($_SESSION['userType']) && ($_SESSION['userType'] == "junior" || $_SESSION['userType'] == "senior"))) {
                echo '<br/>'.$spacing.'
                <input type="submit" class="submitLink" value="Reply" onclick="myFunction('. $messageID .','. $threadID.','. $_SESSION['userID'].')">';
              }
                echo "<br/><br/><br/>";
          }
    }
?>
</table>

<?php
            //Validation - Only member can view "Post" textfield
            if((isset($_SESSION['logged-in']) && $_SESSION['logged-in'] == true) && (isset($_SESSION['userID'])) &&
            (isset($_SESSION['userType']) && ($_SESSION['userType'] == "junior" || $_SESSION['userType'] == "senior"))) {

              /*******************************************************************************************************************************************************
                    DISCUSSION BOARD: (POST MESSAGE) Post New Message Function
              *******************************************************************************************************************************************************/
              if(isset($_POST['postMessage_submit']) && !empty($_POST['txtPostMessage'])){
                //obtain user input
                $post_message = filter_has_var(INPUT_POST,'txtPostMessage') ? $_POST['txtPostMessage']: null;

                //Trim white space
                $post_message = trim($post_message);

                //Sanitize user input
                $post_message = filter_var($post_message, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

                $userID = $_SESSION['userID'];
                // Check connection
                if ($conn->connect_error) {
                   die("Connection failed: " . $conn->connect_error);
                }

                /*******************************************************************************************************************************************************
                      DISCUSSION BOARD: (POST MESSAGE - INAPPROPRIATE) Automatic Moderate Inappropriate Phrase
                *******************************************************************************************************************************************************/
                //check inappropriate Phrase
                $str_message = $post_message;
                $array_message = explode(" ", $str_message);
                echo $array_message;
              	$check_postInappropriateSQL = "SELECT * FROM discussion_inappropriate";

              	$check_postInappropriateResult = mysqli_query($conn, $check_postInappropriateSQL);

              	if (mysqli_num_rows($check_postInappropriateResult) > 0) {
              	    // output data of each row
              	    while($row = mysqli_fetch_assoc($check_postInappropriateResult)) {
              	    	for($i = 0; $i < count($array_message); $i++){
              	    		if(strtolower($array_message[$i]) == $row['inappropriatePhrase']){
              	    			$array_message[$i] = $row['replacementWord'];
              	    		}
              	    	}
              	    }
              	}
              	$post_message = implode(" ", $array_message);

                //insert post message content
                $sql = "INSERT INTO discussion_message (userID, threadID, messageContent, messageStatus)
                        VALUES ('$userID','$threadID','$post_message','active')"; //new posted message status is active

                if ($conn->query($sql) === TRUE) {
                  echo "<script>alert('The message has been posted')</script>";
                  echo "<script>
                                top.window.location='../discussion/Member_postMessage.php?threadID=$threadID';
                       </script>";
                } else {
                  echo "<script>alert('Try again!')</script>";
                }$conn->close();
                //validation: Prevent Resubmit Users' Previous Input Data
                clearstatcache();
              } //END: (3) Post New Message

              echo "
              <div class='message-new'>
              <form method='post'>
              <input type='text' id='txtPostMessage' name='txtPostMessage' data-parsley-required= 'true' placeholder='Post a message..' />
              <input type='submit' id='postMessage_submit' name='postMessage_submit' value='Post'/>
              </form>
              </div>";
            }
            else
            {
              echo "
              <div class='message-new'>
              <form id='postMessage_field' method='post'>
              </form>
              </div>";
            }
        mysqli_close($conn);
?>

</div>
</div>
</div>

<?php
echo makeFooter("../");
echo makePageEnd();
?>
