<?php
   include 'lib.php';
  
   $pageTitle = "Outstanding Requests";  
    
   if (!isset($_SESSION)) {
       session_start();
   }
	checkValidSession();
	
	# Get user's site's facilityId
	$username = $_SESSION["username"];
	//echo "username is: " . $username . "<br>";
	
	if (!empty($_GET['fullyAccept']) || !empty($_GET['partiallyAccept'])) {
		$requestId = null;
		$acceptNum = null;
		$availableQuantity = null;
		
		if (!empty($_GET['fullyAccept'])) {
			// "Handle fully accept...<br>";
			$requestId = $_GET['fullyAccept'];
			$acceptNum = $_GET['acceptQuantity'];
			$availableQuantity = $_GET['availableQuantity'];
		} else {
			// "Handle fully partially Accept...<br>";
			$requestId = $_GET['requestId'];
			$acceptNum = $_GET['partiallyAccept'];
			$availableQuantity = $_GET['availableQuantity'];
		}
		// echo "requestId: " . $requestId . "<br>";
		// echo "acceptNum: " . $acceptNum . "<br>";
		// echo "availableQuantity: " . $availableQuantity . "<br>";
		
		if ($acceptNum > $availableQuantity) {
	        echo "Not enough available quantity for fully accpeted item";
			exit;
		}
		$sql = "SELECT facilityId, itemId FROM `request` WHERE requestId = " . $requestId;
		$result = executeSql($sql);
		$row = $result->fetch_assoc();
		
		// Update available quantity
		$facilityId = $row['facilityId'];
		$itemId = $row['itemId'];
		$updateAvailableQuantity = $availableQuantity-$acceptNum;
		//echo "updateAvailableQuantity: " . $updateAvailableQuantity . "<br>";
		$sql = "UPDATE `foodbanktoitem` SET availableQuantity = " . $updateAvailableQuantity . 
			   " WHERE facilityId = " . $facilityId . " AND itemId = " . $itemId;
		$result = executeSql($sql);
		
		// Update request
		$sql = "UPDATE `request` SET " .
					"quantityFulfilled = " . $acceptNum . 
					", `request`.`Status` = 'CLOSED' " .
					"WHERE requestId = " . $requestId;
		$result = executeSql($sql);
	}
	if (!empty($_GET['reject'])) {
		//echo "Handle fully reject...<br>";
		$requestId = $_GET['reject'];
		//echo "reject request id: " . $requestId . "<br>";
		// Update request
		$sql = "UPDATE `request` SET " .
					"`request`.`Status` = 'CLOSED' " .
					"WHERE requestId = " . $requestId;
		$result = executeSql($sql);
	}
		
	$sql = "SELECT facilityId " .
	"FROM `user`, `sitetoservice` " .
	"WHERE `user`.`SiteId` = `sitetoservice`.`SiteId` AND username = '" . $username . "' AND facilityId IN " .
	"	(SELECT facilityId " .
	"	FROM `service` " .
	"	WHERE facilityId IN (SELECT `foodbank`.`FacilityId` from `foodbank`))";
	") ";
    
	//echo "Query is: " . $sql . "<br>";
    $result = executeSql($sql);
	$userFoodbank = null;
	if ($result->num_rows > 0) {
		$row = $result->fetch_assoc();
		$userFoodbank = $row['facilityId'];
		//echo "user's site's foodbank's facilityId is: " . $userFoodbank . "<br>";
	} else {
		echo "user's site does not have food bank!";
		exit;
	}
	
	$orderBy = 'availableQuantity';
   if (isset($_GET['orderBy'])) {
	   //echo "order by is set, the value is: " . $_GET['orderBy'] . "<br>";
	   $orderBy = $_GET['orderBy'];
   }
   
   # 2. Get outstanding requestIds
   $sql = "SELECT requestId " .
   	"FROM `request` " . 
   	"INNER JOIN ( " . 
	   "SELECT sumRequests.facilityId, sumRequests.itemId " .
	   "FROM ( " .
	   "	SELECT facilityId, `foodbanktoitem`.`ItemId`, availableQuantity, name, storageType, expDate, category, subCategory " .
	   "	FROM `foodbanktoitem`, `item` " .
	   "	WHERE `foodbanktoitem`.`ItemId` = `item`.`ItemId` AND facilityId = " . $userFoodbank .
	   ") AS filteredFoodbankItem " .
	   "INNER JOIN ( " .
	   "    SELECT facilityId, itemId, SUM(quantityRequested) AS requestSum " .
	   "FROM `request` " .
	   "GROUP BY facilityId, itemId " .
	   ") AS sumRequests " .
	   "ON filteredFoodbankItem.facilityId = sumRequests.facilityId AND " .
	   "	filteredFoodbankItem.itemId = sumRequests.itemId AND " .
	   "	filteredFoodbankItem.availableQuantity < sumRequests.requestSum" .
	   ") AS outstandingFacilityItems " .
	   "ON `request`.`FacilityId` = outstandingFacilityItems.facilityId AND " . 
	   "	`request`.`ItemId` = outstandingFacilityItems.itemId ";
   
   //echo "Query is: " . $sql . "<br>";
   $result = executeSql($sql);
   
   # store outstanding ids in an array, since traversing with fetch_assoc() can only be used once.
   $outstandingReqIds = [];
   while($row = $result->fetch_assoc()) {
   	   //print "outstanding request ids: " . $row['requestId'] . "<br>";
	   array_push($outstandingReqIds, $row['requestId']);
   }
   
   # Request join (FoodBankToItem join Item)
      $sql = "SELECT `request`.requestId, " .
  			 "		 `request`.username, " .
       		 "	  	 `request`.facilityId, " .
			 "	  	 `request`.status, " .
			 "	  	 `request`.quantityRequested, " .
			 "	  	 `request`.quantityFulfilled, " .
       		 "	  	 itemTable.name as itemName, " .
       		 "		 itemTable.availableQuantity, " .
       		 "		 itemTable.storageType, " .
       		 "		 itemTable.expDate, " .
       		 "		 itemTable.category, " .
			 "		 itemTable.subCategory " .
			 "FROM `request` " .
			 "INNER JOIN ( " .
		 	 "		SELECT facilityId, `foodbanktoitem`.itemId, availableQuantity, " .
	         "  		   name, storageType, expDate, category, subCategory " .
			 "	 	FROM `foodbanktoitem`, `item` " .
			 "		WHERE `foodbanktoitem`.`ItemId` = `item`.`ItemId` AND facilityId = " . $userFoodbank .
			 ") AS itemTable " .
			 "ON `request`.`ItemId` = itemTable.itemId " .
		     "ORDER BY " . $orderBy . " ASC";
	  //echo "Query is: " . $sql . "<br>";

      $finalResult = executeSql($sql);

?>
<html>
	<head>
 		<title>View Outstanding Requests</title>
   	</head>
   	<body>
		<div><?php displayPageHeading($pageTitle); ?></div>
		<br>
		<div class="report_section">
			<form action="view_outstanding_requests.php" method="get">
			  <input type="radio" name="orderBy" value="storageType" checked> Storage Type<br>
			  <input type="radio" name="orderBy" value="quantityRequested"> Quantity Requested<br>
			  <input type="radio" name="orderBy" value="availableQuantity"> Available Quantity<br>
			  <input type="radio" name="orderBy" value="category"> Category<br>
			  <input type="radio" name="orderBy" value="subCategory"> Sub Category<br>
			  <input type="submit" value="Submit"><br>
			</form>
		</div>
        <div class="report_section">
            <div class="subtitle">Requests Report</div> 
			<br>
			<?php echo "Note: Outstanding Request Will be Marked in Red" ?> 
			<br>
            <table>
                <tr>
                    <td class="heading">RequestId</td>
                    <td class="heading">Username</td>
					<td class="heading">FacilityId</td>
					<td class="heading">Status</td>
					<td class="heading">QuantityRequested</td>
					<td class="heading">QuantityFulfilled</td>
					<td class="heading">ItemName</td>
					<td class="heading">AvailableQuantity</td>
					<td class="heading">StorageType</td>
					<td class="heading">Category</td>
					<td class="heading">SubCategory</td>
					<td class="heading">Action</td>
                </tr>							

                <?php
				
					//if ($result->num_rows > 0) {
					while($row = $finalResult->fetch_assoc()) {
						//echo "this row request id: " . $row['requestId'] . "<br>";
						// Traverse Ids to see if it's in the outstanding id array. HashSet in php?
						$isOutstanding = false;
						foreach ($outstandingReqIds as $id) {
							//echo "outstanding request id: " . $id . "<br>";
							if ($row['requestId'] == $id) {
								//echo "find outstanding requestId: " . $id . "<br>";
								$isOutstanding = true;
								break;
							}
						}
						if ($isOutstanding == true && $row['status'] == 'pending') {
							print "<tr bgcolor='#FF0000'>";
						} else {
							print "<tr>";
						}
						
						print "<td>" . $row['requestId'] . "</td>";
						print "<td>" . $row['username'] . "</td>";
						print "<td>" . $row['facilityId'] . "</td>";
						print "<td>" . $row['status'] . "</td>";
						print "<td>" . $row['quantityRequested'] . "</td>";
						print "<td>" . $row['quantityFulfilled'] . "</td>";
						print "<td>" . $row['itemName'] . "</td>";
						print "<td>" . $row['availableQuantity'] . "</td>";
						print "<td>" . $row['storageType'] . "</td>";
						print "<td>" . $row['category'] . "</td>";
						print "<td>" . $row['subCategory'] . "</td>";
						if ($row['status'] == 'pending') {
							print "<td>" .
									"<a href='view_outstanding_requests.php?fullyAccept=" . $row['requestId'] . 
										"&acceptQuantity=" . $row['quantityRequested'] . 
										"&availableQuantity=" . $row['availableQuantity']. "'>Fully Accept</a><br>" .
									"<a href='view_outstanding_requests.php?reject=" . $row['requestId'] . "'>Reject</a><br>" .
									"<form action='view_outstanding_requests.php' method='get'>
										Partially Accept
										<input type='text' name='partiallyAccept'>
										<input type='hidden' name='requestId' value=". $row['requestId'] . ">
										<input type='hidden' name='availableQuantity' value=". $row['availableQuantity'] . "> 
										<input type='submit' value='Submit'>
									 </form>" .
							  	   "</td>";
						 }
						print "</tr>";
					}
					//}
					
				?>
            </table>						
        </div>
	</body>
</html>