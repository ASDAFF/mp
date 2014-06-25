<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Регистрация");
?>

<?
	/* Функция генерации случайного пароля */
	function make_password($num_chars){ 
		if ((is_numeric($num_chars)) && ($num_chars > 0) && (! is_null($num_chars))) {
			$password = "";
			$accepted_chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyzl234567890";
			for ($i=0; $i<=$num_chars; $i++) {
				$random_number = rand(0, (strlen($accepted_chars)-1));
				$password .= $accepted_chars[$random_number];
			}
			return $password;
		}
	}

	global $USER;
	
	if(isset($_POST['user'])){
		$errors = array();
		$result = '';
		if(empty($_POST['user']['name'])) $errors[] = 'Вы не ввели имя';
		if(empty($_POST['user']['email'])) $errors[] = 'Вы не ввели email';
		if(empty($_POST['user']['phone'])) $errors[] = 'Вы не ввели телефон';
	
		if(empty($errors)){
			$user = new CUser;
			
			$password = make_password(8);
			
			$arFields = Array(
				"NAME"              => $_POST['user']['name'],
				"EMAIL"             => $_POST['user']['email'],
				"LOGIN"             => $_POST['user']['email'],
				"ACTIVE"            => "Y",
				"GROUP_ID"          => array(6),
				"PASSWORD"          => $password,
				"CONFIRM_PASSWORD"  => $password,
				"PERSONAL_PHONE"	=> $_POST['user']['phone']
			);

			$ID = $user->Add($arFields);
			if (intval($ID) > 0){
				$result = "Вы успешно зарегистрированы.";
				$arEventField = array("NAME" => $_POST['user']['name'], "EMAIL" => $_POST['user']['email'], "PASSWORD" => $password);
				CEvent::SendImmediate("NEW_REG", "s1", $arEventField);
				unset($_POST['user']);
				$USER->Authorize($ID);
				
			} else {
				$errors[] = $user->LAST_ERROR;
			}
		}
		
		if(!empty($errors)){
			echo '<p style="color:#f00;">'.implode('<br />', $errors).'</p>';
		}
		
		if(!empty($result)){
			echo '<p style="color:#11934c;">'.$result.'</p>';
		}
	}
?>
<style type="text/css">
	.reg-field
</style>
<form method="post">
	<div class="reg-field">
		<label for="user_name">Имя</label><br />
		<input id="user_name" type="text" value="<?=$_POST['user']['name'];?>" name="user[name]" /><br />
		<span>Как к вам обращаться и куда доставить заказ.</span>
	</div>
	
	<div class="reg-field">
		<label for="user_email">Email</label><br />
		<input id="user_email" type="text" value="<?=$_POST['user']['email'];?>" name="user[email]" /><br />
		<span>Для получения электронного чека и гарантии.</span>
	</div>
	
	<div class="reg-field">
		<label for="user_phone">Телефон</label><br />
		<input id="user_phone" type="text" value="<?=$_POST['user']['phone'];?>" name="user[phone]" /><br />
		<span>Для подтверждения заказа.</span>
	</div>
	
	<div class="reg-submit">
		<input type="submit" value="Зарегистрироваться" />
	</div>
</form>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>