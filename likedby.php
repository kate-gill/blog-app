<?php 
include('header.php');
include('db.php');

if(isset($_GET['likedby'])){

    $likedby = $_GET['likedby'];
    $sql = "SELECT * FROM likes WHERE post_id='$likedby';";
    $results = mysqli_query($connection, $sql);
} 

?>
    <main>
        <h3><i class="fas fa-hand-holding-heart"></i> Liked by:</h3>

        <div class="likesCard card text-white bg-info mb-3">
            <div class="card-body">
            <?php while($row = mysqli_fetch_assoc($results)){ ?>
                <?php if(isset($_SESSION['id']) && $_SESSION['id'] == $row['user_id']){  ?>
                        <a class="likesLink" href="profile.php"><?php echo $row['likedby']?></a>
                        <br>
                <?php } else {?>
                        <a class="likesLink" name="userprofile" href="user_profile.php?userprofile=<?php echo $row['user_id']?>"><?php echo $row['likedby']?></a>
                        <br>
                <?php } 
                } ?>
            </div>
        </div>
        
        <button type="button" class="btn btn-info"><a class="link" href="post.php?post=<?php echo $likedby?>">Go back</a></button>
        
    </main>



<?php include('footer.php')?>

