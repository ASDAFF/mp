<?
// Types

$MESS['PRMEDIA_TC_NAME_ADMIN'] = 'Уведомление администратора о новом комментарии';
$MESS['PRMEDIA_TC_EVENT_DESCRIPTION_ADMIN'] = '
#ELEMENT_ID# - ID комментируемого элемента
#ELEMENT_NAME# - имя комментируемого элемента
#ELEMENT_CODE# - символьный код элемента
#DETAIL_PAGE_URL# - ссылка на детальный просмотр элемента (из настроек инфоблока).
#USER_LOGIN# - логин пользователя
#USER_ID# - ID автора комментария
#COMMENT_ID# - ID комментария
#COMMENT_TEXT# - текст комментария
#EMAIL_FROM# - от кого
#EMAIL_TO# - кому';

$MESS['PRMEDIA_TC_NAME_USER'] = 'Уведомление пользователя после ответа на его комментарий';
$MESS['PRMEDIA_TC_EVENT_DESCRIPTION_USER'] = '
#ELEMENT_ID# - ID комментируемого элемента
#ELEMENT_NAME# - имя комментируемого элемента
#ELEMENT_CODE# - символьный код элемента
#DETAIL_PAGE_URL# - ссылка на детальный просмотр элемента (из настроек инфоблока).
#USER_LOGIN# - логин пользователя
#USER_ID# - ID автора комментария
#COMMENT_ID# - ID комментария
#COMMENT_TEXT# - текст комментария
#PARENT_ID# - ID исходного комментария
#EMAIL_FROM# - от кого
#EMAIL_TO# - кому';

$MESS['PRMEDIA_TC_NAME_USER_MENTION'] = 'Уведомление пользователя после упоминания его в комментарии';
$MESS['PRMEDIA_TC_EVENT_DESCRIPTION_USER_MENTION'] = '
#ELEMENT_ID# - ID комментируемого элемента
#ELEMENT_NAME# - имя комментируемого элемента
#ELEMENT_CODE# - символьный код элемента
#DETAIL_PAGE_URL# - ссылка на детальный просмотр элемента (из настроек инфоблока).
#USER_LOGIN# - логин упомянутого пользователя
#USER_ID# - ID упомянутого пользователя
#COMMENT_ID# - ID комментария
#COMMENT_TEXT# - текст комментария
#AUTHOR_ID# - ID автора комментария
#AUTHOR_LOGIN# - логин автора комментария
#EMAIL_FROM# - от кого
#EMAIL_TO# - кому';

$MESS['PRMEDIA_TC_NAME_ACTIVATE'] = 'Уведомление пользователя после активации модератором его комментария';
$MESS['PRMEDIA_TC_EVENT_DESCRIPTION_ACTIVATE'] = '
#ELEMENT_ID# - ID комментируемого элемента
#ELEMENT_NAME# - имя комментируемого элемента
#ELEMENT_CODE# - символьный код элемента
#DETAIL_PAGE_URL# - ссылка на детальный просмотр элемента (из настроек инфоблока).
#USER_LOGIN# - логин пользователя
#USER_ID# - ID автора комментария
#COMMENT_ID# - ID комментария
#EMAIL_FROM# - от кого
#EMAIL_TO# - кому';

// Templates

$MESS['PRMEDIA_TC_SUBJECT_ADMIN'] = '#SITE_NAME#: Добавлен новый комментарий';
$MESS['PRMEDIA_TC_BODY_MESSAGE_ADMIN'] = '#USER_LOGIN# оставил(а) новый 
<a href="http://#SERVER_NAME##DETAIL_PAGE_URL##comment_#COMMENT_ID#">комментарий</a> 
к элементу <a href="http://#SERVER_NAME##DETAIL_PAGE_URL#">#ELEMENT_NAME#</a>. 
Текст комментария: #COMMENT_TEXT#';

$MESS['PRMEDIA_TC_SUBJECT_USER'] = '#SITE_NAME#: #USER_LOGIN# ответил на ваш комментарий';
$MESS['PRMEDIA_TC_BODY_MESSAGE_USER'] = 'Пользователь <a href="http://#SERVER_NAME#/users/#USER_LOGIN#/">
#USER_LOGIN#</a> ответил(а) на ваш <a href="http://#SERVER_NAME##DETAIL_PAGE_URL##comment_#COMMENT_ID#">
комментарий</a> к записи <a href="http://#SERVER_NAME##DETAIL_PAGE_URL#">#ELEMENT_NAME#</a>. 
Текст комментария:#COMMENT_TEXT#';

$MESS['PRMEDIA_TC_SUBJECT_USER_MENTION'] = '#SITE_NAME#: #USER_LOGIN# упомянул вас в своем комментарии';
$MESS['PRMEDIA_TC_BODY_MESSAGE_USER_MENTION'] = 'Пользователь #USER_LOGIN# упомянул вас в 
<a href="http://#SERVER_NAME##DETAIL_PAGE_URL##comment_#COMMENT_ID#">комментарии</a>:
#COMMENT_TEXT#';

$MESS['PRMEDIA_TC_SUBJECT_ACTIVATE'] = '#SITE_NAME#: Ваш комментарий был активирован';
$MESS['PRMEDIA_TC_BODY_MESSAGE_ACTIVATE'] = 'Здравствуйте, #USER_LOGIN#! Ваш 
<a href="http://#SERVER_NAME##DETAIL_PAGE_URL##comment_#COMMENT_ID#">комментарий</a> к элементу 
<a href="http://#SERVER_NAME##DETAIL_PAGE_URL#">#ELEMENT_NAME#</a> был одобрен модератором.';
?>
