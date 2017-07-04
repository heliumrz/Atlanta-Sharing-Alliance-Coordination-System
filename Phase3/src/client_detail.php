<?php
   include 'lib.php';
   
   $pageTitle = "Client Detail";
  
   function retrieveModificationHistory($clientId) {
      $sql = "SELECT modifiedDateTime, fieldModified, previousValue FROM ClientLog " .
             "WHERE clientId = " . $clientId;

      $result = executeSql($sql);
      return $result;
   }
   
   function retrieveServiceUsageHistory($clientId) {
      $sql = "SELECT siteId, facilityId, serviceDateTime, description, note FROM ClientServiceUsage " .
             "WHERE clientId = " . $clientId;

      $result = executeSql($sql);
      return $result;
   }
   
   // Display client data modification history
   function displayModificationHistory($result) {
      echo "<table>
               <thead>
                 <tr>
                   <th>Modified DateTime</th>
                   <th>Field Modified</th>
                   <th>Previous Value</th>
                 </tr>
               </thead>
               <tbody>";
      
      while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['modifiedDateTime'] . "</td>";
            echo "<td>" . $row['fieldModified'] . "</td>";
            echo "<td>" . $row['previousValue'] . "</td>";
            echo "</tr>";
      }
   }
   
   // Display client service usage history
   function displayServiceUsageHistory($result) {
      echo "<table>
               <thead>
                 <tr>
                   <th>Site Id</th>
                   <th>Facility Id</th>
                   <th>Service DateTime</th>
                   <th>Description</th>
                   <th>Note</th>
                 </tr>
               </thead>
               <tbody>";
      
      while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['siteId'] . "</td>";
            echo "<td>" . $row['facilityId'] . "</td>";
            echo "<td>" . $row['serviceDateTime'] . "</td>";
            echo "<td>" . $row['description'] . "</td>";
            echo "<td>" . $row['note'] . "</td>";
            echo "</tr>";
      }
      echo "   </tbody>" . 
           "</table>";
   }
   
   session_start();
   $result = null;
   $clientRow = null;
   $clientModificationHistory = null;
   $clientServiceUsageHistory = null;

   logout(isset($_POST['logout']));
   goToUserHome(isset($_POST['userHome']));
   
   // Go back to Client Search if Cancel button is pressed
   goToLocation(isset($_POST['cancel']),"/client_search.php");

   $clientId = $_SESSION["clientId"];
      
   if (!empty($clientId)) {
      $sql = "SELECT clientId, firstName, lastName, description, phoneNumber FROM Client " .
             "WHERE clientId = " . $clientId;

      $result = executeSql($sql);
      
      if ($result->num_rows > 0) {
         // Client exists, pull back data and process.
         $clientRow = $result->fetch_assoc();

         // Retrieve history
         $clientModificationHistory = retrieveModificationHistory($clientId);
         $clientServiceUsageHistory = retrieveServiceUsageHistory($clientId);
      } else {
         // If no client exist, go to Client Search screen
         header("Location: /client_search.php");
         exit;            
      }
   } 
?>
<html>
   <head>
      <title><?php displayText($pageTitle);?></title>
   </head>
   <body>
      <form action="/client_detail.php" method="post">
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
                  <strong>First Name</strong>
               </label> 
               <input name="firstName" required="" type="text" value="<?php echo $clientRow['firstName']?>" />
            </p>
            <p>
               <label>
                  <strong>Last Name</strong>
               </label> 
               <input name="lastName" required="" type="text" value="<?php echo $clientRow['lastName']?>" />
            </p>
            <p>
               <label>
                  <strong>Description</strong>
               </label> 
               <input name="description" required="" type="text" value="<?php echo $clientRow['description']?>" />
            </p>
            <p>
               <label>
                  <strong>Phone Number</strong>
               </label> 
               <input name="phoneNumber" type="text" value="<?php echo $clientRow['phoneNumber']?>"/>
            </p>
            <p>
               <button name="save" type="submit">Save</button>
               <button name="checkin" type="submit">Check In</button>
               <button name="cancel" type="submit">Cancel</button>
            </p>
         </div>
         <?php
           displayModificationHistory($clientModificationHistory);
           displayServiceUsageHistory($clientServiceUsageHistory);
         ?>
      </tbody>
   </table>
</form>
</body>
</html>
