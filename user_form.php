
<?php
//echo $_SESSION['user_state'];
if ($_SESSION['user_state']=="-1")
	include("auth_form.php");
elseif ($_SESSION['user_state']=="-2")
	include("register_form.php");
else
	include("inside_form.php");

if(isset($_POST["enter_btn"]))
{
	
	$username=$_POST["username_input"];
	$passwd=$_POST["password_input"];
	
	if (empty($passwd) || empty($username))
		echo "Введите данные!";
	else
	{	
		$query = "SELECT * FROM users WHERE username='$username' AND password='$passwd'";
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
			else
				echo "Такой пользователь не зарегистрирован";
		}		
		
	}
}

if(isset($_POST["register_btn"]))
{
	$_SESSION['user_state'] = "-2"; 
	header("Refresh:0");
}

if(isset($_POST["do_register_btn"]))
{
	$username=$_POST["username_rinput"];
	$passwd=$_POST["password_rinput"];
	$name=$_POST["name_rinput"];
	$phone=$_POST["phone_rinput"];

	if (empty($passwd) || empty($username) || empty($name) || empty($phone))
		echo "Заполните все поля!";
	else
	{	
		$query = "SELECT * FROM users WHERE username='$username'";
		$mysqliquery = mysqli_query($mysqli, $query); 
		if (!empty($mysqliquery))
		{	if ($res=mysqli_fetch_array($mysqliquery))
			{
				echo "Такой логин уже зарегистрирован!";
			}
			else
			{
				$query = "INSERT INTO users (username, password, name, phone) VALUES ('$username', '$passwd', '$name', '$phone')";
				$mysqliquery = mysqli_query($mysqli, $query); 
				
				$query = "SELECT * FROM users WHERE username='$username' AND password='$passwd'";
				//echo $query ;
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
					else
						echo "Ошибка регистрации!";
				}			
			}
		}
	}
	
	
	//header("Refresh:0");
}

if(isset($_POST["lk_btn"]))
{
	$_SESSION['navig'] = "user_lk.php"; 
	header("Refresh:0");
}

if(isset($_POST["korzina_btn"]))
{
	$_SESSION['navig'] = "korzina.php"; 
	header("Refresh:0");
}

if(isset($_POST["history_btn"]))
{
	$_SESSION['navig'] = "user_history.php"; 
	header("Refresh:0");
}

if(isset($_POST["exit_btn"]))
{
	$_SESSION['user_state'] = "-1"; 
	$_SESSION['navig'] = "catalog.php"; 
	$_SESSION['user_name'] = "-1"; 
	$_SESSION['user_id'] = "-1"; 
	$_SESSION['user_phone']= "-1"; 
	$_SESSION['user_login']= "-1"; 
	$_SESSION['user_pass']= "-1"; 
	header("Refresh:0");
}
?>
