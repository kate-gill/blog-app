<?php 
include('header.php') ;
include('db.php');

if(isset($_GET['deleteCommentConfirm'])){
    $id = $_GET['deleteCommentConfirm'];
    $post = $_GET['post'];
}

?>

    <main>
    
        <div class="alert alert-danger" role="alert">
            <h4 class="alert-heading">Are you sure you want to delete this comment permemantly?</h4>
            <p>This action cannot be undone</p>
            <hr>
            <button type="button" class="btn btn-danger"><a href="comment_delete.php?deleteComment=<?php echo $id?>&post=<?php echo $post?>" name="deleteComment">Delete</a></button>
            <button type="button" class="btn btn-warning"><a href="post.php?post=<?php echo $post?>" name="post">Cancel and go back</a></button>
        </div>       
        
    </main>



<?php include('footer.php')?>