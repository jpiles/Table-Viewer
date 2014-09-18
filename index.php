<?php
    require_once('assets/config.php');
    
    //Connect to mysql server
    $link = pg_connect("host=$db_host user=$db_user password=$db_password dbname=$db_database");
    if(!$link) {
           die('Failed to connect to server: ' . pg_last_error());
    }

    $query = "SELECT table_name FROM information_schema.tables WHERE table_schema='public';";
    $result_tables = pg_query($link,$query);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="shortcut icon" href="assets/images/favicon.ico" >
    <title>Table Viewer</title>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    <script type="text/javascript" src="assets/jquery.paginate.js"></script>
    <script type="text/javascript" src="assets/tableupdater.js"></script>

    <link rel="stylesheet" type="text/css" href="assets/style.css" media="screen"></script>
    <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,400italic&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
   
    <link rel="stylesheet" href="assets/chosen.css" />

</head>

<body>
<div id=wrapper>
    <h1><?php echo $db_database; ?></h1>
    <div id="formcontroles">
		<div class="tablecontrols">
			<select data-placeholder="Select table…" class="chzn-select" id="select-table" onchange="changetable()">
				<option value=""></option> 
        		<?php
        			while ($row = pg_fetch_array($result_tables)){
            			echo "<option>".$row[0]."</option>";
        			}
        		?>
			</select>
        </div>
        <div class="tablecontrols">
        	<select id="num-rows" onchange="changetable()" class="chzn-select" tabindex="2">
        		<option>10 Rows</option>
        		<option>20 Rows</option>
        		<option>30 Rows</option>
        		<option>50 Rows</option>
        		<option>100 Rows</option>
        	</select>
        </div>
	</div>
    <div id="table-show"></div>
    <div id="pagination"></div>
</div>
<script src="assets/chosen.jquery.min.js" type="text/javascript"></script>
<script type="text/javascript"> $(".chzn-select").chosen(); $(".chzn-select-deselect").chosen({allow_single_deselect:true}); </script>
</body>
</html>