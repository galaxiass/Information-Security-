// Now sure where and how this works and i am not sure if we are going to need this because
// i have taken out the option to upload files.So if it is only for files maybe we could get it out.
function base64ToArrayBuffer(binaryString) {
  console.log(binaryString);
  var binaryLen = binaryString.length;
  var bytes = new Uint8Array(binaryLen);
  for (var i = 0; i < binaryLen; i++) {
     var ascii = binaryString.charCodeAt(i);
     bytes[i] = ascii;
  }
  return bytes;
}

// Dont know where and why this is used
function getAsciiOfTheString(m){
  var array = new Object();
  let everySeparate = "";
  let asciiM = "";
  for(let i = 0; i < m.length(); i++){
    let charLength = m.charCodeAt(i).length();
    asciiM += m.charCodeAt(i);
    everySeparate += charLength;
  }
  return array;
}

// Scroll down to the latest messages
function scrollToTheBottom(){
  var chatBox = document.getElementById('chat-box');
  chatBox.scrollTop = chatBox.scrollHeight;
}

function capitalizeFirstLetter(string) {
  return string.charAt(0).toUpperCase() + string.slice(1);
}

// Dont know where and how this works
function deleteChilds(parent) {
  var e = parent;
  //e.firstElementChild can be used.
  var child = e.lastElementChild; 
  while (child) {
      e.removeChild(child);
      child = e.lastElementChild;
  }
}

// We will have to change everything related with the 'encMethod' because now we only have RSA and not 4 different algorithms
function getEncMethodId(encMethod){
  if (encMethod == "rsa") return 2;
  else return -1;
}

function hover(type)
{
    if(type == "logout")
    {
        icon = document.getElementById("logoutIcon");
        logout = document.getElementById("logoutP");
    }
    icon.style.display = "inline-block";
}

function out(type)
{
    if(type == "logout")
    {
        icon = document.getElementById("logoutIcon");
        logout = document.getElementById("logoutP");
    }
    icon.style.display = "none";
}

$(document).ready(function() {
  // Calls the 'ajaxDisplayMessages' every 250 ms if the chatModal is open
  setInterval(function() {
    if(modalIsOpen){
      ajaxDisplayMessages("displayNewOnly");
    }
  }, 250);

  // Calls the 'ajaxCheckReceiverStatus' every 2500 ms if the chatModal is open
  setInterval(function() {
    if(modalIsOpen){
      ajaxCheckReceiverStatus("modal");
    }
  }, 2500);

// Calls the 'ajaxCheckkReceiverStatus' every 2500 ms without the chatModal being open
  setInterval(function() {
    ajaxCheckReceiverStatus("home");
  }, 2500);
  // Ensures that Ajax requests dont rely on cached responses and fetch latest data
  $.ajaxSetup({ cache: false });
});

// I dont get this one
if ( window.history.replaceState ){
  window.history.replaceState(null, null, window.location.href);
}
