<?
IncludeModuleLangFile(__FILE__);
Class ws_saleuserprofiles extends CModule
{
    const MODULE_ID = 'ws.saleuserprofiles';
    var $MODULE_ID = 'ws.saleuserprofiles';
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;
    public $MODULE_CSS;
    public $NEED_MAIN_VERSION = '5.0';
    public $NEED_MODULES = array('sale');

    function __construct(){
        $arModuleVersion = array();
        include(dirname(__FILE__)."/version.php");
        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        $this->MODULE_NAME = GetMessage("ws.saleuserprofiles_MODULE_NAME");
        $this->MODULE_DESCRIPTION = GetMessage("ws.saleuserprofiles_MODULE_DESC");

        $this->PARTNER_NAME = GetMessage("ws.saleuserprofiles_PARTNER_NAME");
        $this->PARTNER_URI = GetMessage("ws.saleuserprofiles_PARTNER_URI");
    }

    function InstallDB($arParams = array()){
        RegisterModuleDependences('main', 'OnBuildGlobalMenu', self::MODULE_ID, 'WS_SaleUserProfilesManager', 'OnBuildGlobalMenu');
        return true;
    }

    function UnInstallDB($arParams = array()){
        UnRegisterModuleDependences('main', 'OnBuildGlobalMenu', self::MODULE_ID, 'WS_SaleUserProfilesManager', 'OnBuildGlobalMenu');
        return true;
    }

    function InstallFiles($arParams = array()){
        if (is_dir($p = $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/'.self::MODULE_ID.'/admin'))
        {
            if ($dir = opendir($p))
            {
                while (false !== $item = readdir($dir))
                {
                    if ($item == '..' || $item == '.' || $item == 'menu.php')
                        continue;
                    file_put_contents($file = $_SERVER['DOCUMENT_ROOT'].'/bitrix/admin/'.self::MODULE_ID.'_'.$item,
                        '<'.'? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/'.self::MODULE_ID.'/admin/'.$item.'");?'.'>');
                }
                closedir($dir);
            }
        }
        if (is_dir($p = $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/'.self::MODULE_ID.'/install/components'))
        {
            if ($dir = opendir($p))
            {
                while (false !== $item = readdir($dir))
                {
                    if ($item == '..' || $item == '.')
                        continue;
                    CopyDirFiles($p.'/'.$item, $_SERVER['DOCUMENT_ROOT'].'/bitrix/components/'.$item, $ReWrite = True, $Recursive = True);
                }
                closedir($dir);
            }
        }
        return true;
    }

    function UnInstallFiles(){
        if (is_dir($p = $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/'.self::MODULE_ID.'/admin'))
        {
            if ($dir = opendir($p))
            {
                while (false !== $item = readdir($dir))
                {
                    if ($item == '..' || $item == '.')
                        continue;
                    unlink($_SERVER['DOCUMENT_ROOT'].'/bitrix/admin/'.self::MODULE_ID.'_'.$item);
                }
                closedir($dir);
            }
        }
        if (is_dir($p = $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/'.self::MODULE_ID.'/install/components'))
        {
            if ($dir = opendir($p))
            {
                while (false !== $item = readdir($dir))
                {
                    if ($item == '..' || $item == '.' || !is_dir($p0 = $p.'/'.$item))
                        continue;

                    $dir0 = opendir($p0);
                    while (false !== $item0 = readdir($dir0))
                    {
                        if ($item0 == '..' || $item0 == '.')
                            continue;
                        DeleteDirFilesEx('/bitrix/components/'.$item.'/'.$item0);
                    }
                    closedir($dir0);
                }
                closedir($dir);
            }
        }
        return true;
    }

    function DoInstall(){
        if (is_array($this->NEED_MODULES) && !empty($this->NEED_MODULES)){
            foreach ($this->NEED_MODULES as $module){
                if (!IsModuleInstalled($module)){
                    $this->ShowForm('ERROR', GetMessage('ws.saleuserprofiles_NEED_MODULES', array('#MODULE#' => $module)));
                }
            }
        }

        if (strlen($this->NEED_MAIN_VERSION)<=0 || version_compare(SM_VERSION, $this->NEED_MAIN_VERSION)>=0){
            $this->InstallFiles();
            $this->InstallDB();
            RegisterModule(self::MODULE_ID);
            $this->ShowForm('OK', GetMessage('MOD_INST_OK'));
        }
        else{
            $this->ShowForm('ERROR', GetMessage('ws.saleuserprofiles_NEED_RIGHT_VER', array('#NEED#' => $this->NEED_MAIN_VERSION)));
        }
    }

    function DoUninstall(){
        UnRegisterModule(self::MODULE_ID);
        $this->UnInstallDB();
        $this->UnInstallFiles();
    }

    private function ShowForm($type, $message, $buttonName=''){
        global $APPLICATION;
        $keys = array_keys($GLOBALS);
        for($i=0; $i<count($keys); $i++)
            if($keys[$i]!='i' && $keys[$i]!='GLOBALS' && $keys[$i]!='strTitle' && $keys[$i]!='filepath')
                global ${$keys[$i]};

        $PathInstall = str_replace('\\', '/', __FILE__);
        $PathInstall = substr($PathInstall, 0, strlen($PathInstall)-strlen('/index.php'));
        IncludeModuleLangFile($PathInstall.'/install.php');

        $APPLICATION->SetTitle(GetMessage('ws.saleuserprofiles_MODULE_NAME'));
        include($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_after.php');
        echo CAdminMessage::ShowMessage(array('MESSAGE' => $message, 'TYPE' => $type));
        ?>
    <form action="<?= $APPLICATION->GetCurPage()?>" method="get">
        <p>
            <input type="hidden" name="lang" value="<?= LANG?>" />
            <input type="submit" value="<?= strlen($buttonName) ? $buttonName : GetMessage('MOD_BACK')?>" />
        </p>
    </form>
    <?
        include($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_admin.php');
        die();
    }
}
?>