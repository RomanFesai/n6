<?php

/**
 * Файл login.php для не авторизованного пользователя выводит форму логина.
 * При отправке формы проверяет логин/пароль и создает сессию,
 * записывает в нее логин и id пользователя.
 * После авторизации пользователь перенаправляется на главную страницу
 * для изменения ранее введенных данных.
 **/
header('Content-Type: text/html; charset=UTF-8');

// Начинаем сессию.
session_start();

// В суперглобальном массиве $_SESSION хранятся переменные сессии.
// Будем сохранять туда логин после успешной авторизации.
if (!empty($_SESSION['login'])) {
  // Если есть логин в сессии, то пользователь уже авторизован.
  // TODO: Сделать выход (окончание сессии вызовом session_destroy()
  //при нажатии на кнопку Выход).
  // Делаем перенаправление на форму.
  header('Location: ./');
}

// В суперглобальном массиве $_SERVER PHP сохраняет некторые заголовки запроса HTTP
// и другие сведения о клиненте и сервере, например метод текущего запроса $_SERVER['REQUEST_METHOD'].
if (isset($_COOKIE['beda'])){
    echo '<div class="jumbotron w-25 p-3 mx-auto my-2">Wrong login or password entered</div>';
    setcookie('beda', '', 100000);
}
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
?>		
<html>
<head>
	<title>Web-5</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body class="bg-primary">
	<div class="jumbotron w-25 mx-auto my-5 py-2">
		<div class="my-1">
			<label>Authorization</label>
		</div>
		<div>
			<form action="" method="post">
				<div class="my-3">
					<label>Login:</label>
					<input type="text" class="form-control" name="login" value="">	
				</div>
				<div class="my-3">
					<label>Password:</label>
					<input name="pass" class="form-control" />	
				</div>
  				<button type="submit" class="btn btn-primary">Enter</button>
			</form>
		</div>
		<button class="btn btn-dark"><a href="http://u20237.kubsu-dev.ru/n4/n4">Back</a></button>
		<button class="btn btn-dark"><a href="http://u20237.kubsu-dev.ru/n4/n4/admin.php">Log like admin</a></button>
	</div>
</body>
</html>

<?php
}
// Иначе, если запрос был методом POST, т.е. нужно сделать авторизацию с записью логина в сессию.
else {
  
  // TODO: Проверть есть ли такой логин и пароль в базе данных.
  // Выдать сообщение об ошибках.
  $user = 'u20237';
  $pass = '8241663';
  $db = new PDO('mysql:host=localhost;dbname=u20237', $user, $pass, array(PDO::ATTR_PERSISTENT => true));
  try {
      $stmt = $db->prepare("SELECT COUNT(*) as KOLVO FROM FormFive WHERE EMAIL=:name AND PASS=:upass");   //добавление в базу данные
      $stmt -> execute(array('name'=>$_POST['login'], 'upass'=>md5($_POST['pass'])));
      $kolvo=$stmt->fetchColumn();    //узнаем кол-во подходящих логин-пароль
  }
  catch(PDOException $e){
      print('Error : ' . $e->getMessage());
      exit();
  }
  if ($kolvo==1){
    // Если все ок, то авторизуем пользователя.
    $_SESSION['login'] = $_POST['login'];
    // Записываем ID пользователя.
    $_SESSION['pass'] = $_POST['pass'];
  } else {
      session_destroy();
      setcookie('beda', '1');
      header('Location: ./login.php');
      exit();
  }
  // Делаем перенаправление.
  header('Location: ./');
}
