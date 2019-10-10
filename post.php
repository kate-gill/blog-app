<?php 
include('header.php');
include('db.php'); 

$_SESSION['is_owner'] = false;
$_SESSION['is_comment_owner'] = false;

if(isset($_GET['post'])){

    $id = mysqli_real_escape_string($connection, $_GET['post']);
    $sql = "SELECT * FROM all_posts WHERE id='$id';";
    $result = mysqli_query($connection, $sql);
    $post = mysqli_fetch_assoc($result);
    $posted_by = $post['user_id'];

    $user_sql = "SELECT * from users WHERE id='$posted_by';";
    $users = mysqli_query($connection, $user_sql);
    $user_row = mysqli_fetch_assoc($users)['username'];

    if(isset($_SESSION['id'])){
        if($_SESSION['id'] == $post['user_id']){
            $_SESSION['is_owner'] = true;
        } 
    };            

    if(isset($_SESSION['id'])){

        $userId = $_SESSION['id'];
        $query = "SELECT * FROM likes WHERE user_id='$userId' AND post_id='$id';";
        $all_results = mysqli_query($connection, $query);
        $row = mysqli_fetch_array($all_results);
        
        if($row >= 1){
            $_SESSION['liked'] = true;
        } else {
            $_SESSION['liked'] = false; 
        }
    }
    
    $likesQuery = "SELECT COUNT(*) FROM likes WHERE post_id='$id';";
    $all_likes = mysqli_query($connection, $likesQuery);
    $likesResult = mysqli_fetch_array($all_likes);
  
    if($likesResult != NULL){
        $likes = $likesResult[0];
    } else {
        $likes = 0;
    }

}

?>
    <main>

        <div class="cardMain card text-center">

            <div class="titleHeader card-header">          
                <?php                 
                if($_SESSION['is_owner']){  ?>
                    <i class='fas fa-user'></i><p class="author">Post by: <a href="profile.php"><?php echo $user_row ?></a></p>
                <?php } else {?>
                    <i class='fas fa-user'></i><p class="author">Post by: <a name="userprofile" href="user_profile.php?userprofile=<?php echo $post['user_id']?>"><?php echo $user_row ?></a></p>
                <?php } ?>               
                
                <?php if($_SESSION['is_owner']){  
                        if($likes > 0){?>
                        <i class="far fa-heart"></i><p class="likes"><a class="likesLink" name="likedby" href="likedby.php?likedby=<?php echo $id?>">Likes: <?php  echo $likes ?></a></p> 
                    <?php } else {?>
                        <i class="far fa-heart"></i><p class="likes">Likes: <?php  echo $likes ?></p>
                    <?php } ?>
                        <button type="button" class="postbtn btn btn-danger"><a href="post_delete.php?deleteConfirm=<?php echo $id?>" name="deleteConfirm">Delete</a></button>
                        <button type="button" class="postbtn btn btn-warning"><a href="post_edit.php?edit=<?php echo $id?>" name="edit">Edit</a></button>
                    <?php } else {                 
                        if($likes > 0){?>
                        <i class="far fa-heart"></i><p class="likes"><a class="likesLink" name="likedby" href="likedby.php?likedby=<?php echo $id?>">Likes: <?php  echo $likes ?></a></p> 
                    <?php } else {?>
                        <i class="far fa-heart"></i><p class="likes">Likes: <?php  echo $likes ?></p> 
                    <?php }                              
                        if(isset($_SESSION['username'])){ ?>
                        <?php if($_SESSION['liked'] == true){ ?>
                            <button type="button" class="btnLike btn btn btn-info"><a href="post.php?unlike=<?php echo $id?>" name="unlike">Unlike</a></button>
                        <?php } elseif ($_SESSION['liked'] == false) { ?>
                            <button type="button" class="btnLike btn btn btn-info"><a href="post.php?like=<?php echo $id?>" name="like">Like</a> </button>                     
                        <?php } 
                        } 
                    } ?>

                <div class="date text-muted">
                    <i class='fas fa-thumbtack'></i>Posted on: <?php echo $post['created_at']?>
                </div>

            </div>

            <div class="card-body">
                <h4 class="card-title"><?php echo $post['title']?></h4>
                <?php if($post['image']){ ?>
                    <img class="postImg" src='images/uploads/<?php echo $post['image']?>'>
                <?php } ?>                
                <p class="postMain"><?php echo $post['body']?></p>
            </div>

            <div class="commentFooter card-footer">
                <h5 class="card-title">Comments</h5>
                <?php if(isset($_SESSION['username'])){?>
                <form method="POST" action="post.php">
                    <input type="hidden" name="id" value="<?php echo $_GET['post']?>"/>
                    <textarea class="comment" name="comment"></textarea>
                    <br/>
                    <button type="submit" class="btn btn-success" name="addComment">Add comment</button>
                </form>
            <br/>
            <?php } ?>

            <?php   
                $id = mysqli_real_escape_string($connection, $_GET['post']);
                $sql = "SELECT * FROM comments WHERE postId='$id';";
                $result = mysqli_query($connection, $sql);                    

               if(isset($_SESSION['comment'])){ ?>
                    <div class="alert alert-info" role="alert">
                        <?php echo $_SESSION['comment']; ?>
                    </div>            
                <?php unset($_SESSION['comment']);
                }
                                
                while($row = mysqli_fetch_assoc($result)){ 

                    if(isset($_SESSION['comment'])){ ?>
                        <div class="alert alert-info" role="alert">
                            <?php echo $_SESSION['comment']; ?>
                        </div>            
                    <?php unset($_SESSION['comment']);
                    }
                    
                    if(isset($_SESSION['id'])){
                        if($_SESSION['id'] == $row['commentUserId']){
                            $_SESSION['is_comment_owner'] = true;
                        } else {
                            $_SESSION['is_comment_owner'] = false;
                        }
                    }

                    if($_SESSION['is_comment_owner'] == true){ ?>
                    
                        <div class="commentBox card">
                            <div class="topCard card-header"><i class='fas fa-user'></i> Comment by: <a href="profile.php"><?php echo $row['commentUser'] ?></a>
                                <button type="button" class="btnComment postbtn btn-sm btn-warning"><a href="comment_edit.php?commentEdit=<?php echo $row['id']?>&post=<?php echo $row['postId']?>" name="commentEdit">Edit</a></button>
                                <button type="button" class="btnComment postbtn btn-sm btn-danger"><a href="comment_delete.php?deleteCommentConfirm=<?php echo $row['id']?>&post=<?php echo $row['postId']?>" name="deleteCommentConfirm">Delete</a></button>
                            </div>
                                <div class="card-body">
                                    <p><?php echo $row['comment']?></p>
                                </div>        
                            <div class="footCard text-muted card-footer"><i class='fas fa-thumbtack'></i>Posted on: <?php echo $row['created_at']?></div>                            
                        </div>   

                <?php } elseif ($_SESSION['is_comment_owner'] == false) {?>   
                    
                        <div class="commentBox card">
                            <div class="topCard card-header"><i class='fas fa-user'></i> Comment by: <a name="userprofile" href="user_profile.php?userprofile=<?php echo $row['commentUserId']?>"><?php echo $row['commentUser'] ?></a>                            
                            </div>
                                <div class="card-body">
                                    <p><?php echo $row['comment']?></p>
                                </div>        
                            <div class="footCard text-muted card-footer"><i class='fas fa-thumbtack'></i>Posted on: <?php echo $row['created_at']?></div>                            
                        </div>   

                <?php } 
                } 
                    if(!isset($_SESSION['username'])){ ?>
                        <h6 class="nocomment">Please login to leave a comment</h6>
                <?php } ?>

                <br/>
                <a href="index.php">Back to all posts</a>   

            </div>

        </div>

    </main>

<?php include('footer.php')?>
