<?php
   include 'lib.php';
  
   $pageTitle = "Item Search";  
   
   session_start();
   $result = null;
   $deleteMsg = "";
   $updateItemQuantityMsg = "";
   
   // Ensure session is valid. If not, go to login page.
   checkValidSession();
   
   // Inlude in all pages
   logout(isset($_POST['formAction']) && ($_POST['formAction'] == 'logout'));
   goToUserHome(isset($_POST['formAction']) && ($_POST['formAction'] == 'userHome'));
   
   goToAddNewItem(isset($_POST['addNewItem']));
   
   if (isset($_POST['search'])) {
      $siteId = $_SESSION['siteId'];
      $facilityId = $_POST['facilityId'];
      $expirationDateFrom = $_POST['expirationDateFrom'];
      $expirationDateTo = $_POST['expirationDateTo'];
      $storageType = $_POST['storageType'];
      $category = $_POST['category'];
      $subcategory = $_POST['subcategory'];
      $itemName = $_POST['itemName'];
      
      $sql = "SELECT tdi.facilityId, fba.facilityName, itm.itemId, itm.name itemName, " .
             "       itm.storageType, itm.expDate, itm.category, itm.subcategory, tdi.availableQuantity, " .
             "       CASE sts.siteId WHEN " . $siteId . " THEN 1 ELSE 0 END AS owned " .
             "  FROM FoodBankToItem tdi, item itm, sitetoservice sts, foodbank fba " .
             " WHERE tdi.itemId = itm.itemId " .
             "   AND tdi.facilityId = sts.facilityId " .
             "   AND tdi.facilityId = fba.facilityId " ;
                  
      if (!empty($facilityId )) {
         $sql = $sql . "AND tdi.facilityId = '" . $facilityId . "' ";
      }
      if (!empty($expirationDateFrom )) {
         $sql = $sql . "AND itm.expDate >= str_to_date('" . $expirationDateFrom . "','%Y-%m-%d') ";
      }
      if (!empty($expirationDateTo )) {
         $sql = $sql . "AND itm.expDate <= str_to_date('" . $expirationDateTo . "','%Y-%m-%d') ";
      }
      if (!empty($storageType )) {
         $sql = $sql . "AND itm.storageType = '" . $storageType . "' ";
      }
      if (!empty($category )) {
         $sql = $sql . "AND itm.category = '" . $category . "' ";
      }
      if (!empty($subcategory )) {
         $sql = $sql . "AND itm.subcategory = '" . $subcategory . "' ";
      }
      if (!empty($itemName )) {
         $sql = $sql . "AND itm.name like '%" . $itemName . "%' ";
      }
      $result = executeSql($sql);
   }
   
   if (isset($_POST['deleteItem'])) {
      $facilityId = $_POST['facilityId'];
      $itemId = $_POST['itemId'];
      $deletesql = "DELETE FROM FoodBankToItem " . 
                   " WHERE facilityId = " . $facilityId . " " .
                   "   AND itemId = " . $itemId;
             
      $result = executeSql($deletesql);
      
      $updatesql = "UPDATE Request " . 
                   "   SET status = 'closed' " .
                   " WHERE facilityId = " . $facilityId . " " .
                   "   AND itemId = " . $itemId . " " .
                   "   AND status = 'pending'";
      
      $result = executeSql($updatesql);
      
      $deleteMsg = "Item deleted successfully.";
   }

   if (isset($_POST['updateItemQuantity'])) {
      $facilityId = $_POST['facilityId'];
      $itemId = $_POST['itemId'];
      $availQty = $_POST['availableQuantity'];
      $updatedAvailQty = $_POST['updatedAvailableQuantity'];
      
      if (is_numeric($updatedAvailQty) && ($updatedAvailQty < $availQty) && ($updatedAvailQty >= 0)) {
         $sql = "UPDATE FoodBankToItem " . 
                "   SET availableQuantity = " . $updatedAvailQty . " " .
                " WHERE facilityId = " . $facilityId . " " .
                "   AND itemId = " . $itemId;
                
         $result = executeSql($sql);
         $updateItemQuantityMsg = "Available quantity updated successfully.";
      } else {
         $updateItemQuantityMsg = "Please enter a valid available quantity. Available quantity must be less than original quantity and greater than zero.";
      }
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
      <?php displayFormHeader($MAIN_FORM,$ITEM_SEARCH_URL); ?>
         <div>
            <?php displayPageHeading($pageTitle); ?>            
            <?php 
               displayLogout();
               displayUserHome();
            ?>
         </div>
         <br>
         <div>
            <?php displayItemSearchDataField(); ?>
            <p>
               <?php displaySearchSubmitButton(); ?>
               <?php displayAddNewItemSubmitButton(); ?>
               <?php displayHiddenField(); ?>
            </p>
         </div>
     </form>
         <?php
            if (!(empty($deleteMsg))) {
               echo $deleteMsg;
            }
            
            if (!(empty($updateItemQuantityMsg))) {
               echo $updateItemQuantityMsg;
            }
         
            if (isset($_POST['search'])) {
               displayItemSearchResult($result);
            }
        ?>
      </form>
   </body>
</html>
