<?php

@include 'config.php';

session_start();

$password = "";

function is_valid_password($password)
{
   if ($_SERVER["REQUEST_METHOD"] == "POST")
      // Kiểm tra độ dài mật khẩu (ít nhất 8 ký tự)
      if (strlen($password) < 8) {
         return false;
      }

   // Kiểm tra sự kết hợp của chữ cái in hoa, chữ cái thường, số, và ký tự đặc biệt
   if (
      !preg_match('/[A-Z]/', $password) ||     // ít nhất một chữ cái in hoa
      !preg_match('/[a-z]/', $password) ||     // ít nhất một chữ cái thường
      !preg_match('/[0-9]/', $password) ||     // ít nhất một số
      !preg_match('/[^A-Za-z0-9]/', $password) // ít nhất một ký tự đặc biệt
   ) {
      return false;
   }

   // Mật khẩu hợp lệ
   return true;
}
//email->tim trongdb->co->bao loi


if ($_SERVER["REQUEST_METHOD"] == "POST") {

   $email = mysqli_real_escape_string($conn, $_POST['usermail']);
   $pass = $_POST['password'];
   $confirm_pass = $_POST['cpassword'];

   if (!is_valid_password($pass)) {
      $errorPassword[] = 'Invalid password format.';
   }

   $hash_password = md5($pass);

   $select = " SELECT * FROM user_form WHERE email = '$email'";

   $result = mysqli_query($conn, $select);

   if (mysqli_num_rows($result) > 0) {
      $error[] = 'user already exist';
   } else {
      if ($pass != $confirm_pass) {
         $error[] = 'password not mathched!';
      } else {
         $insert = "INSERT INTO user_form(email, password) VALUES('$email','$hash_password')";
         mysqli_query($conn, $insert);
         header('location:login_form.php');
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
   <link rel="stylesheet" href="css/style.css">
</head>

<body>

   <div class="form-container">

      <form action="" method="post">
         <h3 class="title">register now</h3>
         <?php
         if (isset($error)) {
            foreach ($error as $error) {
               echo '<span class="error-msg">' . $error . '</span>';
            }
         }
         if (isset($errorPassword)) {
            foreach ($errorPassword as $errorPassword) {
               echo '<span class="error-msg">' . $errorPassword . '</span>';
            }
         }
         ?>
         <input type="email" name="usermail" placeholder="enter your email" class="box" required>
         <input type="password" name="password" placeholder="enter your password" class="box" required>
         <input type="password" name="cpassword" placeholder="confirm your password" class="box" required>
         <input type="submit" value="register now" class="form-btn" name="submit">
         <p>already have an account? <a href="login_form.php">login now!</a></p>
      </form>

   </div>

</body>

</html>