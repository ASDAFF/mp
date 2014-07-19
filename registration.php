<?php
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

	function registerUser($userInfo) {
		$errors = array();
		$result = '';
		if(empty($userInfo['name'])) $errors[] = 'Вы не ввели имя';
		if(empty($userInfo['email'])) $errors[] = 'Вы не ввели email';
		if(empty($userInfo['phone'])) $errors[] = 'Вы не ввели телефон';
	
		if(empty($errors)){
			$user = new CUser;
			
			$password = make_password(8);

			$arFields = Array(
				"NAME"              => $userInfo['name'],
				"EMAIL"             => $userInfo['email'],
				"LOGIN"             => $userInfo['email'],
				"ACTIVE"            => "Y",
				"GROUP_ID"          => array(6),
				"PASSWORD"          => $password,
				"CONFIRM_PASSWORD"  => $password,
				"PERSONAL_PHONE"	=> $userInfo['phone']
			);

			$ID = $user->Add($arFields);
			if (intval($ID) > 0){
				$result = "Вы успешно зарегистрированы.";
				$arEventField = array("NAME" => $userInfo['name'], "EMAIL" => $userInfo['email'], "PASSWORD" => $password);
				CEvent::SendImmediate("NEW_REG", "s1", $arEventField);
				unset($_POST['user']);
				global $USER;
				global $APPLICATION;
				$USER->Authorize($ID);
				LocalRedirect($APPLICATION->GetCurPageParam('', array('user[name]', 'user[email]', 'ELEMENT_CODE', 'code')));
				
			} else {
				$errors[] = $user->LAST_ERROR;
			}
		}
		return $errors;
	}
	
	$userInfo = null;
	$eventSent = $forgetError = false;
	if(isset($_POST['user'])){
		$userInfo = $_POST['user'];
		$errors = registerUser($_POST['user']);
	} elseif (isset($_GET['user'])) {
		$userInfo = $_GET['user'];
	} elseif (isset($_POST['forget'])) {
		if (!$_POST['forget']['email']) {
			$forgetError = 'Неверно указан почтовый адрес';
		} else {
			$user = CUser::GetList(($by = "id"), ($order = "desc"), array('EMAIL' => $_POST['forget']['email'], 'ACTIVE' => 'Y'))->Fetch();
			if (!$user) {
				$forgetError = 'Неверно указан почтовый адрес';
			} else {
				$objUser = new CUser;
				$password = make_password(8);
				$objUser->Update($user['ID'], array(
					'PASSWORD' => $password,
					'CONFIRM_PASSWORD' => $password
					));
				$eventFields = array(
					'NAME' => $user['NAME'],
					'PASSWORD' => $password,
					'EMAIL' => $user['EMAIL']
					);
				$eventSent = CEvent::Send('FORGET_PASS', 's1', $eventFields);
			}
		}
	}
?>
<script type="text/javascript">
	$(document).ready(function(){
		$('.open-auth').click(function(e){
			e.preventDefault();
			$('#auth-handler').dialog('open');
			return false;
		});
		
		$('.open-reg').click(function(e){
			e.preventDefault();
			$('#reg-handler').dialog('open');
			return false;
		});

		$('.subscribe-me').click(function(e){
			e.preventDefault();
			$('#subscribe-handler').dialog('open');
			$.getJSON('/ajax/subscription.php', {});
			return false;
		});
		
		$('.subscribe-submit').click(function () {
			$.getJSON('/ajax/subscription.php', {email: $('.subscribe-email').val()}, function (error) {
				if (error == '0') {
					$('.subscribe-actions').text('Вы успешно подписаны на новости Muchmore!');
				} else {
					$('.subsribe-error').html(error);
				}
			});
		});
		
		
		$('#reg-handler').dialog({
			width: 600,
			height: 550,
      		modal: true,
			autoOpen: false,
		});
		
		$('#auth-handler').dialog({
			width: 600,
			// height: 400,
      		modal: true,
			autoOpen: false,
		});

		$('#forget-handler').dialog({
			width: 600,
			// height: 300,
      		modal: true,
			autoOpen: false,
		});

		$('#subscribe-handler').dialog({
			width: 600,
			height: 200,
      		modal: true,
			autoOpen: false,
		});

		function closeModals() {
			$('#reg-handler').dialog('close');
			$('#forget-handler').dialog('close');
			$('#auth-handler').dialog('close');
			$('#subscribe-handler').dialog('close');
		}

		$('.change-auth').on('click', function () {
			closeModals();
			$('#auth-handler').dialog('open');
		});

		$('.change-reg').on('click', function () {
			closeModals();
			$('#reg-handler').dialog('open');
		});

		$('.change-forget').on('click', function () {
			closeModals();
			$('#forget-handler').dialog('open');
		});
		
		<?if (!is_null($userInfo)) : ?>
			closeModals();
			$('#reg-handler').dialog('open');
		<?endif;?>
		
		<?if (isset($_POST['forget']) && false === $forgetError) : ?>
			closeModals();
			$('#forget-handler').dialog('open');
		<?endif;?>

		<?if (isset($_POST['forget']) && false !== $eventSent) : ?>
			closeModals();
			$('#auth-handler').dialog('open');
		<?endif;?>

	});
</script>

<style type="text/css">
	/*.ui-corner-all, .ui-corner-bottom, .ui-corner-right, .ui-corner-br {
		border-bottom-right-radius: 0;
		background: #E9E9E9;
	}*/
	.modal-logo{
		position: absolute;
		top:-50px;
		left:10px;
	}
	
	.modal-header{
		position: absolute;
		top:-35px;
		left:90px;
		font-size: 14px;
		color: #999;
	}
	
	.left-column{
		width:370px;
		float: left;
		border-right: 1px solid #999;
		margin: 50px 0px 50px 0px;
		font-size: 18px;
		line-height: 25px;
		padding: 0px 50px 0px 0px;
		color: #999;
		font-weight: 300;
	}
	
	.left-column .orange{
		color: #f15824;
	}
	
	.right-column{
		width:370px;
		float: right;
		margin: 50px 0px 50px 0px;
		font-size: 18px;
		line-height: 25px;
		padding: 0px 0px 0px 50px;
	}
	
	.right-column .reg-field input{
		width:360px;
		margin: 0px;
		padding: 5px;
		border:1px solid #999;
	}
	
	.right-column .reg-field span{
		color: #999;
		font-size: 14px;
	}
	
	.right-column .reg-submit input{
		color: #fff;
		background-color: #f15824;
		border: none;
		padding: 10px 0px;
		width: 373px;
		border-radius: 5px;
		font-size: 14px;
		cursor: pointer;
	}
	.right-column p.explain {line-height: 4px;}
	.head-text {color: #000; font-size: 19px; }
</style>



<div id="subscribe-handler" style="display: none; height: 550px; padding-bottom: 50px;">
	<div class="right-column" style="margin-top: -35px; float: none; margin:-35px auto; padding: 0; text-align: center;">
		<?if ($USER->isAuthorized()) : ?>
			<p>Вы успешно подписаны на новости Muchmore!</p>
			<p>Хорошего дня!</p>
		<?else : ?>
			<div class="subscribe-actions">
				<form method="post">
					<p class="subsribe-error"></p>
					<p class="reg-field">
						<input type="text" class="subscribe-email" placeholder="Email адрес" /><br />
					</p>
					<!-- <p><small style="font-size: 13px;">Уже впомнили пароль? <a href="javascript:;" class="change-auth">Войти</a></small></p> -->
					<p class="reg-field">
						<a href="javascript:;" class="subscribe-submit">Подписаться</a>
					</p>
				</form>
			</div>
		<?endif?>
	</div>
</div>

<div id="forget-handler" style="display: none; height: 550px; padding-bottom: 50px;">
	<div class="right-column" style="margin-top: -35px; float: none; margin:-35px auto; padding: 0;">
		<!-- <p class="head-text">Пожалуйста, заполните эти поля и пользуйтесь всеми возможностями сайта.</p> -->
		<form method="post">
			<p class="reg-field">
				<!-- <label for="user_name">Имя</label><br /> -->
				<input id="user_name" type="text" value="<?=$userInfo['name'];?>" name="forget[email]" placeholder="Email адрес" /><br />
				<!-- <span>Как к вам обращаться и куда доставить заказ.</span> -->
			</p>
			<!-- <p><small style="font-size: 13px;">Уже впомнили пароль? <a href="javascript:;" class="change-auth">Войти</a></small></p> -->
			<p class="reg-submit">
				<input type="submit" name="forget[submit]" value="Выслать пароль" />
			</p>
		</form>
	</div>
</div>

<div id="reg-handler" style="display: none;  height: 550px;">
	<!-- <div class="modal-logo"><img src="/src/images/muchmore.jpg" style="width:70px;" /></div> -->
	<!-- <div class="modal-header" style="width: 278px;">MUCHMORE МАГАЗИН БЕЗГРАНИЧНЫХ ВОЗМОЖНОСТЕЙ.<br />Москва, Осенняя 23, тел, Wiber, WatsUp: +7 495 517 43 64</div> -->
	<!-- <div class="left-column">
		<p class="orange">Пожалуйста, заполните эти поля <br />и вы получите полный <br />функционал сайта.</p>
		<p>Вам будет удобнее покупать, <br />формировать Вишлисты и отмечать <br />понравившиеся товары.</p>
		<p>Вы получите доступ к разделам <br />«Маркетплейс» Территория<br /> Уникальных Товаров и «Эврика»<br /> Территория Уникальных Идей.</p>
	</div> -->
	
	<div class="right-column" style="margin-top: -35px; float: none; margin:-35px auto; padding: 0;">
		<p class="head-text">Пожалуйста, заполните эти поля и пользуйтесь всеми возможностями сайта.</p>
		<?php
		 //    global $APPLICATION;
			// $client_id = '681312905274770'; // Client ID
			// $client_secret = 'af8718f44710325e293bc2068368dd96'; // Client secret
			// $redirect_uri = 'http://mm.wrdev.ru' . $APPLICATION->GetCurPageParam('', array('code', 'ELEMENT_CODE', 'section', 'catalog')); // Redirect URIs

			// $url = 'https://www.facebook.com/dialog/oauth';

			// $params = array(
			//     'client_id'     => $client_id,
			//     'redirect_uri'  => $redirect_uri,
			//     'response_type' => 'code',
			//     'scope'         => 'email,user_birthday'
			// );

			// echo $link = '<a href="' . $url . '?' . urldecode(http_build_query($params)) . '"><div class="soc fb-auth"></div></a>';

			// if (isset($_GET['code'])) {
			//     $result = false;

			//     $params = array(
			//         'client_id'     => $client_id,
			//         'redirect_uri'  => $redirect_uri,
			//         'client_secret' => $client_secret,
			//         'code'          => $_GET['code']
			//     );

			//     $url = 'https://graph.facebook.com/oauth/access_token';

			//     $tokenInfo = null;
			//     parse_str(file_get_contents($url . '?' . http_build_query($params)), $tokenInfo);

			//     if (count($tokenInfo) > 0 && isset($tokenInfo['access_token'])) {
			//         $params = array('access_token' => $tokenInfo['access_token']);

			//         $userInfo = json_decode(file_get_contents('https://graph.facebook.com/me' . '?' . urldecode(http_build_query($params))), true);

			//         if (isset($userInfo['id'])) {
			//             $userInfo = $userInfo;
			// 			$bitrixUser = CUser::GetByLogin($userInfo['email'])->Fetch();
			// 			if (false !== $bitrixUser) {
			// 				global $USER;
			// 				$USER->Authorize($bitrixUser['ID']);
			// 				LocalRedirect($APPLICATION->GetCurPageParam('', array('code')));
			// 			} else {
			// 				LocalRedirect($APPLICATION->GetCurPageParam('user[name]=' . $userInfo['name'] . '&user[email]=' . $userInfo['email'], array('code')));
			// 			}
			//             $result = true;
			//         }
			//     }
			// }
		?>
		<?php
		if(!empty($errors)){
			echo '<p style="color:#f00;">'.implode('<br />', $errors).'</p>';
		}
		
		if(!empty($result)) {
			echo '<p style="color:#11934c;">' . $result . '</p>';
		}
		?>
		<form method="post">
			<p class="reg-field">
				<!-- <label for="user_name">Имя</label><br /> -->
				<input id="user_name" type="text" value="<?=$userInfo['name'];?>" name="user[name]" placeholder="Имя, Город" /><br />
				<!-- <span>Как к вам обращаться и куда доставить заказ.</span> -->
			</p>
	
			<p class="reg-field">
				<!-- <label for="user_email">Email</label><br /> -->
				<input id="user_email" type="text" value="<?=$userInfo['email'];?>" name="user[email]" placeholder="Email" /><br />
				<!-- <span>Для получения электронного чека и гарантии.</span> -->
			</p>
	
			<p class="reg-field">
				<!-- <label for="user_phone">Телефон</label><br /> -->
				<input id="user_phone" type="text" value="<?=$userInfo['phone'];?>" name="user[phone]" placeholder="Телефон" /><br />
				<!-- <span>Для подтверждения заказа.</span> -->
			</p>
			</hr>
			<p><small style="font-size: 13px;">Регистрировались раньше? <a href="javascript:;" class="change-auth">Войти</a></small></p>
			<p class="reg-submit">
				<input type="submit" value="Зарегистрироваться" />
			</p>
		</form>
		<small style="color: #999; font-size: 13px; line-height: 20px;">Вам будет удобнее покупать, формировать Вишлисты и отмечать понравившиеся товары. Вы получите доступ к разделам «Маркетплейс» Территория Уникальных Товаров и «Эврика»Территория Уникальных Идей.</small>
	</div>
	<div style="clear:both;"></div>
</div>

<div id="auth-handler" style="display: none; padding-bottom: 50px;">
	<!-- <div class="modal-logo"><img src="/src/images/muchmore.jpg" style="width:70px;" /></div> -->
	<!-- <div class="modal-header" style="width: 278px;">MUCHMORE МАГАЗИН БЕЗГРАНИЧНЫХ ВОЗМОЖНОСТЕЙ.<br />Москва, Осенняя 23, тел, Wiber, WatsUp: +7 495 517 43 64</div> -->
	<!-- <div class="left-column"> -->
		<?php
		    /*global $APPLICATION;
			$client_id = '681312905274770'; // Client ID
			$client_secret = 'af8718f44710325e293bc2068368dd96'; // Client secret
			$redirect_uri = 'http://mm.wrdev.ru' . $APPLICATION->GetCurPageParam('', array('code', 'ELEMENT_CODE', 'section', 'catalog')); // Redirect URIs

			$url = 'https://www.facebook.com/dialog/oauth';

			$params = array(
			    'client_id'     => $client_id,
			    'redirect_uri'  => $redirect_uri,
			    'response_type' => 'code',
			    'scope'         => 'email,user_birthday'
			);

			echo $link = '<a href="' . $url . '?' . urldecode(http_build_query($params)) . '"><div class="soc fb-auth" style="margin: 119px auto;"></div></a>';

			if (isset($_GET['code'])) {
			    $result = false;

			    $params = array(
			        'client_id'     => $client_id,
			        'redirect_uri'  => $redirect_uri,
			        'client_secret' => $client_secret,
			        'code'          => $_GET['code']
			    );

			    $url = 'https://graph.facebook.com/oauth/access_token';

			    $tokenInfo = null;
			    parse_str(file_get_contents($url . '?' . http_build_query($params)), $tokenInfo);

			    if (count($tokenInfo) > 0 && isset($tokenInfo['access_token'])) {
			        $params = array('access_token' => $tokenInfo['access_token']);

			        $userInfo = json_decode(file_get_contents('https://graph.facebook.com/me' . '?' . urldecode(http_build_query($params))), true);

			        if (isset($userInfo['id'])) {
			            $userInfo = $userInfo;
						$bitrixUser = CUser::GetByLogin($userInfo['email'])->Fetch();
						if (false !== $bitrixUser) {
							global $USER;
							$USER->Authorize($bitrixUser['ID']);
							LocalRedirect($APPLICATION->GetCurPageParam('', array('code')));
						} else {
							LocalRedirect($APPLICATION->GetCurPageParam('user[name]=' . $userInfo['name'] . '&user[email]=' . $userInfo['email'], array('code')));
						}
			            $result = true;
			        }
			    }
			}*/
		?>
		 
	<!-- </div> -->
	
	<div class="right-column" style="margin-top: -35px; float: none; margin:-35px auto; padding: 0;">
	<?php
	   /* $client_id = '4431649'; // ID приложения
	    $client_secret = '1eNcuku7lIF5JtmMeo5H'; // Защищённый ключ
	    $redirect_uri = 'http://mm.wrdev.ru' . $APPLICATION->GetCurPageParam('', array('code', 'ELEMENT_CODE', 'section', 'catalog')); // Адрес сайта

	    $url = 'http://oauth.vk.com/authorize';

	    $params = array(
	        'client_id'     => $client_id,
	        'redirect_uri'  => $redirect_uri,
	        'response_type' => 'code',
	        'scope' => 'email'
	    );

	    echo $link = '<p><a href="' . $url . '?' . urldecode(http_build_query($params)) . '">Аутентификация через ВКонтакте</a></p>';

		if (isset($_GET['code'])) {
		    $result = false;
		    $params = array(
		        'client_id' => $client_id,
		        'client_secret' => $client_secret,
		        'code' => $_GET['code'],
		        'redirect_uri' => $redirect_uri
		    );

		    $token = json_decode(file_get_contents('https://oauth.vk.com/access_token' . '?' . urldecode(http_build_query($params))), true);

		    if (isset($token['access_token'])) {
		        $params = array(
		            'uids'         => $token['user_id'],
		            'fields'       => 'uid,first_name,last_name,screen_name,sex,bdate,photo_big,contacts',
		            'access_token' => $token['access_token']
		        );

		        $userInfo = json_decode(file_get_contents('https://api.vk.com/method/users.get' . '?' . urldecode(http_build_query($params))), true);
		        if (isset($userInfo['response'][0]['uid'])) {
		            $userInfo = $userInfo['response'][0];
		            $result = true;
		        }
		    }

	    	var_dump($userInfo);
		}*/
	?>

	<?php
		// var_dump('expression');
		// die('asdas');
		// $client_id = '634627425510-s3c975dk8gsda4rt2k4fpk6e1lpg6bal.apps.googleusercontent.com'; // Client ID
		// $client_secret = 'aDdZ2EEIPI8EH5V-mZUbclAY'; // Client secret
		// $redirect_uri = 'http://mm.wrdev.ru/personal/'; // Redirect URIs

		// $url = 'https://accounts.google.com/o/oauth2/auth';

		// $params = array(
		//     'redirect_uri'  => $redirect_uri,
		//     'response_type' => 'code',
		//     'client_id'     => $client_id,
		//     'scope'         => 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile'
		// );

		// echo $link = '<p><a href="' . $url . '?' . urldecode(http_build_query($params)) . '">Аутентификация через Google</a></p>';

		// if (isset($_GET['code'])) {
		//     $result = false;

		//     $params = array(
		//         'client_id'     => $client_id,
		//         'client_secret' => $client_secret,
		//         'redirect_uri'  => $redirect_uri,
		//         'grant_type'    => 'authorization_code',
		//         'code'          => $_GET['code']
		//     );

		//     $url = 'https://accounts.google.com/o/oauth2/token';
		//     $curl = curl_init();
		//     curl_setopt($curl, CURLOPT_URL, $url);
		//     curl_setopt($curl, CURLOPT_POST, 1);
		//     curl_setopt($curl, CURLOPT_POSTFIELDS, urldecode(http_build_query($params)));
		//     curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		//     curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		//     $result = curl_exec($curl);
		//     curl_close($curl);
		//     $tokenInfo = json_decode($result, true);

		//     if (isset($tokenInfo['access_token'])) {
		//         $params['access_token'] = $tokenInfo['access_token'];

		//         $userInfo = json_decode(file_get_contents('https://www.googleapis.com/oauth2/v1/userinfo' . '?' . urldecode(http_build_query($params))), true);
		//         var_dump($userinfo);
		//         if (isset($userInfo['id'])) {
		//             $userInfo = $userInfo;
		//             var_dump($userinfo);
		//             $result = true;
		//         }
		//     }
		// }

	?>

		<?
		if (isset($_POST['forget']) && false !== $eventSent) {
			unset($_POST['forget']);
			echo '<p> Пароль отправлен на ваш email. Скопируйте его и используйте для входа на сайт.</p>';	
		}
		?>
		<?$APPLICATION->IncludeComponent(
			"bitrix:system.auth.form",
			"butik-auth",
			Array(
				"REGISTER_URL" => "",
				"FORGOT_PASSWORD_URL" => "",
				"PROFILE_URL" => "/personal/",
				"SHOW_ERRORS" => "Y"
			)
		);?>
		<p class="explain"><small style="font-size: 13px;">Не регистрировались раньше? <a href="javascript:;" class="change-reg">Зарегистрироваться</a></small></p>
		<p class="explain"><small style="font-size: 13px;">Не сохранили пароль? <a href="javascript:;" class="change-forget">Выслать новый пароль</a></small></p>
	</div>
</div>
<?
// die('asdas');
