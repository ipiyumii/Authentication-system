<?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    $error = '';
    require_once('auth.php');
    require_once('session.php');
    require_once('dbUtil.php');

    if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnregister'])) {
        $username = htmlspecialchars($_POST['username']);
        $email = htmlspecialchars($_POST['email']);
        $address = htmlspecialchars($_POST['address']);
        $password = htmlspecialchars($_POST['password']);

        $isUsernameAvailable = validateUserName($username);

        if (!$isUsernameAvailable) {
            $error = "Username already taken. Please choose a different username.";
        } else {
            $hashedPassword = hashPassword($password);
            $userId = saveUserToDatabase($username, $hashedPassword, $email, $address);

    
            if ($userId) {
                // Registration successful, set session variables and redirect to the dashboard
                setSession('user_id', $userId);
                setSession('username', $username);
                
                // Redirect to the dashboard page
                header('Location: dashboard.php');
                exit();
            } else {
                // Registration failed, handle the error (e.g., display an error message)
                $error = "Registration failed. Please try again.";
            }
        }
    }   

    // Handle login logic
    if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnsubmit'])) {
        $username = htmlspecialchars($_POST['uname']);
        $password = htmlspecialchars($_POST['pwd']);
        
        $user=getUserFromDatabase( $username);
        
        if ($user && verifyPassword($password, $user['pwd'])) {
            // Set session variables or redirect the user to the dashboard upon successful login
            if ($user && verifyPassword($password, $user['pwd'])) {
                // Set session variables
                setSession('user_id', $user['id']);
                setSession('username', $user['uname']);
            
                // Reset login attempts on successful login
                setSession('login_attempts', 0);
                setSession('last_login_attempt', 0);
            
                // Redirect to the dashboard page
                header('Location: dashboard.php');
                exit();
            }
            
        } else {
            // Increment login attempts for account lockout mechanism
            $loginAttempts = getSession('login_attempts') ?? 0;
            setSession('login_attempts', $loginAttempts + 1);
            setSession('last_login_attempt', time());
        
            // Display an error message or handle unsuccessful login
            $error = "Invalid username or password.";
        }
        
    }
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diamond pvt ltd</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" 
    integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.4/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="assests/loginstyle.css">
    <link rel="stylesheet" href="homestyle.css">
  
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</head>
<body id="login">
      <section id="loginfrom">
        <div class="wrapper">
            <div class="form-box login">
                <h2>Login</h2>
                <form action="register.php" method ="post" >
                    <div class="input-box">
                        <span class="icon"><i class="bi bi-person-fill"></i></span>
                        <input type="text" id="uname" name="uname" required>
                        <label>User Name</label>
                    </div>
                    <div class="input-box">
                        <span class="icon"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" id="pwd" name="pwd" required>
                        <label>password</label>
                    </div>
                    <div class="remember-forgot">
                        <label><input type="checkbox"> Remember me</label>
                        <a href="#">Forgot password?</a>
                    </div>
                    <button type="submit" class="btnsubmit" id="btnsubmit" name ="btnsubmit">Login</button>

                    <div class="login-register">
                        <p> Don't have an account? <a href="#" class="register-link"> Register</a></p>
                    </div>
                </form>
            </div>

            <div class="form-box register">
                <h2>Registration</h2>
                <form action="" method="post">
                    <div class="input-box">
                        <span class="icon"><i class="bi bi-person-fill"></i></span>
                        <input type="text" id="username" name="username" value="<?php if(isset($username)) echo $username; ?>" required>
                        <label>User Name</label>
                        
                    </div>
                    <div class="input-box">
                        <span class="icon"><i class="bi bi-envelope-fill"></i></span>
                        <input type="email" id="email" name="email" required>
                        <label>E-mail</label>
                    </div>
                    <div class="input-box">
                        <span class="icon"><i class="bi bi-geo-alt-fill"></i></span>
                        <input type="text" id="address" name="address" required>
                        <label>Address</label>
                    </div>
                    <div class="input-box">
                        <span class="icon"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" id="password" name=" password" required>
                        <label>password</label>
                    </div>
                    <div class="input-box">
                        <span class="icon"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" id="confirmPassword" name="confirmPassword" required>
                        <label>Confirm password</label>
                    </div>

                    <div class="error-message" id="username-error"><?php if(isset($error)) echo $error; ?></div>

                    <button type="submit" class="btnsubmit" id="btnregister" name="btnregister" onsubmit="return validateEmail() && validatePasswords()">Register</button>

                    <div class= "login-register">
                        <p> Already have an account? <a href="#" class="login-link"> Login</a></p>
                    </div>
                </form>
            </div>
        </div>
      </section>

      <script src="assests/script.js"></script>
     
</body>
</html>
