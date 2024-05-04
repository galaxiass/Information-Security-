// I think this is to display the messages inside those little bubbles
function appendNewChatBubble(theSender, theMessage, theTime, theTimeAgo){
  const chatBox = document.getElementById("chat-box");
  const messageNode = document.createElement("div");
  const timeNode = document.createElement("label");
  const downloadIcon = document.createElement("i");

  messageNode.style.clear = "both";
  messageNode.style.padding = "4px 10px";
  messageNode.style.border = "1px solid #ccc";
  messageNode.style.borderRadius = "10px";
  messageNode.style.marginBottom = "2px";
  messageNode.style.marginTop = "15px";
  messageNode.classList.add("animate__animated");
  messageNode.classList.add("animate__fadeInUp");    
  messageNode.classList.add("animate__faster");
  

  timeNode.style.clear = "both";
  timeNode.style.fontSize = "10px";
  timeNode.style.fontWeight = "300";
  timeNode.style.color = "#999";
  timeNode.classList.add("animate__animated");
  timeNode.classList.add("animate__fadeInUp");
  timeNode.classList.add("animate__faster");

  if(theSender == senderId){
    messageNode.style.float = "right";
    messageNode.style.backgroundColor = "#B3E8E5";
    messageNode.style.borderBottomRightRadius = "0";
    messageNode.style.marginLeft = "50px";

    timeNode.style.float = "right";
  }
  else{
    messageNode.style.float = "left";
    messageNode.style.backgroundColor = "#fff";
    messageNode.style.borderBottomLeftRadius = "0";
    messageNode.style.marginRight = "50px";

    timeNode.style.float = "left";
  }

  const messageNodeText = document.createTextNode(theMessage);
  const timeNodeText = document.createTextNode(theTimeAgo);
  

  messageNode.appendChild(messageNodeText);
  timeNode.appendChild(timeNodeText);

  chatBox.appendChild(messageNode);
  chatBox.appendChild(timeNode);
}

// I think this one is to create the message in the chatModal where it says that 'messages are end to end encrypted'
function appendSecuredAlertBubble(){
  const chatBox = document.getElementById("chat-box");
  const alertNode = document.createElement("div");

  alertNode.style.clear = "both";
  alertNode.style.padding = "6px 12px";
  alertNode.style.border = "1px solid #ccc";
  alertNode.style.borderRadius = "10px";
  alertNode.style.margin = "15px auto";
  alertNode.style.backgroundColor = "#FFE3A9";
  alertNode.style.textAlign = "center";
  alertNode.style.fontSize = "14px";
  alertNode.style.width = "90%";

  alertNode.classList.add("animate__animated");
  alertNode.classList.add("animate__fadeIn");    
  alertNode.classList.add("animate__faster");

  const alertNodeIcon = document.createElement("i");
  alertNodeIcon.classList.add("fas");
  alertNodeIcon.classList.add("fa-lock");

  const alertNodeText = document.createTextNode("\t\t Messages are end-to-end encrypted.\nNo one outside of this chat can read them.");
  
  alertNode.appendChild(alertNodeIcon);
  alertNode.appendChild(alertNodeText);

  chatBox.appendChild(alertNode);
}