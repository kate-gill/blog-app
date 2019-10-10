<?php 
include('header.php'); 
include('db.php');

$sql = "SELECT * FROM all_posts ORDER BY created_at DESC";
$results = mysqli_query($connection, $sql);

?>

    <main>

        <?php 
            if(isset($_SESSION['message'])){ ?>
                <div class="alert alert-success" role="alert">
                    <p><?php echo $_SESSION['message']?></p>
                </div>                
                <?php unset($_SESSION['message']);
            } 

            if(isset($_SESSION['success'])){ ?>
                <div class="alert alert-success" role="alert">
                    <p><?php echo $_SESSION['success']?></p>
                </div>                  
                <?php unset($_SESSION['success']);  
            }  

            if(!isset($_SESSION['username'])){ ?>
                <h2 class="header">Welcome!</h2>
                <p>Please login to add a new post!</p>
            <?php } else {?>
                <h2 class="header">Hi there, <?php echo $_SESSION['username']?>!</h2>  
                <p>Check out the latest posts below:</p>      
        <?php } 
                
        while($row = mysqli_fetch_assoc($results)){ ?>
            <div class="container">
                <div class="card text-center">
                    <div class="card-header">
                    <?php                 
                        if(!isset($_SESSION['username'])){  ?>
                            <i class='fas fa-thumbtack'></i></i> Posted by: <a name="userprofile" href="user_profile.php?userprofile=<?php echo $row['user_id']?>"><?php echo $row['username']?></a>
                        <?php } elseif ($_SESSION['username'] == $row['username']) {?>
                            <i class='fas fa-thumbtack'></i> Posted by: <a href="profile.php"><?php echo $row['username']?></a>
                        <?php } elseif (isset($_SESSION['username'])) {?>
                            <i class='fas fa-thumbtack'></i> Posted by: <a name="userprofile" href="user_profile.php?userprofile=<?php echo $row['user_id']?>"><?php echo $row['username']?></a>
                        <?php } ?>
                    </div>
                    <div class="card-body">
                        <?php if($row['image']){ ?>
                            <img class="mainImg" src='images/uploads/<?php echo $row['image']?>'>
                        <?php } ?>      
                        <h5 class="card-title"><?php echo $row['title']?></h5>
                        <p class="card-text text"><?php echo $row['body']?></p>
                        <a href="post.php?post=<?php echo $row['id']?>" name="post" class="btn btn-primary">Read post</a>
                    </div>
                    <div class="frontFooter card-footer text-muted">
                        <i class="fas fa-clock"></i>Posted on: <?php echo $row['created_at']?>
                    </div>
                </div>
            </div>
        <?php } ?>

    </main>
    
<?php include('footer.php')?>