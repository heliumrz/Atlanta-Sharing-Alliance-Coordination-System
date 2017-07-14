<?php
   include 'lib.php';
   
   $pageTitle = "Login";  
   
   ob_start();
   session_start();
   $loginvalid = false;
   
   // Check whether login data was provided
   if (isset($_POST['login']) && !empty($_POST['username']) && !empty($_POST['pwd'])) {
      $user = $_POST['username'];
      $pwd = $_POST['pwd'];
   
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
         // Navigate to User Home if successful login
         header("Location: /user_home.php");
         exit;
      }
   } 
?>
 
<html>
<head>
<style>

div.container {
    width: 100%;
	
}

header, footer {
    padding: 1em;
    color: black;
    background-color: BAE8CB;
	
    clear: left;
    text-align: center;
}

 footer{   
	clear: both;
    position: relative;
    z-index: 10;
    height: 3em;
    margin-top: -3em;
 }

nav {
    float: right;
    width: 200px;
	height:65%;
	background-color: CCF4FF;
    margin: 0;
    padding: 1em;
}

nav ul {
    list-style-type: none;
    padding: 0;
}
   
nav ul a {
    text-decoration: none;
}

section {
	height:65%;
	text-align: center;
	background-color: D9FFF2;
	padding: 1em;
    overflow: hidden;
}

</style>
</head>
   <body>
      <div class="container">
        <header>
            <h1>Atlanta Sharing Alliance Coordination System</h1>
        </header>
		<nav>
		  <h3>Find available housing options and food supply levels</h3>
          <ul>
            <li><a href="available_bunks.php">Avialable bunks</a></li>
            <li><a href="meal_report.php">Meal infomation</a></li>
          </ul>
        </nav>
		<section>
		<div style="box-sizing: border-box; display: inline-block; width: auto; max-width: 550px; background-color:#F1F1F1; border: 2px solid #BAE8E5; border-radius: 5px; box-shadow: 0px 0px 8px #BAE8E5; margin: 50px auto auto;padding: 50px;">
		 <?php displayTitle($pageTitle); ?>
         <?php displayFormHeader($MAIN_FORM,$LOGIN_URL); ?>
         <?php displayPageHeading($pageTitle); ?>
         <br>
     
            <?php displayUsernamePasswordField(); ?>
            <p>
               <?php displayLoginSubmitButton(); ?>
            </p>
            <p>
               <?php 
                  if (isset($_POST['login']) && $loginvalid == false) {
                     echo $INVALID_USER_PASS;
                  }
               ?>
            </p>
			
        </div>
      </section>
	 <footer>Copyright &copy;</footer>
	  </div>
   </body>
</html>
