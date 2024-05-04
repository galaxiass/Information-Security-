<?php
  require_once('../server/DB.php');
  require_once('../modules/methods.php');
  $db = DB::getInstance();

  $senderId = $_POST['sid'];
  $receiverId = $_POST['rid'];
  $message = $_POST['msg'];
  $encMethodId = $_POST['eid'];

  // Encrypt the message
 if($encMethodId == 2){
    $rsa = new RSA();
    $pRsa = $rsa->findRandomPrime(-1);
    $qRsa = $rsa->findRandomPrime($pRsa);
    $nRsa = $rsa->compute_n($pRsa, $qRsa);
    $zRsa = $rsa->eular_z($pRsa, $qRsa);
    $eRsa = $rsa->find_e($zRsa);
    $dRsa = $rsa->find_d($eRsa, $zRsa);
    list($cRsa, $everySeparateRsa) = $rsa->encrypt($message, $eRsa, $nRsa);
    $eMessage = $cRsa;
  }

  $query = "SELECT token from login_details WHERE uid=$senderId";
  $result = mysqli_query($db, $query);
  $row = mysqli_fetch_assoc($result);
  $senderToken = $row['token'];

  $query = "SELECT token from login_details WHERE uid=$receiverId";
  $result = mysqli_query($db, $query);
  $row = mysqli_fetch_assoc($result);
  $receiverToken = $row['token'];

  // Insert the ciphertect in the db
  $query = "INSERT INTO chat (sender_id, receiver_id, message, enc_method, s_token, r_token) VALUES($senderId, $receiverId, '$eMessage', $encMethodId, '$senderToken', '$receiverToken')";
  if($db->query($query) !== true){
    echo "Messsage Has not sent due to an error -1. ".mysqli_error($db);
  }
  else{
    // Insert data into rsa table
    $lastChatId = $db->insert_id;
    if($encMethodId == 2){
      $query = "INSERT INTO rsa (message_id, d, n, every_separate) VALUES ($lastChatId, '$dRsa', '$nRsa', '$everySeparateRsa')";
      if($db->query($query) !== true){
        echo "Messsage Has not sent due to an error 2. ".mysqli_error($db);
      }
    }
  }
?>