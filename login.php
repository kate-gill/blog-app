<?php 
include('header.php');
include('db.php');
?>

    <main>

        <h2>Sign in</h2>

        <?php 
            if(isset($_SESSION['message'])){ ?>
                <div class="alert alert-success" role="alert">
                    <p><?php echo $_SESSION['message']?></p>
                </div>                
                <?php unset($_SESSION['message'])?> 
        <?php } ?>    

        <div class="cardWrap card bg-light mb-3">
            <div class="card-header"><i class="fas fa-user-circle"></i>Your username/email</div>
            <form class="mainForm" method="POST" action="login.php">
                <div class="form-group">
                    <input class="inputField" type="text" name="username" placeholder="Enter your username or email" value="<?php echo $username ?>"/>
                </div>
                <div class="card-header"><i class="fas fa-lock"></i>Your password</div>
                <div class="form-group">               
                    <input class="inputField" type="password" name="password" placeholder="Enter your password"/>
                </div>
                <button type="submit" class="btn btn-success" name="login">Sign in</button>
            </form>
            <a href="password.php">Forgot your password?</a>
            <br/>
            <a href="register.php">Not registered yet?</a>
        </div>

        <?php include('errors.php')?>

    </main>

<?php include('footer.php')?>