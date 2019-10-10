<?php 
include('header.php');
include('db.php');

if(isset($_GET['commentEdit'])){
    $id = $_GET['commentEdit'];
    $post = $_GET['post'];
    $sql = "SELECT * FROM comments WHERE id='$id';";
    $result = mysqli_query($connection, $sql);
    $comment = mysqli_fetch_assoc($result);
    $body = $comment['comment'];
} 

?>

    <main>

        <h3>Edit your comment</h3>

            <div class="cardWrap card bg-light mb-3">
                <form method="POST" action="comment_edit.php" enctype="multipart/form-data">
                    <input class="inputField" type="hidden" name="id" value="<?php echo $id ?>"/>
                    <input class="inputField" type="hidden" name="post" value="<?php echo $post ?>"/>
                    <textarea class="addPost" name="commentBody"><?php echo $body ?></textarea>
                    <button class="btn btn-success" type="submit" name="updateComment">Update</button>   
                    <button type="button" class="btn btn-warning"><a class="link" href="post.php?post=<?php echo $post?>" name="post">Cancel and go back</a></button> 
                </form>                
            </div>
                    
            <?php include('errors.php')?>
            
    </main>


<?php include('footer.php')?>