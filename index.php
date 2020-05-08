<?php
header('Content-Type: text/html; charset=UTF-8');
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Массив для временного хранения сообщений пользователю.
    $messages = array();
    
    // В суперглобальном массиве $_COOKIE PHP хранит все имена и значения куки текущего запроса.
    // Выдаем сообщение об успешном сохранении.
    if (!empty($_COOKIE['save'])) {
        // Удаляем куку, указывая время устаревания в прошлом.
        $secretiki = array();
        if (isset($_COOKIE['login'])){
            $secretiki['l']=$_COOKIE['login'];
            $secretiki['p']=$_COOKIE['pass'];
        }
        setcookie('save', '', 100000);
        setcookie('login', '', 100000);
        setcookie('pass', '', 100000);
        // Если есть параметр save, то выводим сообщение пользователю.
        $messages[] = 'Спасибо, результаты сохранены.';
        if (!empty($_COOKIE['pass'])) {
            echo '<div class="jumbotron w-25 p-3 mx-auto my-2">Your login: ',$secretiki['l'],'</div>';
            echo '<div class="jumbotron w-25 p-3 mx-auto my-2">Your password: ',$secretiki['p'],'</div>';
        }
    }
    
    $errors = array();
    $errors['Name'] = !empty($_COOKIE['Name_error']);
    $errors['Email'] = !empty($_COOKIE['Email_error']);
    $errors['DD'] = !empty($_COOKIE['DD_error']);
    $errors['DM'] = !empty($_COOKIE['DM_error']);
    $errors['DY'] = !empty($_COOKIE['DY_error']);
    $errors['SP'] = !empty($_COOKIE['SP_error']);
    $errors['BG'] = !empty($_COOKIE['BG_error']);
    $errors['CH'] = !empty($_COOKIE['CH_error']);
    
    if ($errors['Name']) {
        // Удаляем куку, указывая время устаревания в прошлом.
        setcookie('Name_error', '', 100000);
        // Выводим сообщение.
        $messages[] = '<div class="error">Заполните имя.</div>';
    }
    if ($errors['Email']) {
        // Удаляем куку, указывая время устаревания в прошлом.
        setcookie('Email_error', '', 100000);
        // Выводим сообщение.
        $messages[] = '<div class="error">Заполните Email.</div>';
    }
    if ($errors['DD']) {
        // Удаляем куку, указывая время устаревания в прошлом.
        setcookie('DD_error', '', 100000);
        // Выводим сообщение.
        $messages[] = '<div class="error">Заполните День</div>';
    }
    if ($errors['DM']) {
        // Удаляем куку, указывая время устаревания в прошлом.
        setcookie('DM_error', '', 100000);
        // Выводим сообщение.
        $messages[] = '<div class="error">Заполните Месяц</div>';
    }
    if ($errors['DY']) {
        // Удаляем куку, указывая время устаревания в прошлом.
        setcookie('DY_error', '', 100000);
        // Выводим сообщение.
        $messages[] = '<div class="error">Заполните Год</div>';
    }
    if ($errors['SP']) {
        // Удаляем куку, указывая время устаревания в прошлом.
        setcookie('SP_error', '', 100000);
        // Выводим сообщение.
        $messages[] = '<div class="error">Выберите суперспособность.</div>';
    }
    if ($errors['BG']) {
        // Удаляем куку, указывая время устаревания в прошлом.
        setcookie('BG_error', '', 100000);
        // Выводим сообщение.
        $messages[] = '<div class="error">Напишите биографию.</div>';
    }
    if ($errors['CH']) {
        // Удаляем куку, указывая время устаревания в прошлом.
        setcookie('CH_error', '', 100000);
        // Выводим сообщение.
        $messages[] = '<div class="error">Необходимо подтвердить, что вы ознакомились с контрактом</div>';
    }
    
    $values = array();
    $values['SP'] = array();
    $values['Name'] = empty($_COOKIE['Name_value']) ? '' : $_COOKIE['Name_value'];
    $values['Email'] = empty($_COOKIE['Email_value']) ? '' : $_COOKIE['Email_value'];
    $values['DD'] = empty($_COOKIE['DD_value']) ? '' : $_COOKIE['DD_value'];
    $values['DM'] = empty($_COOKIE['DM_value']) ? '' : $_COOKIE['DM_value'];
    $values['DY'] = empty($_COOKIE['DY_value']) ? '' : $_COOKIE['DY_value'];
    $values['BG'] = empty($_COOKIE['BG_value']) ? '' : $_COOKIE['BG_value'];
    $values['CH'] = empty($_COOKIE['CH_value']) ? '' : $_COOKIE['CH_value'];
    if (key_exists("PO_value",$_COOKIE)) $values['PO'] = $_COOKIE['PO_value']; else $values['PO'] = "MALE";
    if (key_exists("LI_value",$_COOKIE)) $values['LI'] = $_COOKIE['LI_value']; else $values['LI'] = "0";
    if (key_exists("SP_value",$_COOKIE)) $values['SP'] = unserialize($_COOKIE['SP_value'], ["allowed_classes" => false]);
    
    if (!empty($_COOKIE[session_name()]) && session_start() && !empty($_SESSION['login'])) {
        // TODO: загрузить данные пользователя из БД
        // и заполнить переменную $values,
        // предварительно санитизовав.
        $user = 'u20237';
        $pass = '8241663';
        
        $db = new PDO('mysql:host=localhost;dbname=u20237', $user, $pass, array(PDO::ATTR_PERSISTENT => true));
        try {
            $stmt = $db->prepare("SELECT * FROM FormFive WHERE EMAIL=:name AND  PASS=:upass");   //добавление в базу данные
            $stmt -> execute(array('name'=>$_SESSION['login'],'upass'=>md5($_SESSION['pass'])));
            $base = $stmt->fetch();
            $values['SP'] = array();
            $values['Name'] = $base['NAME'];
            $values['Email'] = $base['EMAIL'];
            $values['DY'] = $base['YEAR'];
            $values['BG'] = $base['BIO'];
            $values['PO'] = $base['SEX'];
            $values['LI'] = $base['NoL'];
            $values['SP'] = explode(" | ",$base['SUPERPOWERS']);
        }
        catch(PDOException $e){
            print('Error : ' . $e->getMessage());
            exit();
        }
        // session_destroy();
        
    }
    
    include('form.php');
}
else {//Проверяем логинизацию
    if($_POST['zapros']=='LO') {
        session_start();
        $_SESSION=array();
        session_destroy();
        header('Location: index.php');
        exit();
    }
    if($_POST['zapros']=='LI'){
        header('Location: ./login.php');
        exit();
   }
    $errors = FALSE;
    if (empty($_POST['Name'])) {
        // Выдаем куку на день с флажком об ошибке в поле fio.
        setcookie('Name_error', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    }
    else {
        // Сохраняем ранее введенное в форму значение на месяц.
        setcookie('Name_value', $_POST['Name'], time() + 30 * 24 * 60 * 60);
    }
    if (empty($_POST['Email'])) {
        // Выдаем куку на день с флажком об ошибке в поле fio.
        setcookie('Email_error', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    }
    else {
        // Сохраняем ранее введенное в форму значение на месяц.
        setcookie('Email_value', $_POST['Email'], time() + 30 * 24 * 60 * 60);
    }
    if (empty($_POST['DD'])) {
        // Выдаем куку на день с флажком об ошибке в поле fio.
        setcookie('DD_error', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    }
    else {
        // Сохраняем ранее введенное в форму значение на месяц.
        setcookie('DD_value', $_POST['DD'], time() + 30 * 24 * 60 * 60);
    }
    if (empty($_POST['DM'])) {
        // Выдаем куку на день с флажком об ошибке в поле fio.
        setcookie('DM_error', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    }
    else {
        // Сохраняем ранее введенное в форму значение на месяц.
        setcookie('DM_value', $_POST['DM'], time() + 30 * 24 * 60 * 60);
    }
    if (empty($_POST['DY'])) {
        // Выдаем куку на день с флажком об ошибке в поле fio.
        setcookie('DY_error', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    }
    else {
        // Сохраняем ранее введенное в форму значение на месяц.
        setcookie('DY_value', $_POST['DY'], time() + 30 * 24 * 60 * 60);
    }
    if (count($_POST['SP'])==0) {
        setcookie('SP_error', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    }
    if (empty($_POST['BG'])) {
        setcookie('BG_error', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    } else {
        setcookie('BG_value', $_POST['BG'], time() + 30 * 24 * 60 * 60);
    }
    if(isset($_POST['CH']) && $_POST['CH'] == 'Yes')
    {
        $ch='OZNACOMLEN';
        setcookie('CH_value', 'checked=""', time() + 30 * 24 * 60 * 60);
    } else {
        setcookie('CH_value', '', time() + 30 * 24 * 60 * 60);
        setcookie('CH_error', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    }
    setcookie('PO_value', $_POST['Rad'], time() + 30 * 24 * 60 * 60);
    setcookie('LI_value', $_POST['Limbs'], time() + 30 * 24 * 60 * 60);
    if (count($_POST['SP'])==0) {
        $noo = array();
        setcookie('SP_value', serialize($noo), time() + 30 * 24 * 60 * 60);
    } else {
        setcookie('SP_value', serialize($_POST['SP']), time() + 30 * 24 * 60 * 60);
    }
    
    if ($errors) {
        // При наличии ошибок перезагружаем страницу и завершаем работу скрипта.
        header('Location: index.php');
        exit();
    }
    else {
        // Удаляем Cookies с признаками ошибок.
        setcookie('Name_error', '', 100000);
        setcookie('Email_error', '', 100000);
        setcookie('DD_error', '', 100000);
        setcookie('DM_error', '', 100000);
        setcookie('DY_error', '', 100000);
        setcookie('SP_error', '', 100000);
        setcookie('BG_error', '', 100000);
        setcookie('CH_error', '', 100000);
        
    }
    // Сохранение в XML-документ.
    // ...
    $sp='';
    
    for($i=0;$i<count($_POST['SP']);$i++){
        $sp .= $_POST['SP'][$i] . '  ';
    }
    $user = 'u20237';
    $pass = '8241663';
    $db = new PDO('mysql:host=localhost;dbname=u20237', $user, $pass, array(PDO::ATTR_PERSISTENT => true));
    
    try {
        
        if (!empty($_COOKIE[session_name()])&
            session_start() && !empty($_SESSION['login'])) {
                // TODO: перезаписать данные в БД новыми данными,
                // кроме логина и пароля.
                $stmt = $db->prepare("UPDATE FormFive SET NAME=:name, YEAR=:year, SEX=:sex, NoL=:nol, SUPERPOWERS=:superpowers, BIO=:bio, CHECKBOX=:checkbox, COMMIT_TIME=FROM_UNIXTIME(:time) WHERE PASS=:upass");   //обновление в базу данные
                $stmt -> execute(array('name'=>$_POST['Name'],'year'=>$_POST['DY'], 'sex'=>$_POST['Rad'],'superpowers'=>$sp,'nol'=>$_POST['Limbs'],'bio'=>$_POST['BG'],'checkbox'=>$ch, 'upass'=>md5($_SESSION['pass']),'time'=>time()));
                //   session_destroy();
                
            }
            else {
                // Генерируем уникальный логин и пароль.
                // сделать механизм генерации, например функциями rand(), uniquid(), md5(), substr().
                $login = $_POST['Email'];
                $upass = rand(0,1000);
                // Сохраняем в Cookies.
                setcookie('login', $login);
                setcookie('pass', $upass);
        
                $stmt = $db->prepare("INSERT INTO FormFive (NAME,EMAIL,PASS,YEAR,SEX,Nol,SUPERPOWERS,BIO,CHECKBOX) VALUES (:NAME,:EMAIL,:PASS,:YEAR,:SEX,:NoL,:SUPERPOWERS,:BIO,:CHECKBOX)");   //добавление в базу данные
                $stmt -> execute(array('NAME'=>$_POST['Name'], 'EMAIL'=>$_POST['Email'],'PASS'=>md5($upass),'YEAR'=>$_POST['DY'],'SEX'=>$_POST['Rad'],'NoL'=>$_POST['Limbs'], 'SUPERPOWERS'=>$sp, 'BIO'=>$_POST['BG'], 'CHECKBOX'=>$ch));
            }
    }
    catch(PDOException $e){
        print('Error : ' . $e->getMessage());
        exit();
    }
    // Сохраняем куку с признаком успешного сохранения.
    setcookie('save', '1');
    
    // Делаем перенаправление.
    header('Location: index.php');
}
?>
