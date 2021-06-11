<?php
$link = mysqli_connect("localhost", "root", "password", "Assignment1");
// Check connection
session_start();
$_SESSION["username"]=$_POST["username"];
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
 
   if($_SERVER["REQUEST_METHOD"] == "POST") {
      $myusername = trim($_POST['username']);
      $mypassword = trim($_POST['password']);
      $param_password = password_hash($mypassword, PASSWORD_DEFAULT); 
      $sql = "SELECT * FROM UserDetails where username = '".$_POST["username"]."'";
      $result = mysqli_query($link,$sql);
      $row = mysqli_fetch_array($result);
      if($mypassword==$row['password']) {
         header("location: userdetails.php");
      }else {
         $error = "UserName or Password is in-correct";
      }
   }
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body {
  font-family: Arial, Helvetica, sans-serif;
  background-color: black;
}

* {
  box-sizing: border-box;
}

/* Add padding to containers */
.container {
  padding: 16px;
  background-color: white;
}

/* Full-width input fields */
input[type=text], input[type=password] {
  width: 100%;
  padding: 15px;
  margin: 5px 0 22px 0;
  display: inline-block;
  border: none;
  background: #f1f1f1;
}

input[type=text]:focus, input[type=password]:focus {
  background-color: #ddd;
  outline: none;
}

/* Overwrite default styles of hr */
hr {
  border: 1px solid #f1f1f1;
  margin-bottom: 25px;
}

/* Set a style for the submit button */
.registerbtn {
  background-color: blue;
  color: white;
  padding: 16px 20px;
  margin: 8px 0;
  border: none;
  cursor: pointer;
  width: 100%;
  opacity: 0.9;
}

.registerbtn:hover {
  opacity: 1;
}

/* Add a blue text color to links */
a {
  color: dodgerblue;
}

/* Set a grey background color and center the text of the "sign in" section */
.signin {
  background-color: #f1f1f1;
  text-align: center;
}
</style>
</head>
<body>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
  <div class="container">
    <h1>Sign in</h1>
    <p>Please fill in this form to sign in to your account.</p>
    <hr>
	<div class="form-group">
    <label for="username"><b>Username</b></label>
    <input type="text" placeholder="Enter Username" name="username" id="username" required>
	</div>
	<div class="form-group">
    <label for="psw"><b>Password</b></label>
    <input type="password" placeholder="Enter Password" name="password" id="password" required>
	</div>
    <hr>
    <button type="submit" class="registerbtn" value="Submit">Sign in</button>
	<span class="invalid-feedback"><?php echo $error; ?></span>
  </div>
  <div class="container signin">
    <p>Need to create an account? <a href="index.php">Sign up</a>.</p>
  </div>
</form>
</body>
</html>

