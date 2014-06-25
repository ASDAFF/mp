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
		
		
		$('#reg-handler').dialog({
			width: 880,
			height: 550,
      		modal: true,
			autoOpen: false,
		});
		
		$('#auth-handler').dialog({
			width: 880,
			height: 550,
      		modal: true,
			autoOpen: false,
		});
		
		<?if(isset($_POST['user'])) : ?>
			$('.open-reg').click();
		<?endif;?>
	});
</script>

<style type="text/css">
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
		width:330px;
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
		width: 340px;
		border-radius: 5px;
		font-size: 14px;
		cursor: pointer;
	}
</style>

<div id="reg-handler" style="display: none">
	<div class="modal-logo"><img src="/src/images/muchmore.jpg" style="width:70px;" /></div>
	<div class="modal-header">MUCHMORE МАГАЗИН БЕЗГРАНИЧНЫХ ВОЗМОЖНОСТЕЙ.<br />Москва, Осенняя 23, тел, Wiber, WatsUp: +7 495 517 43 64</div>
	<div class="left-column">
		<p class="orange">Пожалуйста, заполните эти поля <br />и вы получите полный <br />функционал сайта.</p>
		<p>Вам будет удобнее покупать, <br />формировать Вишлисты и отмечать <br />понравившиеся товары.</p>
		<p>Вы получите доступ к разделам <br />«Маркетплейс» Территория<br /> Уникальных Товаров и «Эврика»<br /> Территория Уникальных Идей.</p>
	</div>
	
	<div class="right-column">
		<?php
		if(!empty($errors)){
			echo '<p style="color:#f00;">'.implode('<br />', $errors).'</p>';
		}
		
		if(!empty($result)){
			echo '<p style="color:#11934c;">'.$result.'</p>';
		}
		?>
		<form method="post">
			<p class="reg-field">
				<!-- <label for="user_name">Имя</label><br /> -->
				<input id="user_name" type="text" value="<?=$_POST['user']['name'];?>" name="user[name]" placeholder="Имя, Город" /><br />
				<span>Как к вам обращаться и куда доставить заказ.</span>
			</p>
	
			<p class="reg-field">
				<!-- <label for="user_email">Email</label><br /> -->
				<input id="user_email" type="text" value="<?=$_POST['user']['email'];?>" name="user[email]" placeholder="Email" /><br />
				<span>Для получения электронного чека и гарантии.</span>
			</p>
	
			<p class="reg-field">
				<!-- <label for="user_phone">Телефон</label><br /> -->
				<input id="user_phone" type="text" value="<?=$_POST['user']['phone'];?>" name="user[phone]" placeholder="Телефон" /><br />
				<span>Для подтверждения заказа.</span>
			</p>
	
			<p class="reg-submit">
				<input type="submit" value="Зарегистрироваться" />
			</p>
		</form>
	</div>
	<div style="clear:both;"></div>
</div>

<div id="auth-handler">
	<div class="modal-logo"><img src="/src/images/muchmore.jpg" style="width:70px;" /></div>
	<div class="modal-header">MUCHMORE МАГАЗИН БЕЗГРАНИЧНЫХ ВОЗМОЖНОСТЕЙ.<br />Москва, Осенняя 23, тел, Wiber, WatsUp: +7 495 517 43 64</div>
	<div class="left-column">
		<p class="orange">Пожалуйста, заполните эти поля <br />и вы получите полный <br />функционал сайта.</p>
		<p>Вам будет удобнее покупать, <br />формировать Вишлисты и отмечать <br />понравившиеся товары.</p>
		<p>Вы получите доступ к разделам <br />«Маркетплейс» Территория<br /> Уникальных Товаров и «Эврика»<br /> Территория Уникальных Идей.</p>
		 
	</div>
	
	<div class="right-column">
    <?php
	    global $APPLICATION;
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

		echo $link = '<a href="' . $url . '?' . urldecode(http_build_query($params)) . '"><div class="soc fb-auth"></div></a>';

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
					}
		            $result = true;
		        }
		    }
		}
		?>
		<?$APPLICATION->IncludeComponent(
			"bitrix:system.auth.form",
			"",
			Array(
				"REGISTER_URL" => "",
				"FORGOT_PASSWORD_URL" => "",
				"PROFILE_URL" => "/personal/",
				"SHOW_ERRORS" => "Y"
			)
		);?>
	</div>
</div>

