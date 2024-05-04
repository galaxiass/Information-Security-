// This was here from before //

# Secure Chatting Application

Secure Chatting Application developed by PHP, JavaScript, and MySQL.

## Algorithms

• RSA<br/>

**All algorithms are implemented from scratch ✅**

## Included Files

• Documentation (Detailed Description on each Algorithm)<br/>
• SQL file (sp_chat.sql)

## Extra Feature

• All the files are encrypted by encrypting their base64 arrayBuffer not by encrypting the path! (It's a requirement)

## Demo

https://user-images.githubusercontent.com/66283081/176962281-60bab593-6bb4-47ae-aa5f-d6ad039ced73.mp4

// EXPLANATION OF THE FILES

-- index.php --
This is the index file where the login takes place. A pretty basic login form with a register link on the bottom if the user hasnt register yet
WE NEED TO CHANGE THE STYLING, TITLES AND OTHERS

-- register.php --
This is the register file where the registration of the user takes place. Similar form as the login's
WE NEED TO CHANGE THE STYLING, TITLES AND OTHERS

SERVER{
-- server/server.php --
This is the server side with the backend that handles the authentication of the users. When a user is successfuly logged in, he is redirected to the chat/index.php

-- DB.php --
This is where the connection with the database happens.

-- chat_server.php -- triggered by the logout button in chat/index.php
Here we handle the logout of the user. I dont understand why we need the generateRandomToken inside this function.
}

CHAT{
-- chat/index.php -- navigated from server.php
This is the page where the user is navigated after logging into the app. This is the starting page inside the app where the user can see the other users he can chat with and select one of them to do so.
All users are displayed in a table using the 'get_users.php'.
If the user clicks on the chat icon a chatModal is popped and the users can start chatting.

-- get_users.php -- triggered inside the chat/index.php
Gets all the users from database and displays them in a table.

-- chat/check_receiver_status.php -- Triggered by resources/ajax.js -> ajaxCheckReceiverStatus
In this file we get the receiver's status from the database. In utilities.js we make an AJAX request when the page is loaded and pass a parameter (home or modal).If the parameter is home, we get all the users' status. If its modal we only get the receiver's status.

-- insert_message.php -- Triggered by 'resources/ajax.js -> ajaxInsertMessage()'
Encrypts the message and inserts it in the db.

-- display_messages.php -- Triggered by 'resources/ajax.js -> ajaxDisplayMessages()'
Responsible for displaying the messages after decryption.s
}

MODULES{
I DONT KNOW WHY WE NEED BOTH .PHP AND .JS
-- methods.php -- Triggered by 'chat/insert_message.php for encryption' and 'chat/display_messages.php for decryption'
Here is where the actual encryption/decryption take place.
}

RESOURCES{
-- resources/utilities.js --
Here are some helpful functions. When the page is loaded (document.ready) we get the receiver's/users' status depending on whether the chatModal is open or not. If it is we also get the new messages displayed.

-- vars.js --
No clue where this is used

-- resources/chatBubbles.js -- Triggered by resources/ajax.js -> ajaxDisplayMessages
2 functions for creating those little bubbles where the messages are being displayed. One is for regular messages and one is for the Big message on top of the chatModal 'Messages are end to end encrypted...'.

-- resources/home.js --
Contains functions to show and update the chatModal so that it stays up-to-date and update dynamically certain variables and aspects such as user's status.

-- resources/chat.js --
In here we add the title to the chatModal. Also here is the implementation of the sendClicked() button, for the sending of messages.

-- resources/ajax.js --
This file contains JavaScript functions responsible for handling various AJAX operations related to the chat functionality.These functions ensure smooth communication between the client-side interface and the server-side logic, allowing users to send and receive messages and stay updated on the online status of other chat participants.
}

---- Potential Flow of the App ----

The user visits the app's index page. The user presses the register link to navigate to the 'register.php'. After a user has registered they go to the login's page and login with their credentials. The registration and login processes are handles in the 'server/server.php' file.

After a user's successful login, the user is navigated to the 'chat/index.php'.

-- Main Page --
-On the top right there is the logout button. When the user presses the logout button, a POST request is sent to the server. The logout process is handled in the 'server/chat_server.php'

-In the center of the page there is a table containing all the users of the app.
This is handled in the 'chat/get_users.php'.

When the user is still in the main page, every 2500 ms ('utilities.js -> $(document).ready()') an AJAX request is being sent ('ajaxCheckReceiverStatus'). In this function a GET request is sent to 'check_receiver_status.php'. Based on the response, there are some adjustments ('Online/Offline') in the user's table using the 'updateUsersPublicStatus()'.

-- Chat Modal --
When the user presses the chat icon, the chat modal is toggled.

When the user is in the chat modal, every 2500 ms ('utilities.js -> $(document).ready()') an AJAX request is being sent ('ajaxCheckReceiverStatus'). In this function a GET request is sent to 'check_receiver_status.php'.

When the chatModal is shown, inside the 'home.js' the ajaxDisplayMessages() is triggered with the parameter 'displayALL'. Inside this AJAX function, a GET request is sent to 'display_messages.php'. The messages are appended into chatBubbles and are shown to the users. Also every 250 ms ('utilities.js -> $(document).ready()') an AJAX request is being sent ('ajaxDisplayMessages("displayNewOnly")') to display the new Messages in the same way.

Inserting Messages
when a user writes a messages and clicks the send Button, the 'sendClicked() inside chat.js' is implemented.Inside sendClicked() an AJAX request is sent. In the 'ajaxInsertMessage' a POST request is sent to 'insert_message.php'. Inside there the message is encrypted using the RSA methods ('modules/methods.php') and the data are saved in the database.
