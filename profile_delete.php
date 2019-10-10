<?php 
include('header.php');
include('db.php');

if(isset($_GET['deleteProfile'])){
    $id = $_GET['deleteProfile'];
}

?>

    <main>

    <div class="alert alert-danger" role="alert">
            <h4 class="alert-heading">Are you sure you want to delete your profile permanently?</h4>
            <p>Please note, you will not be able to recover your profile. All your current posts, comments and likes will be deleted permanently.</p>
            <hr>
            <button type="button" class="btn btn-danger"><a class="link" href="profile_delete.php?profileDelete=<?php echo $id?>" name="profileDelete">Delete</a></button>
            <button type="button" class="btn btn-warning"><a class="link" href="profile.php" name="post">Cancel and go back</a></button>
        </div>
        
    </main>

<?php include('footer.php')?>