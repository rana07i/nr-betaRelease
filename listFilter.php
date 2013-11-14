<?php
	$today = date('Y-m-d');
	
	if (!empty($_GET['tgl']) AND (empty($_GET['rub']))) {	
		$tgl = $_GET['tgl'];
		$tgl = str_replace('/', '-', $tgl);	
		$tgl = date('Y-m-d', strtotime($tgl));
		if ($tgl <= $today) {
			header("location:index.php?screen=listexp&tgl=$tgl");
			exit;
		}
		header("location:index.php?screen=list&tgl=$tgl");
	} elseif (empty($_GET['tgl']) AND (!empty($_GET['rub']))) {	
		$tgl = date('Y-m-d', time()+86400);
		$rub = $_GET['rub'];
		header("location:index.php?screen=list&rub=$rub");
	} elseif (!empty($_GET['tgl']) AND (!empty($_GET['rub']))) {	
		$tgl = $_GET['tgl'];
		$tgl = str_replace('/', '-', $tgl);	
		$tgl = date('Y-m-d', strtotime($tgl));
		$rub = $_GET['rub'];
		if ($tgl <= $today) {
			header("location:index.php?screen=listexp&tgl=$tgl&rub=$rub");
			exit;
		}
		header("location:index.php?screen=list&tgl=$tgl&rub=$rub");
	} else {
		header("location:index.php?screen=list");
	}

?>