<?php include('header.php');
include('db.php');

if(isset($_GET['edit'])){
    $id = mysqli_real_escape_string($connection, $_GET['edit']);
    $sql = "SELECT * FROM all_posts WHERE id='$id';";
    $result = mysqli_query($connection, $sql);
    $post = mysqli_fetch_assoc($result);
    $title = $post['title'];
    $body = $post['body'];
} 

?>

    <main>

        <h3>Edit your post</h3>
        <div class="cardWrap card bg-light mb-3">
            <div class="card-header">Post title</div>
            <form method="POST" action="post_edit.php" enctype="multipart/form-data">
                <div class="form-group">                    
                <input type="hidden" name="id" value="<?php echo $id ?>"/>
                <input class="inputField" type="text" name="title" value="<?php echo $title ?>"/>
                </div>
                <div class="card-header">Upload a new image</div>
                <div class="form-group">
                    <input type="file" name="editFile">
                    <small id="emailHelp" class="form-text text-muted">Please note, your current image will be replaced</small>
                    <small id="emailHelp" class="form-text text-muted">Supported file types: jpeg, jpg, png, gif</small>
                </div>
                <div class="card-header">Enter your post here</div>
                <div class="form-group">               
                    <textarea class="addPost" name="postBody"><?php echo $body ?></textarea>
                </div>
                <button type="submit" class="btn btn-success" name="update">Update</button>
                <button type="button" class="btn btn-warning"><a class="link" href="post.php?post=<?php echo $id?>" name="post">Cancel and go back</a></button>    
            </form>            
            <?php include('errors.php')?>
        </div>

    </main>

<?php include('footer.php')?>