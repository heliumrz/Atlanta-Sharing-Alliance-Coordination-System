<?php
include 'lib.php';

$pageTitle = "Add New Request";

session_start();

logout(isset($_POST['logout']));
goToUserHome(isset($_POST['userHome']));   
goToClientSearch(isset($_POST['cancel']));

function getItemwithItemId($itemId){
    $sql = "SELECT * FROM item where ItemId=".$itemId;
    return executeSql($sql);
}
$displayItemToRequest = false;

if (isset($_POST['request']) && isset($_POST['facilityId']) && isset($_POST['itemId'])) {
    //just ask for quantity
    $itemId = $_POST['itemId'];
    $FacilityId = $_POST['facilityId'];
    $facilityName = $_POST['facilityName'];
    $result = getItemwithItemId($itemId);
    while ($item = $result->fetch_assoc()){
        $itemName = $item['Name'];
        $storageType = $item['StorageType'];
        $expDate = $item['ExpDate'];
    }
    $displayItemToRequest = true;
}

if (isset($_POST['save']) ) {
   $userName = $_POST['userName'];
   $facilityId = $_POST['facilityId'];
   $itemId = $_POST['itemId'];
   $quantityRequested = $_POST['quantityRequested'];
   
    $insertSql = "INSERT INTO request (Username, FacilityId, ItemId,  Status, QuantityRequested, QuantityFulfilled) " .
                   "VALUES ('" . $userName . "','" . $facilityId . "','"  . $itemId . "', 'Pending', '" . $quantityRequested . "','0' )";
    $requestId = insertSql($insertSql);
    if ($requestId > 0) {
         $_SESSION['message'] = "Request submitted with id ". $requestId;
      } else {
          $_SESSION['message'] = "There was a problem submitting your request. Try again later.";
      }
      $displayResult = true;
} 
?>
<html>
   <head>
       <?php 
          displayTitle($pageTitle);
          displayCss();
       ?>
   </head>
   <?php displayBodyHeading(); ?>
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
      <form action="/request_item.php" method="post">
         <div>
            <div style="float: left"><strong><?php displayText($pageTitle);?></strong>
            </div>
         </div>
         <br>
         <div>
                <?php
                if ($displayItemToRequest) {
                    echo '
                        <p>
                           <label>
                              <strong>Facility Name: </strong>
                           </label> 
                           <strong>'.$facilityName.'<strong/>
                        </p>
                    <label>
                       <strong> Item: </strong>
                    </label> 
                    <strong>'.$itemName.'</strong>
                 </p>
                    <input name="itemId" value="'. $itemId. '" type="hidden" />
                    <input name="facilityId" value="'. $FacilityId. '" type="hidden" />
                    <input name="userName" value="'. $_SESSION['username']. '" type="hidden" />
                 <p>
                    <label>
                       <strong>quantity Requested</strong>
                    </label> 
                    <input name="quantityRequested" type="text" required/>
                 </p>
            
                 <p>
                    <button name="save" type="submit">Submit Request</button>
                    ';
                }
                if ($displayResult) {
                    echo $_SESSION['message'];
                }
                ?>
         </div>
</form>
</body>
</html>
