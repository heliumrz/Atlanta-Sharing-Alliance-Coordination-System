<?php
include 'lib.php';

$pageTitle = "Add New Service";

session_start();

logout(isset($_POST['logout']));
goToUserHome(isset($_POST['userHome']));
goToClientSearch(isset($_POST['cancel']));

if (isset($_POST['save']) && $_POST['serviceType'] != '0' ) {

	$serviceType = $_POST['serviceType'];
	$_SESSION["serviceType"] = $serviceType;
	header('Location: create_service.php');
	exit();
//	$insertSql = "INSERT INTO request (Username, FacilityId, ItemId, QuantityRequested, Status, QuantityFulfilled) " .
  //                 "VALUES ('" . $userName . "','" . $facilityId . "','"  . $itemId . "', '" . $quantityRequested . "','Pending','0' )";
	  
	  // $insertSql = "INSERT INTO request (NULL, '" . $userName . "','" . $facilityId . "','"  . $itemId . "','', '" . $quantityRequested . "', '' )";
	  
      // $requestId = insertSql($insertSql);
		 //       if ($requestId > 0) {
		 //          $_SESSION["requestId"] = $requestId;
		 //          // header("Location: /request_detail.php");
		 // echo "Request submitted with id ". $requestId;
		 //
		 //          exit;
		 //       } else {
		 //          echo "Error: " . $insertSql . "<br>" .$requestId;
		 //       }  
} 
?>
<html>
   <head>
      <title><?php displayText($pageTitle);?></title>
   </head>
   <body>
      <form action="./add_service.php" method="post">
         <div>
            <div style="float: left"><strong><?php displayText($pageTitle);?></strong>
            </div>
            <?php 
               displayLogout();
               displayUserHome();
            ?>
         </div>
         <br>
         <div>
            <p>
               <label>
                  <strong>Select the type of service</strong>
               </label> 
			   <select id="serviceType" name="serviceType">                      
			     <option value="0">--Select Service--</option>
			     <option value="foodbank">Food Bank</option>
				 <option value="foodpantry">Food Pantry</option>
			     <option value="soupkitchen">Soup Kitchen</option>
			     <option value="shelter">Shelter</option>
			   </select>
            </p>
            <p>
               <button name="save" type="submit">Next</button>
            </p>
         </div>         
	 </form>
	</body>
</html>
