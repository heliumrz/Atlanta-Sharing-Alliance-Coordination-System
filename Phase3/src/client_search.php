<?php
   include 'lib.php';
  
   function displaySearchResult($result) {
      $searchValid = false;
      $errorMsg = "";
      $rowcnt = $result->num_rows;
      if ($rowcnt > 0 && $rowcnt < 5 ) {
          $searchValid = true;
      } elseif ($rowcnt == 0) {
         $errorMsg = "No Client found, please try again.";
      } elseif ($rowcnt >= 5) {
         $errorMsg = "Too many Clients found, please narrow search criteria(s).";
      }
      
      if ($searchValid) {
         echo "<table>
                  <thead>
                    <tr>
                      <th>First Name</th>
                      <th>Last Name</th>
                      <th>Description</th>
                      <th>Phone Number</th>
                    </tr>
                  </thead>
                  <tbody>";
         
         while($row = $result->fetch_assoc()) {
               echo "<tr>";
               echo "<td>" . $row['firstName'] . "</td>";
               echo "<td>" . $row['lastName'] . "</td>";
               echo "<td>" . $row['description'] . "</td>";
               echo "<td>" . $row['phoneNumber'] . "</td>";
               echo "</tr>";
         }
      } else {
         echo "<p>
               <label>
                 <strong>" . $errorMsg . "</strong>
               </label>";
      }     
  }
?>
<html>
   <head>
      <title>Client Search</title>
   </head>
   <?php
      session_start();
      $result = null;
      if (isset($_POST['add'])) {
         header("Location: /client_add.php");
         exit;
      }
      
      if (isset($_POST['search']) && !empty($_POST['firstName']) && !empty($_POST['lastName']) && !empty($_POST['description'])) {
         $firstName = $_POST['firstName'];
         $lastName = $_POST['lastName'];
         $description = $_POST['description'];
         $phoneNumber = $_POST['phoneNumber'];
      
      
         $sql = "SELECT clientId, firstName, lastName, description, phoneNumber FROM Client " .
                "WHERE firstName like '%" . $firstName . "%' " .
                "AND lastName like '%" . $lastName . "%' " .
                "AND description like '%" . $description . "%' ";
                  
         if (!empty($phoneNumber )) {
            $sql = $sql . "AND phoneNumber like '%" . $phoneNumber . "%'";
         }

         $result = executeSql($sql);
      } 
    ?>
   <body>
      <form action="/client_search.php" method="post">
         <label>
            <strong>Client Search</strong>
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
               <button name="search" type="submit">Search</button>
               <button name="add" type="submit">Add New Client</button>
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
