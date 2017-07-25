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
               <col width="200px">
               <col width="300px">
               <tr>
       <td align="left">Facility Name<sup> *</sup>: </td>
       <td align="left"><input id="facilityName" name="facilityName" type="text" value="' . $firstrow['FacilityName'] . '" required/></td>
       </tr><tr>
       <td align="left">Eligibility Condition<sup> *</sup>: </td>
       <td align="left"><input id="EligibilityCondition" name="EligibilityCondition" type="text" value="' . $firstrow['EligibilityCondition'] . '" required/><td>
       </tr><tr>
       <td align="left">Hours Of Operation<sup> *</sup>: </td>
       <td align="left"><input id="HoursOfOperation" name="HoursOfOperation" type="text" value="' . $firstrow['HoursOfOperation'] . '" required/></td>
       </tr><tr> ';
       }	
}
function displayFoodBankInputFields($facilityId) {
    $result = getFacilityForFacilityId("foodbank", $facilityId);
    while($firstrow = $result->fetch_assoc()) {
    echo '
        <td align="left">Facility Name<sup> *</sup>: </td>
        <td align="left">
        <input id="FacilityId" name="FacilityId" type="hidden" value="' . $facilityId . '"/>
        <input id="facilityName" name="facilityName" type="text" value="' . $firstrow['FacilityName'] . '" required/></td>
       </tr></table>';
    }
}
function displaySoupKitchenInputFields($facilityId) {
    $result = getFacilityForFacilityId("soupkitchen", $facilityId);
    while($firstrow = $result->fetch_assoc()) {
    echo '<td align="left">Seat Available<sup> *</sup>: </td>
       <td align="left"><input id="SeatAvail" name="SeatAvail" type="text" value="' . $firstrow['SeatAvail'] . '" required/></td>
       </tr><tr>
       <td align="left">Seat Total<sup> *</sup>: </td>
       <td align="left"><input id="SeatTotal" name="SeatTotal" type="text" value="' . $firstrow['SeatTotal'] . '" required/></td>
       </tr></table> ';
    }	
}
function displayBunkTypeOptionsforEdit($originalVal){
    $bunkTypes = [ "male/female/mixed" => "Male/Female/Mixed", "male"=>"Male", "female"=>"Female"];
    $optionString = '<select id="BunkType" name="BunkType">';
    foreach($bunkTypes as $value => $label){
        if ($value == $originalVal) {
            $optionString .= '<option value="' . $value .'" selected>'.$label.'</option>';
        } else {
            $optionString .= '<option value="' . $value .'">'.$label.'</option>';
        }
    }
    $optionString .= '</select>';
    return $optionString;
}
function displayShelterInputFields($facilityId) {
    $result = getFacilityForFacilityId("shelter", $facilityId);
    while($firstrow = $result->fetch_assoc()) {
    echo '
       <td align="left">Bunk Type<sup> *</sup>: </td>
       <td align="left">';
       echo displayBunkTypeOptionsforEdit($firstrow['BunkType']);
       echo '</td>
       </tr><tr>
       <td align="left"><label>Bunk Capacity (Male)<sup> *</sup>: </td>
       <td align="left"><input id="BunkCapacityMale" name="BunkCapacityMale" type="text" value="' . $firstrow['BunkCapacityMale'] . '" required/></td>
       </tr><tr>
       <td align="left"><label>Bunk Capacity (Female)<sup> *</sup>:</td>
       <td align="left"><input id="BunkCapacityFemale" name="BunkCapacityFemale" type="text" value="' . $firstrow['BunkCapacityFemale'] . '" required/></td>
       </tr><tr>
       <td align="left"><label>Bunk Capacity (Mixed)<sup> *</sup>:</td>
       <td align="left"><input id="BunkCapacityMixed" name="BunkCapacityMixed" type="text" value="' . $firstrow['BunkCapacityMixed'] . '" required/></td>
       </tr></table> ';
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

function UpdateFacilityInShelter($FacilityId, $BunkType, $BunkCapacityMale, $BunkCapacityFemale, $BunkCapacityMixed) {
    $sql = "UPDATE Shelter SET BunkType='".$BunkType."', BunkCapacityMale=".$BunkCapacityMale.", BunkCapacityFemale=".$BunkCapacityFemale.", BunkCapacityMixed=".$BunkCapacityMixed." WHERE FacilityId=".$FacilityId;
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
		$BunkCapacityMale = $_POST['BunkCapacityMale'];
		$BunkCapacityFemale = $_POST['BunkCapacityFemale'];
		$BunkCapacityMixed = $_POST['BunkCapacityMixed'];
		UpdateFacilityInClientService($FacilityId, $SiteId, $FacilityName, $EligibilityCondition, $HoursOfOperation, $FacilityType);
		UpdateFacilityInShelter($FacilityId, $BunkType, $BunkCapacityMale, $BunkCapacityFemale, $BunkCapacityMixed);
        $_SESSION['message'] = "This Facility is edited successfully. <a href=\"./services.php\">Go back to services directory.</a> ";
        break;
	case "foodbank":
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
$svc = array("foodbank"=>"Food Bank", "shelter"=>"Shelter", "foodpantry"=>"Food Pantry", "soupkitchen"=>"Soup Kitchen");
             if (isset($_GET["type"]) && isset($_GET["id"])){
                 $serviceType = $_GET["type"];
                 $facilityId = $_GET["id"];
                 $siteId = $_SESSION["siteId"];
                 echo "<h2>".$svc[$serviceType].":</h2>";
                 echo '<input id="serviceTypeToEdit" name="serviceTypeToEdit" type="hidden" value="' . $serviceType . '"/>';
                 // displayClientServiceInputFields($siteId, $facilityId);
                 switch ($serviceType) {
                     case "soupkitchen":
                         displayClientServiceInputFields($siteId, $facilityId);
                         displaySoupKitchenInputFields($facilityId);
                         echo '<p>(*) <i>denotes required fields.</i></p>';
                         echo '<button name="edit" type="submit">Edit Service</button>';
                         break;
                     case "shelter":
                         displayClientServiceInputFields($siteId, $facilityId);
                         displayShelterInputFields($facilityId);
                         echo '<p>(*) <i>denotes required fields.</i></p>';
                         echo '<button name="edit" type="submit">Edit Service</button>';
                         break;
                     case "foodpantry":
                         displayClientServiceInputFields($siteId, $facilityId);
                         echo "</tr></table> ";
                         echo '<p>(*) <i>denotes required fields.</i></p>';
                         echo '<button name="edit" type="submit">Edit Service</button>';
                         break;
                     case "foodbank":
                         displayFoodBankInputFields($facilityId);
                         echo '<p>(*) <i>denotes required fields.</i></p>';
                         echo '<button name="edit" type="submit">Edit Service</button>';
                         break;
                     default:
                         break;
                 }
             } else {
                 //do nothing
             }
             ?>
             
             
         </div>
</form>
</body>
</html>
