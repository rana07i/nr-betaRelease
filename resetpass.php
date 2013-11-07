<form class="form-horizontal" method="post" enctype="multipart/form-data">  
	<div style="margin-top:10px;">  
		<input type="password" class="input-medium" name="newpass" placeholder="New Password" required>  
	</div> 
	<div style="margin-top:10px;">  
		<input type="password" class="input-medium" name="retypepass" placeholder="Retype New Password" required>  
	</div> 
	<div style="margin-top:10px;">  
		<input type="email" class="input-medium" name="emailalt" placeholder="Alternative Email" required>  
	</div>
	<div class="form-actions">  
		<button type="submit" class="btn btn-primary" name="update" >Update</button>   
	</div>  
</form>

	<?php

		$con=mysqli_connect("localhost","root","","newsroom");
		
		if (mysqli_connect_errno()){
		  echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		
		if (isset($_POST['update'])) {
			$newpass=$_POST['newpass']; 
			$retypepass=$_POST['retypepass']; 
			$emailalt=$_POST['emailalt']; 
			$passwdmd5=md5($retypepass);

			if ($newpass==$retypepass){
				$query = "UPDATE tbluser SET passwd='$retypepass', passwdmd5='$passwdmd5', emailnoncorp='$emailalt' WHERE iduser='$_SESSION[iduser]'";
				// echo $query;
				$sql = mysqli_query ($con,$query);
				header("location:index.php");
			}
			else {
				header("location:index.php?screen=rpasswd");
			}
		}
	?>