<?php
include 'lib.php';

$pageTitle = "Add New Request";
$displayResult = false;

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
    $facilityId = $_POST['facilityId'];
    $facilityName = $_POST['facilityName'];
    $availQuant = $_POST['availQuant'];
    $result = getItemwithItemId($itemId);
    $item = $result->fetch_assoc();
        $itemName = $item['Name'];
        $storageType = $item['StorageType'];
        $expDate = $item['ExpDate'];
    
    $displayItemToRequest = true;
}

if (isset($_POST['save']) ) {
   $userName = $_POST['userName'];
   $facilityName = $_POST['facilityName'];
   $facilityId = $_POST['facilityId'];
   $itemId = $_POST['itemId'];
   $itemName = $_POST['itemName'];
   $quantityRequested = $_POST['quantityRequested'];
   $availQuant = $_POST['availQuant'];
   
   if ( preg_match('/^\d+$/',$quantityRequested)) {
     // valid input.
     if ($availQuant < $quantityRequested) {
       $_SESSION['message'] = "Request quantity cannot be more than available quantity. Please Enter another quantity.";
       $displayResult = true;
       $displayItemToRequest = true;
      } else {
       $insertSql = "INSERT INTO request (Username, FacilityId, ItemId,  Status, QuantityRequested, QuantityFulfilled) " .
                      "VALUES ('" . $userName . "','" . $facilityId . "','"  . $itemId . "', 'pending', '" . $quantityRequested . "','0' )";
       $requestId = insertSql($insertSql);
         if ($requestId > 0) {
            $_SESSION['message'] = "Request submitted with id ". $requestId;
            $displayResult = true;
         } else {
             $_SESSION['message'] = "There was a problem submitting your request. Try again later.";
             $displayResult = true;
         }
      }
    } else {
        $_SESSION['message'] = "ERROR: Invalid value in Request  Quantity field. Try again";
        $displayResult = true;
        $displayItemToRequest = true;
    }
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
   <form action="./login.php">
       <input type="submit" value="Logout" />
   </form>
   </div>
   <div style="float: right">
   <form action="./user_home.php">
       <input type="submit" value="User Home" />
   </form>
   </div>
   
      <form action="/request_item.php" method="post">
         <div>
            <div style="float: left"><strong><?php displayText($pageTitle);?></strong>
            </div>
         </div>
         <p>
             <br/>
         </p>
         <div>
                <?php
                if ($displayItemToRequest) {
                    echo '
                        <table>
                               <col width="200px">
                               <col width="300px">
                               <tr>
                       <td align="left">Facility Name: </td>
                       <td align="left">'.$facilityName.'</td>
                       </tr><tr>
                       <td align="left">Item: </td>
                       <td align="left">'.$itemName.'</td>
                       </tr><tr>
                       <td align="left">Available Quanitity: </td>
                       <td align="left">'.$availQuant.'</td>
                       </tr><tr>
                    <input name="itemId" value="'. $itemId. '" type="hidden" />
                    <input name="itemName" value="'. $itemName. '" type="hidden" />
                    <input name="facilityName" value="'. $facilityName. '" type="hidden" />
                    <input name="facilityId" value="'. $facilityId. '" type="hidden" />
                    <input name="userName" value="'. $_SESSION['username']. '" type="hidden" />
                    <input id="availQuant" name="availQuant" type="hidden" value="'. $availQuant .'"/>
                    <td align="left">Quantity Requested: </td>
                    <td align="left"><input name="quantityRequested" type="text" required/></td>
                    </tr><tr>
                    <td align="left"></td>
                    <td align="left"><button name="save" type="submit">Submit Request</button></td>
                    ';
                }
                if ($displayResult) {
                    echo "<i>".$_SESSION['message']."</i><br/>";
                    echo "<a href='./item_search.php'>Back to Item Search</a>";
                }
                ?>
         </div>
</form>
</body>
</html>
