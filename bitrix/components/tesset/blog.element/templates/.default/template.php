<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?$item = $arResult;?>
<div class="content-blog-item">
    <div class="blog-item-header">
        <h1><?=$item['name']?></h1>
        <div class="blog-item-info">
            <span class="fa fa-clock-o"> <?=$item['date']?></span>
            <span class="fa fa-eye"> <?=$item['shows']?></span>
            <span class="fa fa-heart-o blog-item-like blog-item-active-info <?=($item['likes']['already_liked']) ? 'active-like' : ''?>" data-object="<?=$item['id']?>" data-ib="<?=$item['iblockId']?>"> <?=$item['likes']['value']?></span>
            <span class="fa fa-comment-o blog-item-comments blog-item-active-info"> <?=$item['comments']?></span>
        </div>
        <div class="blog-item-picture">
            <img src="<?=$item['picture']['src']?>" alt="<?=$item['name']?>" title="<?=$item['name']?>"/>
        </div>
    </div>
    <div class="blog-item-text">
        <?=$item['text']?>
    </div>
    <div style="width: 300px; text-align: center; margin: 0 auto;">
        <a class="index-cat-evrika" style="color: #999 !important; border-color: #999 !important;" href="mailto:office@muchmore.ru">СВЯЗАТЬСЯ С АВТОРОМ</a>
    </div>
    <div class="cat-text" id="comments">
        <h3>Комментарии</h3>
        <?$APPLICATION->IncludeComponent("prmedia:treelike_comments", "butik-treecomments", array(
            "LEFT_MARGIN" => "50",
            "MAX_DEPTH_LEVEL" => "5",
            "ASNAME" => "login",
            "SHOW_USERPIC" => "Y",
            "SHOW_DATE" => "Y",
            "SHOW_COMMENT_LINK" => "N",
            "DATE_FORMAT" => "d.m.Y",
            "SHOW_COUNT" => "Y",
            "OBJECT_ID" => $item['id'],
            "CAN_MODIFY" => "N",
            "PREMODERATION" => "Y",
            "ALLOW_RATING" => "Y",
            "NON_AUTHORIZED_USER_CAN_COMMENT" => "N",
            "USE_CAPTCHA" => "NO",
            "FORM_MIN_TIME" => "3",
            "NO_FOLLOW" => "N",
            "NO_INDEX" => "N",
            "SEND_TO_USER_AFTER_ANSWERING" => "Y",
            "SEND_TO_USER_AFTER_MENTION_NAME" => "Y",
            "SEND_TO_ADMIN_AFTER_ADDING" => "Y",
            "SEND_TO_USER_AFTER_ACTIVATE" => "Y",
            "AUTH_PATH" => "/personal/",
            "TO_USERPAGE" => "/users/#USER_LOGIN#/",
            "CACHE_TYPE" => "N",
            "CACHE_TIME" => "3600"
            ),
            false
        ); ?>
    </div>
</div>
