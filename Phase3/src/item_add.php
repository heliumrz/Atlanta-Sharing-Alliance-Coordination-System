<?php
   include 'lib.php';
  
   $pageTitle = "Add New Item";  
   
   session_start();
   $result = null;
   $added = false;
   
   // Ensure session is valid. If not, go to login page.
   checkValidSession();
   
   // Inlude in all pages
   logout(isset($_POST['formAction']) && ($_POST['formAction'] == 'logout'));
   goToUserHome(isset($_POST['formAction']) && ($_POST['formAction'] == 'userHome'));
   goToItemSearch(isset($_POST['itemSearch']));
   
   $username = $_SESSION["username"];
     
   $sql = "SELECT t3.FacilityId, t3.FacilityName FROM User t1 LEFT JOIN SiteToService t2 ON t1.SiteId = t2.SiteId LEFT JOIN FoodBank t3 ON t2.FacilityId = t3.FacilityId WHERE username = '" . $username . "'";
   $result = executeSql($sql);
   $row = $result->fetch_assoc();
   $bankName = $row["FacilityName"];
   $facilityId = $row["FacilityId"];
                 
   
   
   if (isset($_POST['addItem'])&& !empty($_POST['expirationDate'])&& !empty($_POST['itemName']) && !empty($_POST['storageType']) && !empty($_POST['category']) && !empty($_POST['subcategory']) && !empty($_POST['quantity'])) {
	if (is_numeric($_POST['quantity'])) { 
		
   		$expirationDate = $_POST['expirationDate'];
		$storageType = $_POST['storageType'];
		$category = $_POST['category'];
		$subcategory = $_POST['subcategory'];
		$itemName = $_POST['itemName'];
		$quant = $_POST['quantity'];
	   
		$sql = "SELECT tdi.facilityId, tdi.availableQuantity, fba.facilityName, itm.itemId, itm.name itemName, itm.storageType, itm.expDate, itm.category, itm.subcategory, tdi.availableQuantity " .
             "FROM FoodBankToItem tdi, item itm, sitetoservice sts, foodbank fba " .
             "WHERE tdi.itemId = itm.itemId " .
             "AND tdi.facilityId = sts.facilityId " .
             "AND tdi.facilityId = fba.facilityId " .
             "AND tdi.facilityId = '" . $facilityId . "' ".
			 "AND itm.storageType = '" . $storageType . "' ".
			 "AND itm.expDate = '" . $expirationDate . "' ".
			 "AND itm.category = '" . $category . "' ".
			 "AND itm.subcategory = '" . $subcategory . "' ".
			 "AND itm.name like '%" . $itemName . "%' ";
		

      $result = executeSql($sql);
	  if ($result->num_rows == 1) {
		// item already exit, need to add the quantity		
         $row = $result->fetch_assoc();
         $itemId = $row["itemId"];
		 //echo $itemId;
         $quant = $row["availableQuantity"] + $quant;
		 $query ="DELETE FROM Item WHERE ItemId = '" . $itemId . "' ";
         executeSql($query);
      }
	  // add the new or updated item.
         $insertSql = "INSERT INTO Item (ItemId,expDate,StorageType,Category,SubCategory,name) " . 
                      "VALUES (NULL,'" . $expirationDate . "','" . $storageType . "','"  . $category . "','"  . $subcategory ."','" . $itemName . "')";
		 $itemId = insertSql($insertSql);
		 $query = "INSERT INTO FoodBankToItem (FacilityId, ItemId,AvailableQuantity) " . 
                   "VALUES ('$facilityId','$itemId','$quant')";
		 insertSql($query);
		 
		if ($itemId > 0) {
            $added = true;
         } else {
            echo "Error: " . $insertSql . "<br>";
         } 
	  
	}
	else{
		echo '<script language="javascript">';
		echo 'alert("Please enter a number in Quantity.")';
		echo '</script>';
	}
   }
   else{
	if (isset($_POST['addItem'])){
		echo '<script language="javascript">';
		echo 'alert("Expiration Date, Storage Type, Category, Sub-Category and Item Name are required in Add New Item.")';
		echo '</script>';
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
      <?php displayFormHeader($MAIN_FORM,$ITEM_ADD_URL); ?>
         <div>
            <?php displayPageHeading($pageTitle); ?>            
            <?php 
               displayLogout();
               displayUserHome();
            ?>
         </div>
        <br>
         <div>
            <?php displayItemAddDataField($bankName); ?>
            <p>
               <?php 
				echo '<button name="addItem" type="submit" onClick="validationRequired=true">Add Item</button>'; 
				echo '<button name="itemSearch" type="submit" onClick="validationRequired=true">Item Search in System</button>'; ?>
               
               <?php displayHiddenField(); ?>
            </p>
         </div>
         <?php
		 $sql = "SELECT tdi.facilityId, fba.facilityName, itm.itemId, itm.name itemName, itm.storageType, itm.expDate, itm.category, itm.subcategory, tdi.availableQuantity " .
             "FROM FoodBankToItem tdi, item itm, sitetoservice sts, foodbank fba " .
             "WHERE tdi.itemId = itm.itemId " .
             "AND tdi.facilityId = sts.facilityId " .
             "AND tdi.facilityId = fba.facilityId " .
			 "AND tdi.facilityId = '" . $facilityId . "'".
			 "ORDER BY itm.ItemId DESC";
	     $result = executeSql($sql);
       if (isset($_POST['addItem']) && $added) {
				echo "
      <label>
         <strong>Added item into $bankName:</strong>
      </label>
      <table border='1' class='altcolor'>
         <thead>
            <tr>
               <th align='left' class='hide'>Facility Id</th>
               <th align='left'>Facility Name</th>
               <th align='left' class='hide'>Item Id</th>
               <th align='left'>Item Name</th>
               <th align='left'>Storage Type</th>
               <th align='left'>Expiration Date</th>
               <th align='left'>Category</th>
               <th align='left'>Sub-Category</th>
               <th align='left'>Available Quantity</th>
            </tr>
         </thead>
         <tbody>";

      while($row = $result->fetch_assoc()) {
            echo "
            <tr>
               <td class='hide'>" . $row['facilityId'] . "</td>
               <td>" . $row['facilityName'] . "</td>
               <td class='hide'>" . $row['itemId'] . "</td>
               <td>" . $row['itemName'] . "</td>
               <td>" . $row['storageType'] . "</td>
               <td>" . $row['expDate'] . "</td>
               <td>" . $row['category'] . "</td>
               <td>" . $row['subcategory'] . "</td>
               <td>" . $row['availableQuantity'] . "</td>
            </tr>";
      }
      echo "
         </tbody>
      </table>
      <br>
      ";
    }
        ?>
      </form>
   </body>
</html>
