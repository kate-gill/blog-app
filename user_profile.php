<?php 
include('header.php'); 
include('db.php');

if(isset($_GET['userprofile'])){

    $id = mysqli_real_escape_string($connection, $_GET['userprofile']);
    $sql = "SELECT * FROM users WHERE id='$id';";
    $results = mysqli_query($connection, $sql);
    $row = mysqli_fetch_array($results);

    $query = "SELECT COUNT(*) FROM all_posts WHERE user_id='$id';";
    $all = mysqli_query($connection, $query);
    $res = mysqli_fetch_array($all);

    $query2 = "SELECT COUNT(*) FROM comments WHERE commentUserId='$id';";
    $allComments = mysqli_query($connection, $query2);
    $resComments = mysqli_fetch_array($allComments);
    
}

?>

    <main>

        <h2>User profile</h2>

        <div class="cardWrap card bg-light mb-3">
        
        <?php if($row['profile']){ ?>
                <img class="profileImg" src='images/profile/<?php echo $row['profile']?>'>
        <?php } else {?>
                <img class="profileImg" src='images/no-profile-picture.jpg'>
        <?php } ?>
        
        <div class="card-header"><h4><i class="fas fa-user-circle"></i><?php echo $row['username']?></h4></div>
        <div class="form-group">    
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><p class="info"><i class="far fa-calendar-alt"></i>Registration date:</p> <?php echo $row['created_at']?></li>
                <li class="list-group-item"><p class="info"><i class="fas fa-pen-fancy"></i>Total posts:</p> <?php echo $res[0]?></li>
                <li class="list-group-item"><p class="info"><i class="fas fa-pencil-alt"></i>Total comments:</p> <?php echo $resComments[0]?></li>
            </ul>          
        </div>               

    </main>

<?php include('footer.php')?>