<?php ob_start(); ?>
<!DOCTYPE html>
<html lang="en">
 <head>
    <meta charset="utf-8">
    <title>Newsroom</title>
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <style type="text/css">
      /* Override some defaults */
      html, body {
        background-color: #eee;
      }
      body {
        padding-top: 40px; 
      }
      .container {
        width: 300px;
      }

      /* The white background content wrapper */
      .container > .content {
        background-color: #fff;
        padding: 20px;
        margin: 0 -20px; 
        -webkit-border-radius: 10px 10px 10px 10px;
           -moz-border-radius: 10px 10px 10px 10px;
                border-radius: 10px 10px 10px 10px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.15);
           -moz-box-shadow: 0 1px 2px rgba(0,0,0,.15);
                box-shadow: 0 1px 2px rgba(0,0,0,.15);
      }

	  .login-form {
		margin-left: 65px;
	  }
	
	  legend {
		margin-right: -50px;
		font-weight: bold;
	  	color: #404040;
	  }

    </style>

</head>
<body>
	<div class="container">
		<div class="content">
			<div class="row">
				<div class="login-form">
					<h2>Newsroom</h2>
					<form method="post" name="loginAuth">
						<fieldset>
							<div class="clearfix">
								<input type="text" placeholder="Username" name="uname" required>
							</div>
							<div class="clearfix">
								<input type="password" placeholder="Password" name="paswd" required>
							</div>
							<button class="btn primary" type="submit" name="loginAuth">Login</button>
						</fieldset>
					</form>
				</div>
			</div>
		</div>
	</div> <!-- /container -->
  
	<?php
			
		$con = mysqli_connect("localhost","k2459657_nruser","@harianNasional","k2459657_newsroom");
		if (mysqli_connect_errno()){
		  echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		
		if (isset($_POST['loginAuth'])) {
			$username=$_POST['uname'];
			$password=$_POST['paswd']; 
			
			// protect sql injection
			$myusername = stripslashes($username);
			$mypassword = stripslashes($password);
			// $myusername = mysqli_real_escape_string($con,$myusername);
			// $mypassword = mysqli_real_escape_string($con,$mypassword);

			$query="SELECT firstName, midleName, lastName, uname, passwd, passwdmd5, iduser, jabatan FROM tbluser WHERE uname='$myusername' and passwd='$mypassword'";
			$result=mysqli_query($con,$query);
			
			// get nama lengkap
			$record = mysqli_fetch_array($result);
			$fname = $record['firstName'];
			$mname = $record['midleName'];
			$lname = $record['lastName'];
			$namaLengkap = $fname." ".$mname." ".$lname;
			$idUser = $record['iduser'];
			$jabatan = $record['jabatan'];
			$passwdmd5 = $record['passwdmd5'];
			
			 // echo $query;
			
			// Mysql_num_row is counting table row
			$count=mysqli_num_rows($result);
			//echo $count;
			
			// If result matched $myusername and $mypassword, table row must be 1 row
			if ($count==1){
				session_start();
				$_SESSION['username'] = $myusername;
				$_SESSION['namaLengkap'] = $namaLengkap;
				$_SESSION['iduser'] = $idUser;
				$_SESSION['jabatan'] = $jabatan;
				
				if ($passwdmd5==null) {
					header("location:index.php?screen=rpasswd");
				}	
				else {
					header("location:index.php");
				}
			} 
			else {
				// echo "<p><center><i>Username or Password incorect.</i></p></center>";
				echo "<div class='alert alert-error'>  
						<a class='close' data-dismiss='alert'></a>  
						<strong>Warning!</strong> Username or Password Incorect.
					</div>";
			}
	
		}
	?>
  
</body>
</html>