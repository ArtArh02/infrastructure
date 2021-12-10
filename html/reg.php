<?php
	$db = new mysqli("mysql","root", "Str0ngPaSSw0rd","users");
	
	if (preg_match('/^[0-9A-Za-z_]{6,30}$/', $_POST['login'])) {$login = $_POST['login'];} 
    if (preg_match('/^(?=.*\d)(?=.*[@#\-_$%^&+=§!\?])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_$%^&+=§!\?]{8,30}$/',$_POST['password'])) {$password=$_POST['password'];}
	if (isset($_POST['repassword'])) {$repassword=$_POST['repassword'];}
	if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {$email=$_POST['email'];}
	
	if (empty($login) or empty($password) or empty($email) or empty($repassword) or $repassword != $password) {
		exit ("В заполненных полях допущена ошибка, вернитесь назад и перепроверьте введенные данные. <a href='index.php'>Главная страница</a>");
    }
	
	function protectcode($var) {return trim(htmlspecialchars(stripslashes($var)));}
	
	$login = protectcode($login);
	$password = protectcode($password);
	$email = protectcode($email);
	
	$loginCheckResult = mysqli_query($db, "SELECT id FROM users WHERE login='$login'");
    $logUnic = mysqli_fetch_array($loginCheckResult);
	
	$emailCheckResult = mysqli_query($db, "SELECT id FROM users WHERE email='$email'");
    $emailUnic = mysqli_fetch_array($emailCheckResult);
	
    if (!empty($logunic['id']) or !empty($emailUnic['id'])) {exit ("Извините, введённый вами логин и/или email уже используются. Повторите попытку указав другие данные. <a href='index.php'>Главная страница</a>");}
	
    $regresult = mysqli_query($db, "INSERT INTO users (login,password,email) VALUES('$login','$password','$email')");
	
    if ($regresult=='TRUE') {echo "Вы успешно зарегистрированы! Теперь вы можете зайти на сайт. <a href='index.php'>Главная страница</a>";}
	else {echo "Ошибка! Вы не зарегистрированы. Повторите Попытку позднее.<a href='index.php'>Главная страница</a>";}
?>