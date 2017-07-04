<?php
   include 'lib.php';
  
   $pageTitle = "User Home";  
   
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
      <title><?php displayText($pageTitle);?></title>
   </head>
   <?php
      session_start();
      $result = null;
      $userRow = null;
      
      logout(isset($_POST['logout']));
      
      if (isset($_POST['clientSearch'])) {
         header("Location: /client_search.php");
         exit;
      } else {
         $username = $_SESSION["username"];
               
         $sql = "SELECT firstName, lastName, email FROM User " .
                "WHERE username = '" . $username . "' ";

         $result = executeSql($sql);
         $userRow = $result->fetch_assoc();
      } 
   ?>
   <body>
      <form action="/user_home.php" method="post">
         <div>
            <div style="float: left"><strong><?php displayText($pageTitle);?></strong>
            </div>
            <?php 
               displayLogout();
            ?>
         </div>
         <br>
         <div>
            <p>
               <label>
                  First Name: <?php echo $userRow['firstName']?>
               </label> 
            </p>
            <p>
               <label>
                  Last Name: <?php echo $userRow['lastName']?>
               </label> 
            </p>
            <p>
               <label>
                  Email: <?php echo $userRow['email']?>
               </label> 
            </p>
            <p>
               <label>
                  <strong>Actions</strong>
               </label> 
            </p>
            <p>
               <button name="clientSearch" type="submit">Client Search</button>
            </p>
         </div>
         <?php
            // Show search results if search button was pressed prior
            if (isset($_POST['search'])) {
               displaySearchResult($result);
            }
        ?>
      </form>
   </body>
</html>
