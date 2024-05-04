// When the chatModal is shown to the user this function is executed.
$('#chatModal').on('shown.bs.modal', function (e) {
  document.getElementById("message-text").focus();
  modalIsOpen = true;
  ajaxDisplayMessages("displayAll");
})

// When the chatModal is closed this function is executed to reset certain variables
$('#chatModal').on('hidden.bs.modal', function (e) {
  var chatBox = document.getElementById('chat-box');
  senderId = 0;
  receiverId = 0;
  modalIsOpen = false;
  ctModalOpen = 0;
  document.getElementById('last-seen').innerHTML = "Status";
  deleteChilds(chatBox);
})

// Updates the user's Status in the table and the icon's colour -- Triggered by ajax.js -> ajaxCheckReceiverStatus
function updateUsersPublicStatus(userStatus, whichRow){
  var dots = document.getElementsByClassName('receiver-public-dot');
  var statusTexts = document.getElementsByClassName('receiver-public-statusText');
  var chatIcons = document.getElementsByClassName('receiver-public-chatIcon');

  if(userStatus != statusTexts[whichRow].innerHTML){
    if(userStatus == "Online"){
      statusTexts[whichRow].innerHTML = "Online";
      dots[whichRow].style.color = "#198853"; 
      chatIcons[whichRow].style.color = "#0E6DFD";
    } else if (userStatus == "Offline"){
      statusTexts[whichRow].innerHTML = "Offline";
      dots[whichRow].style.color = "#6C757D"; 
      chatIcons[whichRow].style.color = "#6C757D";
    }
  }
}
