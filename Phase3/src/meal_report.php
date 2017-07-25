<?php
include 'lib.php';

$pageTitle = "Meals Remaining Report";

If(isset($_POST['formAction']) && ($_POST['formAction'] == 'login')){
	header("Location: /login.php");
    exit;
	}

//sql query for table joining and group by subcategory
$query ="SELECT SUM(t1.AvailableQuantity)AS TotalCount,t2.SubCategory FROM FoodBankToItem t1 LEFT JOIN Item t2 ON t1.ItemId = t2.ItemId GROUP BY SubCategory ORDER BY TotalCount";
$result = executeSql($query);
$displayresult = executeSql($query);

//compare the count of different food category
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
	if ($row['SubCategory'] === "Dairy/eggs")
		$protein1 = $row['TotalCount'];
	else if ($row['SubCategory'] === "Meat/seafood")
		$protein2 = $row['TotalCount'];
	else if ($row['SubCategory'] === "Nuts/grains/beans")
		$carb = $row['TotalCount'];
	else if ($row['SubCategory'] === "Vegetables")
		$veg = $row['TotalCount'];
}
$protein = $protein1+$protein2;
$minCount = min($carb, $protein, $veg);


?>
<html>
<head>
	<script>
         <?php displayJavascriptLib();?>
	</script>
<style>

div.container {
    width: 100%;
	
}

header, footer {
    padding: 1em;
    color: black;
    background-color: BAE8CB;
	charset="utf-8";
    clear: left;
    text-align: left;
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
    width:400px;
	height:100%;
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
	height:100%;
	text-align: center;
	margin-top: 3em;
	padding: 1em;
    overflow: hidden;
}
</style>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  
</head>
<body>
<div class="container">
	<header>
	<?php displayFormHeader($MAIN_FORM,$USER_HOME_URL); ?>
	 <div>
		<?php
		echo '<div style="float: right">
            <button id="login" name="login" type="button" onClick="submitMainForm(' . "'login'" . ')">Login</button>
         </div>';  
	
		?>
	</div>
  	<h2>Meals Remaining Report</h2>
	<p>
               <?php 
			   displayHiddenField(); 
               ?>
    </p>
    </header>
    <nav>
	<div class="bodycontainer scrollable">
<table style="border: 2px solid #d3d3d3;"class="table table-hover table-striped table-condensed table-scrollable">
    <tbody>
	<label>
         <strong>All Items In System:</strong>
    </label>
		<thead>
            <tr>
               <th align='left'>Sub-Category</th>
			   <th align='center'>Available Total Quantity</th>
            </tr>
         </thead>
	<?php
	
        while($row = $displayresult->fetch_assoc()) {
            echo "
            <tr>
               <td>" . $row['SubCategory'] . "</td>
			   <td>" . $row['TotalCount'] . "</td>
            </tr>";
      }
     ?>
	 
         </tbody>
      </table>
      </div>
	
	</nav>
	<section>
		<?php	
			// tie break using alphabet order
			if ($minCount == $carb)
				$minType = "Nuts/grains/beans";
			else if ($minCount == $protein)
				$minType = "Meat/seafood or Dairy/eggs";
			else if ($minCount == $veg)
				$minType = "Vegetables";
				            
		?>
		<h3>We have <?php echo '<span style="font-size: 28pt">' . $minCount . '</span>'; ?> meals remaining in inventory of all food banks in our system.</h3>
		<h3> To provide more meals, the most needed donation types are <?php echo '<span style="font-size: 28pt">' . $minType . '</span>'; ?>.</h3>						
	</section>
	
	</div>                            
</body>
</html>
