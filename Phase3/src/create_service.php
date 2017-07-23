<?php
   include 'lib.php';
   
   // input field for facilityName
function displayClientServiceInputFields() {
    echo ' <p>
       <label>Facility Name: </label>
       <input id="facilityName" name="facilityName" type="text" value="' . $EMPTY_STRING . '"/>
       </p>
   <p>
       <label>Eligibility Condition: </label>
       <input id="EligibilityCondition" name="EligibilityCondition" type="text" value="' . $EMPTY_STRING . '"/>
       </p>
   <p>
       <label>Hours Of Operation: </label>
       <input id="HoursOfOperation" name="HoursOfOperation" type="text" value="' . $EMPTY_STRING . '"/>
       </p> ';	
}
function displaySoupKitchenInputFields() {
    echo ' <p>
       <label>Seat Available: </label>
       <input id="SeatAvail" name="SeatAvail" type="text" value="' . $EMPTY_STRING . '"/>
       </p>
       <p>
       <label>Seat Total: </label>
       <input id="SeatTotal" name="SeatTotal" type="text" value="' . $EMPTY_STRING . '"/>
       </p> ';	
}
function displayShelterInputFields() {
    echo ' <p>
       <label>Bunk Type: </label>
       <input id="BunkType" name="BunkType" type="text" value="' . $EMPTY_STRING . '"/>
       </p>
	   <p>
       <label>Bunk Count (Male): </label>
       <input id="BunkCountMale" name="BunkCountMale" type="text" value="' . $EMPTY_STRING . '"/>
       </p>
	   <p>
       <label>Bunk Count (Female):</label>
       <input id="BunkCountFemale" name="BunkCountFemale" type="text" value="' . $EMPTY_STRING . '"/>
       </p> 
       <label>Bunk Count (Mixed):</label>
       <input id="BunkCountMixed" name="BunkCountMixed" type="text" value="' . $EMPTY_STRING . '"/>
       </p> ';	
}

function retrieveAllSiteNames() {
   $sql = "SELECT ShortName, SiteId FROM Site";
   $result = executeSql($sql);
   return $result;
}

function displaySiteNamesOptions() {
   $result = retrieveAllSiteNames();
   $str = "<select id='Site' name='Site'>";
   while($row = $result->fetch_assoc()) {
      $str = $str . "
         <option value='" . $row['SiteId'] . "'>" . $row['ShortName'] . "</option>";
   }
   $str = $str . " </select>";
   echo $str;
}
# adds a facility and returns the id of the new row
function addFacilityToServiceTable() {
	$sql = "INSERT INTO Service (FacilityId) VALUES (NULL)";
    return insertSql($sql);
}

function addFacilityToClientServiceTable($FacilityId, $SiteId, $FacilityName, $EligibilityCondition, $HoursOfOperation, $FacilityType) {
	$sql = "INSERT INTO ClientService (FacilityId, SiteId, FacilityName, EligibilityCondition, HoursOfOperation, FacilityType) VALUES (". 
		$FacilityId .",'". $SiteId ."','". $FacilityName ."','". $EligibilityCondition ."','". $HoursOfOperation ."','". $FacilityType. "')";
	   return insertSql($sql);
}

function addFacilityToSoupKitchen($FacilityId, $SeatAvail, $SeatTotal) {
	$sql = "INSERT INTO SoupKitchen (FacilityId, SeatAvail, SeatTotal) VALUES ('" . 
		$FacilityId . "','". $SeatAvail . "','". $SeatTotal . "')";
	return insertSql($sql);
}

function addFacilityToFoodBank($FacilityId, $FacilityName) {
	$sql = "INSERT INTO FoodBank (FacilityId, FacilityName) VALUES ('" . $FacilityId . "', '". $FacilityName ."')";
	return insertSql($sql);
}

function addFacilityToFoodPantry($FacilityId) {
	$sql = "INSERT INTO FoodPantry (FacilityId) VALUES ('" . $FacilityId ."')";
	return insertSql($sql);
}

function addFacilityToShelter($FacilityId, $BunkType, $BunkCountMale, $BunkCountFemale, $BunkCountMixed) {
	$sql = 	$sql = "INSERT INTO Shelter (FacilityId, BunkType, BunkCountMale, BunkCountFemale, BunkCountMixed) VALUES ('" . 
		$FacilityId . "','". $BunkType . "','". $BunkCountMale . "','". $BunkCountFemale . "','". $BunkCountMixed . "')";
	return insertSql($sql);
}

function addToSiteToServiceTable($FacilityId, $SiteId) {
	$sql = "INSERT INTO SiteToService (FacilityId, Siteid) VALUES ('" . 
		$FacilityId . "','". $SiteId . "')";
	return insertSql($sql);
}

function doesServiceExist($SiteId, $FacilityType) {
    $sql = "SELECT * FROM clientservice WHERE SiteId=". $SiteId ." AND FacilityType='". $FacilityType ."'";
    $result = executeSql($sql);
    $rowcnt = mysqli_num_rows($result);
    if ($rowcnt >= 1) {
        return true;
    } else {
        return false;
    }
}
function doesFoodbankExist($SiteId, $FacilityType) {
    $sql = "SELECT * FROM sitetoservice, foodbank WHERE SiteId=". $SiteId ." AND sitetoservice.FacilityId = foodbank.FacilityId";
    $result = executeSql($sql);
    $rowcnt = mysqli_num_rows($result);
    if ($rowcnt >= 1) {
        return true;
    } else {
        return false;
    }
}
$pageTitle = "Create a new service"; 
session_start();
// Ensure session is valid. If not, go to login page.
checkValidSession();
$_SESSION['message'] = " "; 
   if (isset($_POST['create']) && !empty($_POST['serviceTypeToAdd'])) {
       $SiteId = $_SESSION['siteId'];
       $FacilityName = $_POST['facilityName'];
       $EligibilityCondition = $_POST['EligibilityCondition'];
       $HoursOfOperation = $_POST['HoursOfOperation'];
       $FacilityType = $_POST['serviceTypeToAdd'];
       $check = false;
       if ($FacilityType == "foodbank") {
          $check = doesFoodbankExist($SiteId, $FacilityType);  
       } else {
          $check = doesServiceExist($SiteId, $FacilityType);
          
       }
       if ( $check == true) {
           $_SESSION['message'] = "This Site already owns that service. <a href=\"./services.php\">Go back to services directory.</a> ";
       } else {
   switch ($FacilityType) {
   	case "soupkitchen":
            $SeatAvail = $_POST['SeatAvail'];
            $SeatTotal = $_POST['SeatTotal'];				
            $FacilityId = addFacilityToServiceTable();
            addFacilityToClientServiceTable($FacilityId, $SiteId, $FacilityName, $EligibilityCondition, $HoursOfOperation, $FacilityType);
            addFacilityToSoupKitchen($FacilityId, $SeatAvail, $SeatTotal);
            addToSiteToServiceTable($FacilityId, $SiteId);
            $_SESSION['message'] = "New Facility is created successfully. <a href=\"./services.php\">Go back to services directory.</a> ";
   		break;
	case "shelter":
		$BunkType = $_POST['BunkType']; 
		$BunkCountMale = $_POST['BunkCountMale'];
		$BunkCountFemale = $_POST['BunkCountFemale'];
		$BunkCountMixed = $_POST['BunkCountMixed'];
		$FacilityId = addFacilityToServiceTable();
		addFacilityToClientServiceTable($FacilityId, $SiteId, $FacilityName, $EligibilityCondition, $HoursOfOperation, $FacilityType);
		addFacilityToShelter($FacilityId, $BunkType, $BunkCountMale, $BunkCountFemale, $BunkCountMixed);
		addToSiteToServiceTable($FacilityId, $SiteId);
        $_SESSION['message'] = "New Facility is created successfully. <a href=\"./services.php\">Go back to services directory.</a> ";
		break;
	case "foodbank":
		$FacilityId = addFacilityToServiceTable();
		addFacilityToFoodBank($FacilityId, $FacilityName);
		addToSiteToServiceTable($FacilityId, $SiteId);
        $_SESSION['message'] = "New Facility is created successfully. <a href=\"./services.php\">Go back to services directory.</a> ";
		break;
	case "foodpantry":
		$FacilityId = addFacilityToServiceTable();
		addFacilityToClientServiceTable($FacilityId, $SiteId, $FacilityName, $EligibilityCondition, $HoursOfOperation, $FacilityType);
		addFacilityToFoodPantry($FacilityId);
		addToSiteToServiceTable($FacilityId, $SiteId);
        $_SESSION['message'] = "New Facility is created successfully. <a href=\"./services.php\">Go back to services directory.</a> ";
		break;
   	default:
   		break;
   }
}
} else {
	//do nothing.
}	
?>
<html>
   <head>
      <?php 
         displayTitle($pageTitle);
         displayCss();
      ?>
      <script>
         <?php displayJavascriptLib();?>
      </script>
   </head>
   <?php displayBodyHeading(); ?>
       <div>
            <div style="float: right">
            <form action="./user_home.php">
                <input type="submit" value="User Home" />
            </form>
            </div>
            <div style="float: right">
            <form action="./login.php">
                <input type="submit" value="Logout" />
            </form>
            </div>
       <h2><?php echo $pageTitle; ?></h2>
       <?php
       if (!empty($_SESSION['message'])){
           echo "<p>".$_SESSION['message']."</p>";
       }
       ?>
     </div>
      <form action="./create_service.php" method="post">
         <div>
         </div>
         <br>
         <div>
			 <?php
			 $serviceType = $_POST["serviceType"];
			 # we need this type to find the right tables to update
			 echo '<input id="serviceTypeToAdd" name="serviceTypeToAdd" type="hidden" value="' . $serviceType . '"/>';
			 displayClientServiceInputFields();
			 switch ($serviceType) {
				case "soupkitchen":
					displaySoupKitchenInputFields();
					break;
				case "shelter":
					displayShelterInputFields();
					break;
			 	default:
			 		echo " ";
			 		break;
			 }
			 ?>
			 <button name="create" type="submit">Create Service</button>
         </div>
</form>
</body>
</html>
