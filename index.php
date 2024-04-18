
<?php
session_start();

if (!isset($_SESSION['navig'])) 
  $_SESSION['navig'] = "catalog.php"; 

if (!isset($_SESSION['user_state'])) 
  $_SESSION['user_state'] = "-1"; 

if (!isset($_SESSION['user_id'])) 
  $_SESSION['user_id'] = "-1"; 

if (!isset($_SESSION['user_name'])) 
  $_SESSION['user_name'] = "-1"; 

if (!isset($_SESSION['user_phone'])) 
  $_SESSION['user_phone'] = "-1"; 

if (!isset($_SESSION['user_login'])) 
  $_SESSION['user_login'] = "-1"; 

if (!isset($_SESSION['user_pass'])) 
  $_SESSION['user_pass'] = "-1"; 




?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Квартирки и студии!</title>
<link rel="stylesheet" href="mystyle.css">
</head>  
<body>
<?php 	
	include("setup.php");
	if(isset($_POST["navig"]))
	{
		$_SESSION['navig'] = $_POST["navig"];
	}
?>


<div class="whole">
	<div class="navigation">

		<h2>Навигация по сайту</h2>
		<br>
		<form method = "POST">

			<button style="width:100%; height:40px;" type="submit" name="navig" value="catalog.php" >Каталог</button>
			<br>
			<button style="width:100%; height:40px;" type="submit" name="navig" value="info.php">Информация о нас</button>
			<br>
			<button style="width:100%; height:40px;" type="submit" name="navig" value="fedback.php">Связаться с нами</button>
			<br>


		</form>
		<br>
		<?php 	
		include("news_page.php");	
		include("user_form.php");	
		

		?>
	</div>

	<div class = "main">
		<?php 	
		if ($_SESSION['navig']!= "user_lk.php"){
			$_SESSION['text_or_pass'] = "password"; 
		}
		
		include($_SESSION['navig']);	
		?>
	</div>
</div>

</body>
</html>


