<?php
include('db.php');

if($errors){
    foreach($errors as $error){ ?>
    <div class="alert alert-danger" role="alert">
        <p><?php echo $error?></p>
    </div>
<?php } 
    } ?>



