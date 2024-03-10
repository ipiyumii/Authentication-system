<?php
   ini_set('display_errors', 1);
   error_reporting(E_ALL);    
    $error='';
   require_once('auth.php');
   require_once('session.php');
   require_once('dbUtil.php');
//   require_once('auth0.php');


if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnsubmit'])) {
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);

    clearLoginAttempts($username);

    if (empty($password)) {
        $error = "Password is required.";
    } else {
        $user = getUserFromDatabase($username);

        // Set session variables or redirect the user to the dashboard upon successful login
        if ($user && verifyPassword($password, $user['password'])) {
            // Set session variables
            setSession('user_id', $user['id']);
            setSession('username', $user['username']);

            // Reset login attempts on successful login
            setSession('login_attempts', 0);
            setSession('last_login_attempt', 0);

            resetLoginAttempts($username);
            // Redirect to the dashboard page
            header('Location: dashboard.php');
            exit();
        } else {
            // Increment login attempts for account lockout mechanism
            incrementLoginAttempts($username);
            // Check if the account should be locked
            $isAccountLocked = isAccountLocked($username);
            if ($isAccountLocked) {
                $error = "Your account has been locked due to multiple failed login attempts. Please contact support.";
            } else {
                $error = "Invalid username or password.";
            }
        }
    }



//    if (empty($password)) {
//            $error = "Password is required.";
//         } else {
//            $user = getUserFromDatabase($username);
//
//            // Set session variables or redirect the user to the dashboard upon successful login
//            if ($user && verifyPassword($password, $user['password'])) {
//                // Set session variables
//                setSession('user_id', $user['id']);
//                setSession('username', $user['username']);
//
//                // Reset login attempts on successful login
//                setSession('login_attempts', 0);
//                setSession('last_login_attempt', 0);
//
//                // Redirect to the dashboard page
//                header('Location: dashboard.php');
//                exit();
//            } else {
//            // Increment login attempts for account lockout mechanism
//            incrementLoginAttempts($username);
//                  // Check if the account should be locked
//                  $isAccountLocked = isAccountLocked($username);
//                  if ($isAccountLocked) {
//                      $error = "Your account has been locked due to multiple failed login attempts. Please contact support.";
//                  } else {
//                      $error = "Invalid username or password.";
//                  }
//           }
//        }
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
    <link rel="stylesheet" href="assests/loginstyle.css">

    <script src="node_modules/@auth0/auth0-spa-js/dist/auth0-spa-js.production.js"></script>
    <script src="https://cdn.auth0.com/js/auth0-spa-js@latest.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</head>
<body id="login">
      <section id="loginfrom">
        <div class="wrapper">
            <div class="form-box login">
                <h2>Login</h2>
                <form action="login.php" method ="post" >

                <div class="input-box">
                        <span class="icon"><i class="bi bi-person-fill"></i></span>
                        <input type="text" id="username" name="username" required>
                        <label>User Name</label>
                    </div>
                    <div class="input-box">
                        <span class="icon"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" id="password" name="password" required>
                        <label>password</label>
                    </div>
                    <div class="remember-forgot">
                        <a href="#">Forgot password?</a>
                    </div>
                    <button type="submit" class="btnsubmit" id="btnsubmit" name ="btnsubmit">Login</button>

                    <div class="login-register">
                        <p> Don't have an account? <a href="register.php" class="register-link"> Register</a></p>
                    </div>

                    <div class="error-message">
                        <?php 
                            if (isset($error) && !empty($error)) {
                                echo $error;
                            }                         
                        ?>
                    </div>

                    <div class="btn-sso">
                        <a href="https://accounts.google.com/o/oauth2/v2/auth?client_id=601545463122-q61e84etavrj2stgh6erhfq2mvl2gf48.apps.googleusercontent.com&redirect_uri=http://localhost/auth_system/callback.php&response_type=code&scope=email%20profile&access_type=offline">Sign in with Google</a>

                        <!--                        <button id="login-button" type="button">Login with Google</button>-->

                    </div>
                   

                </form>
            </div>
        </div>   
</section>  

</body>
</html>
