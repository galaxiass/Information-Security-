<?php
  require_once('../server/DB.php');
  require_once('../modules/methods.php');
  $db = DB::getInstance();

  $senderId = $_REQUEST['sid'];
  $receiverId = $_REQUEST['rid'];
  $whichCase = $_REQUEST['whichCase'];

  $query = "";
  $updateRequired = false;
  $senderIsHere = true;

  // If the modal is just opened get all messsages
  if($whichCase == "displayAll"){
    $updateRequired = false;
    $query = "SELECT * 
              FROM chat 
              WHERE (sender_id=$senderId AND receiver_id=$receiverId) OR (sender_id=$receiverId AND receiver_id=$senderId) 
              ORDER BY timestamp";
  }
  // If the modal was open get just the new ones
  else if ($whichCase == "displayNewOnly"){
    $updateRequired = true;
    $query = "SELECT * 
              FROM chat
              WHERE (s_stat=0 OR r_stat=0) AND ((sender_id=$senderId AND receiver_id=$receiverId) OR (sender_id=$receiverId AND receiver_id=$senderId))
              ORDER BY timestamp";
  }

  $result = mysqli_query($db, $query);
  $messages = [];

  while($row = mysqli_fetch_assoc($result)){
    $node = [];
    $chatId = $row['id'];
    $sender = $row['sender_id'];
    $receiver = $row['receiver_id'];
    $enc = $row['enc_method'];
    $message = "-1";//This encryption method is still not fully implemented.// This was here from before
    $timestamp = $row['timestamp'];

    // Decryption of the ciphertext
    if($enc == 2){
      $RSA = new RSA();
      $query = "SELECT *
                FROM rsa
                WHERE message_id=$chatId
                ORDER BY timestamp";
      $rsaResult = mysqli_query($db, $query);
      $rsaRow = mysqli_fetch_assoc($rsaResult);
      $message = $RSA->decrypt($row['message'], $rsaRow['d'], $rsaRow['n'], $rsaRow['every_separate']);
    }

    $node[] = $sender;
    $node[] = $receiver;
    $node[] = $enc;
    $node[] = $message;
    $node[] = $timestamp;
    $node[] = time_elapsed_string($timestamp);
    

    if($updateRequired){
      if($row['sender_id'] == $senderId && $row['s_stat'] == 0){
        $senderIsHere = true;
        $messages[] = $node;
      }
      else if($row['sender_id'] == $receiverId && $row['r_stat'] == 0){
        $senderIsHere = false;
        $messages[] = $node;
      }
    }
    else{
      $messages[] = $node;
    }
  }

  if($updateRequired){
    $query = "";
    if($senderIsHere){
      $query = "UPDATE chat 
                SET s_stat=1
                WHERE s_stat=0 AND ((sender_id=$senderId AND receiver_id=$receiverId) OR (sender_id=$receiverId AND receiver_id=$senderId))
                ORDER BY timestamp";
    }
    else{
      $query = "UPDATE chat 
                SET r_stat=1
                WHERE r_stat=0 AND ((sender_id=$senderId AND receiver_id=$receiverId) OR (sender_id=$receiverId AND receiver_id=$senderId))
                ORDER BY timestamp";
    }

    if($db->query($query) === true){
      echo json_encode($messages);
    }
    else{
      die();
    }
  } else {
    echo json_encode($messages);
  }
  

  function time_elapsed_string($datetime = "", $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
  }
?>