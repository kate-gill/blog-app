<?php 
include('header.php'); 
include('db.php');

if(!isset($_GET['token'])){
    array_push($errors, "Something went wrong... Please request a new link");
} else {
    $_SESSION['token'] = $_GET['token'];
    $_SESSION['message'] = "Password has been changed successfully. Please use login form to sign in";
}

?>
    <main>

        <h2>Create new password</h2>

        <div class="cardWrap card bg-light mb-3">
            <div class="card-header"><i class='fas fa-lock'></i>New Password</div>
            <form method="POST" action="new_password.php">
                <div class="form-group">
                    <input class="inputField" type="password" name="password" placeholder="Password"/>                    
                </div>
                <div class="card-header"><i class='fas fa-user-lock'></i>Repeat New Password</div>
                <div class="form-group">               
                    <input class="inputField" type="password" name="password2" placeholder="Repeat your password"/>
                </div>
                <button type="submit" class="btn btn-success" name="new_password">Update</button>
            </form>
        </div>

        <?php include('errors.php')?>

    </main>
    
<?php include('footer.php')?>