<?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);    
   
    $usernameError = '';
    $emailError='';
    $passwordError= '';
    $confirmPasswordError= '';
    $addressError= '';

    require_once('auth.php');
    require_once('session.php');
    require_once('dbUtil.php');
    require_once('validateInputs.php');

    if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnregister'])) {
        $username = htmlspecialchars($_POST['username']);
        $email = htmlspecialchars($_POST['email']);
        $telephone = htmlspecialchars($_POST['telephone']);
        $address = htmlspecialchars($_POST['address']);
        $password = htmlspecialchars($_POST['password']);
        $confirmPassword=htmlspecialchars($_POST['confirmPassword']);

        $errors = validateRegistrationInput($username, $email, $password, $confirmPassword, $telephone, $address);

        if(empty($errors)){
            $hashedPassword = hashPassword($password);
            $userId = saveUserToDatabase($username, $hashedPassword, $email, $address,$telephone);

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
        } else {
            $usernameError = $errors['username'] ?? '';
            $emailError = $errors['email'] ?? '';
            $passwordError = $errors['password'] ?? '';
            $confirmPasswordError = $errors['confirmPassword'] ?? '';
            $addressError = $errors['address'] ?? '';        
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
    <link rel="stylesheet" href="assests/loginstyle.css">
  
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</head>
<body id="login">
      <section id="loginfrom">
      <div class="registerwrapper">
            <div class="form-box register">
                <h2>Registration</h2>
                <form action="register.php" method="post">
                    <div class="input-box">
                        <span class="icon"><i class="bi bi-person-fill"></i></span>
                        <input type="text" id="username" name="username" value="<?php if(isset($username)) echo $username; ?>" required>
                        <label>User Name</label>
                        
                    </div>
                    <div class="input-box">
                        <span class="icon"><i class="bi bi-envelope-fill"></i></span>
                        <input type="email" id="email" name="email" value="<?php if(isset($email)) echo $email; ?>" required>
                        <label>E-mail</label>
                    </div>
                    <div class="input-box"> 
                        <span class="icon"><i class="bi bi-telephone-fill"></i></span>
                        <input type="text" id="telephone" name="telephone" value="<?php if(isset($telephone)) echo $telephone; ?>" required>
                        <label>Telephone</label>
                    </div>
                    <div class="input-box">
                        <span class="icon"><i class="bi bi-geo-alt-fill"></i></span>
                        <input type="text" id="address" name="address" value="<?php if(isset($address)) echo $address; ?>"required>
                        <label>Address</label>
                    </div>
                    <div class="input-box">
                        <span class="icon"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" id="password" name=" password" value="<?php if(isset($password)) echo $password; ?>" required>
                        <label>password</label>
                    </div>
                    <div class="input-box">
                        <span class="icon"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" id="confirmPassword" name="confirmPassword" value="<?php if(isset($confirmPassword)) echo $confirmPassword; ?>" required>
                        <label>Confirm password</label>
                    </div>

                    <div class="error-message">
                        <?php 
                        if (isset($usernameError) && !empty($usernameError)) {
                            echo $usernameError;
                        } else if (isset($emailError) && !empty($emailError)) {
                            echo "<p style='color: red;'>$emailError</p>";
                        } else if (isset($passwordError) && !empty($passwordError)) {
                            echo "<p style='color: red;'>$passwordError</p>";
                        }else if(isset($confirmPasswordError) && !empty($confirmPasswordError)) {  
                            echo "<p style='color: red;'>$confirmPasswordError</p>";
                        } else if (isset($addressError) && !empty($addressError)) {
                            echo "<p style='color: red;'>$addressError</p>";
                        }
                        ?>
                    </div>

                    <button type="submit" class="btnsubmit" id="btnregister" name="btnregister" >Register</button>

                    <div class= "login-register">
                        <p> Already have an account? <a href="login.php" class="login-link"> Login</a></p>
                    </div>
                    
                </form>
            </div>
        </div>
    </section>

    <script src="assests/script.js"></script>  
</body>
</html>
