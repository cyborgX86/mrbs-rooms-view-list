<?php

//Pendiente: revisar css para quitar propiedades sin uso.

require_once ("config.inc.php");
require_once ("functions.inc.php");

$connection=mysqli_connect($mysqlhost, $mysqluser, $mysqlpwd, $mysqldb);
mysqli_select_db($connection, $mysqldb);

$date   = date('Y-m-d');
$time   = date('H:i');
$dateEnd = date_create($date);
date_add ($dateEnd, date_interval_create_from_date_string('' . $dateRange . 'days'));
$dateEnd = date_format($dateEnd, 'Y-m-d');

echo '<!DOCTYPE html>
			<html>
			<head>
			<meta charset="UTF-8">
			<link rel="stylesheet" type="text/css" href="css/style.css" />
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<title>' . $titlePage . '</title>
			<script type="text/javascript" src="js/jquery-3.1.1.min.js"></script>
			<script type="text/javascript" src="js/infinite.scroll.js"></script>
			</head>
			<body>';

echo '<div id="booking-table">
			<div class="bookings" id="main">
			<h3>' . $title;

if (occupationState(qryState($date, $dateEnd, $mrbsIdRoom1, $mrbsIdRoom2, $mrbsIdRoom3)) == 1){
	echo '<span class="status"><font color="#4267b2">PRÓXIMAMENTE</font></span></h3>';

}else{
	echo '<span class="status"><font color="#4267b2">SIN ACTIVIDADES EN PRÓXIMOS DÍAS</font>
				</span></h3>';
}

printOccupationTable(qry($date, $dateEnd, $mrbsIdRoom1, $mrbsIdRoom2, $mrbsIdRoom3));
echo '</div></div>';

getIndicators(qry($date, $dateEnd, $mrbsIdRoom1, $mrbsIdRoom2, $mrbsIdRoom3));
echo '</body></html>';
?>
