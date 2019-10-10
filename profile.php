<?php 
include('header.php'); 
include('db.php');

$id = $_SESSION['id'];
$sql = "SELECT * FROM users WHERE id='$id';";
$results = mysqli_query($connection, $sql);
$row = mysqli_fetch_array($results);

$query = "SELECT COUNT(*) FROM all_posts WHERE user_id='$id';";
$all = mysqli_query($connection, $query);
$res = mysqli_fetch_array($all);

$query2 = "SELECT COUNT(*) FROM comments WHERE commentUserId='$id';";
$allComments = mysqli_query($connection, $query2);
$resComments = mysqli_fetch_array($allComments);

?>

    <main>

        <h2>My profile</h2>

        <div class="cardWrap card bg-light mb-3">
        
            <?php if($row['profile']){ ?>
                    <img class="profileImg" src='images/profile/<?php echo $row['profile']?>'>
            <?php } else {?>
                    <img class="profileImg" src='images/no-profile-picture.jpg'>
            <?php } ?>

            <div class="card-header">Change profile picture:</div>
            <form method="POST" action="profile.php" enctype="multipart/form-data">
                <div class="form-group">
                    <input type="hidden" name="id" value="<?php echo $id ?>"/>
                    <input type="file" name="newProfile">
                    <small id="emailHelp" class="form-text text-muted">Supported file types: jpeg, jpg, png, gif</small>
                    <br/>
                    <button type="submit" class="btn btn-success" name="profile">Update</button>
                    <button type="submit" class="btn btn-warning" name="deletePic">Delete profile picture</button>
                    <?php include('errors.php')?>
                </div>
                
                <div class="card-header">Profile information:</div>
                <div class="form-group">    
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><p class="info"><i class="fas fa-user-circle"></i>My username</p> <?php echo $_SESSION['username']?></li>
                        <li class="list-group-item"><p class="info"><i class="fa fa-envelope-open"></i>My email:</p> <?php echo $row['email']?></li>
                        <li class="list-group-item"><p class="info"><i class="far fa-calendar-alt"></i>Registration date:</p> <?php echo $row['created_at']?></li>
                        <li class="list-group-item"><p class="info"><i class="fas fa-pen-fancy"></i>Total posts:</p> <?php echo $res[0]?></li>
                        <li class="list-group-item"><p class="info"><i class="fas fa-pencil-alt"></i>Total comments:</p> <?php echo $resComments[0]?></li>
                    </ul>          
                </div>    
                <div class="card-header">Profile action:</div>
                <div class="form-group">     
                    <a href="password_reset.php" class="list-group-item list-group-item-action list-group-item-warning">Change my password</a>
                    <a href="profile_delete.php?deleteProfile=<?php echo $id?>" name="deleteProfile" class="list-group-item list-group-item-action list-group-item-danger">Delete my profile</a>          
                </div>             
            </form>
        </div>
    
    </main>

<?php include('footer.php')?>
