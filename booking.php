<!-- <?php
?>
<div class="booking-info">
    <p>Reservation Date: January 15, 2024</p>
    <p>Room Type: Deluxe Suite</p>
    <p>Rate: $200 per night</p>
</div> -->


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
			<li><a href="dashboard.php" ><span class="las la-home"></span><span> <small>Dashboard</small></span></a></li>
			<li><a href="profile.php"><span class="las la-user-alt"></span><span><small>Profile</small></span></a></li>
			<li><a href="#" class="active"><span class="las la-tasks"></span><span><small>Booking</small></span></a></li>
         
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
                <h1>Booking</h1>
                <small>Home / Dashboard</small>
            </div>
            
            <div class="page-content">
           
            
            </div>
            
        </main>
        
    </div>
</body>
</html>
