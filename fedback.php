
<div class="container" >
<div class="column center">

<form method="POST">
<?php
 if ($_SESSION['user_name'] != "-1")
$uname = $_SESSION['user_name'];
else
	$uname="";
?>
<h3 style="text-align:center;">Наш телефон: +0123456789<br>Адрес: наб. реки Мойки, 42, офис 11<br>
или</h3>
<h2>Расскажите нам всё:</h2>
<table  style="width: 30%; margin: auto;" >
	<tr>
	<tr>
	<td>Как к вам обращаться?</td>
	<td><input type="text" name="l_name" value="<?php echo $uname;?>"></td>
	</tr>	
	
	<tr>
	<td>Ваш email:</td>
	<td><input type="text" name="l_email" ></td>
	</tr>
	
	<tr>
	<td>Сообщение:</td>
	<td><textarea name="l_text" rows="4"></textarea></td>
	</tr>
	<tr >
	<td colspan='2' style="text-align:center;">
	<input class="btn"  type="submit" name="send_btn" value="Отправить"></input> 
	</td>
	</tr>
	</table>
<br>
	

<?php

if (isset($_POST["send_btn"]))
{
	if(!empty($_POST["l_name"]) and !empty($_POST["l_email"]) and !empty($_POST["l_text"]))
	{
		$message = "От ".$_POST["l_name"]." (email: ".$_POST["l_email"].")\nСообщение： ".$_POST["l_text"];	

		
		$to ='sonya_web_mail@mail.ru';
		$subject=date('d.m.Y');
		$subject.=" от: ".$_POST["l_email"];
		$headers="From: sonya_web_mail@mail.ru\r\n";
		$headers.="Reply-To: sonya_web_mail@mail.ru\r\n";

		mail($to, $subject, $message, $headers);
		
		header("refresh:0");
	}
	else
	{
		echo "Введите данные!";
	}
}



?>

</form>
</div>
</div>




