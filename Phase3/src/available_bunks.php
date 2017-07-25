<?php
include 'lib.php';

$pageTitle = "Available Bunk Report";

If(isset($_POST['formAction']) && ($_POST['formAction'] == 'login')){
	header("Location: /login.php");
    exit;
	}

//sql query for table joining 
$query ="SELECT t1.FacilityId, t1.BunkCountMale, t1.BunkCountFemale, t1.BunkCountMixed,t2.FacilityName,t2.EligibilityCondition,t2.HoursOfOperation,t2.SiteID,t3.StreetAddress, t3.City, t3.State, t3.ZipCode, t3.PhoneNumber FROM Shelter t1 INNER JOIN ClientService t2 ON t1.FacilityId = t2.FacilityId LEFT JOIN Site t3 ON t2.SiteId = t3.SiteId WHERE t1.BunkCountMale > 0 OR t1.BunkCountFemale >0 OR t1.BunkCountMixed >0";
		 
$result = executeSql($query);
?>
<html>
<head>
   	<script>
         <?php displayJavascriptLib();?>
	</script>
<style >

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
 section {
	height:100%;
	background-color: CCF4FF;
	text-align: center;
	padding: 1em;
    overflow: hidden;
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
<body >
<div class="container">
	<header>
  	 <?php displayFormHeader($MAIN_FORM,$USER_HOME_URL); ?>
	 <div>
		<?php
		echo '<div style="float: right">
            <button id="login" name="login" type="button" onClick="submitMainForm(' . "'login'" . ')">Login</button>
         </div>';  
	
		?>
	</div>
	<h2>Available Bunk Report</h2>
	<p>
               <?php 
			   displayHiddenField(); 
               ?>
    </p>
    </header>
    <section>
<div class="bodycontainer scrollable">
<table style="border: 2px solid #d3d3d3;" class="table table-hover table-striped table-condensed table-scrollable">
    <tbody>
	<thead>
      <tr>
       
        <th align='left'>Facility Name</th>
        <th align='left'>Male Bunk</th>
        <th align='left'>Female Bunk</th>
        <th align='left'>Mixed Bunk</th>
        <th align='left'>Site Location</th>
        <th align='left'>Phone Number</th>
        <th align='left'>Hours of Operation</th>
        <th align='left'>Eligibility Conditions</th>
       </tr>
    </thead>
								<?php								
								    if ($result->num_rows == 0)
								        echo("Sorry, all shelters are currently at maximum capacity.");
                                    else {
                                        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                                            print "<tr>";
                                            print "<td>{$row['FacilityName']}</td>";
                                            print "<td>{$row['BunkCountMale']}</td>";
                                            print "<td>{$row['BunkCountFemale']}</td>";
                                            print "<td>{$row['BunkCountMixed']}</td>";
                                            print "<td>{$row['StreetAddress']},{$row['City']},{$row['State']} {$row['ZipCode']}</td>";
                                            print "<td>{$row['PhoneNumber']}</td>";
                                            print "<td>{$row['HoursOfOperation']}</td>";
                                            print "<td>{$row['EligibilityCondition']}</td>";
                                            print "</tr>";							
                                        }
                                    }
									
                                ?>
 </tbody>
 </table>
 </div>
 </section> 
 </div>

 </div>
 		 
</body>
</html>
