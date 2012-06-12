<?php 
	session_start();
	
	$terms = array(
		array("id" => 56, "name" => "Blooming"),
		array("id" => 3, "name" => "Buds Bursting"), 
		array("id" => 54, "name" => "It's Fall"), 
	    array("id" => 11, "name" => "It's Spring"), 
		array("id" => 51, "name" => "It's Summer"), 
		array("id" => 60, "name" => "It's Winter"), 
		array("id" => 5, "name" => "Leaf Out"), 
		array("id" => 6, "name" => "Leafing Out"), 
		array("id" => 8, "name" => "New Flowers"), 
		array("id" => 10, "name" => "Newly Flowering"), 
		array("id" => 4, "name" => "New Leaves")
	);

	$search_id = 6;
	$search_term = "leafing out";
	if(isset($_POST['SubmitCheck'])) {
		$search_id = $_POST['term_id'];
		foreach($terms as &$arr){
			if($arr["id"] == $search_id)
				$search_term = $arr["name"];
		}
	}

	$_SESSION['term'] = $search_id;
?>
<html>
  <head>
    <title>Tweet Calendar</title>
    <script type="text/javascript" src="../d3.v2.js"></script>
    <link type="text/css" rel="stylesheet" href="../lib/colorbrewer/colorbrewer.css"/>
    <link type="text/css" rel="stylesheet" href="calendar.css"/>
  </head>
  <body>

  	<table >
  		<tr>
  			<td><h2>Percentage of Tweets Per Day Containing "<?php echo $search_term; ?>"</h2></td>
  			<td width="200px" align="right"><b>Choose New Term:</b></td>
			<form name="search_term" action="dji_custom.php" method="post">
				<td><select name="term_id">
					<option selected>Choose One...</option>
          			<?php  
						foreach($terms as &$arr){
							echo "<option value='" . $arr["id"] . "'>" . $arr["name"] . "</option>";
						}
					?>
				</select></td>
					<input type="hidden" name="SubmitCheck" value="sent"/>
				<td> <input type="submit" value="Submit"> </form>
  		</tr>
  	</table>
	<div id="chart"></div>
	<script type="text/javascript" src="dji_custom.js"></script>
  </body>
</html>
