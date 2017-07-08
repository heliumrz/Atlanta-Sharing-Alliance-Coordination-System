<?php
   include 'lib.php';
   
   $pageTitle = "Client Detail";
  
   // Display client data modification history
   function displayModificationHistory($result) {
      echo "
         <label>
            <strong>Modification History:</strong>
         </label> 
         <table border='1'>
            <thead>
              <tr>
                <th>Modified DateTime</th>
                <th>Field Modified</th>
                <th>Previous Value</th>
              </tr>
            </thead>
            <tbody>";
      
      while($row = $result->fetch_assoc()) {
            echo "
               <tr>
                  <td>" . $row['modifiedDateTime'] . "</td>
                  <td>" . $row['fieldModified'] . "</td>
                  <td>" . $row['previousValue'] . "</td>
               </tr>";
      }
      
      echo "
            </tbody>
         </table>
         <p></p>";
   }
   
   // Display client service usage history
   function displayServiceUsageHistory($result) {
      echo "
         <label>
            <strong>Service Usage History:</strong>
         </label> 
         <table border='1'>
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
            echo "
               <tr>
                  <td>" . $row['siteId'] . "</td>
                  <td>" . $row['facilityId'] . "</td>
                  <td>" . $row['serviceDateTime'] . "</td>
                  <td>" . $row['description'] . "</td>
                  <td>" . $row['note'] . "</td>
               </tr>";
      }
      echo "
            </tbody>
         </table>";
   }
   
   session_start();
   $result = null;
   $clientRow = null;
   $clientModificationHistory = null;
   $clientServiceUsageHistory = null;
   
   // Ensure session is valid. If not, go to login page.
   checkValidSession();
   
   logout(isset($_POST['logout']));
   goToUserHome(isset($_POST['userHome']));
   goToClientSearch(isset($_POST['clientSearch']));

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
      <?php displayCss();?>
      <script>
         <?php displayJsLib();?>
         <?php displayValidateField();?>
      
         function validateInput() {
            if (validateField("firstName") && validateField("firstName") && validateField("firstName")) {
               return true;
            } else {
               alert("First Name, Last Name, and Description are required in search. \nThe following characters are not allowed: ;");
               return false;
            }
         }
      </script>
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
            <?php displayClientDataField($clientRow);?>
            <p>
               <button name="updateClient" type="submit">Update Info</button>
               <button name="checkinClient" type="submit">Check In Client</button>
               <button name="clientSearch" type="submit">Client Search</button>
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
