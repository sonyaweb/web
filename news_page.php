<div style="background-color:#e5ccfc; min-height: 80px; padding: 4px; border: 4px solid #6f6599;">
<b>Новости:
	<?php
	$query ="SELECT * FROM news";
	$mysqliquery = mysqli_query($mysqli, $query); 
	$num = mysqli_num_rows($mysqliquery);

	$n = rand(1, $num);
	
	$query ="SELECT text, YEAR(date) as year, MONTH(date) as mon, DAY(date) as day FROM news WHERE id=$n";
	$mysqliquery = mysqli_query($mysqli, $query); 
	if ($res=mysqli_fetch_array($mysqliquery))
	{
		echo $res['day']."/".$res['mon']."/".$res['year'];
		echo "</b><br>";
		echo $res['text'];
	}
	
	?>
</div>