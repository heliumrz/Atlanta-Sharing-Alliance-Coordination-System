<?php
   include 'lib.php';
  
   $pageTitle = "Outstanding Requests";  
    
   if (!isset($_SESSION)) {
       session_start();
   }
	checkValidSession();
	
	$orderBy = 'availableQuantity';
   if (isset($_POST['orderBy'])) {
	   $orderBy = $_POST['orderBy'];
   }
   
   # Get outstanding requestIds
   $sql = "SELECT requestId " .
   "FROM `request` " . 
   "INNER JOIN ( " .
   "    SELECT sumRequests.facilityId, sumRequests.itemId " .
   "    FROM `filteredfoodbankitem` " .
   "    INNER JOIN ( " .
   "        SELECT facilityId, itemId, SUM(quantityRequested) AS requestSum " .
   "   	FROM `request` " .
   "        GROUP BY facilityId, itemId " .
   "    ) AS sumRequests " .
   "    ON `filteredfoodbankitem`.`facilityId` = sumRequests.facilityId AND " .
   "    `filteredfoodbankitem`.`ItemId` = sumRequests.itemId AND " .
   "    `filteredfoodbankitem`.`availableQuantity` < sumRequests.requestSum " .
   ") AS outstandingFacilityItems " .
   "ON `request`.`FacilityId` = outstandingFacilityItems.facilityId AND `request`.`ItemId` = outstandingFacilityItems.itemId ";
   
   //echo "Query is: " . $sql . "<br>";

   $result = executeSql($sql);
   # store outstanding ids in an array, since traversing with fetch_assoc() can only be used once.
   $outstandingReqIds = [];
   while($row = $result->fetch_assoc()) {
   	   # print "outstanding request ids: " . $row['requestId'] "<br>";
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
       		 "		 itemTable.expirationDate, " .
       		 "		 itemTable.itemType " .
			 "FROM `request` " .
			 "INNER JOIN ( " .
		 	 "		SELECT facilityId, `foodbanktoitem`.itemId, availableQuantity, name, storageType, expirationDate, itemType " .
			 "	 	FROM `foodbanktoitem`, `item` " .
			 "		WHERE `foodbanktoitem`.`ItemId` = `item`.`ItemId` " .
			 ") AS itemTable " .
			 "ON `request`.`ItemId` = itemTable.itemId " .
		     "ORDER BY '" . $orderBy . "' ";
	  //echo "Query is: " . $sql . "<br>";

      $result = executeSql($sql);

   // if (isset($_POST['clientDetail'])) {
   //    $_SESSION["clientId"] = $_POST["clientId"];
   //    goToClientDetail(true);
   // }
?>
<html>
	<head>
 		<title>View Outstanding Requests</title>
   	</head>
   	<body>
		<div><?php displayPageHeading($pageTitle); ?></div>
		<br>
        <div class="report_section">
            <div class="subtitle">Requests Report</div>  
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
					<td class="heading">ItemType</td>
                </tr>							

                <?php
				
					//if ($result->num_rows > 0) {
					while($row = $result->fetch_assoc()) {
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
						if ($isOutstanding == true) {
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
						print "<td>" . $row['itemType'] . "</td>";
						print "</tr>";
					}
					//}
							
				?>
            </table>						
        </div>
	</body>
</html>