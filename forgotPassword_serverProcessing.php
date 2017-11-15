<?php
include 'db/database_conn.php';
require_once('functions.php');
include_once 'config.php';
$environment = LOCAL;
// header('content-type: application/json');

$function = filter_has_var(INPUT_POST, 'action') ? $_POST['action']: null;
$function = trim($function);
$function = filter_var($function, FILTER_SANITIZE_STRING);

// $function = "resetPassword";

if ($function == "forgotPassword") {
  //Obtain passed value, trim whitespace & sanitize value
  $email = filter_has_var(INPUT_POST, 'email') ? $_POST['email']: null;
  $email = trim($email);
  $email = filter_var($email, FILTER_SANITIZE_STRING);

  $emailSQL = "SELECT fullName, userStatus FROM user WHERE emailAddr = ?";
  $stmt = mysqli_prepare($conn, $emailSQL) or die( mysqli_error($conn));
  mysqli_stmt_bind_param($stmt, "s", $email);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_bind_result($stmt, $fullName, $userStatus);
  mysqli_stmt_fetch($stmt);
  mysqli_stmt_close($stmt);

  if ($fullName != "") { //Account exists; Send email containing URL to reset password
    if ($userStatus == "active") {
      $memberConfirmationExpiryDate = time(); //Get current date
      $memberConfirmationExpiryDate = date('Y-m-d H:i:s', strtotime('+1 day', $memberConfirmationExpiryDate)); //Calculate url expiration date

      $updateEmailSQL = "UPDATE user SET memberConfirmationExpiryDate = ? WHERE emailAddr = ?";
      $stmt = mysqli_prepare($conn, $updateEmailSQL) or die( mysqli_error($conn));
      mysqli_stmt_bind_param($stmt, "ss", $memberConfirmationExpiryDate, $email);
      mysqli_stmt_execute($stmt);

      if (mysqli_stmt_affected_rows($stmt) > 0) {
        //Encode variables to be used in url
        $emailEncoded = urlencode(base64_encode($email));
        $fullNameEncoded = urlencode(base64_encode($fullName));
        $memberConfirmationExpiryDateEncoded = urlencode($memberConfirmationExpiryDate);
        $url = $environment . "/CM0656-Assignment/resetPassword.php?mail=" . $emailEncoded . "&name=" . $fullNameEncoded
        . "&exDate=" . $memberConfirmationExpiryDateEncoded;

        if (sendEmail($email, $fullName, 'Request to Reset Password', 'email/notifier_resetPassword.html', $url)) { //Email sent
          echo "A password reset link has been sent to your email address!";
        }
        else { //Email failed to send
          echo "Failed to send email!";
        }
      }
      else { //Unable to update user table
        echo "Unable to update table!";
      }
    }
    else if ($userStatus == "pending") {
      echo "You have yet to complete the registration process! Please check your email for further instructions.";
    }
    else if ($userStatus == "banned") {
      echo "You are not allowed to reset your password!";
    }
  }
  else { //Account does not exist
    echo "Oops! We couldn't find your account, please try again.";
  }
}
else if ($function == "resetPassword") { //TODO: Do not allow user to enter current password
  //Obtain passed value, trim whitespace & sanitize value
  $email = filter_has_var(INPUT_POST, 'email') ? $_POST['email']: null;
  $email = trim($email);
  $email = filter_var($email, FILTER_SANITIZE_STRING);

  $password = filter_has_var(INPUT_POST, 'password') ? $_POST['password']: null;
  $password = trim($password);
  $password = filter_var($password, FILTER_SANITIZE_STRING);
  $password = password_hash($password, PASSWORD_DEFAULT); //Hash password

  //Retrieve user's details
  $detailsSQL = "SELECT passwordHash FROM user WHERE emailAddr = ?";
  $stmt = mysqli_prepare($conn, $detailsSQL) or die( mysqli_error($conn));
  mysqli_stmt_bind_param($stmt, "s", $email);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_bind_result($stmt, $passwordHash);
  mysqli_stmt_fetch($stmt);
  mysqli_stmt_close($stmt);

  if ($passwordHash != "") {
    if (!password_verify($password, $passwordHash)) { //New password != old password; Update password
      $updatePwSQL = "UPDATE user SET passwordHash = ? WHERE emailAddr = ?";
      $stmt = mysqli_prepare($conn, $updatePwSQL) or die( mysqli_error($conn));
      mysqli_stmt_bind_param($stmt, "ss", $password, $email);
      mysqli_stmt_execute($stmt);

      if (mysqli_stmt_affected_rows($stmt) > 0) {
        echo "1Password updated!";
      }
      else {
        echo "2Failed to update password!";
      }
    }
    else { //New password = old password; Do not allow update password
      echo "3New password cannot be same as your existing password!";
    }
  }
  else { //No password available in database for user
    echo "4No existing password found!";
  }
  mysqli_stmt_close($stmt);
  mysqli_close($conn);
}
?>
