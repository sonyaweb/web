<?php
if (isset($_POST["add_btn"]))
{
	$nedvizh_id = $_POST["add_btn"];
	$user_id=$_SESSION['user_id'];
	$query="SELECT id FROM sdelka WHERE user_id='$user_id' AND sdelka_state_id='1'";
	$mysqliquery = mysqli_query($mysqli, $query); 
	$sdelka_id = "";
	if (!empty($mysqliquery))
	{	
		if ($res=mysqli_fetch_array($mysqliquery))
		{
			$sdelka_id=$res[id];
		}
		else
		{
			$query="INSERT INTO sdelka (user_id, sdelka_state_id, sdelka_show_type_id) VALUES('$user_id','1','1')";
			$mysqliquery = mysqli_query($mysqli, $query); 
			$query="SELECT id FROM sdelka WHERE user_id='$user_id' AND sdelka_state_id='1'";
			$mysqliquery = mysqli_query($mysqli, $query); 
			$res=mysqli_fetch_array($mysqliquery);
			$sdelka_id=$res[id];
		}
	}
	$query="INSERT INTO sdelka_bucket (nedvizh_id, sdelka_id) VALUES ('$nedvizh_id','$sdelka_id')";
	$mysqliquery = mysqli_query($mysqli, $query); 
	
	$query="UPDATE nedvizh SET state_id='2' WHERE id=$nedvizh_id";
	$mysqliquery = mysqli_query($mysqli, $query); 
	
	
}

	$query = 
	"
	SELECT nedvizh.*, nedvizh_types.name as typename,
	nedvizh_states.name as statename
	FROM nedvizh
	JOIN nedvizh_types ON nedvizh.tupe_id=nedvizh_types.id
	JOIN nedvizh_states ON nedvizh.state_id=nedvizh_states.id
	";
	$where_flag = false;
	if (isset($_POST["clear_search"]))
	{
		header('Location: '.$_SERVER['REQUEST_URI']);
	}
	
	if (isset($_POST["send_search"]))
	{

		for($i = 0; $i < count($_POST["type"]); $i++)
		{	
			$where_flag = true;
			$t=$_POST["type"][$i];

			if ($i == 0)
				$query .= "WHERE (";

			if ($i > 0)	$query .=" OR ";
		
			$query .=" nedvizh.tupe_id=$t ";
			
			if ($i == count($_POST["type"])-1)
				$query .=") ";
		}
		
		for($i = 0; $i < count($_POST["state"]); $i++)
		{			
			$t=$_POST["state"][$i];

			if ($i == 0 and !$where_flag)
				$query .= "WHERE (";
			else
				if ($i==0 and $where_flag)
					$query .= "AND (";
			$where_flag=true;
			if ($i > 0)	$query .=" OR ";
		
			$query .=" nedvizh.state_id=$t ";
			
			if ($i == count($_POST["state"])-1)
				$query .=") ";
		}
				
		if ($_POST["size_sort"] != "none")
		{
			$query .=" ORDER BY nedvizh.size ".$_POST["size_sort"];
		}
	}

	$mysqliquery = mysqli_query($mysqli, $query); 
?>

<div class="container">

	<div class="column center">
	<h2>Каталог</h2>
	<table>
				
		<form action="" method="POST">
		<table style="width:100%; text-align:center;">
		<tr>
			<th>Площадь, m2</th>
			<th>Цена, rub</th>
			<th>Тип</th>
			<th>Состояние</th>
			<?php
			if ($_SESSION['user_id'] != "-1" && $_SESSION['user_id'] != "-2")
			{
				echo "<th>Добавить</th>";			
			}
			
			?>
		</tr>
			
<?php	
	if (!empty($mysqliquery))
		while ($res=mysqli_fetch_array($mysqliquery))
		{				
			echo "<tr>";
			echo "<td width='25%;'>";
				echo "<br>".$res[size];	
			echo "</td>";
			echo "<td width='25%;'>";			
						echo "<br>".$res[price];
			echo "</td>";	
			echo "<td width='25%;'>";			
						echo "<br>".$res[typename];
			echo "</td>";	
			echo "<td width='25%;'>";			
						echo "<br>".$res[statename];
			echo "</td>";
			if ($_SESSION['user_id'] != "-1" && $_SESSION['user_id'] != "-2")
			{
				if ($res[statename] == "забронирована" || $res[statename] == "продано")
					echo "<td width='25%;' hidden='true'>";		
				else			
					echo "<td width='25%;'>";			
				echo "<br><button type='submit' name='add_btn' value='$res[id]'>Добавить</button>";
				echo "</td>";
			}
			
			echo "</tr>";
		}

	?>


	
	</table>
	</form>
</div>
<div class="column right">   
<h2 >Поиск</h2>
	<form action="" name="search" method="POST">
	Сортировать по площади:
		<select name="size_sort" size="1">
			<option value = "none" <?php if ($_POST["size_sort"] == "none") echo " selected"?>></option>
			<option value = "ASC" <?php if ($_POST["size_sort"] == "ASC") echo " selected"?>>Возрастание</option>
			<option value = "DESC" <?php if ($_POST["size_sort"] == "DESC") echo " selected"?>>Убывание</option>
		</select>
	<br>
	<br>


		<?php

		$query = 
		"
		SELECT DISTINCT * FROM nedvizh_types
		";
		$mysqliquery = mysqli_query($mysqli, $query); 
		$count = mysqli_num_rows($mysqliquery);
		echo "Тип квартиры:<br>";

		while($res=mysqli_fetch_array($mysqliquery))
		{
			echo "<input type='checkbox' name='type[]' value=$res[id] ";
			if (isset($_POST['type']))
			if (in_array($res[id], $_POST['type']))
			{
				echo " checked ";
			}		
			echo ">".$res[name]."<br>";	
		}
		
		$query = 
		"
		SELECT DISTINCT * FROM nedvizh_states
		";
		
		$mysqliquery = mysqli_query($mysqli, $query); 
		$count = mysqli_num_rows($mysqliquery);

		echo "<br><br>Статус квартиры:<br>";
		
			while($res=mysqli_fetch_array($mysqliquery))
		{
			echo "<input type='checkbox' name='state[]' value=$res[id] ";
			if (isset($_POST['state']))
			if (in_array($res[id], $_POST['state']))
			{
				echo " checked ";
			}		
			echo ">".$res[name]."<br>";	
		}
		
		echo '<br><input  style="width:100%; height:40px;" type="submit" name="send_search" value="Поиск"> <br><br>';
		echo '<br><input  style="width:100%; height:40px;" type="submit" name="clear_search" value="Сброс"> <br><br>';

		?>


	</form>
</div>		
</div>