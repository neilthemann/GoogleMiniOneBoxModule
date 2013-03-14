<?php
header("Content-type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
echo "<OneBoxResults>";
echo "<provider>Employee Directory</provider>";

// CONNECT TO THE DATABASE
$db=@mysql_connect('[db url]', '[db user]', '[db password]');
if (!$db) {
	// failed to connect, outputting lookupFailure will tell the Google Mini to ignore the results and move on
    echo('<Diagnostics>lookupFailure</Diagnostics></OneBoxResults>');
    exit();
}
// DATABASE NAME
if (!@mysql_select_db("[db name]")) {
    die ('<Diagnostics>lookupFailure</Diagnostics></OneBoxResults>');
}

$sqlQuery = "SELECT * FROM main WHERE title LIKE '" . $_GET['query'] . "' LIMIT 3;";
$result = mysql_query($sqlQuery);

if (mysql_num_rows($result) == 0) { // query does not match a title, search by department


	$sqlQuery = "SELECT * FROM main WHERE department LIKE '%" . $_GET['query'] . "%' OR title LIKE '%" . $_GET['query'] . "%' LIMIT 3;";
	$result = mysql_query($sqlQuery);
	
	if (mysql_num_rows($result) == 0) { // query does not match a department or title, search by name
		
		// get the query from the URL and put into an array
		$queryArray = explode(" ", $_GET['query']);	
		
		if (count($queryArray) > 1){ // more than one word typed (first and last name)
			$sql.= "fname LIKE '" .addslashes($queryArray[0]). "'";
			$sql.= "AND lname LIKE '" .addslashes($queryArray[1]). "'";
		} else { // only one word typed (first OR last name)
			$sql.= "fname LIKE '" .addslashes($queryArray[0]). "'";
			$sql.= "OR lname LIKE '" .addslashes($queryArray[0]). "'";
		}
		
		// add the keywords to the rest of the sql query, maximum of 3 results
		$sqlQuery = "SELECT * FROM main WHERE " . $sql . " LIMIT 3;";
		
		// run the query
		$result = mysql_query($sqlQuery);
		
	}
}

// loop through results and output the XML for the Google Mini
while ($row = mysql_fetch_assoc($result)) {
	echo "<MODULE_RESULT>";
	echo "<U>http://www.example.com/employeedirectory.php?id=" . $row['ID'] . "</U>";
	echo "<Title>" . ucfirst(strtolower($row['fname'])) . " " . ucfirst(strtolower($row['lname'])) . "</Title>";
	$title = str_replace("&", "and", $row['title']);
	echo '<Field>' . ucwords(strtolower($title)) . '</Field>';
	echo '<Field>' . ucwords(strtolower($row['department'])) . '</Field>';
	echo '<Field>' . $row['email'] . '</Field>';
	if ($row['phone'] != "NOT LISTED") {
		echo '<Field>' . $row['phone'] . '</Field>';
	}
	echo "</MODULE_RESULT>";
}
echo "</OneBoxResults>";

?>