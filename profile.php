<?php
    // Include necessary files
    require_once('session.php');
    require_once('dbUtil.php');
    require_once('auth.php');
    // Check if the user is logged in
    if (!getSession('user_id')) {
        // Redirect to the login page if not logged in
        header('Location: login.php');
        exit();
    }
 
    // Fetch user data from the database
    $userId = getSession('user_id');
    $user = getUserById($userId); // Function to retrieve user data by ID from the database

    // Check if user data is retrieved
    if (!$user) {
        // Redirect to the login page if user data is not found
        header('Location: login.php');
        exit();
    }

    // Extract user details
    $id = $user['id'];
    $username = $user['username'];
    $email = $user['email'];
    $address = $user['address'];
    $telephone = $user['telephone'];
    $password = $user['password'];

     // Define variables to hold error messages
   //  $currentPasswordError = $newPasswordError = $confirmPasswordError = 'error updating password';

    // Handle password change logic when the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnchange'])) {
    // Retrieve form data
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Validate current password
    if (!verifyPassword($currentPassword, $user['password'])) {
        $currentPasswordError = "Incorrect current password.";
    }

    // Validate new password and confirm password
    if ($newPassword !== $confirmPassword) {
        $newPasswordError = "New password and confirm password do not match.";
    }

    // If no errors, proceed to change password
    if (empty($currentPasswordError) && empty($newPasswordError)) {
        // Hash the new password
        $hashedNewPassword = hashPassword($newPassword);

        // Update the user's password in the database
        $updateSuccess = updateUserPassword($userId, $hashedNewPassword);

        // Check if update was successful
        if ($updateSuccess) {
            // Redirect to profile page or display success message
            header('Location: profile.php?success=password_changed');
            exit();
        } else {
            // Display error message if update failed
            $updateError = "Failed to update password. Please try again later.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">
    <title>Admin Dashboard | Keyframe Effects</title>
    <link rel="stylesheet" href="assests/dashboardstyle.css">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
</head>
<body>

<div class="sidebar">
    <div class="sidebar-brand">
        <h2><span class="fa fa-user-o"> </span> MSM Traders</h2>
    </div>
</div>

<input type="checkbox" id="menu-toggle">
<div class="sidebar">
    <div class="side-header">
        <h3>L<span>ogo</span></h3>
    </div>

    <div class="side-content">
        <div class="side-menu">
            <ul>
                <li><a href="dashboard.php"><span class="las la-home"></span><span> <small>Dashboard</small></span></a></li>
                <li><a href="#" class="active"><span class="las la-user-alt"></span><span><small>Profile</small></span></a></li>
                <li><a href="booking.php"><span class="las la-tasks"></span><span><small>Booking</small></span></a></li>
            </ul>
        </div>
    </div>
</div>

<div class="main-content">
    <header>
        <div class="header-content">
            <label for="menu-toggle">
                <span class="las la-bars"></span>
            </label>
            <div class="header-menu">
                <label for="">
                    <span class="las la-search"></span>
                </label>
                <div class="notify-icon">
                    <span class="las la-envelope"></span>
                    <span class="notify">4</span>
                </div>
                <div class="notify-icon">
                    <span class="las la-bell"></span>
                    <span class="notify">3</span>
                </div>
                <a href="logout.php">
                    <div class="user">
                        <div class="bg-img" style="background-image: url(img/1.jpeg)"></div>
                        
                        <span class="las la-power-off"></span>
                        <span>Logout</span>
                    </div>
                    </a>
            </div>
        </div>
    </header>

    <main>
        <div class="page-header">
            <h1>Profile</h1>
            <small>Home / Dashboard</small>
        </div>
        <div class="page-content">
            <div class="icon">
                <span><i class="bi bi-person-circle"></i> <h2></h2></span>
            </div>
            <div class="details" id="details">
                <form action="myaccount.php" method="POST">
                    <label for=""> UserID</label> <br>
                    <input type="text" name="id" id="id" value="<?php echo $id; ?>"> <br> <br> 
                    <label for=""> Username</label> <br>
                    <input type="text" name="name" id="name" value="<?php echo $username; ?>"> <br> <br> 
                    <label for="">Email</label> <br>
                    <input type="text" name="email" id="email" value="<?php echo $email; ?>"> <br> <br>
                    <label for="">Address</label> <br>
                    <input type="text" name="address" id="address" value="<?php echo $address; ?>"> <br> <br>
                    <label for="">Telephone</label> <br>
                    <input type="text" name="tele" id="tele" value="<?php echo $telephone; ?>">
                </form>
            </div>
        </div>
        <div class="change-password-button">
    <button type="button" class="btnsubmit" id="btnsubmit" name ="btnsubmit">Change Password</button>
</div>
    </main>
    
    <div class="change-password-container" id="change-password-container" style="display: none;">
        <div class="container">
            <form method="post" action="profile.php">
                <div class="form-group">
                    <label for="current_password">Current Password:</label>
                    <input type="password" id="current_password" name="current_password" required>
                </div>
                <div class="form-group">
                    <label for="new_password">New Password:</label>
                    <input type="password" id="new_password" name="new_password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm New Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                <button type="submit" class="btnsubmit" id="btnchange" name ="btnchange">Change </button>

                <div class="error-message">
                        <?php 
                            if (isset($updateError) ) {
                                echo "<p style='color: red;'>$updateError</p>";
                            } elseif(isset($currentPasswordError))  {
                                echo "<p style='color: red;'>$currentPasswordError</p>";
                            } elseif(isset($newPasswordError))  {
                                echo "<p style='color: red;'>$newPasswordError</p>";
                            }  elseif(isset($updateSuccess))    {
                                echo "<p style='color: red;'>$updateSuccess</p>";
                            }               
                        ?>
                    </div>
            </form>
        </div>
    </div>
</div>



<script>
    document.getElementById('btnsubmit').addEventListener('click', function() {
        var container = document.getElementById('change-password-container');
        container.style.display = container.style.display === 'none' ? 'block' : 'none';
    });
</script>
</body>
</html>
