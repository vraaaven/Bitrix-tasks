<?php
AddEventHandler("iblock", "OnBeforeIBlockElementAdd", Array("CIBLockHandler", "AddArticleToElementName"));
AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", Array("CIBLockHandler", "AddArticleToElementName"));
AddEventHandler('form', 'onAfterResultAdd', Array("FormHandler", "SendFormResult"));
AddEventHandler("sale", "OnOrderNewSendEmail", Array("OrderNewSendHandler", "SendNewOrder"));
require_once ($_SERVER['DOCUMENT_ROOT'].'/local/php_interface/include/handlers.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/local/php_interface/include/constants.php');