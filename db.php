<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once './PHPMailer/src/Exception.php';
require_once './PHPMailer/src/PHPMailer.php';
require_once './PHPMailer/src/SMTP.php';

if(!isset($_SESSION)){
    session_start();
}

$serverName = 'to-be-replaced-with-actual-details';
$dbUsername = 'to-be-replaced-with-actual-details';
$dbPassword = 'to-be-replaced-with-actual-details';
$dbName = 'to-be-replaced-with-actual-details';

$connection = mysqli_connect($serverName, $dbUsername, $dbPassword, $dbName);

$errors = array();
$username = '';
$email = '';
$header = '';
$body = '';


if(isset($_POST['register'])){

    $username = mysqli_real_escape_string($connection, $_POST['username']);
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $password = mysqli_real_escape_string($connection, $_POST['password']);
    $password2 = mysqli_real_escape_string($connection, $_POST['password2']);

    if(empty($username)){
        array_push($errors, "Username is required");
    } 
    if(empty($email)){
        array_push($errors, "Email is required");
    }
    if(empty($password) || empty($password2)){
        array_push($errors, "Please fill in both passwords");
    }

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        array_push($errors, "Please enter a valid email");
    }

    if($password != $password2){
        array_push($errors, "Passwords don't match");
    }

    $sql = "SELECT * FROM users WHERE username=? OR email=? LIMIT 1;";
    
    $stmt = mysqli_stmt_init($connection);
    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $username, $email);
    mysqli_stmt_execute($stmt);
    $results = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($results);

    if($user){
        if($user['username'] == $username){
            array_push($errors, "This username is already taken");
        }
        if($user['email'] == $email){
            array_push($errors, "This email is already registered");
        }
    }

    $file = $_FILES['profile'];

    $fileName = $_FILES['profile']['name'];
    $fileTmpName = $_FILES['profile']['tmp_name'];
    $fileSize = $_FILES['profile']['size'];
    $fileError = $_FILES['profile']['error'];
    $fileType = $_FILES['profile']['type'];

    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));

    $allowed = array('jpg', 'jpeg', 'png', 'gif');

    if(!isset($_FILES['profile']) || $_FILES['profile']['error'] == UPLOAD_ERR_NO_FILE) {
        $fileNameNew = null;
    } else {
        if(in_array($fileActualExt, $allowed)){
            if($fileError == 0){
                if($fileSize < 500000){
                    $fileNameNew = uniqid('', true).".".$fileActualExt;
                    $fileDestination = 'images/profile/'.$fileNameNew;
                    move_uploaded_file($fileTmpName, $fileDestination);                
                } else {
                    array_push($erorr, "Size file is too big");
                }
            } else {
                array_push($errors, "There was an error uploading your file");
            }
        } else {
            array_push($errors, "You can only upload jpg, jpeg or png files");
        }
    }
    
    if(count($errors) == 0){
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username, email, password, profile) VALUES (?, ?, ?, ?);";

        $stmt = mysqli_stmt_init($connection);
        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_bind_param($stmt, "ssss", $username, $email, $hashedPassword, $fileNameNew);
        mysqli_stmt_execute($stmt);
        $_SESSION['message'] = 'Successfully registered. Please use the login form below to sign in';
        header('Location: login.php');
    }
}

if(isset($_POST['login'])){
    $username = mysqli_real_escape_string($connection, $_POST['username']);
    $password = mysqli_real_escape_string($connection, $_POST['password']);
    
    if(empty($username)){
        array_push($errors, "Username is required");
    }
    if(empty($password)){
        array_push($errors, "Password is required");
    }

    if(count($errors) == 0){
        $sql = "SELECT * FROM users WHERE username=? OR email=?";

        $stmt = mysqli_stmt_init($connection);
        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $username, $username);
        mysqli_stmt_execute($stmt);
        $results = mysqli_stmt_get_result($stmt);

        if(mysqli_num_rows($results) == 1){

            if($row = mysqli_fetch_assoc($results)){
                $passwordCheck = password_verify($password, $row['password']);

                if($passwordCheck){
                    $_SESSION['id'] = $row['id'];
                    $_SESSION['username'] = $row['username'];
  	                $_SESSION['success'] = "You are now logged in";
  	                header('Location: index.php');
                } else {
                    array_push($errors, "Wrong password. Please try again");
                }
            }          
        }  else {
            array_push($errors, "This user is not found");
        }

    }

}


if(isset($_POST['reset'])){

    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $sql = "SELECT * FROM users WHERE email=?;";
    $stmt = mysqli_stmt_init($connection);
    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $results = mysqli_stmt_get_result($stmt);

    if(empty($email)){
        array_push($errors, "Email is required");
    }
    if(mysqli_num_rows($results) <= 0) {
        array_push($errors, "This email is not registered in our system");
    }

    $token = bin2hex(random_bytes(50));

    if(count($errors) == 0) {
        $sql = "INSERT INTO pwdReset (email, token) VALUES (?, ?);";

        $stmt = mysqli_stmt_init($connection);
        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $email, $token);
        mysqli_stmt_execute($stmt);

        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'ssl';
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = '465';
        $mail->isHTML();
        $mail->Username = 'to-be-replaced-with-actual-details';
        $mail->Password = 'to-be-replaced-with-actual-details';
        $mail->SetFrom('no-reply@blogapp.com');
        $mail->Subject = "Blog app: password reset";
        $mail->Body = "Hi there! We received a request to reset your password. <br> Please click on this <a href='https://kates-blog-app.herokuapp.com/new_password.php?token=$token'>link</a> and follow further instructions. If you didn't request this, please ignore this email.";
        $mail->AddAddress($email);

        $mail->Send();

        header('Location: pending.php');
    }
}


if(isset($_POST['new_password'])){
    $password = mysqli_real_escape_string($connection, $_POST['password']);
    $password2 = mysqli_real_escape_string($connection, $_POST['password2']);
    $token = $_SESSION['token'];
     
    if(empty($password) || empty($password2)){        
        array_push($errors, "Password required");
    }

    if($password != $password2){        
        array_push($errors, "Passwords don't match");
    }

    if(count($errors) == 0){
        $sql = "SELECT email FROM pwdReset WHERE token='$token' LIMIT 1;";
        $results = mysqli_query($connection, $sql);
        $email = mysqli_fetch_assoc($results)['email'];

        if($email){
            $new_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "UPDATE users SET password=? WHERE email='$email';";
            $stmt = mysqli_stmt_init($connection);
            mysqli_stmt_prepare($stmt, $sql);
            mysqli_stmt_bind_param($stmt, "s", $new_password);
            mysqli_stmt_execute($stmt);

            header('Location: login.php');
        }
    }
}


if(isset($_POST['publish'])){

    $title = mysqli_real_escape_string($connection, $_POST['title']);   
    $postBody = str_replace("\r\n", '<br/>', $_POST['postBody']);

    $file = $_FILES['file'];

    $fileName = $_FILES['file']['name'];
    $fileTmpName = $_FILES['file']['tmp_name'];
    $fileSize = $_FILES['file']['size'];
    $fileError = $_FILES['file']['error'];
    $fileType = $_FILES['file']['type'];

    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));

    $allowed = array('jpg', 'jpeg', 'png', 'gif');

    if(!isset($_FILES['file']) || $_FILES['file']['error'] == UPLOAD_ERR_NO_FILE) {
        $fileNameNew = NULL;
    } else {
        if(in_array($fileActualExt, $allowed)){
            if($fileError == 0){
                if($fileSize < 500000){
                    $fileNameNew = uniqid('', true).".".$fileActualExt;
                    $fileDestination = 'images/uploads/'.$fileNameNew;
                    move_uploaded_file($fileTmpName, $fileDestination);                
                } else {
                    array_push($erorr, "Size file is too big");
                }
            } else {
                array_push($errors, "There was an error uploading your file");
            }
        } else {
            array_push($errors, "You can only upload jpg, jpeg or png files");
        }
    }         
  
    if(empty($title)){
        array_push($errors, "Title can't be empty");
    }
    if(empty($postBody)){
        array_push($errors, "Blog post can't be empty");
    }

    if(count($errors) == 0){

        $username = $_SESSION['username'];

        $query = "SELECT * FROM users WHERE username='$username';";
        $results = mysqli_query($connection, $query);
        $id = mysqli_fetch_assoc($results)['id'];

        if($id){

            $sql = "INSERT INTO all_posts (user_id, title, body, created_at, image, username) VALUES (?, ?, ?, now(), '$fileNameNew', '$username');";

            $stmt = mysqli_stmt_init($connection);
            mysqli_stmt_prepare($stmt, $sql);
            mysqli_stmt_bind_param($stmt, "sss", $id, $title, $postBody);
            mysqli_stmt_execute($stmt);

            $_SESSION['message'] = "Post created successfully";
            header('Location: index.php');

        }

    }
}

if(isset($_GET['delete'])){
    $id = mysqli_real_escape_string($connection, $_GET['delete']);
    $sql = "DELETE FROM all_posts WHERE id='$id';";
    mysqli_query($connection, $sql);
    $query = "DELETE FROM comments WHERE postId='$id';";
    mysqli_query($connection, $query);
    $_SESSION['message'] = "Post successfully deleted";
    header('Location: index.php');
}


if(isset($_POST['update'])){
    
    $id = mysqli_real_escape_string($connection, $_POST['id']);
    $title = mysqli_real_escape_string($connection, $_POST['title']);   
    $body = str_replace("\r\n", '<br/>', $_POST['postBody']);

    $file = $_FILES['editFile'];

    $fileName = $_FILES['editFile']['name'];
    $fileTmpName = $_FILES['editFile']['tmp_name'];
    $fileSize = $_FILES['editFile']['size'];
    $fileError = $_FILES['editFile']['error'];
    $fileType = $_FILES['editFile']['type'];

    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));

    $allowed = array('jpg', 'jpeg', 'png', 'gif');

    if(empty($title)){
        array_push($errors, "Title can't be empty");
    }
    if(empty($body)){
        array_push($errors, "Your post can't be empty");
    }

    if(!isset($_FILES['editFile']) || $_FILES['editFile']['error'] == UPLOAD_ERR_NO_FILE) {
        if(count($errors) == 0){

            $sql = "UPDATE all_posts SET title=?, body=? WHERE id='$id';";
            $stmt = mysqli_stmt_init($connection);
            mysqli_stmt_prepare($stmt, $sql);
            mysqli_stmt_bind_param($stmt, "ss", $title, $body);
            mysqli_stmt_execute($stmt);
        
            header('Location: post.php?post='.$id);     
    
        }
    } else {
        if(in_array($fileActualExt, $allowed) && count($errors) == 0){
            if($fileError == 0){
                if($fileSize < 500000){
                    $fileNameNew = $id.".".$fileActualExt;
                    $fileDestination = 'images/uploads/'.$fileNameNew;
                    move_uploaded_file($fileTmpName, $fileDestination);    
                    $sql = "UPDATE all_posts SET title=?, body=?, image='$fileNameNew' WHERE id='$id';"; 
                    $stmt = mysqli_stmt_init($connection);
                    mysqli_stmt_prepare($stmt, $sql);
                    mysqli_stmt_bind_param($stmt, "ss", $title, $body);
                    mysqli_stmt_execute($stmt);
  
                    header('Location: post.php?post='.$id);             
                } else {
                    array_push($errors, "Size file is too big");
                }
            } else {
                array_push($errors, "There was an error uploading your file");
            }
        } else {
            array_push($errors, "You can only upload jpg, jpeg or png files");
        }
    }

}


if(isset($_POST['addComment'])){
    $comment = str_replace("\r\n", '<br/>', $_POST['comment']);
    $commentUser = $_SESSION['username'];
    $commentUserId = $_SESSION['id'];
    $postId = $_POST['id'];

    if(empty($comment)){
        array_push($errors, "Comment can't be empty");
        $_SESSION['comment'] = "Comment can't be empty";  
        header('Location: post.php?post='.$postId);
        exit();
    }

    if(count($errors) == 0){
        $sql = "INSERT INTO comments (comment, commentUser, commentUserId, postId, created_at) VALUES (?, '$commentUser', '$commentUserId', '$postId', now());";
        $stmt = mysqli_stmt_init($connection);
        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_bind_param($stmt, "s", $comment);
        mysqli_stmt_execute($stmt);

        $_SESSION['comment'] = "Comment has been added";  
        header('Location: post.php?post='.$postId);
        exit();
    }
    
}


if(isset($_GET['deleteComment'])){
    $id = $_GET['deleteComment'];
    $post = $_GET['post'];
    $sql = "DELETE FROM comments WHERE id='$id';";
    mysqli_query($connection, $sql);
    $_SESSION['comment'] = "Comment successfully deleted";
    header('Location: post.php?post='.$post);
}


if(isset($_POST['updateComment'])){
    
    $id = $_POST['id'];
    $post = $_POST['post'];
    $body = str_replace("\r\n", '<br/>', $_POST['commentBody']);
    
    if(empty($body)){
        array_push($errors, "Your comment can't be empty");
        $_SESSION['comment'] = "Your comment can't be empty";
    }

    if(count($errors) == 0){
        $sql = "UPDATE comments SET comment=? WHERE id='$id';"; 
        $stmt = mysqli_stmt_init($connection);
        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_bind_param($stmt, "s", $body);
        mysqli_stmt_execute($stmt);
 
        $_SESSION['comment'] = "Comment has been updated";  
        header('Location: post.php?post='.$post);  
    }

}

if(isset($_GET['like'])){
    
    $userId = $_SESSION['id'];
    $likedby = $_SESSION['username'];
    $postId = $_GET['like'];
    $sql = "INSERT INTO likes (user_id, post_id, likedby) VALUES ('$userId', '$postId', '$likedby');";
    mysqli_query($connection, $sql);    
    header('Location: post.php?post='.$postId);    

}


if(isset($_GET['unlike'])){
    
    $userId = $_SESSION['id'];
    $postId = $_GET['unlike'];
    $sql = "DELETE FROM likes WHERE post_id='$postId';";
    mysqli_query($connection, $sql);
    $_SESSION['liked'] = false;
    header('Location: post.php?post='.$postId);    

}

if(isset($_POST['password_reset'])){
    $userId = $_SESSION['id'];
    $curPassword = mysqli_real_escape_string($connection, $_POST['curpassword']);
    $password = mysqli_real_escape_string($connection, $_POST['password']);
    $password2 = mysqli_real_escape_string($connection, $_POST['password2']);

    $query = "SELECT * FROM users WHERE id='$userId';";
    $result = mysqli_query($connection, $query);
    $row = mysqli_fetch_assoc($result);
    $passwordCheck = password_verify($curPassword, $row['password']);

    if(!$passwordCheck){
        array_push($errors, "Current password doesn't match our record");
    }

    if($curPassword == $password){
        array_push($errors, "New password needs to be different from current one");
    }             

    if(empty($password) || empty($password2)){        
        array_push($errors, "Password required");
    }

    if($password != $password2){        
        array_push($errors, "Passwords don't match");
    }

    if(count($errors) == 0){

        $new_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET password=? WHERE id='$userId';";
        $stmt = mysqli_stmt_init($connection);
        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_bind_param($stmt, "s", $new_password);
        mysqli_stmt_execute($stmt);

        $_SESSION['message'] = "Password has been changed successfully";
        header('Location: index.php');
    }
}


if(isset($_POST['profile'])){
    
    $id = $_POST['id'];
       
    $file = $_FILES['newProfile'];

    $fileName = $_FILES['newProfile']['name'];
    $fileTmpName = $_FILES['newProfile']['tmp_name'];
    $fileSize = $_FILES['newProfile']['size'];
    $fileError = $_FILES['newProfile']['error'];
    $fileType = $_FILES['newProfile']['type'];

    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));

    $allowed = array('jpg', 'jpeg', 'png', 'gif');

    if(!isset($_FILES['newProfile']) || $_FILES['newProfile']['error'] == UPLOAD_ERR_NO_FILE) {
        array_push($errors, "No file chosen");
    } else {
        if(in_array($fileActualExt, $allowed) && count($errors) == 0){
            if($fileError == 0){
                if($fileSize < 500000){
                    $fileNameNew = $id.".".$fileActualExt;
                    $fileDestination = 'images/profile/'.$fileNameNew;
                    move_uploaded_file($fileTmpName, $fileDestination);    
                    $sql = "UPDATE users SET profile='$fileNameNew' WHERE id='$id';";                                    
                    mysqli_query($connection, $sql);     
                    header('Location: profile.php');             
                } else {
                    array_push($errors, "Size file is too big");
                }
            } else {
                array_push($errors, "There was an error uploading your file");
            }
        } else {
            array_push($errors, "You can only upload jpg, jpeg or png files");
        }
    }

}

if(isset($_POST['deletePic'])){

    $id = $_POST['id'];
    $pic = null;
    $sql = "UPDATE users SET profile='$pic' WHERE id='$id';";
    mysqli_query($connection, $sql);
    header('Location: profile.php');
    
}


if(isset($_GET['profileDelete'])){

    $id = mysqli_real_escape_string($connection, $_GET['profileDelete']);
    $sql = "DELETE FROM users WHERE id='$id';";
    $sqlComments = "DELETE FROM comments WHERE commentUserId='$id';";
    $sqlLikes = "DELETE FROM likes WHERE user_id='$id';";
    $sqlPosts = "DELETE FROM all_posts WHERE user_id='$id'";
    mysqli_query($connection, $sql);
    mysqli_query($connection, $sqlComments);
    mysqli_query($connection, $sqlLikes);
    mysqli_query($connection, $sqlPosts);    
    $_SESSION['message'] = "Your profile has been deleted";
    $_SESSION['is_comment_owner'] = false;    
    unset($_SESSION['username']);
    unset($_SESSION['id']);
    header('Location: index.php');
    
}
