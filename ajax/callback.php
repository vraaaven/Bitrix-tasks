<?
define('STOP_STATISTICS', true);
define('NO_KEEP_STATISTIC', 'Y');
define('NO_AGENT_STATISTIC', 'Y');
define('NO_AGENT_CHECK', true);
define('DisableEventsCheck', true);
define('BX_SECURITY_SHOW_MESSAGE', true);
define('NOT_CHECK_PERMISSIONS', true);
define("STATISTIC_SKIP_ACTIVITY_CHECK", "true");

require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php"); ?>


<?
if (CModule::IncludeModule("form")) {
    if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')
    {
            if (!empty($_POST['phone']) && !empty($_POST['name']) && check_bitrix_sessid())
            {
                $arValues = [
                    "form_text_5" => strip_tags($_POST['name']),
                    "form_text_6" => strip_tags($_POST['phone']),
                    "form_text_7" => strip_tags($_POST['product']),
                    "form_text_8" => strip_tags($_POST['userId']),
                ];
                $FORM_ID = 2;
                $formRes = new CFormResult;
                $formRes->Add($FORM_ID, $arValues);
                //Bitrix\Main\Diag\Debug::dumpToFile(strip_tags($_POST['userId']));
                if ($formRes)
                {
                    $result['status'] = 'success';
                    $result['message'] = 'Ваша заявка успешно отправлена';
                } else
                {
                    $result['status'] = 'error';
                    $result['message'] = 'Произошла ошибка';
                }

            } else {
                $result['status'] = 'error';
                $result['message'] = 'Имя и номер телефона обязательны';

            }
        echo json_encode($result);

    }
}
?>
