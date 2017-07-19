<?php
   include 'lib.php';
   
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
	   switch ($serviceType) {
	   	case 'soupkitchen':
	   		# code...
	   		break;
		case 'shelter':
			#code
			break;
		case 'foodbank':
			#code
			break;
		case 'foodpantry':
			#code
			break;
	   	default:
	   		# code...
	   		break;
	   }
	} else {
		//do nothing.
	}	
   // // Handle data update logic
   // if (isset($_POST['updateClient']) && !empty($clientId) && !empty($username)) {
   //    updateClientData($clientId,$username,$_POST);
   // }
   //
   // // Go to Client Check-In
   // if (isset($_POST['checkinClient']) && !empty($clientId) && !empty($username)) {
   //    goToClientCheckin(true);
   // }
   //
   // if (!empty($clientId)) {
   //    $result = retrieveClientFromId($clientId);
   //
   //    if ($result->num_rows > 0) {
   //       // Client exists, pull back data and process.
   //       $clientRow = $result->fetch_assoc();
   //
   //       // Retrieve history
   //       $clientModificationHistory = retrieveClientModificationHistory($clientId);
   //       $clientServiceUsageHistory = retrieveClientServiceUsageHistory($clientId);
   //    } else {
   //       // If no client exist, go to Client Search screen
   //       goToClientSearch(true);
   //    }
   // }
?>
<html>
   <head>
      <?php 
         displayTitle($pageTitle);
         displayCss();
      ?>
      <script>
         <?php displayJavascriptLib();?>
         
		  // function validateInput() {
//             if (validateField("firstName") && validateField("lastName") && validateField("description") && validateValidCharacter("phoneNumber")) {
//                if (validateDataUpdated()) {
//                   return true;
//                } else {
//                   alert(clientNoDataUpdated);
//                   return false;
//                }
//             } else {
//                alert(clientRequiredField);
//                return false;
//             }
//          }
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