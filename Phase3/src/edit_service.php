<?php
   include 'lib.php';

function getClientServicesForSiteandFacilityId($siteId, $facilityId) {
    $sql = "SELECT * FROM clientservice WHERE SiteId= ". $siteId. " AND FacilityId=".$facilityId;
    $result = executeSql($sql);
    return $result;
}

function getFacilityForFacilityId($faciltyType, $facilityId) {
    $sql = "SELECT * FROM ".$faciltyType. " WHERE FacilityId=".$facilityId;
    $result = executeSql($sql);
    return $result;
}
   // input field for facilityName
function displayClientServiceInputFields($siteId, $facilityId) {
    $result = getClientServicesForSiteandFacilityId($siteId, $facilityId);
    while($firstrow = $result->fetch_assoc()) {
    echo ' 
        <input id="FacilityId" name="FacilityId" type="hidden" value="' . $facilityId . '"/>
        <table>
               <col width="40%">
               <col width="60%">
               <tr>
       <td align="left">Facility Name: </td>
       <td align="left"><input id="facilityName" name="facilityName" type="text" value="' . $firstrow['FacilityName'] . '"/></td>
       </tr><tr>
       <td align="left">Eligibility Condition: </td>
       <td align="left"><input id="EligibilityCondition" name="EligibilityCondition" type="text" value="' . $firstrow['EligibilityCondition'] . '"/><td>
       </tr><tr>
       <td align="left">Hours Of Operation: </td>
       <td align="left"><input id="HoursOfOperation" name="HoursOfOperation" type="text" value="' . $firstrow['HoursOfOperation'] . '"/></td>
       </tr><tr> ';
       }	
}
function displayFoodBankInputFields($facilityId) {
    $result = getFacilityForFacilityId("foodbank", $facilityId);
    while($firstrow = $result->fetch_assoc()) {
    echo ' <p>
        <input id="FacilityId" name="FacilityId" type="hidden" value="' . $facilityId . '"/>
        <label>Facility Name: </label>
        <input id="facilityName" name="facilityName" type="text" value="' . $firstrow['FacilityName'] . '"/>
       </p> ';
    }
}
function displaySoupKitchenInputFields($facilityId) {
    $result = getFacilityForFacilityId("soupkitchen", $facilityId);
    while($firstrow = $result->fetch_assoc()) {
    echo '<td align="left">Seat Available: </td>
       <td align="left"><input id="SeatAvail" name="SeatAvail" type="text" value="' . $firstrow['SeatAvail'] . '"/></td>
       </tr><tr>
       <td align="left">Seat Total: </td>
       <td align="left"><input id="SeatTotal" name="SeatTotal" type="text" value="' . $firstrow['SeatTotal'] . '"/></td>
       </tr></table> ';
    }	
}
function displayShelterInputFields($facilityId) {
    $result = getFacilityForFacilityId("shelter", $facilityId);
    while($firstrow = $result->fetch_assoc()) {
    echo ' <p>
       <label>Bunk Type: </label>
       <input id="BunkType" name="BunkType" type="text" value="' . $firstrow['BunkType'] . '"/>
       </p>
	   <p>
       <label>Bunk Count (Male): </label>
       <input id="BunkCountMale" name="BunkCountMale" type="text" value="' . $firstrow['BunkCountMale'] . '"/>
       </p>
	   <p>
       <label>Bunk Count (Female):</label>
       <input id="BunkCountFemale" name="BunkCountFemale" type="text" value="' . $firstrow['BunkCountFemale'] . '"/>
       </p> 
       <label>Bunk Count (Mixed):</label>
       <input id="BunkCountMixed" name="BunkCountMixed" type="text" value="' . $firstrow['BunkCountMixed'] . '"/>
       </p> ';
   }
}

function UpdateFacilityInClientService($FacilityId, $SiteId, $FacilityName, $EligibilityCondition, $HoursOfOperation, $FacilityType) {
    // UPDATE `clientservice` SET `FacilityName`=[value-3],`EligibilityCondition`=[value-4],`HoursOfOperation`=[value-5] WHERE `FacilityId`=60
    $sql = "UPDATE ClientService SET FacilityName='".$FacilityName ."', EligibilityCondition='".$EligibilityCondition. "', HoursOfOperation='".$HoursOfOperation. "' WHERE FacilityId=".$FacilityId;
   return executeSql($sql);
}

function UpdateFacilityInSoupKitchen($FacilityId, $SeatAvail, $SeatTotal) {
    $sql = "UPDATE SoupKitchen SET SeatAvail=".$SeatAvail .", SeatTotal=". $SeatTotal. " WHERE FacilityId=".$FacilityId;
	return executeSql($sql);
}

function UpdateFoodBank($FacilityId, $FacilityName) {
    $sql = "UPDATE FoodBank SET FacilityName='". $FacilityName ."' WHERE FacilityId=".$FacilityId;
    return executeSql($sql);
}

function UpdateFacilityInShelter($FacilityId, $BunkType, $BunkCountMale, $BunkCountFemale, $BunkCountMixed) {
    $sql = "UPDATE Shelter SET BunkType='".$BunkType."', BunkCountMale='".$BunkCountMale."', BunkCountFemale='".$BunkCountFemale."', BunkCountMixed='".$BunkCountMixed."' WHERE FacilityId=".$FacilityId;
    return executeSql($sql);
}

$pageTitle = "Edit Facility"; 
session_start();
// Ensure session is valid. If not, go to login page.
checkValidSession();
$_SESSION['message'] = " "; 
if (isset($_POST['edit']) && !empty($_POST['serviceTypeToEdit'])) {
       $SiteId = $_SESSION['siteId'];
       $FacilityName = $_POST['facilityName'];
       $FacilityId = $_POST['FacilityId'];
       $EligibilityCondition = $_POST['EligibilityCondition'];
       $HoursOfOperation = $_POST['HoursOfOperation'];
       $FacilityType = $_POST['serviceTypeToEdit'];
   switch ($FacilityType) {
   	case "soupkitchen":
            $SeatAvail = $_POST['SeatAvail'];
            $SeatTotal = $_POST['SeatTotal'];
            $sql = "UPDATE ClientService SET FacilityName='".$FacilityName ."', EligibilityCondition='".$EligibilityCondition. "', HoursOfOperation='".$HoursOfOperation. "' WHERE FacilityId=".$FacilityId;
            UpdateFacilityInClientService($FacilityId, $SiteId, $FacilityName, $EligibilityCondition, $HoursOfOperation, $FacilityType);
            UpdateFacilityInSoupKitchen($FacilityId, $SeatAvail, $SeatTotal);
            $_SESSION['message'] = "This Facility is edited successfully. <a href=\"./services.php\">Go back to services directory.</a> ";
   		break;
	case "shelter":
		$BunkType = $_POST['BunkType']; 
		$BunkCountMale = $_POST['BunkCountMale'];
		$BunkCountFemale = $_POST['BunkCountFemale'];
		$BunkCountMixed = $_POST['BunkCountMixed'];
		UpdateFacilityInClientService($FacilityId, $SiteId, $FacilityName, $EligibilityCondition, $HoursOfOperation, $FacilityType);
		UpdateFacilityInShelter($FacilityId, $BunkType, $BunkCountMale, $BunkCountFemale, $BunkCountMixed);
        $_SESSION['message'] = "This Facility is edited successfully. <a href=\"./services.php\">Go back to services directory.</a> ";
		break;
	case "foodbank":
    // var_dump($_POST);
    // $sql = "UPDATE FoodBank SET FacilityName='" $FacilityName ."' WHERE FacilityId=".$FacilityId;
    // echo "<br>".$sql;
        UpdateFoodBank($FacilityId, $FacilityName);
        $_SESSION['message'] = "This Facility is edited successfully. <a href=\"./services.php\">Go back to services directory.</a> ";
		break;
	case "foodpantry":
		UpdateFacilityInClientService($FacilityId, $SiteId, $FacilityName, $EligibilityCondition, $HoursOfOperation, $FacilityType);
        $_SESSION['message'] = "This Facility is edited successfully. <a href=\"./services.php\">Go back to services directory.</a> ";
		break;
   	default:
   		break;
   }
} else {
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
      <form action="./edit_service.php" method="post">
         <div>
         </div>
         <br>
         <div>
<?php
             if (isset($_GET["type"]) && isset($_GET["id"])){
                 $serviceType = $_GET["type"];
                 $facilityId = $_GET["id"];
                 $siteId = $_SESSION["siteId"];
                 
                 echo '<input id="serviceTypeToEdit" name="serviceTypeToEdit" type="hidden" value="' . $serviceType . '"/>';
                 displayClientServiceInputFields($siteId, $facilityId);
                 switch ($serviceType) {
                     case "soupkitchen":
                        // displayClientServiceInputFields($siteId, $facilityId);
                         displaySoupKitchenInputFields($facilityId);
                         break;
                     case "shelter":
                        // displayClientServiceInputFields($siteId, $facilityId);
                         displayShelterInputFields($facilityId);
                         break;
                     case "foodbank":
                         displayFoodBankInputFields($facilityId);
                         break;
                     default:
                         echo " ";
                         break;
                 }
             } else {
                 //do nothing
             }
             ?>
<button name="edit" type="submit">Edit Service</button>
         </div>
</form>
</body>
</html>
