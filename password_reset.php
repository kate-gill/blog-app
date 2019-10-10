<?php 
include('header.php'); 
include('db.php');

?>
    <main>

        <h2>Change password</h2>

        <div class="cardWrap card bg-light mb-3">
            <div class="card-header"><i class='fas fa-lock'></i>Current password</div>
            <form method="POST" action="password_reset.php">
                <div class="form-group">
                    <input class="inputField" type="password" name="curpassword" placeholder="Current password"/>                    
                </div>
                <div class="card-header"><i class='fas fa-lock'></i>New Password</div>
                <div class="form-group">               
                    <input class="inputField" type="password" name="password" placeholder="Current password"/>
                </div>
                <div class="card-header"><i class='fas fa-user-lock'></i>Repeat New Password</div>
                <div class="form-group">               
                    <input class="inputField" type="password" name="password2" placeholder="Repeat your password"/>
                </div>
                <button type="submit" class="btn btn-success" name="password_reset">Update</button>
            </form>
        </div>

        <?php include('errors.php')?>

    </main>
    
<?php include('footer.php')?>