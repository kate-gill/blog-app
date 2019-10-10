<?php session_start(); 

if(isset($_GET['logout'])){
    session_destroy();
    unset($_SESSION['username']);
    $_SESSION['success'] = "Successfully logged out";
    header('Location: index.php');    
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Blog App</title>
    <link rel="icon" href="images/favicon.ico" type="image/png">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">    
</head>
<body>
    <div id="mainContainer">
        <nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top justify-content-center">
       
            <div class="myNavbar">

                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarMenu">
                    <span class="navbar-toggler-icon"></span>
                </button>                
                                 
                <div id="navbarMenu" class="collapse navbar-collapse">            
                    <ul class="navbar-nav">
                        <li><a href="index.php">Home</a></li>
                        <?php if(isset($_SESSION['username'])){ ?>
                            <li><a href="addPost.php">Add post</a></li>
                            <li><a href="profile.php">My profile</a></li>
                            <li><a href="index.php?logout=1" name="logout">Logout</a></li>  
                        <?php } else {?>
                            <li><a href="login.php">Login</a></li>
                            <li><a href="register.php">Register</a></li>
                        <?php } ?>
                    </ul>
                </div>

            </div>  
                          
        </nav>

    