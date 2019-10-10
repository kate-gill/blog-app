<?php include('header.php')?>

    <main>

        <h2>Restore password</h2>

        <div class="cardWrap card bg-light mb-3">
            <div class="card-header"><i class="fa fa-envelope-open"></i>Please enter an email you registered with</div>
            <form method="POST" action="password.php">
                <div class="form-group">
                    <input class="inputField" type="text" name="email" placeholder="Enter your email"/>
                    <small id="emailHelp" class="form-text text-muted">We will send you an email with instructions</small>
                </div>
                <button class="btn btn-success" type="submit" name="reset">Send</button>
            </form>
        </div>

        <?php include('errors.php')?>

    </main>

<?php include('footer.php')?>