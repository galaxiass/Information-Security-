//  Handles AJAX insertion of messages into the chat. Triggered by 'resources.js/chat.js -> sendClicked()'
function ajaxInsertMessage(){
  var messageText = document.getElementById('message-text').value;
  var encMethod = document.getElementById('enc-method').value;
  var encMethodId = getEncMethodId(encMethod);

  let c1;
  let xa;
  let q;
  let es;

  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        if(this.responseText != "")
          alert(this.responseText);
      } else {
          // alert(this.status + " " + this.readyState);
      }
  };
  // i think we need to change something in the next line because we dont use Gamal algorithm.
  var params = "sid=" + senderId + "&rid=" + receiverId + "&msg=" + encodeURIComponent(messageText) + "&eid=" + encMethodId + 
                "&c1Gamal=" + c1 + "&xaGamal=" + xa + "&qGamal=" + q + "&esGamal=" + es;
  xmlhttp.open("POST", "insert_message.php", true);
  xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xmlhttp.send(params);
}

// Handles AJAX displaying of messages, either all of them or only new. 
// Triggered by 'resources/home.js -> $('#chatModal').on('shown.bs.modal', function (e)' or
// 'resources/utilities.js $(document).ready(function()'
function ajaxDisplayMessages(whichCase){
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        if(this.responseText != ""){
          // alert(this.responseText);
          messagesData = JSON.parse(this.responseText);
          if(ctModalOpen == 0){
            appendSecuredAlertBubble();
            ctModalOpen++;
          }
          for(let i = 0; i < messagesData.length; i++){
            let theMessage = messagesData[i][3];
            appendNewChatBubble(messagesData[i][0], theMessage, messagesData[i][4], messagesData[i][5]);
          }
          if(messagesData.length > 0){
            scrollToTheBottom();
          }
        }
      } else {
          // alert(this.status + " " + this.readyState);
      }
  };
  xmlhttp.open("GET", "display_messages.php?sid=" + senderId + "&rid=" + receiverId + "&whichCase=" + whichCase, true);
  xmlhttp.send();
}

// The call of this function is in utilities.js -> $(document).ready(function()
function ajaxCheckReceiverStatus(whichStatus){
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        if(this.responseText != ""){
          if(whichStatus == "modal"){
            const isOnline = JSON.parse(this.responseText)[0];
            const logoutTimestamp = JSON.parse(this.responseText)[1];

            var lastSeen = document.getElementById('last-seen');
            var receiverDot = document.getElementById('receiver-dot');
            
            if(isOnline == 1){
              lastSeen.innerHTML = "Online";
              receiverDot.classList.remove("text-secondary");
              receiverDot.classList.add("text-success");
              chatButtonEvent.setAttribute('data-bs-status', '0');
            }else{
              lastSeen.innerHTML = "Last Seen " + logoutTimestamp;
              receiverDot.classList.remove("text-success");
              receiverDot.classList.add("text-secondary");
              chatButtonEvent.setAttribute('data-bs-status', '0');
            }
          } else if (whichStatus == "home"){
            const usersStatus = JSON.parse(this.responseText);
            for(let i = 0; i < usersStatus.length; i++){
              updateUsersPublicStatus(usersStatus[i], i);
            }
          }
        }
      } else {
          // alert(this.status + " " + this.readyState);
      }
  };
  xmlhttp.open("GET", "check_receiver_status.php?rid=" + receiverId + "&whichStatus=" + whichStatus, true);
  xmlhttp.send();
}
