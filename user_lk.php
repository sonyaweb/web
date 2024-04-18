<?php


if (isset($_POST["del_history_btn"]))
{
	$query="UPDATE sdelka SET sdelka_show_type_id = '2' WHERE id =".$_POST["del_history_btn"];
	$mysqliquery = mysqli_query($mysqli, $query);
	
}


?>


<div class="container">
<div class="column center" >
<?php


	$user_id = $_SESSION['user_id'];
	$query = "SELECT sdelka.id FROM sdelka WHERE user_id='$user_id' AND sdelka_state_id='2' AND sdelka_show_type_id = '1'";
	$mysqliquery = mysqli_query($mysqli, $query);
	if (mysqli_num_rows($mysqliquery) >0)
	{
	echo '
	
		<h2>История заказов</h2>
		<form method="POST">
		<table style="width:70%; text-align:left;  margin:auto;">
		<thead>
		<th>№</th>
		<th>Список квартир</th>
		</thead>
		<tbody>';

			
		$total = 0;
		$sdelka_count = 1;		
		 
		if (!empty($mysqliquery))
		while ($res=mysqli_fetch_array($mysqliquery))
		{	
			$sum = 0;
			$sdelka_id = $res[id];
			echo "<tr>";
			echo "<td width='4%;'>";
			echo $sdelka_count;
			echo "</td>";			

			
			$sdelka_count+=1;
			

			$query_details = "SELECT sdelka_bucket.*, 
			nedvizh.size as size,
			nedvizh.price as price,
			nedvizh_types.name as type 	
			FROM sdelka_bucket 
			
			JOIN nedvizh ON sdelka_bucket.nedvizh_id=nedvizh.id
			JOIN nedvizh_types ON nedvizh_types.id=nedvizh.tupe_id
			WHERE sdelka_bucket.sdelka_id=$sdelka_id";

			$count = 0;
			$mysqliquery_det = mysqli_query($mysqli, $query_details); 
			
			echo "<td width='25%;'>";
			
			if (!empty($mysqliquery_det))
			{
				while ($res_d=mysqli_fetch_array($mysqliquery_det))
				{	
					$count+=1;
					$sum+=$res_d[price];
					echo "<b>".$count."</b> ";	
					echo "$res_d[type] ($res_d[size] м2) цена: $res_d[price]";	
					echo "<br>";
					
				}
			}			
			
			echo "</td>";

			
			echo "</tr>";
				
			echo "<tr >";
			echo "<td colspan='3' style='text-align: right;'>";
			echo "<b>итог сделки: ". $sum."</b><br><button type='submit' name='del_history_btn' value='$sdelka_id' >Удалить из истории</button>";	
			$total+=$sum;
			echo "</td>";
			
			echo "</tr>";

			

			
			echo "<br>";
			echo "<br>";
		}
		

	echo "</tbody>
	</table>
	</form>";
	echo "<br><div style='text-align: right;'>
	<b>итого за всё время: $total</b>
	</div>";
	
	}
else
	echo "<h2>История заказов пуста!</h2>";

?>

</div>
<?php

if (!isset($_SESSION['text_or_pass'])) 
{
	$_SESSION['text_or_pass'] = "password"; 	
}


$text_or_pass=$_SESSION['text_or_pass'];
if ($text_or_pass == "password")
{
	$btn_name="Показать пароль";
}
else
	$btn_name="Скрыть пароль";

if (isset($_POST["show_pass_btn"]))
{
	if ($_SESSION['text_or_pass'] == "text")
	{
		$_SESSION['text_or_pass'] = "password";
		$btn_name="Показать пароль";
	}
	else
	{
		$_SESSION['text_or_pass'] = "text";
		$btn_name="Скрыть пароль";
	}
	$text_or_pass=$_SESSION['text_or_pass'];
}



?>


<div class="column right">
	<h2>Личный кабинет</h2>
	<form method="POST"  style="padding:2%;">
	Имя:<br>
	<input style="width:40%; height:20px;" type="text" name="username_value" value="<?=$_SESSION["user_name"]?>"></input> 
	<br>
	Телефон:<br>
	<input style="width:40%; height:20px;" type="text" name="userprhone_value" value="<?=$_SESSION["user_phone"]?>"></input> 
	<br>
	Логин:<br>
	<input style="width:40%; height:20px;" type="text" name="userlogin_value" value="<?=$_SESSION["user_login"]?>"></input> 
	<br>
	Пароль:<br>
	<input style="width:40%; height:20px;" type="<?=$text_or_pass;?>" name="userpass_value" value="<?=$_SESSION["user_pass"]?>"></input> 
	<input style="width:50%; height:30px;"  type="submit" name="show_pass_btn" value="<?=$btn_name;?> "></input> 
	<br>
	<br>
	<input style="width:100%; height:40px;"  type="submit" name="save_changes_btn" value="Сохранить изменения"></input> 

	</form>
	<?php
	
if (isset($_POST["save_changes_btn"]))
{
	$name = $_POST["username_value"];
	$phone = $_POST["userprhone_value"];
	$login = $_POST["userlogin_value"];
	$pass = $_POST["userpass_value"];
	if (!empty ($name) && !empty ($phone) && !empty($login) && !empty($pass))
	{
		$query = "SELECT id FROM users WHERE username = '".$login."' AND id != ".$_SESSION["user_id"];
		$mysqliquery = mysqli_query($mysqli, $query);
		if (mysqli_num_rows($mysqliquery) != 0)
		{
			echo "Такой логин занят!";
		}
		else
		{
			$query = "UPDATE users SET name='$name', phone='$phone', username='$login', password='$pass' WHERE id = '".$_SESSION["user_id"]."'";
			$mysqliquery = mysqli_query($mysqli, $query);
			if (empty($mysqliquery))
			{
				echo "Ошибка ввода данных. Телефонный номер - только цифры. Мы и так знаем, где Вас найти.";
				return;
			}
			$query = "SELECT * FROM users WHERE username='$login' AND password='$pass'";
			$mysqliquery = mysqli_query($mysqli, $query); 
			if (!empty($mysqliquery))
			{	if ($res=mysqli_fetch_array($mysqliquery))
				{
					$_SESSION['user_state']=$res[id];
					$_SESSION['user_id']=$res[id];
					$_SESSION['user_name']=$res[name];
					$_SESSION['user_phone']=$res[phone];
					$_SESSION['user_login']=$res[username];
					$_SESSION['user_pass']=$res[password];
					header("Refresh:0");
				}
			}
		}
	}
	else
		echo "Поля должны быть заполнены.";
}
	
	?>
</div>
</div>
	


</div>