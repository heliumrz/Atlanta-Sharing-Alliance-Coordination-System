<?php
   include 'lib.php';
  
   $pageTitle = "Item Requests Status For Current User";  
    
   if (!isset($_SESSION)) {
       session_start();
   }
	checkValidSession();
	
	logout(isset($_POST['logout']));
	goToUserHome(isset($_POST['userHome']));
	
	# Get user's site's facilityId
	$username = $_SESSION["username"];
	//echo "username is: " . $username . "<br>";
	
	// $_GET['editRequest'], need to have requestId and updateQuantityRequested passed in
	if (!empty($_GET['editRequest'])) {
		//echo "Handle edit request...<br>";
		$requestId = $_GET['requestId'];
		$updateQuantityRequested = $_GET['editRequest'];
		
		if (!is_numeric($updateQuantityRequested)) {
			echo "Please enter numeric number for request quantity. <br>";
			exit;
		}

		//echo "requestId: " . $requestId . "<br>";
		//echo "updateQuantityRequested: " . $updateQuantityRequested . "<br>";

		// Update request
		$sql = "UPDATE `request` SET " .
					"quantityRequested = " . $updateQuantityRequested . 
					" WHERE requestId = " . $requestId;
		//echo "Edit query is: " . $sql . "<br>";
		$result = executeSql($sql);
	}
	if (!empty($_GET['cancel'])) {
		//echo "Handle cancel request...<br>";
		$requestId = $_GET['cancel'];
		//echo "cancel request id: " . $requestId . "<br>";
		// Update request
		$sql = "UPDATE `request` SET " .
					"quantityRequested = 0 " . 
					", `request`.`Status` = 'closed' " .
					"WHERE requestId = " . $requestId;
		//echo "Cancel query is: " . $sql . "<br>";
		$result = executeSql($sql);
	}
   
   # Get all requests current user sent
      $sql = 
		  "SELECT `request`.`RequestID`, " .
		  "			`request`.`Username`, " .
		  "			`request`.`FacilityId`, " .
		  "			`request`.`Status`, " .
		  "			`request`.`QuantityRequested`, " .
		  "			`request`.`QuantityFulfilled`, " .
		  "			`foodbanktoitem`.`AvailableQuantity`, " .
		  "			`item`.`Name` AS ItemName, " .
		  "			`item`.`Category`, " .
		  "			`item`.`SubCategory` " .
		  "FROM `request` " .
		  "INNER JOIN `foodbanktoitem` " .
		  "ON `request`.`FacilityId` = `foodbanktoitem`.`FacilityId` AND " .
		  "   `request`.`ItemId` = `foodbanktoitem`.`ItemId` " .
		  "INNER JOIN `item` ON `foodbanktoitem`.`ItemId` = `item`.`ItemId` " .
		  "WHERE `request`.`Username` = '" . $username . "' " .
		  "ORDER BY requestId ";
	  //echo "Query is: " . $sql . "<br>";

      $finalResult = executeSql($sql);

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
		<br>
		<div>
			<?php displayPageHeading($pageTitle); ?>   
		</div>
        <div>
           <p>
              <?php displayHiddenField(); ?>
           </p>
        </div>
        <div class="report_section">
            <table border='1' class='altcolor'>
                <tr>
                    <td class="heading">RequestID</td>
                    <td class="heading">Username</td>
					<td class="heading">FacilityId</td>
					<td class="heading">Status</td>
					<td class="heading">QuantityRequested</td>
					<td class="heading">QuantityFulfilled</td>
					<td class="heading">AvailableQuantity</td>
					<td class="heading">ItemName</td>
					<td class="heading">Category</td>
					<td class="heading">SubCategory</td>
					<td class="heading">Action</td>
                </tr>							
				<br>
                <?php		
				
				//echo "result num: " . $finalResult->num_rows .  "<br>";
				if ($finalResult->num_rows > 0) {
					while($row = $finalResult->fetch_assoc()) {
						print "<td>" . $row['RequestID'] . "</td>";
						print "<td>" . $row['Username'] . "</td>";
						print "<td>" . $row['FacilityId'] . "</td>";
						print "<td>" . $row['Status'] . "</td>";
						print "<td>" . $row['QuantityRequested'] . "</td>";
						print "<td>" . $row['QuantityFulfilled'] . "</td>";
						print "<td>" . $row['AvailableQuantity'] . "</td>";
						print "<td>" . $row['ItemName'] . "</td>";
						print "<td>" . $row['Category'] . "</td>";
						print "<td>" . $row['SubCategory'] . "</td>";
						if ($row['Status'] == 'pending') {
							print "<td>" .
									"<a href='user_request_status.php?cancel=" . $row['RequestID'] . "'>Cancel</a><br>" .
									"<form action='user_request_status.php' method='get'>
										Edit Quantity Requested
										<input type='text' name='editRequest'>
										<input type='hidden' name='requestId' value=". $row['RequestID'] . "> 
										<input type='submit' value='Submit'>
									 </form>" .
							  	   "</td>";
						 }
						print "</tr>";
					}
				}
					
				?>
            </table>						
        </div>
   </body>
</html>