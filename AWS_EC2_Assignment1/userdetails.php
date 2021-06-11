<?php
      
    if(isset($_POST['signoff'])) {
        session_destroy();
    }
?>

<?php
session_start();
if(empty($_SESSION["username"])){
header("location: signin.php");
}
?>
<html>
<head>

<form action="signin.php">

<mat-card>
  <div class="buttons">
  <button mat-fab name="signoff" color:red>SignOff</button>
  </div>
</mat-card>

</form>

<style>
table, td, th {
  border: 1px solid black;
  text-align: center;
}

table {
  border-collapse: collapse;
  width: 1000px;
}

th {
  height: 20px;
}

.mat-card{ position: relative }

h1{ margin-right: 120px; }

.buttons{
  position: absolute;
  top:50%;
  right: 0;
  width: 120px;
  transform: translateY(-50%);
}

</style>
<h1> Welcome <?php echo $_SESSION["username"] ?> !! Now you are logged in </h1>
</head>
<?php
$link = mysqli_connect("localhost", "root", "password", "Assignment1");

// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
// Attempt select query execution
$sql = "SELECT * FROM UserDetails where username='".$_SESSION["username"]."'";
if($result = mysqli_query($link, $sql)){
    if(mysqli_num_rows($result) > 0){
        echo "<table>";
            echo "<tr>";
        echo "<th>firstname</th>";
                echo "<th>lastname</th>";
                echo "<th>email</th>";
            echo "</tr>";
        while($row = mysqli_fetch_array($result)){
            echo "<tr>";
                echo "<td>" . $row['firstname'] . "</td>";
                echo "<td>" . $row['lastname'] . "</td>";
                echo "<td>" . $row['email'] . "</td>";
            echo "</tr>";
                        echo "<br>";
                        $count = 0;

                        //Opens a file in read mode
                        $filep = "upload/".$_SESSION["username"]."/".$row['filename'];
                        $file = fopen($filep, "r");

                        //Gets each line till end of file is reached
			while (($line = fgets($file)) !== false) {
				$count = $count + preg_match_all("/[\w']+/", $line);
                                //Splits each line into words
                                //$words = explode(" ", $line);
				//$count = $count + count($words);
				
                        }

                        echo "<h1>Number of words in your file : ".$count."</h1>";
                        fclose($file);
        }
        echo "</table>";
        echo "<br>";
        echo "<br>";
        echo "<br>";
        // Free result set
        mysqli_free_result($result);
    } else{
        echo "No records matching your query were found.";
    }
} else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
}

// Close connection
mysqli_close($link);
?>
<body>
<div class="row">
        <div class="col-xs-8 col-xs-offset-2">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>File Name</th>
                        <th>View</th>
                        <th>Download</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                                $link = mysqli_connect("localhost", "root", "password", "Assignment1");

                                // Check connection
                                if($link === false){
                                        die("ERROR: Could not connect. " . mysqli_connect_error());
                                }
                                $sql = "SELECT * FROM UserDetails where username='".$_SESSION["username"]."'";
                                $result = mysqli_query($link, $sql);
                while($row = mysqli_fetch_array($result)) { ?>
                <tr>
                    <td><?php echo $row['filename']; ?></td>
                    <td><a href="upload/<?php echo $_SESSION["username"]."/".$row['filename']; ?>" target="_blank">View</a></td>
                    <td><a href="upload/<?php echo $_SESSION["username"]."/".$row['filename']; ?>" download>Download</td>
                </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
<br>
<br>
<!--
<form action="signin.php"> 
<mat-card>
  <div class="buttons">
  <button mat-fab onclick="<?php //session_destroy();?>" color=red>SignOff</button>
  </div>
</mat-card>

</form> 
-->
</body>
</html>

