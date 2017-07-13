<?php
include 'lib.php';

$pageTitle = "Available Bunk Report";

//drop the old table if exists
$query = "DROP TEMPORARY TABLE IF EXISTS BunkReport";
executeSql($query);

//save the update result into new table
$query = "CREATE TEMPORARY TABLE BunkReport AS( ".
        "SELECT s.FacilityId, s.BunkCountMale, s.BunkCountFemale,".  
               "s.BunkCountMixed, c.FacilityName, c.EligibilityCondition,".
               "c.HoursOfOperation, t.SiteId, i.LocationStreetAddress, i.LocationCity,". 
               "i.LocationState, i.LocationZipCode, i.phone".
        "FROM Shelter AS s".
            "INNER JOIN ClientService AS c".
                "ON s.FacilityId = c.FacilityId".
            "LEFT JOIN SiteToService AS t".
                "ON s.FacilityId = t.FacilityId".
            "LEFT JOIN Site AS i".
                "ON t.SiteId = i.SiteId".
        "WHERE s.BunkCountMale > 0".
        "OR s.BunkCountFemale >0".
        "OR s.BunkCountMixed >0".
        ")";
$result = executeSql($query);
?>
<html>
<head>
<style>

div.container {
    width: 100%;
	
}

header, footer {
    padding: 1em;
    color: black;
    background-color: BAE8CB;
	charset="utf-8";
    clear: left;
    text-align: left;
}

 footer{   
	clear: both;
    position: relative;
    z-index: 10;
    height: 3em;
    margin-top: -3em;
 }
 
.bodycontainer { max-height: 450px; width: 100%; margin: 0; overflow-y: auto; }
.table-scrollable { margin: 0; padding: 0; }
</style>
<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container">
	<header>
  	<h2>Available Bunk Report</h2>
    </header>
    
<div class="table-responsive">
<table class="table table-striped table-hover table-condensed">
    <thead>
      <tr>
        <th>FacilityId</th>
        <th>Facility name</th>
        <th>Male bunk</th>
        <th>Female bunk</th>
        <th>Mixed bunk</th>
        <th>Site location</th>
        <th>Phone number</th>
        <th>Hours of operation</th>
        <th>Eligibility conditions</th>
       </tr>
    </thead>
</table>
<div class="bodycontainer scrollable">
<table class="table table-hover table-striped table-condensed table-scrollable">
    <tbody>
								<?php								
								    if (empty($result) || (mysqli_num_rows($result) == 0) )
								        echo("Sorry, all shelters are currently at maximum capacity.");
                                    else {
                                        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                                            print "<tr>";
                                            print "<td>{$row['FacilityId']}</td>";
                                            print "<td>{$row['FacilityName']}</td>";
                                            print "<td>{$row['BunkCountMale']}</td>";
                                            print "<td>{$row['BunkCountFemale']}</td>";
                                            print "<td>{$row['BunkCountMixed']}</td>";
                                            print "<td>{$row['LocationStreetAddress']}{$row['LocationCity']}{$row['LocationState']}{$row['LocationZipCode']}</td>";
                                            print "<td>{$row['Phone']}</td>";
                                            print "<td>{$row['HoursOfOperation']}</td>";
                                            print "<td>{$row['EligibilityCondition']}</td>";
                                            print "</tr>";							
                                        }
                                    }
                                ?>
 </tbody>
 </table>
 </div>
 </div>
 </div>
                              
</body>
</html>