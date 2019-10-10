<?php 
include('header.php');
include('db.php');
?>

    <main>

        <h2>Sign up</h2>

        <div class="cardWrap card bg-light mb-3">
            <div class="card-header"><i class="far fa-user-circle"></i>Username</div>
            <form class="mainForm" method="POST" action="register.php" enctype="multipart/form-data">
                <div class="form-group">
                    <input class="inputField" type="text" name="username" placeholder="Your username" value="<?php echo $username ?>"/>
                </div>
                <div class="card-header"><i class="fa fa-envelope-open"></i>Email</div>
                <div class="form-group">               
                    <input class="inputField" type="text" name="email" placeholder="Your email" value="<?php echo $email ?>"/>
                </div>
                <div class="card-header"><i class="fas fa-user-circle"></i>Upload profile picture (optional)</div>
                <div class="form-group">               
                    <input type="file" name="profile">
                    <small id="emailHelp" class="form-text text-muted">Supported file types: jpeg, jpg, png, gif</small>
                </div>
                <div class="card-header"><i class='fas fa-lock'></i>Password</div>
                <div class="form-group">               
                    <input class="inputField" type="password" name="password" placeholder="Choose your password"/>
                </div>
                <div class="card-header"><i class='fas fa-user-lock'></i>Please repeat password</div>
                <div class="form-group">               
                    <input class="inputField" type="password" name="password2" placeholder="Please repeat your password"/>
                </div>                
                <button type="submit" class="btn btn-success" name="register">Sign up</button>
            </form>
            <a href="login.php">Already a member?</a>
        </div>

        <?php include('errors.php')?>

    </main>

<?php include('footer.php')?>