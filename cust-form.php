<!---------------------------Registration Part------------------------------------->

<?php
  session_start();
  session_destroy();
    $id =NULL;
  $data_in = 0;
  // Create database connection
  $db = mysqli_connect("localhost", "root", "", "vega");

  if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
  }
// echo "Connected successfully";
  // If uploadl button is clicked ...
  if (isset($_POST['upload'])) {
    //echo "IN";
    $firstname = mysqli_real_escape_string($db, $_POST['firstname']);
    $middlename = mysqli_real_escape_string($db,$_POST['middlename']);
    $lastname = mysqli_real_escape_string($db,$_POST['lastname']);
    $dob = mysqli_real_escape_string($db,$_POST['dob']);
    $sex = mysqli_real_escape_string($db,$_POST['sex']);
    $city = mysqli_real_escape_string($db,$_POST['city']);
    $phoneno = mysqli_real_escape_string($db,$_POST['phoneno']);
    $emailid = mysqli_real_escape_string($db,$_POST['emailid']);
    $pass1 = mysqli_real_escape_string($db,$_POST['pass1']);
    $pass2 = mysqli_real_escape_string($db,$_POST['pass2']);
    if($pass1 == $pass2)
    {
      $passencrypt = sha1($pass1);
      // echo $passencrypt;
      $data_in = 1;
    }
    else
    {
      echo "Passwords dont match";
    }
    
  }
    
            
  
   if ($data_in == 1){
    // echo $city;
    $new_sql = "SELECT * from agent where city = '$city' ORDER BY RAND() LIMIT 1";
      $new_result = $db->query($new_sql);
      // echo "number of rows: " . $new_result->num_rows;
      $new_row = $new_result->fetch_assoc();
      // print_r($new_row);
    $agent_assigned = $new_row['agent_id'];
    // echo "agent assigned ".$agent_assigned;
       // echo $firstname;
       $sql = "INSERT INTO customer (firstname, middlename, lastname,dob, sex ,City,  phoneno, email, password, agent_assigned) 
        VALUES ('$firstname','$middlename','$lastname','$dob','$sex' ,'$city','$phoneno', '$emailid', '$passencrypt','$agent_assigned')";
       // execute query
       if ($db->query($sql) === TRUE) {
       // echo "Registration done";
       // header("Location: login_customer.php");
       // die();
       // echo "agent assigned ".$agent_assigned;

   } else {
       echo "Error: " . $sql . "<br>" . $db->error;
   }
       
     }
  //$result = mysqli_query($db, "SELECT * FROM customer");
  mysqli_close($db);
?>

<!---------------------------Login Part------------------------------------->
<?php
  
  // Create database connection
  $db = mysqli_connect("localhost", "root", "", "vega");

 if (isset($_POST['check'])){
    $login_id = mysqli_real_escape_string($db, $_POST['login_id']);
    $password = mysqli_real_escape_string($db,$_POST['password']);
    $passenc = sha1($password);
    $checker = $login_id.$passenc;
    // echo $checker;
    }
  
  
  $sql = "SELECT * from customer";
  $result = $db->query($sql);
  // $id = isset($_GET['login_id']) ? $_GET['login_id'] : '';
  // echo $id;

  if (($result->num_rows > 0)  && (isset($_POST['check'])))
   {
      // output data of each row
    while($row = $result->fetch_assoc()) 
      {
         // print_r($row);
          $check = $row["email"].$row["password"];
         if($check == $checker)
         {
          $id = $row["cust_id"];
          $city = $row["City"];
          // echo $id;
          session_start();

          $_SESSION["login_id"] = $id;
          $_SESSION["who"] = "customer";
          $_SESSION["city"] = $city;
          $_SESSION["agent_assigned"] = $row["agent_assigned"];
          // print_r($_SESSION);
          // echo $row["firstname"];
          header("Location: homepage_after.php", TRUE, 301);
          die();
           }
          else{
            // echo "wrong password";
            $new_var = 1;
               }
             
      }
  } 
  if(isset($new_var) == 1)
  {
    echo "Wrong Password";
  }

  mysqli_close($db);
?>

<!---------------------------Form------------------------------------->

<!DOCTYPE html>
<html lang="eng">
<head>
	<title>VEGA Insurance</title>
	<link rel="stylesheet" type="text/css"href="cust-form.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">
	
</head>
<body>

	<div class="container" id="container">
		<div class="form-container sign-up-container">
			<form method="POST"  enctype="multipart/form-data">
				<h2>Create Account</h2>
				<input type="text" placeholder="First Name" name="firstname">
				<input type="text" placeholder="Middle Name" name="middlename">
				<input type="text" placeholder="Last Name" name="lastname">
				<input required="" type="text" class="form-control" placeholder="Date Of Birth" onfocus="(this.type='date')" name="dob"/>
                <select name="sex">
                <!-- <option value="Gender">Gender</optio -->n>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                </select>
                <select name="city">
                <!-- <option value="city" >City</option> -->
                <option value="Mumbai" >Mumbai</option>
                <option value="Delhi" >Delhi</option>
                <option value="Kolkata" >Kolkata</option>
                <option value="Chennai" >Chennai</option>
                <option value="Hyderabad" >Hyderabad</option>
                <option value="Bangalore" >Bangalore</option>
                <option value="Chandigarh" >Chandigarh</option>
                </select>          
                <input type="tel" placeholder="Phone No." name="phoneno">
				<input type="email" placeholder="Email" name="emailid">
                <!-- <textarea placeholder="Address" Name="Address" Rows="5" Cols="15"></textarea> -->
               <!--  <select>
                <option value="Policy Type">Select Policy Type</option>
                <option value="LIFE">LIFE</option>
                <option value="CAR">CAR</option>
                <option value="HOUSE">HOUSE</option>
                <option value="HEALTH">HEALTH</option> -->
                <input type="password" placeholder="Password" name="pass1" required/>
                 <input type="password" placeholder=" Confirm Password" name="pass2" required/>
            </select> 
				<button type="submit" name="upload">Sign Up</button>
                <button type="reset">Reset</button>
			</form>
		</div>
	
		<div class="form-container sign-in-container">
			<form method="POST"  enctype="multipart/form-data">
				<h2> Customer Sign in</h2>
				<input type="email" placeholder="Email" name="login_id" required />
				<input type="password" placeholder="Password" name="password" required />
				<a href="#">Forgot your password?</a>
				<button type="submit" name="check">Sign In</button>
			</form>
		</div>
		<div class="overlay-container">
			<div class="overlay">
				<div class="overlay-panel overlay-left">
					<h2>Existing User?</h2>
					<p>To keep connected with us please login with your personal info</p>
					<button class="ghost" id="signIn">Sign In</button>
				</div>
				<div class="overlay-panel overlay-right">
					<h2>New User?</h2>
					<p>Enter your personal details and start journey with us</p>
					<button type="submit" class="ghost" id="signUp" >Sign Up</button>
				</div>
			</div>
		</div>
	</div>
	

<script src="cust-form.js"></script>
</body>
</html>


