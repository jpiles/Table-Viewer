<?php

include "config.php";

//Connect to server
$link = pg_connect("host=$db_host user=$db_user password=$db_password dbname=$db_database");
if(!$link) {
       die('Failed to connect to server: ' . pg_last_error());
}

if (!isset($_REQUEST["table"])){
	die ("no table selected");
}

$num_rows = (isset($_REQUEST["num_rows"])) ? intval($_REQUEST["num_rows"]) : 30;
$page = (isset($_REQUEST["page"])) ? intval($_REQUEST["page"]) : 0;


$query_fields = 'SELECT column_name FROM information_schema.columns WHERE table_name = $1';
$results_fields = pg_query_params($link, $query_fields,array($_REQUEST["table"]));

$r_fields = array();
while ($row = pg_fetch_assoc($results_fields)){
	$r_fields[] = $row["column_name"];
}


$query = "SELECT * FROM ".pg_escape_identifier($_REQUEST["table"]);
if (isset($_REQUEST["order"]) && $_REQUEST["order"] != "null"){
	$query .= " ORDER BY ".pg_escape_identifier($_REQUEST["order"]);
	if (isset($_REQUEST["dir"])){
		$query .= " ".pg_escape_string($_REQUEST["dir"]);
	}
}

$query .= " LIMIT ".$num_rows." OFFSET ".($num_rows * $page);

$result = pg_query($link,$query);

$results_array = array();
if (!$result) {
	die('Failed to get results '. pg_last_error());
}

if (pg_num_rows($result) > 0){
	
	while($row = pg_fetch_assoc($result)){
		$row_array = array();
		foreach ($row as $key=>$value){
			$row_array[] = $value;
		}
		$results_array[] = $row_array;
	}
	
}

$query = "SELECT COUNT(*) FROM ".pg_escape_identifier($_REQUEST["table"]);
$result_count = pg_query($link,$query);
$row_count = pg_fetch_array($result_count);
$num = $row_count[0];

class returnObj{
	public $rows;
	public $fields;
	public $total_entries;
}

$return_obj = new returnObj;
$return_obj->rows = $results_array;
$return_obj->fields = $r_fields;
$return_obj->total_entries = $num;

echo json_encode($return_obj);

?>
