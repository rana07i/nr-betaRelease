<?php
	ob_start();
	session_start();
	include "inc/function.php";
	// echo $_SESSION[namaLengkap];
	if(!isset($_SESSION['username'])) {
		header("location:login.php");
	}
	
	
?>
<!DOCTYPE html>
<html>
<head>
	<?php include "inc/header.php"; ?>

</head>

<body>
	<div class="container-fluid" style="margin-top:5px;">
		<header>
			<div class="row-fluid">
				<?php include "inc/menu.php"; ?>
			</div>
		</header>
	<div>
	<?php
		include "inc/footer.php";
		ob_flush();
	?>
</body>
</html>