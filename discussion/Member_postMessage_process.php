<!--******************************************************************************************************************************************************
      DISCUSSION BOARD: (2.1) Reply Message (Member)
*******************************************************************************************************************************************************-->

<?php
  include '../db/database_conn.php';
  ini_set("session.save_path", "");
  session_start();


    $replyThread  = $_POST['replyTo_threadID'];
    $replyInput   = $_POST['txtReplyMessage'];
    $replyMessage = $_POST['replyTo_messageID'];

    //obtain user input
    $replyInput = filter_has_var(INPUT_POST,'txtReplyMessage') ? $_POST['txtReplyMessage']: null;

    //Trim white space
    $replyInput = trim($replyInput);

    //Sanitize user input
    $replyInput = filter_var($replyInput, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

    $messageStatus = "active";

    $sqlReplyMessage = "INSERT INTO discussion_message (userID, threadID, messageContent, messageStatus, replyTo)	VALUES (?,?,?,?,?)";
    $stmtReplyMessage = mysqli_prepare($conn, $sqlReplyMessage) or die( mysqli_error($conn));
    mysqli_stmt_bind_param($stmtReplyMessage, 'iissi', $_SESSION['userID'], $replyThread, $replyInput, $messageStatus, $replyMessage);
    mysqli_stmt_execute($stmtReplyMessage);

    if(mysqli_stmt_affected_rows($stmtReplyMessage) > 0){
      echo "<script>alert('The message has been posted')</script>";
    }
    else {
      echo "<script>alert('Try again!')</script>";

    }
    //validation: Prevent Resubmit Users' Previous Input Data
    clearstatcache();
    mysqli_stmt_close($stmtReplyMessage);

    $url="Member_postMessage.php?threadID=".$replyThread;
    header('Location: '.$url);

?>

<!--*******************************************************************************************************************************************************
      DISCUSSION BOARD: Automatic Moderation inappropriate Word
*******************************************************************************************************************************************************-->
