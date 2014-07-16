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
        <!-- Товары к статье блога -->
            <?if ($arResult['related']) : ?>
            <div class="related">
                <h3>Магазин</h3>
                <?foreach ($arResult['related'] as $id => $related) : ?>
                <div class="item-block2">
                    <div style="height:200px; width:300px;">
                        <a href="/butik/<?=$related['code']?>/">
                            <img style="width:300px; height:199px;" src="<?=$related['picture']['src']?>" alt="">
                        </a>
                    </div>              
                    <a href="/butik/<?=$related['code']?>/" class="cat-link">
                        <?=$related['name']?>
                    </a>
                    <div class="price"><?=$related['price']?> р.</div>
                    <div class="cabinet-box3" style="float: right; margin-top: -46px;">
                        <ul>
                            <li>
                                <?if (!$USER->isAuthorized()) : ?>
                                    <a class="open-reg" style="background: #f15824; width: 160px; box-shadow: none;" href="/personal/">Купить</a>
                                <?else : ?>
                                    <a style="background: #f15824; width: 160px; box-shadow: none;" class="buy-link" href="/butik/<?=$related['code']?>/?action=BUY&amp;id=<?=$id?>&amp;ELEMENT_CODE=<?=$related['code']?>">В корзину</a>
                                <?endif;?>
                            </li>                           
                            <ul>
                            </ul>
                        </ul>
                    </div>
                </div>
                <?endforeach;?>
            </div>
            <?endif;?>
    </div>
    <?if ($arParams['SHOW_CONNECT']) : ?>
        <div style="width: 300px; text-align: center; margin: 0 auto;">
            <a class="index-cat-evrika" style="color: #999 !important; border-color: #999 !important;" href="mailto:office@muchmore.ru">СВЯЗАТЬСЯ С АВТОРОМ</a>
        </div>
    <?endif;?>
    <ul class="blog-item-social" <?if ($arParams['SHOW_CONNECT']) : ?>style="margin-top: -21px; margin-right: 75px;"<?endif;?>>
        <li style="width: 89px;">
            <!-- Put this script tag to the <head> of your page -->
            <script type="text/javascript" src="//vk.com/js/api/openapi.js?113"></script>

            <script type="text/javascript">
              VK.init({apiId: 4454187, onlyWidgets: true});
            </script>

            <!-- Put this div tag to the place, where the Like block will be -->
            <div id="vk_like"></div>
            <script type="text/javascript">
            VK.Widgets.Like("vk_like", {type: "mini"});
            </script>
            </li>
            <li>
                <div id="fb-root"></div>
                <script>(function(d, s, id) {
                  var js, fjs = d.getElementsByTagName(s)[0];
                  if (d.getElementById(id)) return;
                  js = d.createElement(s); js.id = id;
                  js.src = "//connect.facebook.net/ru_RU/sdk.js#xfbml=1&appId=681312905274770&version=v2.0";
                  fjs.parentNode.insertBefore(js, fjs);
                }(document, 'script', 'facebook-jssdk'));</script>
                <div class="fb-like" data-href="<?=$APPLICATION->GetCurDir();?>" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false"></div>
                <!-- <div class="fb-like" data-href="<?=$APPLICATION->GetCurDir();?>" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false"></div> -->
            </li>
            <li style="width: 56px;">
                <a href="https://twitter.com/share" class="twitter-share-button" data-url="http://muchmore.ru/" data-text="Muchmore" data-via="muchmore" data-lang="ru" data-related="muchmore" data-hashtags="muchmore">Твитнуть</a>
                <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
            </li>
    </ul>
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
