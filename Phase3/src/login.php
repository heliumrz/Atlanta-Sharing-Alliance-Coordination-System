<?php
  include 'lib.php';
?>

<html>
  <head>
    <title>Login</title>
  </head>
  <?php
    ob_start();
    session_start();
     
    // Check whether login data was provided
    if (isset($_POST['login']) && !empty($_POST['username']) && !empty($_POST['pwd'])) {
       $user = $_POST['username'];
       $pwd = $_POST['pwd'];
    
       $loginvalid = false;
    
       $sql    = "SELECT password FROM User WHERE username = '" . $user . "'";
       $result = executeSql($sql);
    
       if ($result->num_rows > 0) {
           // output data of each row
           $row = $result->fetch_assoc();
           //echo "password: " . $row["password"] . "<br>";
           if ($row["password"] == $pwd) {
              $loginvalid = true;
           }
           $_SESSION["username"] = $user;
       }
    
       if ($loginvalid == true) {
          //echo "Login successful.";
          header("Location: /client_search.php");
          exit;
       }
       else {
           echo "Username and password are required. Please try again.";
       }
    } 
  ?>
   <body>
     <form action="/login.php" method="post">
       <div>
       <label>
         <strong>Username</strong>
       </label> 
       <input name="username" required="" type="text" value="test1" />
       <p>
       <label>
         <strong>Password</strong>
       </label> 
       <input name="pwd" required="" type="password" value="test1" /></p>
       <p>
         <button name="login" type="submit">Login</button>
       </p></div>
     </form>
   </body>
</html>
