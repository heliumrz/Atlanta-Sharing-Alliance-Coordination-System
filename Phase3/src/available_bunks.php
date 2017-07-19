<?php
include 'lib.php';

$pageTitle = "Available Bunk Report";

If(isset($_POST['formAction']) && ($_POST['formAction'] == 'login')){
	goToLogin(true);
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
								    if ($result->num_rows == 0)
								        echo("Sorry, all shelters are currently at maximum capacity.");
                                    else {
                                        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                                            print "<tr>";
                                            print "<td>{$row['FacilityId']}</td>";
                                            print "<td>{$row['FacilityName']}</td>";
                                            print "<td>{$row['BunkCountMale']}</td>";
                                            print "<td>{$row['BunkCountFemale']}</td>";
                                            print "<td>{$row['BunkCountMixed']}</td>";
                                            print "<td>{$row['StreetAddress']}{$row['City']}{$row['State']}{$row['ZipCode']}</td>";
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
 </div>
 </div>
 		 
</body>
</html>