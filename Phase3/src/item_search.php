<?php
   include 'lib.php';
  
   $pageTitle = "Item Search";  
   
   session_start();
   $result = null;
   
   // Ensure session is valid. If not, go to login page.
   checkValidSession();
   
   // Inlude in all pages
   logout(isset($_POST['formAction']) && ($_POST['formAction'] == 'logout'));
   goToUserHome(isset($_POST['formAction']) && ($_POST['formAction'] == 'userHome'));
   
   goToAddNewItem(isset($_POST['addNewItem']));
   
   if (isset($_POST['search'])) {
      $facilityId = $_POST['facilityId'];
      $expirationDate = $_POST['expirationDate'];
      $storageType = $_POST['storageType'];
      $category = $_POST['category'];
      $subcategory = $_POST['subcategory'];
      $itemName = $_POST['itemName'];
   
      $sql = "SELECT tdi.facilityId, fba.facilityName, itm.itemId, itm.name itemName, itm.storageType, itm.expDate, itm.category, itm.subcategory, tdi.availableQuantity " .
             "FROM FoodBankToItem tdi, item itm, sitetoservice sts, foodbank fba " .
             "WHERE tdi.itemId = itm.itemId " .
             "AND tdi.facilityId = sts.facilityId " .
             "AND tdi.facilityId = fba.facilityId " ;
                  
      if (!empty($facilityId )) {
         $sql = $sql . "AND tdi.facilityId = '" . $facilityId . "' ";
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
         <?php
            if (isset($_POST['search'])) {
               displayItemSearchResult($result);
            }
        ?>
      </form>
   </body>
</html>
