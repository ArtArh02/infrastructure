<?php
    session_start();
	
	if (isset($_POST['login'])) {$login = $_POST['login'];} 
    if (isset($_POST['password'])) {$password=$_POST['password'];}
	if (empty($login) or empty($password)) {
		exit("Вы ввели не всю информацию, вернитесь назад и заполните все поля!<a href='index.php'>Главная страница</a>");
    }
    
	function protectcode($var) {return trim(htmlspecialchars(stripslashes($var)));}
	$login = protectcode($login);
	$password = protectcode($password);
	
    $db = new mysqli("mysql","root", "Str0ngPaSSw0rd","users");
 
	$result = mysqli_query($db, "SELECT * FROM users WHERE login='$login'");
    $myrow = mysqli_fetch_array($result);
	
    if (empty($myrow['password'])) {
		exit ("Извините, введённый вами login и/или пароль неверный.<a href='index.php'>Главная страница</a>");
    }

    if ($myrow['password']==$password) {
		$_SESSION['login']=$myrow['login']; 
		$_SESSION['id']=$myrow['ID'];
		$_SESSION['pl']=$myrow['pl'];
		echo "Вы успешно вошли на сайт! <a href='index.php'>Главная страница</a>";
    }
	else {
		exit ("Извините, введённый вами login и/или пароль неверный.<a href='index.php'>Главная страница</a>");
    }
?>