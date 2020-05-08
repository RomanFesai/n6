<?php

/**
 * Задача 6. Реализовать вход администратора с использованием
 * HTTP-авторизации для просмотра и удаления результатов.
 **/

// Пример HTTP-аутентификации.
// PHP хранит логин и пароль в суперглобальном массиве $_SERVER.
// Подробнее см. стр. 26 и 99 в учебном пособии Веб-программирование и веб-сервисы.
$user = 'u20237';
$pass = '8241663';
$kolvo = 0;
$db = new PDO('mysql:host=localhost;dbname=u20237', $user, $pass, array(PDO::ATTR_PERSISTENT => true));
if (!empty($_SERVER['PHP_AUTH_USER']) && !empty($_SERVER['PHP_AUTH_PW'])){
    try {
        $stmt = $db->prepare("SELECT COUNT(*) as KOLVO FROM FormSix WHERE LOGIN=:name AND PASS=:upass");   //добавление в базу данные
        $stmt -> execute(array('name'=>$_SERVER['PHP_AUTH_USER'], 'upass'=>md5($_SERVER['PHP_AUTH_PW'])));
        $kolvo=$stmt->fetchColumn();    //узнаем кол-во подходящих логин-пароль
    }
    catch(PDOException $e){
        print('Error : ' . $e->getMessage());
        exit();
    }
}
if ($kolvo == 0) {
    header('HTTP/1.1 401 Unanthorized');
    header('WWW-Authenticate: Basic realm="Oko Saurona"');
    echo'<!DOCTYPE html>
        <html>
        <head>
	       <title>Web-5</title>
	       <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        </head>
        <body class="bg-dark">
	       <div class="jumbotron w-25 mx-auto my-5 py-2">For further work, enter the administrator login and password</div>
        </body>
        </html>';
    exit();
}
// *********
// Здесь нужно прочитать отправленные ранее пользователями данные и вывести в таблицу.
// Реализовать просмотр и удаление всех данных.
// *********

try {
    $stmt = $db->prepare("SELECT * FROM FormFive");   //добавление в базу данные
    $stmt -> execute(array());
    $base = $stmt->fetchAll();
}
catch(PDOException $e){
    print('Error : ' . $e->getMessage());
    exit();
}
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    echo'
<html lang="ru">
<head>
	<title>Web - 6</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body class = "bg-primary">
	<div class="jumbotron w-100 mx-auto my-5 p-4">
        <div class="my-2">ADMIN PANEL</div>
		<form action="" method="POST">
			<table class="table table-hover">
			  <thead>
			    <tr>
			      <th scope="col"></th>
			      <th scope="col">ID</th>
			      <th scope="col">NAME</th>
			      <th scope="col">EMAIL</th>
			      <th scope="col">PASS</th>
			      <th scope="col">YEAR</th>
			      <th scope="col">SEX</th>
			      <th scope="col">Number of Limbs</th>
                  <th scope="col">SUPERPOWERS</br></th>
			      <th scope="col">BIOGRAPHY</th>
			      <th scope="col">CHECKBOX</th>
			      <th scope="col">Commit Time</th>
			    </tr>
			  </thead>
			  <tbody>';
    for($i=0;$i<count($base);$i++){
        if ($i%2==0)
            echo '<tr class="table-secondary">'; else echo '<tr>';
            echo        '<td><div class="d-flex justify-content-center my-1"><input type="checkbox" value="',$i,'" name="del[]" /></td>
			      <td><input class="my-2" type="hidden" value="',$base[$i]['id'],'" name="Id[]">',$base[$i]['id'],'</td>
			      <td>', $base[$i]['NAME'],'</td>
			      <td>', $base[$i]['EMAIL'],'</td>
			      <td>', $base[$i]['PASS'],'</td>
			      <td>', $base[$i]['YEAR'],'</td>
			      <td>', $base[$i]['SEX'],'</td>
			      <td>', $base[$i]['NoL'],'</td>
                  <td>';
            $puperpower = explode(" | ",$base[$i]['SUPERPOWERS']);
            for($j=0;$j<count($puperpower);$j++)
                echo   $puperpower[$j],'</br>';
                echo       '</td>
	              <td>', $base[$i]['BIO'],'</td>
                  <td>', $base[$i]['CHECKBOX'],'</td>
			      <td>', $base[$i]['COMMIT_TIME'],'</td>
			</tr>';
    }
    
    echo'
            </tbody>
		</table>
        <button type="submit" class="btn btn-primary">Delete</button>
		</form>
        <button class="btn btn-dark"><a href="http://u20237.kubsu-dev.ru/n4/n4">Back</a></button>
	</div>
</body>
</html>';
}
else {
    for($i=0;$i<count($_POST['del']);$i++) {
        $stmt = $db->prepare("DELETE FROM FormFive WHERE ID=:idd");
        $stmt -> execute(array('idd'=>$_POST['Id'][$_POST['del'][$i]]));
    }
    header('Location: ./admin.php');
}