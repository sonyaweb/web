<?php
	$user_id = $_SESSION['user_id'];
	$query="SELECT * FROM sdelka WHERE user_id='$user_id' AND sdelka_state_id='1'";
	$mysqliquery = mysqli_query($mysqli, $query); 
	$res=mysqli_fetch_array($mysqliquery);
	$sdelka_id = $res[id];	
	
	
	$query2="SELECT sdelka_bucket.nedvizh_id as nedvizh_id FROM sdelka_bucket WHERE sdelka_id='$sdelka_id'"; // get all nedvizh from that sdelka
	$mysqliquery2 = mysqli_query($mysqli, $query2); 
	$count = mysqli_num_rows($mysqliquery2);	
	
	
if(isset($_POST["buy_all_btn"]))
{
	$sdelka_id = $_POST["buy_all_btn"];
	
	$mysqliquery2 = mysqli_query($mysqli, $query2); 
	if (!empty($mysqliquery2))
	{
		while ($res2=mysqli_fetch_array($mysqliquery2))
		{			
			$nedvizh_id = $res2[nedvizh_id];
			$query="UPDATE nedvizh SET state_id='3' WHERE id=$nedvizh_id";
			//echo $query;
			$mysqliquery = mysqli_query($mysqli, $query);
		}
	}
		
	
	
	$query="UPDATE sdelka SET sdelka_state_id='2' WHERE id=$sdelka_id";
	$mysqliquery = mysqli_query($mysqli, $query);
	header("Refresh:0");
	
}
	
if(isset($_POST["del_btn"]))
{
	$del_id=$_POST["del_btn"];
	
	
	$query="DELETE FROM sdelka_bucket WHERE sdelka_id='$sdelka_id' AND nedvizh_id='$del_id'";
	$mysqliquery = mysqli_query($mysqli, $query);
	
	$query="UPDATE nedvizh SET state_id='1' WHERE id=$del_id";
	$mysqliquery = mysqli_query($mysqli, $query);	
	header("Refresh:0");
	

}



?>

<div class="container">

	<div class="column center" >
	<h2>Корзина
	<?php
	if ($count == 0)
	{
		echo " пуста!";
		$is_table_hidden='hidden';
	}
	else
		$is_table_hidden='';

	?>
	</h2>
	<div <?=$is_table_hidden;?> style="width:80%;  margin: auto;">
				
		<form action="" method="POST">
		<table style="text-align:center; margin: auto;">
		<tr>
			<th>Площадь, m2</th>
			<th>Цена, rub</th>
			<th>Тип</th>
			<th>Состояние</th>
			<th>Удалить</th>

		</tr>
			
<?php	
	
	if (!empty($mysqliquery2))
		while ($res2=mysqli_fetch_array($mysqliquery2))
		{			
			$nedvizh_id = $res2[nedvizh_id];
			
			$query3=
			"
			SELECT nedvizh.*,
			nedvizh_types.name as typename,
			nedvizh_states.name as statename
			FROM nedvizh 	
			JOIN nedvizh_types ON nedvizh.tupe_id=nedvizh_types.id
			JOIN nedvizh_states ON nedvizh.state_id=nedvizh_states.id
			WHERE nedvizh.id='$nedvizh_id'
			";

			$mysqliquery3 = mysqli_query($mysqli, $query3); 
			$res3=mysqli_fetch_array($mysqliquery3);

			echo "<tr>";
			echo "<td width='25%;'>";
				echo "<br>".$res3[size];	
			echo "</td>";
			echo "<td width='25%;'>";			
						echo "<br>".$res3[price];
			echo "</td>";	
			echo "<td width='25%;'>";			
						echo "<br>".$res3[typename];
			echo "</td>";	
			echo "<td width='25%;'>";			
						echo "<br>".$res3[statename];
			echo "</td>";
		
			echo "<td width='25%;'>";			
			echo "<br><button type='submit' name='del_btn' value='$nedvizh_id'>Удалить</button>";
			echo "</td>";

			//$count=$count+1;
			echo "</tr>";
		}

	?>

	</table>
<div style='text-align: center;'>
<button type='submit' name='buy_all_btn' value='<?=$sdelka_id;?>'>Купить всё</button>
		</div>
	</form>
	
	</div>
</div>

