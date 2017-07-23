<?php
include 'lib.php';

$pageTitle = "Add New Request";

session_start();
$result = null;
$insertSql = null;
$clientExists = false;

logout(isset($_POST['logout']));
goToUserHome(isset($_POST['userHome']));   
goToClientSearch(isset($_POST['cancel']));

//if (isset($_POST['save']) && !empty($_POST['userName']) && !empty($_POST['facilityId']) && !empty($_POST['itemId']) && !empty($_POST['quantityRequested']) ) {
if (isset($_POST['save']) && !empty($_GET['facilityId']) && !empty($_GET['itemId'])) ) {
   $userName = $_POST['userName'];
   $facilityId = $_POST['facilityId'];
   $itemId = $_POST['itemId'];
   $quantityRequested = $_POST['quantityRequested'];
   


	  $insertSql = "INSERT INTO request (Username, FacilityId, ItemId,  Status, QuantityRequested,, QuantityFulfilled) " .
                   "VALUES ('" . $userName . "','" . $facilityId . "','"  . $itemId . "', 'Pending', '" . $quantityRequested . "','0' )";
      $requestId = insertSql($insertSql);
      if ($requestId > 0) {
         $_SESSION["requestId"] = $requestId;
         // header("Location: /request_detail.php");
		 echo "Request submitted with id ". $requestId;
         exit;
      }            
} 

?>
<html>
   <head>
      <title><?php displayText($pageTitle);?></title>
   </head>
   <body>
      <form action="/request_item.php" method="post">
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
                  <strong>User Name</strong>
               </label> 
               <input name="userName" required="" type="text" />
            </p>
            <p>
               <label>
                  <strong>Facility ID</strong>
               </label> 
               <input name="facilityId" required="" type="text" />
            </p>
            <p>
               <label>
                  <strong>Item ID</strong>
               </label> 
               <input name="itemId" required="" type="text" />
            </p>
            
            <p>
               <label>
                  <strong>quantity Requested</strong>
               </label> 
               <input name="quantityRequested" type="text" />
            </p>
            
            <p>
               <button name="save" type="submit">Submit Request</button>
               
            </p>
         </div>
         
      </tbody>
   </table>
</form>
</body>
</html>
