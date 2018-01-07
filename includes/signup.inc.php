<?php 	

//I am using this file as an error handler . This file also directs signup and login forms
if (isset($_POST['submit'])) {
	
	include_once 'dbh.inc.php';
//To keep This simple and to enhance security i decided to use mysqli_real_escape_string() function instead of Prepared Statements.
	$first=mysqli_real_escape_string($conn,$_POST['first']);
	$last=mysqli_real_escape_string($conn,$_POST['last']);
	$email=mysqli_real_escape_string($conn,$_POST['email']);
	$uid=mysqli_real_escape_string($conn,$_POST['uid']);
	$pwd=mysqli_real_escape_string($conn,$_POST['pwd']);
//Error Handlers Here..
//Check for Empty Fields
	if(empty($first)||empty($last)||empty($email)||empty($uid)||empty($pwd)){
         header("Location: ../signup.php?signup=incomplete");
	exit();
	}
	//Everything is perfectly Filled
	else{
             //Check if input characters are valid
		if(!preg_match("/^[a-zA-Z]*$/", $first)||!preg_match("/^[a-zA-Z]*$/", $last)){
			header("Location: ../signup.php?signup=invalid");
         	exit();

		}else{
			//Checking For Email
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
	          header("Location: ../signup.php?signup=invalidEmail");
              exit();			
			}
			else{
				//Check for duplicate User IDs
				$sql="SELECT * FROM users WHERE user_uid='$uid'";
				$result=mysqli_query($conn,$sql);
				$resultCheck=mysqli_num_rows($result);
			  if($resultCheck)
			  {
                header("Location: ../signup.php?signup=userAlreadyExist");
	            exit();
			  }
			  else{
			  	//Hashing the Password To secure Account
			  	$hashedPwd = password_hash($pwd,PASSWORD_DEFAULT);
			  	//Insert the user into database
			  	$sql="INSERT INTO users (user_first,user_last,user_email,user_uid,user_pwd) VALUES('$first','$last','$email','$uid','$hashedPwd');"
			  	mysqli_query($conn,$sql);
			  	header("Location: ../signup.php");
	            exit();

			  }
			}
		}
	}

}
// In case Users Acts smart to get in without password
else{
	header("Location: ../signup.php");
	exit();
}