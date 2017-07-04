<?php
   include 'lib.php';
?>
<html>
   <head>
      <title>Add New Client</title>
   </head>
   <?php
      session_start();
      $result = null;
      $insertSql = null;
      
      // If Cancel button is pressed, go back to Client Search screen
      if (isset($_POST['cancel'])) {
         header("Location: /client_search.php");
         exit;
      }
      
      if (isset($_POST['save']) && !empty($_POST['firstName']) && !empty($_POST['lastName']) && !empty($_POST['description'])) {
         $firstName = $_POST['firstName'];
         $lastName = $_POST['lastName'];
         $description = $_POST['description'];
         $phoneNumber = $_POST['phoneNumber'];
      
         $sql = "SELECT clientId FROM Client " .
                "WHERE firstName = '" . $firstName . "' " .
                "AND lastName = '" . $lastName . "' " .
                "AND description = '" . $description . "' ";
                  
         if (!empty($phoneNumber )) {
            $sql = $sql . "AND phoneNumber = '" . $phoneNumber . "'";
         }
         $result = executeSql($sql);
         
         if ($result->num_rows == 0) {
            // No existing client with provided info, therefore, add new client.
            $insertSql = "INSERT INTO Client (clientId,firstName,lastName,description,phoneNumber) " . 
                         "VALUES (NULL,'" . $firstName . "','" . $lastName . "','"  . $description . "','"  . $phoneNumber . "')";
            $clientId = insertSql($insertSql);
            
            if ($clientId > 0) {
               $_SESSION["clientId"] = $clientId;
               header("Location: /client_detail.php");
               exit;
            } else {
               echo "Error: " . $insertSql . "<br>";
            }            
         }
      } 
    ?>
   <body>
      <form action="/client_add.php" method="post">
         <label>
            <strong>Add New Client</strong>
         </label>
         <div>
            <p>
               <label>
                  <strong>First Name</strong>
               </label> 
               <input name="firstName" required="" type="text" value="Jane" />
            </p>
            <p>
               <label>
                  <strong>Last Name</strong>
               </label> 
               <input name="lastName" required="" type="text" value="Doe" />
            </p>
            <p>
               <label>
                  <strong>Description</strong>
               </label> 
               <input name="description" required="" type="text" value="FL" />
            </p>
            <p>
               <label>
                  <strong>Phone Number</strong>
               </label> 
               <input name="phoneNumber" type="text" />
            </p>
            <p>
               <button name="save" type="submit">Save</button>
               <button name="cancel" type="submit">Cancel</button>
            </p>
         </div>
         <?php
            if (isset($_POST['search'])) {
               displaySearchResult($result);
            }
        ?>
      </tbody>
   </table>
</form>
</body>
</html>
