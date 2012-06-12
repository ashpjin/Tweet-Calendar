<?php
session_start();

$search_id = $_SESSION['term'];
//if(isset($_POST['SubmitCheck'])) {
//	$search_id = $_SESSION['term'];	
//}

	// setup database connection
$mysql_conn = new mysqli("localhost", "root", "adam17", "twitter");

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
	    exit();
}

	//establish variables
	$min_unix = "2009-04-01";
	$max_unix = date('U');
								
	$day_lower_bound = strtotime($min_unix);
	$day_upper_bound = strtotime(date("Y-m-d", strtotime($min_unix) . " +1 day"));
	$day_counts = array();
										
	$current_max = 0;


	if($query = $mysql_conn -> prepare("SELECT COUNT(1) FROM search_result WHERE search_id=" . $search_id . "  AND created >= ? AND created < ?;"))
	{
	// build array of dates with their counts
	while($day_lower_bound <= $max_unix){
		
		// execute sql query
		$query -> bind_param("ii", $day_lower_bound, $day_upper_bound);
		$query -> execute();
		$query -> bind_result($value);
		$query -> fetch();
																									
		// manipulate the results
		if($value > $current_max) {
			$current_max = $value;
		}
		// create "row" for csv: Date, Tweet Count, Tweet Percentage 
		// can't calculate Tweet Percentage until we find max count
		$day_counts[] = array(date("Y-m-d", $day_lower_bound), $value, 0);
		
		//increase bounds
		$day_lower_bound = $day_upper_bound;
		$day_upper_bound = strtotime('+1 day', $day_lower_bound);
	}
		$query -> close();
	}
	
	$mysql_conn -> close();

	// iterate to calculate percentage
	for( $index=0; $index < sizeof($day_counts); $index++){
		$day_counts[$index][2] = ($day_counts[$index][1] / $current_max);
	}
//print_r($day_counts);
//echo $current_max;
																																	// send response headers to the browser
	// following headers instruct the browser to treat the data as a csv file called export.csv
	header( 'Content-Type: text/csv' );
	header( 'Content-Disposition: attachment;filename=export.csv' );

	// output header row (if atleast one row exists)
	$header_row = array("Date", "Count", "Percentage");
	echocsv($header_row);
																																	// output data rows (if atleast one row exists)
	for($index = 0; $index < sizeof($day_counts); $index++){	
		echo $day_counts[$index][0] . "," . $day_counts[$index][1] . "," . $day_counts[$index][2] . "\n";
	}

	// echocsv function
	// echo the input array as csv data maintaining consistency with most CSV implementations
	// * uses double-quotes as enclosure when necessary
	// * uses double double-quotes to escape double-quotes 
	// * uses CRLF as a line separator
	function echocsv( $fields )	{
		$separator = '';
		foreach ( $fields as $field ){
			if ( preg_match( '/\\r|\\n|,|"/', $field ) ){																						$field = '"' . str_replace( '"', '""', $field ) . '"';
			}
			echo $separator . $field;
			$separator = ',';
		}
		echo "\r\n";
	} 
?>
