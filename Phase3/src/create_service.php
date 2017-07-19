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

   function addFacilityToClientServiceTable($FacilityId, $SiteId, $FacilityName, $EligibilityCondition, $HoursOfOperation) {
   	$sql = "INSERT INTO ClilentService (FacilityId, SiteId, FacilityName, EligibilityCondition, HoursOfOperation) VALUES (". 
   		$FacilityId .",'". $SiteId ."','". $FacilityName ."','". $EligibilityCondition ."','". $HoursOfOperation ."')";
       return insertSql($sql);
   }

   function addFacilityToSoupKitchen($FacilityId, $SeatAvail, $SeatTotal) {
   	$sql = "INSERT INTO SoupKitchen (FacilityId, SeatAvail, SeatTotal) VALUES ('" . 
   		$FacilityId . "','". $SeatAvail . "','". $SeatTotal . "')";
   	return insertSql($sql);
   }

   function addFacilityToFoodBank($FacilityId) {
   	$sql = "INSERT INTO FoodBank (FacilityId) VALUES ('" . $FacilityId . "')";
   	return insertSql($sql);
   }

   function addFacilityToFoodPantry($FacilityId) {
   	$sql = "INSERT INTO FoodPantry (FacilityId) VALUES ('" . $FacilityId . "')";
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
   
   $svc = [
       "soupkitchen" => "Soup Kitchen",
       "foodpantry" => "Food Pantry",
	   "shelter" => "Shelter",
	   "foodbank" => "Food Bank",
   ];
   $pageTitle = "Create a new service"; //. $svc[ $_SESSION["serviceType"] ];
  
   session_start();
   // $serviceType = $_SESSION["serviceType"];
   // Ensure session is valid. If not, go to login page.
   checkValidSession();
   
   // Inlude in all pages
   logout(isset($_POST['formAction']) && ($_POST['formAction'] == 'logout'));
   goToUserHome(isset($_POST['formAction']) && ($_POST['formAction'] == 'userHome'));

   if (isset($_POST['create']) && !empty($_POST['serviceTyeToAdd'])) {
	   $SiteId = $_POST['Site'];
	   $FacilityName = $_POST['facilityName'];
	   $EligibilityCondition = $_POST['EligibilityCondition'];
	   $HoursOfOperation = $_POST['HoursOfOperation'];
	   switch ($serviceType) {
	   	case 'soupkitchen':
			$SeatAvail = $_POST['SeatAvail'];
			$SeatTotal = $_POST['SeatTotal'];				
	   		$FacilityId = addFacilityToServiceTable();
			addFacilityToClientServiceTable($FacilityId, $SiteId, $FacilityName, $EligibilityCondition, $HoursOfOperation);
			addFacilityToSoupKitchen($FacilityId, $SeatAvail, $SeatTotal);
			addToSiteToServiceTable($FacilityId, $SiteId);
	   		break;
		case 'shelter':
			$BunkType = $_POST['BunkType']; 
			$BunkCountMale = $_POST['BunkCountMale'];
			$BunkCountFemale = $_POST['BunkCountFemale'];
			$BunkCountMixed = $_POST['BunkCountMixed'];
			$FacilityId = addFacilityToServiceTable();
			addFacilityToClientServiceTable($FacilityId, $SiteId, $FacilityName, $EligibilityCondition, $HoursOfOperation);
			addFacilityToShelter($FacilityId, $BunkType, $BunkCountMale, $BunkCountFemale, $BunkCountMixed);
			addToSiteToServiceTable($FacilityId, $SiteId);
			break;
		case 'foodbank':
			$FacilityId = addFacilityToServiceTable();
			addFacilityToClientServiceTable($FacilityId, $SiteId, $FacilityName, $EligibilityCondition, $HoursOfOperation);
			addFacilityToFoodBank($FacilityId);
			addToSiteToServiceTable($FacilityId, $SiteId);
			break;
		case 'foodpantry':
			$FacilityId = addFacilityToServiceTable();
			addFacilityToClientServiceTable($FacilityId, $SiteId, $FacilityName, $EligibilityCondition, $HoursOfOperation);
			addFacilityToFoodPantry($FacilityId);
			addToSiteToServiceTable($FacilityId, $SiteId);
			break;
	   	default:
	   		# do nothing
	   		break;
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
   <body>
	   <?php displayPageHeading($pageTitle); ?>            
      <form action="./add_service.php" method="post">
         <div>
            <?php 
               displayLogout();
               displayUserHome();
            ?>
         </div>
         <br>
         <div>
			 <?php
			 $serviceType = $_SESSION["serviceType"];
			 # we need this type to find the right tables to update
			 echo '<input id="serviceTypeToAdd" name="" type="hidden" value="' . $serviceType . '"/>';
			 displaySiteNamesOptions();
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
