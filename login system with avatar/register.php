<?php

include 'config.php';

$name = isset($_POST['name']) ? $_POST['name'] : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$cpassword = isset($_POST['cpassword']) ? $_POST['cpassword'] : '';

if(isset($_POST['submit'])){

   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $pass = mysqli_real_escape_string($conn, md5($_POST['password']));
   $cpass = mysqli_real_escape_string($conn, md5($_POST['cpassword']));
   $image = $_FILES['image']['name'];
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_img/'.$image;

   // Email validation
   if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $message[] = 'Invalid email format';
   }

   // Password validation
   if ($pass !== $cpass) {
      $message[] = 'Confirm password not matched!';
   } elseif (!preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z]{8,}$/', $_POST['password'])) {
      $message[] = 'Password must be at least 8 characters long and contain at least one number, one uppercase letter, and one lowercase letter!';
   }

   if($image_size > 2000000){
      $message[] = 'Image size is too large!';
   }

   $select = mysqli_query($conn, "SELECT * FROM `user_form` WHERE email = '$email'") or die('query failed');

   if(mysqli_num_rows($select) > 0){
      $message[] = 'User already exists'; 
   }

   if (!isset($message)) {
      $insert = mysqli_query($conn, "INSERT INTO `user_form`(name, email, password, image) VALUES('$name', '$email', '$pass', '$image')") or die('query failed');

      if($insert){
         move_uploaded_file($image_tmp_name, $image_folder);
         $message[] = 'Registered successfully!';
         header('location:login.php');
      } else {
         $message[] = 'Registration failed!';
      }
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>register</title>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
<nav>
   <div id="google_element">
              <script>
                  function googleTranslateElementInit() {
                      new google.translate.TranslateElement({pageLanguage: 'en'}, 'google_element');
                  }
                  var googleTranslateScript = document.createElement('script');
                  googleTranslateScript.type = 'text/javascript';
                  googleTranslateScript.async = true;
                  googleTranslateScript.src = 'https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit';
                  ( document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0] ).appendChild(googleTranslateScript);
              </script>
                  </div>
   </nav>
<div class="form-container">
   

   <form action="" method="post" enctype="multipart/form-data">
      <h3>Register Now</h3>
      <?php
      if(isset($message)){
         foreach($message as $message){
            echo '<div class="message">'.$message.'</div>';
         }
      }
      ?>
      <input type="text" name="name" placeholder="Enter username" class="box" required value="<?php echo $name; ?>">
      <input type="email" name="email" placeholder="Enter email" class="box" required value="<?php echo $email; ?>">
      <input type="password" name="password" placeholder="Enter password" class="box" required value="<?php echo $password; ?>">
      <input type="password" name="cpassword" placeholder="Confirm password" class="box" required value="<?php echo $cpassword; ?>">
      <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png">
      <input type="submit" name="submit" value="Register Now" class="btn">
      <p>Already have an account? <a href="login.php">Login now</a></p>
   </form>

</div>

</body>
</html>
