<?php 
include('header.php');
include('db.php');

if(isset($_GET['deleteConfirm'])){
    $id = $_GET['deleteConfirm'];
}

?>

    <main>

        <div class="alert alert-danger" role="alert">
            <h4 class="alert-heading">Are you sure you want to delete this post permemantly?</h4>
            <p>This action cannot be undone</p>
            <hr>
            <button type="button" class="btn btn-danger"><a href="post_delete.php?delete=<?php echo $id?>" name="delete">Delete</a></button>
            <button type="button" class="btn btn-warning"><a href="post.php?post=<?php echo $id?>" name="post">Cancel and go back</a></button>
        </div>  
        
    </main>

<?php include('footer.php')?>