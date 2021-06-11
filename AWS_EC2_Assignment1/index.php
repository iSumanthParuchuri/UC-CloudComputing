<?php
// Include config file
require_once "config.php";
session_start(); 
// Define variables and initialize with empty values
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";
$firstname = $lastname = $email = "";
$firstname_err = $lastname_err = $email_err = "";

$file = $file_err = "";
$location = "";
$filename = "";
$_SESSION["username"]=$_POST["username"];;
if (isset($_POST['submit'])){
    $filename = $_FILES['file1']['name'];
    if($filename!=""){
		$ext = pathinfo($filename, PATHINFO_EXTENSION);
		$allowed = ['txt'];
		if (in_array($ext, $allowed)){
			$location = "upload/".$_POST["username"]."/";
                        if(!is_dir($location)){
                                mkdir($location, 0755);
                        }
			move_uploaded_file($_FILES['file1']['tmp_name'],($location.$filename));
		}
		else{
			$file_err = "File format not supported !!";
		}
    }else{
		$file_err = "Cannot leave empty !!";
    }
// Processing form data when form is submitted
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
        $username_err = "Username can only contain letters, numbers, and underscores.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM UserDetails WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
    
	// Validate firstname
    if(empty(trim($_POST["firstname"]))){
        $firstname_err = "Please enter a firstname.";
    } elseif(!preg_match('/^[a-zA-Z]+$/', trim($_POST["firstname"]))){
        $firstname_err = "firstname can only contain letters.";
    } else{
		$firstname = trim($_POST["firstname"]);
	}
    
    // Validate lastname
    if(empty(trim($_POST["lastname"]))){
        $lastname_err = "Please enter a lastname.";
    } elseif(!preg_match('/^[a-zA-Z]+$/', trim($_POST["lastname"]))){
        $lastname_err = "lastname can only contain letters.";
    } else{
		$lastname = trim($_POST["lastname"]);
	}
    //Validate email
    $email = trim($_POST["email"]);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  	$emailErr = "Invalid email format";
    } 
	
    // Check input errors before inserting in database
    if(empty($file_err) && empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($firstname_err) && empty($lastname_err) && empty($email_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO UserDetails (username, password, email, firstname, lastname, filename) VALUES (?, ?, ?, ?, ?, ?)";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssss", $param_username, $param_password, $param_email, $param_firstname, $param_lastname, $param_filename);
            
            // Set parameters
            $param_username = $username;
	    //$param_password = password_hash($password, PASSWORD_DEFAULT);
	    $param_password = $password;
	    $param_firstname = $firstname;
	    $param_lastname = $lastname;
	    $param_email = $email;
	    $param_filename = $filename;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: userdetails.php?username=".$username);
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
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
<form enctype='multipart/form-data' action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
  <div class="container">
    <h1>Register</h1>
    <p>Please fill in this form to create an account.</p>
    <hr>
	<div class="form-group">
    <label for="username"><b>Username</b></label>
    <input type="text" placeholder="Enter Username" name="username" id="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>" required>
	<span class="invalid-feedback"><?php echo $username_err; ?></span>
	</div>
	<div class="form-group">
    <label for="psw"><b>Password</b></label>
    <input type="password" placeholder="Enter Password" name="password" id="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>" required>
	<span class="invalid-feedback"><?php echo $password_err; ?></span>
	</div>
	<div class="form-group">
    <label for="psw-repeat"><b>Repeat Password</b></label>
    <input type="password" placeholder="Repeat Password" name="confirm_password" id="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>" required>
	<span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
	</div>
	<div class="form-group">
    <label for="firstname"><b>First Name</b></label>
    <input type="text" placeholder="Enter firstname" name="firstname" id="firstname" class="form-control <?php echo (!empty($firstname_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $firstname; ?>" required>
	<span class="invalid-feedback"><?php echo $firstname_err; ?></span>
	</div>
	<div class="form-group">
    <label for="lastname"><b>Last Name</b></label>
    <input type="text" placeholder="Enter lastname" name="lastname" id="lastname" class="form-control <?php echo (!empty($lastname_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $lastname; ?>" required>
	<span class="invalid-feedback"><?php echo $lastname_err; ?></span>
	</div>
	<div class="form-group">
    <label for="email"><b>Email</b></label>
    <input type="text" placeholder="Enter email" name="email" id="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>" required>
	<span class="invalid-feedback"><?php echo $email_err; ?></span>
	</div>
	<div class="form-group">
    <label for="file"><b>Upload a file (.txt extension file only) :</b></label>
    <input type="file" name="file1" id="file1" required>
	<span class="invalid-feedback"><?php echo $file_err; ?></span>
	</div>
    <hr>
    <button type="submit" name="submit" class="registerbtn" value="Submit">Register</button>
  </div>
  
  <div class="container signin">
    <p>Already have an account? <a href="signin.php">Sign in</a>.</p>
  </div>
</form>
</body>
</html>

