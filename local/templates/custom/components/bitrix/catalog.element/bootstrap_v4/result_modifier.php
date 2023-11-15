<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var CBitrixComponentTemplate $this
 * @var CatalogElementComponent $component
 */

if (CModule::IncludeModule("sale")) {
    //$productId = $arResult['ID'];
    $arProductsId = [];
    foreach($arResult["OFFERS"] as $arItem){
        $arProductsId[]=$arItem["ID"];
    }
    $count = 0;
    $basketRes = Bitrix\Sale\Internals\BasketTable::getList(array(
        'filter' => array(
            '=PRODUCT_ID' => $arProductsId,
            '!ORDER_ID' => false,
        ),
        'select' => array('QUANTITY'),
    ));
    while ($item = $basketRes->fetch()) {
        $count += $item['QUANTITY'];
    }
    $arResult['QUANTITY']=$count;
}

$component = $this->getComponent();
$arParams = $component->applyTemplateModifications();