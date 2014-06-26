<?
// подключим все необходимые файлы:
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php"); // первый общий пролог

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/ws.saleuserprofiles/include.php"); // инициализация модуля
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/sale/include.php"); // инициализация модуля
//require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/ws.saleuserprofiles/prolog.php"); // пролог модуля

// подключим языковой файл
IncludeModuleLangFile(__FILE__);

// получим права доступа текущего пользователя на модуль
$POST_RIGHT = $APPLICATION->GetGroupRight("ws.saleuserprofiles");
// если нет прав - отправим к форме авторизации с сообщением об ошибке
if ($POST_RIGHT == "D"){
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
}

$sTableID = "b_sale_user_props"; // ID таблицы
$oSort = new CAdminSorting($sTableID, "ID", "desc"); // объект сортировки
$lAdmin = new CAdminList($sTableID, $oSort); // основной объект списка
?>
<?
// здесь будет вся серверная обработка и подготовка данных
// ******************************************************************** //
//                           ФИЛЬТР                                     //
// ******************************************************************** //

// *********************** CheckFilter ******************************** //
function CheckFilter()
{
    global $FilterArr, $lAdmin;
    foreach ($FilterArr as $f) global $$f;

    // В данном случае проверять нечего.
    // В общем случае нужно проверять значения переменных $find_имя
    // и в случае возниконовения ошибки передавать ее обработчику
    // посредством $lAdmin->AddFilterError('текст_ошибки').

    return count($lAdmin->arFilterErrors) == 0; // если ошибки есть, вернем false;
}
// *********************** /CheckFilter ******************************* //


// опишем элементы фильтра
$FilterArr = Array(
    "find_id",
    "find_name",
    "find_user_id",
    "find_person_type_id",
    //"find_date_update",
);

// инициализируем фильтр
$lAdmin->InitFilter($FilterArr);

// если все значения фильтра корректны, обработаем его
if (CheckFilter())
{
    // создадим массив фильтрации для выборки CRubric::GetList() на основе значений фильтра
    $arFilter = array();
    if(!empty($find_id)) {
        $arFilter["ID"] = $find_id;
    }
    if(!empty($find_name)) {
        $arFilter["%NAME"] = $find_name;
    }
    if(!empty($find_person_type_id)) {
        $arFilter["PERSON_TYPE_ID"] = $find_person_type_id;
    }
    if(!empty($find_user_id)) {
        $arFilter["USER_ID"] = $find_user_id;
    }
    /*if(!empty($find_date_update)) {
        $arFilter["DATE_UPDATE"] = $find_date_update;
    }*/
}

// сохранение отредактированных элементов
if($lAdmin->EditAction() && $POST_RIGHT=="W"){
    // пройдем по списку переданных элементов
    foreach($FIELDS as $ID=>$arFields)
    {
        if(!$lAdmin->IsUpdated($ID)){
            continue;
        }

        $res = WS_SaleUserProfilesManager::Update($ID, $arFields);
        if ($err = $res->getErrorsAsString()) {
            $lAdmin->AddGroupError($err, $ID);
        }

        // сохраним изменения каждого элемента
//        $DB->StartTransaction();
//        $ID = IntVal($ID);
//        if(($rsData = CSaleOrderUserProps::GetByID($ID)) && ($arData = $rsData->Fetch()))
//        {
//            foreach($arFields as $key=>$value){
//                $arData[$key]=$value;
//            }
//            if(!CSaleOrderUserProps::Update($ID, $arData))
//            {
//                $lAdmin->AddGroupError(GetMessage("ws.saleuserprofiles_save_error")." ". 'Ошибка сохранения записи' , $ID);
//                $DB->Rollback();
//            }
//        }
//        else
//        {
//            $lAdmin->AddGroupError(GetMessage("ws.saleuserprofiles_save_error")." ".GetMessage("ws.saleuserprofiles_no_profile"), $ID);
//            $DB->Rollback();
//        }
//        $DB->Commit();

    }
}

// обработка одиночных и групповых действий
if(($arID = $lAdmin->GroupAction()) && $POST_RIGHT=="W")
{
    // если выбрано "Для всех элементов"
    if($_REQUEST['action_target']=='selected')
    {
        $rsData = CSaleOrderUserProps::GetList(array($by=>$order), $arFilter);
        while($arRes = $rsData->Fetch()){
            $arID[] = $arRes['ID'];
        }
    }

    // пройдем по списку элементов
    foreach($arID as $ID)
    {
        if(strlen($ID)<=0){
            continue;
        }
        $ID = IntVal($ID);

        // для каждого элемента совершим требуемое действие
        switch($_REQUEST['action'])
        {
            // удаление
            case "delete":
                @set_time_limit(0);
                $DB->StartTransaction();
                if(!CSaleOrderUserProps::Delete($ID))
                {
                    $DB->Rollback();
                    $lAdmin->AddGroupError(GetMessage("ws.saleuserprofiles_del_err"), $ID);
                }
                $DB->Commit();
                break;
            // активация/деактивация
            /*case "activate":
            case "deactivate":
                if(($rsData = CSaleOrderUserProps::GetByID($ID)) && ($arFields = $rsData->Fetch()))
                {
                    $arFields["ACTIVE"]=($_REQUEST['action']=="activate"?"Y":"N");
                    if(!CSaleOrderUserProps::Update($ID, $arFields))
                        $lAdmin->AddGroupError(GetMessage("ws.saleuserprofiles_save_error").$cData->LAST_ERROR, $ID);
                }
                else
                    $lAdmin->AddGroupError(GetMessage("ws.saleuserprofiles_save_error")." ".GetMessage("ws.saleuserprofiles_no_profile"), $ID);
                break;*/
        }
    }
}

// ******************************************************************** //
//                ВЫБОРКА ЭЛЕМЕНТОВ СПИСКА                              //
// ******************************************************************** //

// выберем список профилей пользователей
$rsData = CSaleOrderUserProps::GetList(array($by=>$order), $arFilter);
//$arData = array();
//while ($arRes = $rsData->Fetch()) {
//    $arData[] = $arRes;
//}

// преобразуем список в экземпляр класса CAdminResult
//$rsData = new CDBResult;
$rsData = new CAdminResult($rsData, $sTableID);
//$rsData->InitFromArray($arData);


// аналогично CDBResult инициализируем постраничную навигацию.
$rsData->NavStart();


// отправим вывод переключателя страниц в основной объект $lAdmin
$lAdmin->NavText($rsData->GetNavPrint(GetMessage("ws.saleuserprofiles_nav")));

// ******************************************************************** //
//                ПОДГОТОВКА СПИСКА К ВЫВОДУ                            //
// ******************************************************************** //

$lAdmin->AddHeaders(array(
    array(  "id"    =>"ID",
        "content"  =>GetMessage("ws.saleuserprofiles_row_id"),
        "sort"    =>"id",
        "align"    =>"right",
        "default"  =>true,
    ),
    array(  "id"    =>"NAME",
        "content"  =>GetMessage("ws.saleuserprofiles_row_name"),
        "sort"    =>"name",
        "default"  =>true,
    ),
    array(  "id"    =>"USER_ID",
        "content"  =>GetMessage("ws.saleuserprofiles_row_user_id"),
        "sort"    =>"user_id",
        "default"  =>true,
    ),
    array(  "id"    =>"PERSON_TYPE_ID",
        "content"  =>GetMessage("ws.saleuserprofiles_row_person_type_id"),
        "sort"    =>"person_type_id",
        "default"  =>true,
    ),
    array(  "id"    =>"DATE_UPDATE",
        "content"  =>GetMessage("ws.saleuserprofiles_row_person_date_update"),
        "sort"    =>"person_date_update",
        "default"  =>true,
    ),
));

$personTypes = array();
$users = array();
while($arRes = $rsData->NavNext(true, "f_")){
    // получаем типы плательщиков
    if (!empty($f_PERSON_ID)) {
        if (empty($personTypes[$f_PERSON_ID])) {
            $rs = CSalePersonType::GetList(Array(), Array('ID'=>$f_PERSON_ID), false, array('nTopCount' => 1));
            while ($arRs = $rs->Fetch()) {
                $personTypes[$arRs['ID']] = $arRs;
            }
        }
        $GLOBALS['f_PERSON_TYPE_NAME'] = $personTypes[$f_PERSON_TYPE_ID]['NAME'];
    }

    // получаем пользователей
    if (!empty($f_USER_ID)) {
        if (empty($users[$f_USER_ID])) {
            $rs =  CUser::GetList(($sort_by="personal_country"), ($sort_order="desc"), array('ID'=>$f_USER_ID));
            while ($arRs = $rs->Fetch()) {
                $users[$arRs['ID']] = $arRs;
            }
        }
        $GLOBALS['f_USER_NAME'] = $users[$f_USER_ID]['LOGIN'];
    }

    // создаем строку. результат - экземпляр класса CAdminListRow
    $row =& $lAdmin->AddRow($f_ID, $arRes);

    // далее настроим отображение значений при просмотре и редаткировании списка

    // параметр NAME будет редактироваться как текст, а отображаться ссылкой
    $row->AddViewField("NAME", '<a href="ws.saleuserprofiles_edit.php?ID='.$f_ID.'&lang='.LANG.'">'.$f_NAME.'</a>');
    $row->AddInputField("NAME", array("size"=>20));

    $row->AddViewField("USER_ID", $f_USER_NAME . ' (<a href="user_edit.php?ID='.$f_USER_ID.'&lang='.LANG.'">'.$f_USER_ID.'</a>)');
    $row->AddInputField("USER_ID", array("size"=>20));


    $row->AddViewField("PERSON_TYPE_ID", $f_PERSON_TYPE_NAME . ' (<a href="sale_person_type_edit.php?ID='.$f_PERSON_TYPE_ID.'&lang='.LANG.'">'.$f_PERSON_TYPE_ID.'</a>)');
    //$row->AddEditField("PERSON_TYPE_ID", WS_SaleUserProfiles::SelectBoxPersonTypes($f_ID, $f_PERSON_TYPE_ID));

//    $row->AddCheckField("ACTIVE");
//    $row->AddCheckField("VISIBLE");

    // параметр AUTO будет отображаться в виде "Да" или "Нет", полужирным при редактировании
//    $row->AddViewField("AUTO", $f_AUTO=="Y"?GetMessage("POST_U_YES"):GetMessage("POST_U_NO"));
//    $row->AddEditField("AUTO", "<b>".($f_AUTO=="Y"?GetMessage("POST_U_YES"):GetMessage("POST_U_NO"))."</b>");

    // сформируем контекстное меню1
    $arActions = Array();

    // редактирование элемента
    $arActions[] = array(
        "ICON"=>"edit",
        "DEFAULT"=>true,
        "TEXT"=>GetMessage("ws.saleuserprofiles_edit"),
        "ACTION"=>$lAdmin->ActionRedirect("ws.saleuserprofiles_edit.php?ID=".$f_ID)
    );

    // удаление элемента
    if ($POST_RIGHT>="W")
        $arActions[] = array(
            "ICON"=>"delete",
            "TEXT"=>GetMessage("ws.saleuserprofiles_del"),
            "ACTION"=>"if(confirm('".GetMessage('ws.saleuserprofiles_del_conf')."')) ".$lAdmin->ActionDoGroup($f_ID, "delete")
        );

    // вставим разделитель
    $arActions[] = array("SEPARATOR"=>true);

    // если последний элемент - разделитель, почистим мусор.
    if(is_set($arActions[count($arActions)-1], "SEPARATOR"))
        unset($arActions[count($arActions)-1]);

    // применим контекстное меню к строке
    $row->AddActions($arActions);

}

// резюме таблицы
$lAdmin->AddFooter(
    array(
        array("title"=>GetMessage("MAIN_ADMIN_LIST_SELECTED"), "value"=>$rsData->SelectedRowsCount()), // кол-во элементов
        array("counter"=>true, "title"=>GetMessage("MAIN_ADMIN_LIST_CHECKED"), "value"=>"0"), // счетчик выбранных элементов
    )
);

// групповые действия
$lAdmin->AddGroupActionTable(Array(
    "delete"=>GetMessage("MAIN_ADMIN_LIST_DELETE"), // удалить выбранные элементы
    //"activate"=>GetMessage("MAIN_ADMIN_LIST_ACTIVATE"), // активировать выбранные элементы
    //"deactivate"=>GetMessage("MAIN_ADMIN_LIST_DEACTIVATE"), // деактивировать выбранные элементы
));

// ******************************************************************** //
//                АДМИНИСТРАТИВНОЕ МЕНЮ                                 //
// ******************************************************************** //

// сформируем меню из одного пункта - добавление рассылки
$aContext = array(
    array(
        "TEXT"=>GetMessage("ws.saleuserprofiles_add"),
        "LINK"=>"ws.saleuserprofiles_edit.php?lang=".LANG,
        "TITLE"=>GetMessage("ws.saleuserprofiles_add"),
        "ICON"=>"btn_new",
    ),
);

// и прикрепим его к списку
$lAdmin->AddAdminContextMenu($aContext);

// ******************************************************************** //
//                ВЫВОД                                                 //
// ******************************************************************** //

// альтернативный вывод
$lAdmin->CheckListMode();

// установим заголовок страницы
$APPLICATION->SetTitle(GetMessage("ws.saleuserprofiles_title"));

// не забудем разделить подготовку данных и вывод
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");

// ******************************************************************** //
//                ВЫВОД ФИЛЬТРА                                         //
// ******************************************************************** //

// создадим объект фильтра
$oFilter = new CAdminFilter(
    $sTableID."_filter",
    array(
        GetMessage("ws.saleuserprofiles_filter_id"),
        //GetMessage("ws.saleuserprofiles_name"),
        GetMessage("ws.saleuserprofiles_filter_user_id"),
        GetMessage("ws.saleuserprofiles_filter_person_type_id"),
        //GetMessage("ws.saleuserprofiles_person_date_update"),
    )
);
?>
<form name="find_form" method="get" action="<?echo $APPLICATION->GetCurPage();?>">
    <?$oFilter->Begin();?>
    <tr>
        <td><b><?=GetMessage("ws.saleuserprofiles_filter_name").":"?></b></td>
        <td><input type="text" name="find_name" size="47" value="<?echo htmlspecialchars($find_name)?>"></td>
    </tr>
    <tr>
        <td><?=GetMessage("ws.saleuserprofiles_filter_id")?>:</td>
        <td><input type="text" name="find_id" size="47" value="<?echo htmlspecialchars($find_id)?>"></td>
    </tr>
    <tr>
        <td><?=GetMessage("ws.saleuserprofiles_filter_user_id").":"?></td>
        <td>
            <input type="text" id="find_user_id" name="find_user_id" size="41" value="<?echo htmlspecialchars($find_user_id)?>">
            <input class="tablebodybutton" type="button" name="FindUser" id="FindUser" onclick="window.open('/bitrix/admin/user_search.php?lang=ru&amp;FN=find_form&amp;FC=find_user_id', '', 'scrollbars=yes,resizable=yes,width=760,height=500,top='+Math.floor((screen.height - 560)/2-14)+',left='+Math.floor((screen.width - 760)/2-5));" value="...">
        </td>
    </tr>
    <tr>
        <td><?=GetMessage("ws.saleuserprofiles_filter_person_type_id").":"?></td>
        <td><?=WS_SaleUserProfilesManager::SelectBoxPersonTypes($find_person_type_id, "find_person_type_id", 'style="width: 100%;"')?></td>
    </tr>
    <?
    $oFilter->Buttons(array("table_id"=>$sTableID,"url"=>$APPLICATION->GetCurPage(),"form"=>"find_form"));
    $oFilter->End();
    ?>
</form>
<?
// выведем таблицу списка элементов
$lAdmin->DisplayList();
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>