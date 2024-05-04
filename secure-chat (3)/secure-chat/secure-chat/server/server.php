<!-- Handling authentication -->
<?php
require_once('DB.php');
$db = DB::getInstance();
session_start();

// Logout if user is already logged in. Dont really get how its working and whats the purpose of it
if(isset($_SESSION['uid']) || isset($_SESSION['username'])){
    $uid = $_SESSION['uid'];
    $query = "UPDATE users SET online=0, logout_timestamp=CURRENT_TIMESTAMP() WHERE id=$uid";
    if($db->query($query) === true){
        session_unset();
        session_destroy();
    }
}

// Process login
if(isset($_POST['login']))
{
    loginProcess($db);
    unset($_POST['login']);
}

// Process registration
if(isset($_POST['register']))
{
    registerProcess($db);
    unset($_POST['register']);
}

// Login process function
function loginProcess($db)
{
    if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['password']) && !empty($_POST['password']))
    {
        $username = strtolower($_POST['username']);
        $password = $_POST['password'];
        if(strpos($username, "'") === false && strpos($password, "'") === false)
        {
            $query = "SELECT * FROM users WHERE username = '$username'";
            $result = mysqli_query($db, $query);
            if(mysqli_num_rows($result) == 1)
            {
                $row = mysqli_fetch_assoc($result);
                // Verify password using password_verify()
                if(password_verify($password, $row['password']))
                {
                    echo "<div class='text-success' style='font-size: 16px; font-weight: bold; margin-top:20px; text-align:center;'>Logging In..&nbsp <i class='fas fa-lock'></i></div>";
                    $_SESSION['uid'] = $row['id'];
                    $_SESSION['username'] = $row['username'];
                    $uid = $_SESSION['uid'];
                    $query = "UPDATE users SET online=1, last_timestamp=CURRENT_TIMESTAMP() WHERE id=$uid";
                    if($db->query($query) === true)
                    {
                        $token = generateRandomToken($db);
                        $query = "SELECT * FROM login_details WHERE uid = $uid";
                        $result = mysqli_query($db, $query);

                        if(mysqli_num_rows($result) == 0){
                            $query = "INSERT INTO login_details (uid, token) VALUES ($uid, '$token')";
                            if($db->query($query) === true)
                            {
                                header( "refresh:1;url=./chat" );
                            }
                        }
                        else
                        {
                            $query = "UPDATE login_details SET token='$token', last_timestamp = CURRENT_TIMESTAMP() WHERE uid=$uid";
                            if($db->query($query) === true)
                            {
                                header( "refresh:1;url=./chat" );
                            }
                        }
                    }
                }
                else
                {
                    echo "<div class='text-danger' style='font-size: 16px; font-weight: bold; margin-top:20px; text-align:center;'>Wrong Username or Password</div>";
                }
            }
            else
            {
                echo "<div class='text-danger' style='font-size: 16px; font-weight: bold; margin-top:20px; text-align:center;'>Wrong Username or Password</div>";
            }
        }
    }
}

// Registration process function
function registerProcess($db)
{
    if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['password']) && !empty($_POST['password']))
    {
        $username = strtolower($_POST['username']);
        $password = $_POST['password'];
        if(strpos($username, "'") === false && strpos($password, "'") === false)
        {
            // Hash the password using bcrypt
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $query = "SELECT * FROM users WHERE username = '$username'";
            $result = mysqli_query($db, $query);
            if(mysqli_num_rows($result) == 1)
            {
                echo "<div class='text-danger' style='font-size: 16px; font-weight: bold; margin-top:20px; text-align:center;'>Please choose another username</div>";
            }
            else
            {
                $query = "INSERT INTO users (username, password) VALUES ('$username', '$hashed_password')";
                if($db->query($query) === true){
                    echo "<div class='text-success' style='font-size: 16px; font-weight: bold; margin-top:20px; text-align:center;'>Successfully Registered &nbsp <i class='fas fa-lock'></i></div>";
                    header( "refresh:2;url=./index.php" );
                }
                else{
                    echo "<div class='text-danger' style='font-size: 16px; font-weight: bold; margin-top:20px; text-align:center;'>". mysqli_error($db) ."</div>";
                }
            }
        }
    }
}

// Function to generate a random token. It is used in the login_details table in the db.Not really sure the purpose of it
function generateRandomToken($db)
{
    for(;;)    
    {
        $length = 16;
        $word = array_merge(range('a', 'z'), range(0, 9), range('A', 'Z'));
        shuffle($word);
        $token = substr(implode($word), 0, $length);

        $query = "SELECT * FROM login_details WHERE token='$token'";
        $result = mysqli_query($db, $query);
        if(mysqli_num_rows($result) == 0)
            break;
    }
    return $token;
}
?>