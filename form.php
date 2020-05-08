<!DOCTYPE html>
<html lang="ru">
<head>
	<title>Web - 5</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
	 <style>
.error {
  border: 2px solid red;
}
    </style>
</head>
<body class="bg-primary">
<?php
if (!empty($messages)) {
  print('<div id="messages">');
  // Выводим все сообщения.
  foreach ($messages as $message) {
    print($message);
  }
  print('</div>');
}

// Далее выводим форму отмечая элементы с ошибками классом error
// и задавая начальные значения элементов ранее сохраненными.
?>
	<div class="jumbotron w-25 mx-auto my-5">
	<?php if(!empty($_COOKIE[session_name()]) && !empty($_SESSION['login'])) {
                 echo '<div class="">
                          <form action="" method="POST">
                          <input name="zapros" type="hidden" value="LO" />
                          <button type="submit" class="btn btn-primary">Log out</button>
                          </form>
                       </div>';
              } else{
                 echo '<div>
                          <form action="" method="POST">
                          <input name="zapros" type="hidden" value="LI" />
                          <div class="d-flex justify-content-between">
                              <label>Have an account?</label>
                              <button type="submit" class="btn btn-primary">Log In</button>
                          </div>
                          </form>
                       </div>'; 
              }
    ?>
		<form action="" method="POST">
			<div class="from-group d-flex flex-column">
				<div class="my-2">
					<label>First Name</label>
					<div class="col-sm-10">
        				<input type="text" class="form-control" name="Name" <?php if ($errors['Name']) {print 'class="error"';} ?> value="<?php print $values['Name']; ?>"/>
      				</div>
      			</div>
      			<div class="my-2">
      				<label>Email</label>
      				<div class="col-sm-10">
        				<input type="email" class="form-control" name="Email" <?php if ($errors['Email']) {print 'class="error"';} ?> aria-describedby="emailHelp" value="<?php print $values['Email']; ?>"/>
      				</div>
      			</div>
      			<div class="my-2">
      				<label>Date of Birth</label>
      				<div class="d-flex flex-row justify-content-around">
      					<div class="d-flex flex-column">
      						<label>Day</label>
      						<input type="text" class="form-control" name="DD" <?php if ($errors['DD']) {print 'class="error"';} ?> value="<?php print $values['DD']; ?>"/>
      					</div>
      					<div class="d-flex flex-column">
      						<label>Month</label>
      						<input type="text" class="form-control" name="DM" <?php if ($errors['DM']) {print 'class="error"';} ?> value="<?php print $values['DM']; ?>"/>
      					</div>
      					<div class="d-flex flex-column">
      						<label>Year</label>
      						<input type="text" class="form-control" name="DY" <?php if ($errors['DY']) {print 'class="error"';} ?> value="<?php print $values['DY']; ?>"/>
      					</div>
      				</div>
      			</div>
      			<div class="my-2">
              <label>Sex</label>
              <div class="form-check">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="Rad" id="SMale" value="MALE" <?php if ($values['PO']=='MALE') print 'checked=""'; ?> >Male
                </label>
              </div>
              <div class="form-check">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="Rad" id="SFe" value="FEMALE" <?php if ($values['PO']=='FEMALE') print 'checked=""'; ?>>Female
                </label>
              </div>
            </div>
      			<div class="my-2">
              <label>Number of limbs</label>
              <div class="d-flex justify-content-between">
                <div class="form-check">
                  <label class="form-check-label">
                      <input type="radio" class="form-check-input" name="Limbs" id="0" value="0" <?php if ($values['LI']=='0') print 'checked=""'; ?>>0
                  </label>
                </div>
                <div class="form-check">
                  <label class="form-check-label">
                      <input type="radio" class="form-check-input" name="Limbs" id="1" value="1" <?php if ($values['LI']=='1') print 'checked=""'; ?>>1
                  </label>
                </div>
                <div class="form-check">
                  <label class="form-check-label">
                      <input type="radio" class="form-check-input" name="Limbs" id="2" value="2" <?php if ($values['LI']=='2') print 'checked=""'; ?>>2
                  </label>
                </div>
                <div class="form-check">
                  <label class="form-check-label">
                      <input type="radio" class="form-check-input" name="Limbs" id="3" value="3" <?php if ($values['LI']=='3') print 'checked=""'; ?>>3
                  </label>
                </div>
                <div class="form-check">
                  <label class="form-check-label">
                      <input type="radio" class="form-check-input" name="Limbs" id="4" value="4" <?php if ($values['LI']=='4') print 'checked=""'; ?>>4
                  </label>
                </div>
              </div>
            </div>
      			<div class="my-2 <?php if ($errors['SP']) {print 'class="error"';} ?>">
              <div class="form-group" value="">
                <label for="exampleSelect2">Superpowers</label>
                <select multiple="" class="form-control" name="SP[]">
                <option <?php for ($i=0;$i<count($values, COUNT_RECURSIVE)-10;$i++) if ($values['SP'][$i]=='Great power') print 'selected=""' ?>>Great power</option>
                <option <?php for ($i=0;$i<count($values, COUNT_RECURSIVE)-10;$i++) if ($values['SP'][$i]=='Invisibility') print 'selected=""' ?>>Invisibility</option>
                <option <?php for ($i=0;$i<count($values, COUNT_RECURSIVE)-10;$i++) if ($values['SP'][$i]=='Absolute knowledge') print 'selected=""' ?>>Absolute knowledge</option>
                <option <?php for ($i=0;$i<count($values, COUNT_RECURSIVE)-10;$i++) if ($values['SP'][$i]=='Fundamental immortality') print 'selected=""' ?>>Fundamental immortality</option>
              </select>
            </div>
            </div>
      			 <div class="my-2">
              <div class="form-group">
                <label>Biography</label>
                <textarea class="form-control <?php if ($errors['BG']) {print 'class="error"';} ?>" name="BG" rows="3"><?php print $values['BG']; ?></textarea>
            </div>  
            </div>
            <div class="my-2 jumbotron p-2 <?php if ($errors['CH']) {print 'class="error"';} ?>"">
              <div class="form-check">
                <label><input class="form-check-input" type="checkbox" name="CH" value="Yes" <?php print $values['CH']; ?>>I got acquainted with the contact
                </label>
              </div>
            </div>
            <button type="submit" class="btn btn-primary">Send</button>
      </div>
    </form>
  </div>
</body>
</html>