<?php
   include 'lib.php';
   
   // input field for facilityName
function displayFoodbankInputFields() {
   $EMPTY_STRING = "";
   echo ' <p>
       <label>Facility Name: </label>
       <input id="facilityName" name="facilityName" type="text" value="' . $EMPTY_STRING . '" required/>
       </p>';
}

function displayClientServiceInputFields() {
   $EMPTY_STRING = "";
   echo '<table>
               <col width="200px">
               <col width="300px">
               <tr>
       <td align="left">Facility Name<sup> *</sup>: </td>
       <td align="left"><input id="facilityName" name="facilityName" type="text" value="' . $EMPTY_STRING . '" required/></td>
       </tr><tr>
       <td align="left">Eligibility Condition<sup> *</sup>: </td>
       <td align="left"><input id="EligibilityCondition" name="EligibilityCondition" type="text" value="' . $EMPTY_STRING . '" required/></td>
       </tr><tr>
       <td align="left">Hours Of Operation<sup> *</sup>: </td>
       <td align="left"><input id="HoursOfOperation" name="HoursOfOperation" type="text" value="' . $EMPTY_STRING . '" required/></td>
       </tr><tr> ';	
}
function displaySoupKitchenInputFields() {
   $EMPTY_STRING = "";
   echo '
        <td align="left">Seat Available<sup> *</sup>: </td>
       <td align="left"><input id="SeatAvail" name="SeatAvail" type="text" value="' . $EMPTY_STRING . '" required/></td>
       </tr><tr>
       <td align="left">Seat Total<sup> *</sup>: </td>
       <td align="left"><input id="SeatTotal" name="SeatTotal" type="text" value="' . $EMPTY_STRING . '" required/></td>
       </tr><tr> ';	
}
function displayShelterInputFields() {
   $EMPTY_STRING = "";
   echo ' 
        <td align="left">Bunk Type<sup> *</sup>: </td>
       <td align="left">
        <select id="BunkType" name="BunkType">
             <option value="male/female/mixed">Male/Female/Mixed</option>
             <option value="male">Male</option>
             <option value="female">Female</option>
             <option value="mixed">Mixed</option>
        </select>
       </td>
       </tr><tr>
       <td align="left">Bunk Capacity (Male)<sup> *</sup>: </td>
       <td align="left"><input id="BunkCapacityMale" name="BunkCapacityMale" type="text" value="' . $EMPTY_STRING . '" required/></td>
       </tr><tr>
       <td align="left">Bunk Capacity (Female)<sup> *</sup>: </td>
       <td align="left"><input id="BunkCapacityFemale" name="BunkCapacityFemale" type="text" value="' . $EMPTY_STRING . '" required/></td>
       </tr><tr>
       <td align="left">Bunk Capacity (Mixed)<sup> *</sup>: </td>
       <td align="left"><input id="BunkCapacityMixed" name="BunkCapacityMixed" type="text" value="' . $EMPTY_STRING . '" required/></td>
       </tr><tr>';	
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

function addFacilityToShelter($FacilityId, $BunkType, $BunkCapacityMale, $BunkCapacityFemale, $BunkCapacityMixed) {
	$sql = "INSERT INTO Shelter (FacilityId, BunkType, BunkCapacityMale, BunkCapacityFemale, BunkCapacityMixed, BunkCountMale, BunkCountFemale, BunkCountMixed) VALUES (" . $FacilityId . ",'". $BunkType . "',". $BunkCapacityMale . ",". $BunkCapacityFemale . ",". $BunkCapacityMixed . ",". $BunkCapacityMale . ",". $BunkCapacityFemale . ",". $BunkCapacityMixed .")";
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
$pageTitle = "Create Service"; 
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
		$BunkCapacityMale = $_POST['BunkCapacityMale'];
		$BunkCapacityFemale = $_POST['BunkCapacityFemale'];
		$BunkCapacityMixed = $_POST['BunkCapacityMixed'];
		$FacilityId = addFacilityToServiceTable();
		addFacilityToClientServiceTable($FacilityId, $SiteId, $FacilityName, $EligibilityCondition, $HoursOfOperation, $FacilityType);
		addFacilityToShelter($FacilityId, $BunkType, $BunkCapacityMale, $BunkCapacityFemale, $BunkCapacityMixed);
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
           <form action="./login.php">
               <input type="submit" value="Logout" />
           </form>
           </div>
            <div style="float: right">
            <form action="./user_home.php">
                <input type="submit" value="User Home" />
            </form>
            </div>
            
       <?php
       $svc = array("foodbank"=>"Food Bank", "shelter"=>"Shelter", "foodpantry"=>"Food Pantry", "soupkitchen"=>"Soup Kitchen");
       echo "<h1>".$pageTitle."</h1>";
       if (!empty($_SESSION['message'])){
           echo "<p>".$_SESSION['message']."</p>";
       }
       ?>
     </div>
      <form action="./create_service.php" method="post">
			 <?php
			 $serviceType = $_POST["serviceType"];
			 echo "<h2>".$svc[$serviceType]."</h2>";
             echo "<div>";
             # we need this type to find the right tables to update
			 echo '<input id="serviceTypeToAdd" name="serviceTypeToAdd" type="hidden" value="' . $serviceType . '"/>';
			 
			 switch ($serviceType) {
				case "soupkitchen":
                    displayClientServiceInputFields();
					displaySoupKitchenInputFields();
                    echo '</tr></table><p>(*) <i>denotes required fields.</i></p>';
					echo '<button name="create" type="submit">Create Service</button>';
                    break;
				case "shelter":
                    displayClientServiceInputFields();
					displayShelterInputFields();
					echo '</tr></table><p>(*) <i>denotes required fields.</i></p>';
                    echo '<button name="create" type="submit">Create Service</button>';
                    break;
                case "foodbank":
                    displayFoodbankInputFields();
					echo '<p>(*) <i>denotes required fields.</i></p>';
                    echo '<button name="create" type="submit">Create Service</button>';
                    break;
                case "foodpantry":
                    displayClientServiceInputFields();
                    echo '</tr></table><p>(*) <i>denotes required fields.</i></p>';
                    echo '<button name="create" type="submit">Create Service</button>';
                    break;
                default:
			 		echo " ";
			 		break;
			 }
			 ?>
			 
         </div>
</form>
</body>
</html>
