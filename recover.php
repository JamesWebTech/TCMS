<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>forgot Password</title>
  <link rel="stylesheet" href="forgot.css"> 
  <link rel="icon" href="css/bin.png">

</head>
<body>
<div class="wrap">
    
    <form action="reset.php" method="POST">
        <div class="Login form">
          <h2>Recovery form</h2>
        </div>
     
      <div class="input_box">
        <input  placeholder="Enter OTP Code:" >
      </div>
      
      <button type="submit"  class="btn" name="send">send</button>
      
      <div class="Login_registration">
       <p>REMEMBERED ACCOUNT?<a href="login.php" class="register-link">login</a></p>
      </div>
    </form>
  </div>
</body>