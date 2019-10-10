<?php include('header.php');

if(!isset($_SESSION['username'])){
    header('Location: login.php');
}

if(isset($_POST['title'])){
    $title = $_POST['title'];
} else {
    $title = '';
}

if(isset($_POST['postBody'])){
    $postBody = $_POST['postBody'];
} else {
    $postBody = '';
}

?>

    <main>

        <h2>Add New Post</h2>

        <div class="cardWrap card bg-light mb-3">
            <div class="card-header">Title of your post</div>
            <form method="POST" action="addPost.php" enctype="multipart/form-data">
                <div class="form-group">
                    <input class="inputField" type="text" name="title" placeholder="Post title" value="<?php echo $title?>"/>
                </div>
                <div class="card-header">Upload an image</div>
                <div class="form-group">
                    <input type="file" name="file">
                    <small id="emailHelp" class="form-text text-muted">Supported file types: jpeg, jpg, png, gif</small>
                </div>
                <div class="card-header">Your post</div>
                <div class="form-group">               
                    <textarea class="addPost" name="postBody"><?php echo $postBody?></textarea>
                </div>
                <button type="submit" class="btn btn-success" name="publish">Post</button>
            </form>
        </div>

        <?php include('errors.php')?>

    </main>

<?php include('footer.php')?>