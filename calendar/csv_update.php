<?php
parse_str(implode('&', array_slice($argv, 1)), $_GET);

// get the last date in the current data file
echo $_GET['last_date'] . "\n";

// open file for appending
$data_file = "/home/ashpjin/public_html/viz/calendar/norm_data.csv";
$destination = fopen($data_file, 'a');

// get the past day's bounds
$day_begin = strtotime(date("Y-m-d", strtotime($_GET['last_date'] . " +1 day")));
$day_end = strtotime(date("Y-m-d", strtotime($_GET['last_date'] . " +2 day")));

// in case this job runs twice on accident
if($day_end > time()){
	echo "not updating\n";
}

// setup database connection
$mysql_conn = new mysqli("localhost", "root", "adam17", "twitter");

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}

$search_id = array(6, 56, 3, 54, 11, 51, 60, 5, 8, 10, 4);
$row = array();
$row[] = date("Y-m-d", $day_begin);
$day_count = array();
$averages = array();

// Prepared Statement for Year averages
if($query = $mysql_conn -> prepare("SELECT average FROM calendar_year_averages WHERE search_id=?;")){
    foreach($search_id as &$id){
	    $query -> bind_param("i", $id);
		$query -> execute();
		$query -> bind_result($value);
		$query -> fetch();

		$averages[] = $value;
	}
	$query -> close();

}

// Prepared Statement for day count
if($query = $mysql_conn -> prepare("SELECT COUNT(1) FROM search_result WHERE search_id=?  AND created >= " . $day_begin . " AND created < " . $day_end . ";"))
{

//echo "SELECT COUNT(1) FROM search_result WHERE search_id=?  AND created >= " . $day_begin . " AND created < " . $day_end . ";\n";
	foreach ($search_id as &$id) {
		$query -> bind_param("i", $id);
		$query -> execute();
		$query -> bind_result($value);
		$query -> fetch();
	
		// manipulate data *** need to get year max somehow! ***
		$day_count[] = $value;
	//	echo $id. ": " . $value . "\n";
	}
	$query -> close();
}
$mysql_conn -> close();

for($index = 0; $index < sizeof($day_count); $index++){
	$row[] = $day_count[$index] / $averages[$index];
}
//print_r($day_count);
//print_r($averages);
//print_r($row);

fputcsv($destination, $row);
fclose($destination);

?>
