<?php

include "config.php";

//Connect to mysql server
$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
if(!$link) {
       die('Failed to connect to server: ' . mysql_error());
}

//Select database
$db = mysql_select_db(DB_DATABASE);
if(!$db) {
       die("Unable to select database");
}

$query = "SHOW TABLES;";
$result_tables = mysql_query($query);

if (!isset($_REQUEST["table"])){
	die ("no table selected");
}

$num_rows = (isset($_REQUEST["num_rows"])) ? intval($_REQUEST["num_rows"]) : 30;
$page = (isset($_REQUEST["page"])) ? intval($_REQUEST["page"]) : 0;


$query_fields = "SHOW COLUMNS FROM ".$_REQUEST["table"];
$results_fields = mysql_query($query_fields);

$r_fields = array();
while ($row = mysql_fetch_assoc($results_fields)){
	$r_fields[] = $row["Field"];
}


$query = "SELECT * FROM ".$_REQUEST["table"];
if (isset($_REQUEST["order"])){
	$query .= " ORDER BY ".$_REQUEST["order"];
	if (isset($_REQUEST["dir"])){
		$query .= " ".$_REQUEST["dir"];
	}
}

$query .= " LIMIT ".$num_rows." OFFSET ".($num_rows * $page);

$result = mysql_query($query);

$results_array = array();

if (mysql_num_rows($result) > 0){
	
	while($row = mysql_fetch_assoc($result)){
		$row_array = array();
		foreach ($row as $key=>$value){
			$row_array[] = $value;
		}
		$results_array[] = $row_array;
	}
	
}

$query = "SELECT COUNT(id) FROM ".$_REQUEST["table"];
$result_count = mysql_query($query);
$row_count = mysql_fetch_array($result_count);
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
