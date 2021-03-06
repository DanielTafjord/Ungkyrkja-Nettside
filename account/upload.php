<?php
include('error.php');
# connect to database
$con = mysqli_connect('localhost','ungkyrkja','ungkyrkja','ungkyrkja');
if($con->connect_errno){
  error::report('upload.php', $con->connect_errno, 'fatal', $_SERVER['REMOTE_ADDR'], date('Y-m-d h:i:sa'));
  header('location: /?alert=1010');
}

// Check user
if(isset($_COOKIE['auth-u']) && isset($_COOKIE['auth'])){
  $auth_u = $_COOKIE['auth-u'];
  $auth = $_COOKIE['auth'];

  $user = $con->query("SELECT user, pass, role FROM users WHERE user LIKE '$auth_u'")->fetch_array();

  if($auth_u != $user['user'] || $auth != $user['pass'] || $user['role'] < 1){
    header('location: /?alert=1015');
  }
}
else{
  header('location: ../?alert=1009');
}

# if superglobal post['desc'] is set run upload script
if (isset($_POST['desc'])) {
  $desc = $_POST['desc'];
  //Loop through each file
  for($i=0; $i<count($_FILES['upload']['name']); $i++) {
    //Get the temp file path
    $tmpFilePath = $_FILES['upload']['tmp_name'][$i];

    //Make sure we have a filepath
    if ($tmpFilePath != ""){
      //Setup our new file path
      $newFilePath = "../bilder/" . uniqid() . $_FILES['upload']['name'][$i];
      $extension = strrpos($newFilePath, ',');
      if(in_array($extension, array('.jpg', '.jpeg', '.png', '.gif'))){
      //Upload the file into the temp dir
  		  $check_uniqe = mysqli_query($con, "SELECT * FROM bilder WHERE img='$newFilePath' LIMIT 1");
        if(move_uploaded_file($tmpFilePath, $newFilePath) && !mysqli_fetch_array($check_uniqe,MYSQLI_ASSOC) == true) {
  			  mysqli_query($con, "INSERT INTO bilder (img, description) VALUES ('$newFilePath', '$desc')");
  			  header("Location: ../bilder.php");
        } else {
  			  echo "Upload failed!";
  		  }
      }
    }
  }
}

# if superglobal post['desc'] is set run upload script
if (isset($_POST['submit-front'])) {
  $active = $_POST['active'];

  for($i=0; $i<count($_FILES['upload']['name']); $i++) {
    //Get the temp file path
    $tmpFilePath = $_FILES['upload']['tmp_name'][$i];

    //Make sure we have a filepath
    if ($tmpFilePath != ""){
      //Setup our new file path
      $newFilePath = "../img/" . uniqid() . $_FILES['upload']['name'][$i];

      //Upload the file into the temp dir
      $check_uniqe = mysqli_query($con, "SELECT * FROM fremside WHERE name='$newFilePath' LIMIT 1");
      if(move_uploaded_file($tmpFilePath, $newFilePath) && !mysqli_fetch_array($check_uniqe,MYSQLI_ASSOC) == true) {
        $sql = mysqli_query($con, "SELECT * FROM fremside WHERE active LIKE '1'");
          if (!empty($active) && mysqli_num_rows($sql) == 0) {
            mysqli_query($con, "INSERT INTO fremside (name, active) VALUES ('$newFilePath', '1')");
            header('Location: ../index.php');
          } else {
            mysqli_query($con, "INSERT INTO fremside (name) VALUES ('$newFilePath')");
            header('Location: index.php');
          }

      } else {
        echo "Upload failed!";
      }
    }
  }

}


?>
