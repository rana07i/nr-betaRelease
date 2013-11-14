<div class="navbar navbar-inverse">
	<div class="navbar-inner">
		<div class="container-fluid">
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a>
			<div class="nav-collapse collapse">
				<?php 
					if ($_SESSION['jabatan']=="Layouter" or $_SESSION['jabatan']=="Grafis") { ?>
						<ul class="nav">
							<!-- li class="active"><a href="#"><i class="icon-home icon-white"></i> NR</a></li -->
							<li><a href="index.php?screen=logout">Logout</a></li>										
						</ul>
						<ul class="nav navbar-nav pull-right">
							<li><a href="#"><?=$_SESSION['namaLengkap'];?></a></li>
						</ul>
					<?php } 
					else { ?>
						<ul class="nav">
							<!-- li class="active"><a href="#"><i class="icon-home icon-white"></i> NR</a></li -->
							<li><a href="index.php?screen=input">Add</a></li>										
						</ul>
						<!-- Dropdown menu -->
						<ul class="nav pull-left">
						  <li class="dropdown">
							<a  class="dropdown-toggle" data-toggle="dropdown">List<b class="caret"></b></a>
							<ul class="dropdown-menu">
							  <li><a href="index.php?screen=listByUser">By User</a></li>
							  <li><a href="index.php?screen=list">All</a></li>
							  <li><a href="index.php?screen=layout">Layout</a></li>
							</ul>
						  </li>
						</ul>								
						<ul class="nav">
							<!-- li class="active"><a href="#"><i class="icon-home icon-white"></i> NR</a></li -->
							<li><a href="index.php?screen=logout">Logout</a></li>										
						</ul>
						<ul class="nav navbar-nav pull-right">
							<li><a href="#"><?=$_SESSION['namaLengkap'];?></a></li>
						</ul>
					<?php } ?>
			</div><!-- /.nav-collapse -->
		</div><!-- /.container -->
	</div><!-- /.navbar-inner -->
</div><!-- /.navbar -->

<!-- Menu content -->

<section id="input" style="padding-top: 0px; padding-bottom: 10px;">
	<?php
		if ($_SESSION['jabatan']=="Layouter" or $_SESSION['jabatan']=="Grafis") { 
			$screen = (isset($_GET['screen']))? $_GET['screen'] : "main";
			switch ($screen) {
				case 'logout' : include "logout.php"; break;
				case 'rpasswd' : include "resetpass.php"; break;
				case 'layout' : include "listLayout.php"; break;
				case 'layoutf' : include "listLayoutFilter.php"; break;
				case 'main' : include "listLayout.php";
				// default : 					
			}
		}
		else {
			$screen = (isset($_GET['screen']))? $_GET['screen'] : "main";
			switch ($screen) {
				case 'list': include "list.php"; break;
				case 'listexp': include "listExp.php"; break;
				case 'listByUser': include "listByUser.php"; break;
				case 'layout' : include "listLayout.php"; break;
				case 'input': include "input.php"; break;
				case 'edit' : include "edit.php"; break;
				case 'gettext' : include "gettext.php"; break;
				case 'logout' : include "logout.php"; break;
				case 'rpasswd' : include "resetpass.php"; break;
				case 'main' : include "listByUser.php";
				// default : 					
			}
		}
	?>	
</section>
