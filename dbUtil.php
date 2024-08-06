<?php
    function dbConnection(){
        $servername = "localhost";
        $username = "root";
        $password = "root";
        $dbname = "auth_system";

        $mysqli = new mysqli($servername, $username, $password, $dbname);

        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }
        return $mysqli;
    }
    function getUserFromDatabase($uname) {
        $mysqli = dbConnection();
     
        $stmt = $mysqli->prepare("SELECT id, username, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $uname);
        $stmt->execute();
        $stmt->bind_result($userId, $dbUsername, $dbPassword);
    
        $stmt->fetch();
    
        $stmt->close();
        $mysqli->close();
    
        return $dbUsername ? ['id' => $userId, 'username' => $dbUsername, 'password' => $dbPassword] : null;
    }

     function saveUserToDatabase($username, $hashedPassword, $email, $address,$telephone) {
        $mysqli = dbConnection();

         $username = mysqli_real_escape_string($mysqli, $username);
         $email = mysqli_real_escape_string($mysqli, $email);
         $address = mysqli_real_escape_string($mysqli, $address);
         $telephone = mysqli_real_escape_string($mysqli, $telephone);

         if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
             return array('error' => 'Invalid email format');
         } else{
             $insertQuery = "INSERT INTO users (username, password, email, address,telephone) VALUES (?, ?, ?, ?,?)";
             $stmt = $mysqli->prepare($insertQuery);

             if ($stmt) {
                 $stmt->bind_param("sssss", $username, $hashedPassword, $email, $address,$telephone);
                 $stmt->execute();

                 $userId = $stmt->insert_id;

                 $stmt->close();
                 $mysqli->close();

                 return $userId;
             } else {
                 $mysqli->close();
                 return "Error in prepared statement: " . $mysqli->error;
             }
         }

    }
    
    function validateUserName($username) {
        $mysqli = dbConnection();

        $checkUsernameQuery = "SELECT * FROM users WHERE username = ?"; 
        $checkUsernameStmt = $mysqli->prepare($checkUsernameQuery);
        $checkUsernameStmt->bind_param("s", $username);
        $checkUsernameStmt->execute();
        $checkUsernameResult = $checkUsernameStmt->get_result();

        $isUsernameAvailable = ($checkUsernameResult->num_rows === 0);

        $checkUsernameStmt->close();
    
        return $isUsernameAvailable;
    }
 
    function getUserByGoogleId($google_id) {
        $mysqli = dbConnection();
    
        $stmt = $mysqli->prepare("SELECT * FROM users WHERE google_id = ?");
        $stmt->bind_param("s", $google_id);
        $stmt->execute();
        $result = $stmt->get_result();
     
        $user = $result->fetch_assoc();
    
        $stmt->close();
        $mysqli->close();
    
        return $user; 
    }
    
    function incrementLoginAttempts($username) {
        $mysqli = dbConnection();

        $username = mysqli_real_escape_string($mysqli, $username);

        // Fetch the number of login attempts
        $stmt = $mysqli->prepare("SELECT login_attempts FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($loginAttempts);
        $stmt->fetch();
        $stmt->close();
    
        $loginAttempts++;
    
        // Update the login_attempts column in db
        $updateStmt = $mysqli->prepare("UPDATE users SET login_attempts = ? WHERE username = ?");
        $updateStmt->bind_param("is", $loginAttempts, $username);
        $updateStmt->execute();
        $updateStmt->close();
    
        $mysqli->close();
    }
    
    function isAccountLocked($username) {
        $mysqli = dbConnection();
        
        $stmt = $mysqli->prepare("SELECT login_attempts FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($loginAttempts);
        $stmt->fetch();
        $stmt->close();
    
        $maxAttempts = 3;
    
        $isLocked = ($loginAttempts >= $maxAttempts);
    
        $mysqli->close();
    
        return $isLocked;
    }

function getUserById($userId) {
    $mysqli = dbConnection();
 
    // Fetch user information based on id
    $stmt = $mysqli->prepare("SELECT id, username, email, address, telephone,password FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);  
    $stmt->execute();
    $stmt->bind_result($id, $username, $email, $address, $telephone,$passowrd);

    $stmt->fetch();

    $stmt->close();
    $mysqli->close();

    return $username ? ['id' => $id, 'username' => $username, 'email' => $email, 'address' => $address, 'telephone' => $telephone,'password' => $passowrd] : null;
}
function updateUserPassword($userId, $hashedPassword) {
    $mysqli = dbConnection();

    $stmt = $mysqli->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->bind_param("si", $hashedPassword, $userId);
    
    $success = $stmt->execute();

    $stmt->close();
    $mysqli->close();

    return $success;
}

function saveGoogleUserToDatabase($username,$email,$id){
    $mysqli = dbConnection();

    $username = mysqli_real_escape_string($mysqli, $username);
    $email = mysqli_real_escape_string($mysqli, $email);
    $id = mysqli_real_escape_string($mysqli, $id);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return array('error' => 'Invalid email format');
    } else{
        $insertQuery = "INSERT INTO users (username,email, google_id) VALUES (?, ?, ?)";
        $stmt = $mysqli->prepare($insertQuery);

        if ($stmt) {
            $stmt->bind_param("sss", $username, $email,$id);
            $stmt->execute();

            $userId = $stmt->insert_id;

            $stmt->close();
            $mysqli->close();

            return $userId;
        } else {
            $mysqli->close();
            return "Error in prepared statement: " . $mysqli->error;
        }
    }
}

function resetLoginAttempts($username) {
    $mysqli = dbConnection();
    $resetQuery = "UPDATE users SET login_attempts = 0 WHERE username = ?";

    $stmt = $mysqli->prepare($resetQuery);

    $stmt->bind_param("s", $username); 

    $success = $stmt->execute();

    $stmt->close();
    $mysqli->close();

    return $success;
}


function clearLoginAttempts($username) {
    $mysqli = dbConnection();

    $clearTime = strtotime('-1 hour'); 

    $stmt = $mysqli->prepare("SELECT last_login_attempt FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($lastLoginAttempt);
    $stmt->fetch();
    $stmt->close();

    if ($lastLoginAttempt < $clearTime) {
        $updateStmt = $mysqli->prepare("UPDATE users SET login_attempts = 0 WHERE username = ?");
        $updateStmt->bind_param("s", $username);
        $updateStmt->execute();
        $updateStmt->close();
    }

    $mysqli->close();
}

?>