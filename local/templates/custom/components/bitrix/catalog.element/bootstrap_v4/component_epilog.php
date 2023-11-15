<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	die();
}

use Bitrix\Main\Loader;

/**
 * @var array $templateData
 * @var array $arParams
 * @var string $templateFolder
 * @global CMain $APPLICATION
 */

global $APPLICATION;

if (!empty($templateData['TEMPLATE_LIBRARY']))
{
	$loadCurrency = false;

	if (!empty($templateData['CURRENCIES']))
	{
		$loadCurrency = Loader::includeModule('currency');
	}

	CJSCore::Init($templateData['TEMPLATE_LIBRARY']);
	if ($loadCurrency)
	{
		?>
		<script>
			BX.Currency.setCurrencies(<?=$templateData['CURRENCIES']?>);
		</script>
		<?php
	}
}

if (isset($templateData['JS_OBJ']))
{
	?>
	<script>
		BX.ready(BX.defer(function(){
			if (!!window.<?=$templateData['JS_OBJ']?>)
			{
				window.<?=$templateData['JS_OBJ']?>.allowViewedCount(true);
			}
		}));
	</script>
	<?php
	// check compared state
	if ($arParams['DISPLAY_COMPARE'])
	{
		$compared = false;
		$comparedIds = array();
		$item = $templateData['ITEM'];

		if (!empty($_SESSION[$arParams['COMPARE_NAME']][$item['IBLOCK_ID']]))
		{
			if (!empty($item['JS_OFFERS']) && is_array($item['JS_OFFERS']))
			{
				foreach ($item['JS_OFFERS'] as $key => $offer)
				{
					if (array_key_exists($offer['ID'], $_SESSION[$arParams['COMPARE_NAME']][$item['IBLOCK_ID']]['ITEMS']))
					{
						if ($key == $item['OFFERS_SELECTED'])
						{
							$compared = true;
						}

						$comparedIds[] = $offer['ID'];
					}
				}
			}
			elseif (array_key_exists($item['ID'], $_SESSION[$arParams['COMPARE_NAME']][$item['IBLOCK_ID']]['ITEMS']))
			{
				$compared = true;
			}
		}

		if ($templateData['JS_OBJ'])
		{
			?>
			<script>
				BX.ready(BX.defer(function(){
					if (!!window.<?=$templateData['JS_OBJ']?>)
					{
						window.<?=$templateData['JS_OBJ']?>.setCompared('<?=$compared?>');

						<?php
						if (!empty($comparedIds)):
						?>
						window.<?=$templateData['JS_OBJ']?>.setCompareInfo(<?=CUtil::PhpToJSObject($comparedIds, false, true)?>);
						<?php
						endif;
						?>
					}
				}));
			</script>
			<?php
		}
	}

	// select target offer
	$request = Bitrix\Main\Application::getInstance()->getContext()->getRequest();
	$offerNum = false;
	$offerId = (int)$this->request->get('OFFER_ID');
	$offerCode = $this->request->get('OFFER_CODE');

	if ($offerId > 0 && !empty($templateData['OFFER_IDS']) && is_array($templateData['OFFER_IDS']))
	{
		$offerNum = array_search($offerId, $templateData['OFFER_IDS']);
	}
	elseif (!empty($offerCode) && !empty($templateData['OFFER_CODES']) && is_array($templateData['OFFER_CODES']))
	{
		$offerNum = array_search($offerCode, $templateData['OFFER_CODES']);
	}

	if (!empty($offerNum))
	{
		?>
		<script>
			BX.ready(function(){
				if (!!window.<?=$templateData['JS_OBJ']?>)
				{
					window.<?=$templateData['JS_OBJ']?>.setOffer(<?=$offerNum?>);
				}
			});
		</script>
		<?php
	}
}


$iblockId = $arResult['IBLOCK_ID'];
$elementId = $arResult['ID'] ;
$VALUES = array();
$res = CIBlockElement::GetProperty($iblockId, $elementId, "sort", "asc", array("CODE" => "BUY_WITH_THIS_PRODUCT"));
while ($ob = $res->GetNext())
{
    if(isset($ob['VALUE']))
    {
     $idArray[] = $ob['VALUE'];
     $GLOBALS['arrFilter'] = ['ID' => $idArray];
    }
}
if(isset($idArray)) {
?>

<?$APPLICATION->IncludeComponent(
"bitrix:catalog.section",
"buyWithThisProduct",
array(
"ACTION_VARIABLE" => "action",
"ADD_PICT_PROP" => "MORE_PHOTO",
"ADD_PROPERTIES_TO_BASKET" => "Y",
"ADD_SECTIONS_CHAIN" => "N",
"ADD_TO_BASKET_ACTION" => "ADD",
"AJAX_MODE" => "N",
"AJAX_OPTION_ADDITIONAL" => "",
"AJAX_OPTION_HISTORY" => "N",
"AJAX_OPTION_JUMP" => "N",
"AJAX_OPTION_STYLE" => "Y",
"BACKGROUND_IMAGE" => "-",
"BASKET_URL" => "/personal/basket.php",
"BROWSER_TITLE" => "-",
"CACHE_FILTER" => "N",
"CACHE_GROUPS" => "Y",
"CACHE_TIME" => "36000000",
"CACHE_TYPE" => "A",
"COMPATIBLE_MODE" => "N",
"COMPONENT_TEMPLATE" => "buyWithThisProduct",
"CONVERT_CURRENCY" => "N",
"CUSTOM_FILTER" => "{\"CLASS_ID\":\"CondGroup\",\"DATA\":{\"All\":\"AND\",\"True\":\"True\"},\"CHILDREN\":[]}",
"DETAIL_URL" => "#ELEMENT_CODE#",
"DISABLE_INIT_JS_IN_COMPONENT" => "N",
"DISPLAY_BOTTOM_PAGER" => "Y",
"DISPLAY_COMPARE" => "N",
"DISPLAY_TOP_PAGER" => "N",
"ELEMENT_SORT_FIELD" => "sort",
"ELEMENT_SORT_FIELD2" => "id",
"ELEMENT_SORT_ORDER" => "asc",
"ELEMENT_SORT_ORDER2" => "desc",
"ENLARGE_PRODUCT" => "PROP",
"ENLARGE_PROP" => "-",
"FILTER_NAME" => "arrFilter",
"HIDE_NOT_AVAILABLE" => "N",
"HIDE_NOT_AVAILABLE_OFFERS" => "N",
"IBLOCK_ID" => "4",
"IBLOCK_TYPE" => "1c_catalog",
"INCLUDE_SUBSECTIONS" => "Y",
"LABEL_PROP" => array(),
"LAZY_LOAD" => "N",
"LINE_ELEMENT_COUNT" => "3",
"LOAD_ON_SCROLL" => "N",
"MESSAGE_404" => "",
"MESS_BTN_ADD_TO_BASKET" => "В корзину",
"MESS_BTN_BUY" => "Купить",
"MESS_BTN_DETAIL" => "Подробнее",
"MESS_BTN_LAZY_LOAD" => "Показать ещё",
"MESS_BTN_SUBSCRIBE" => "Подписаться",
"MESS_NOT_AVAILABLE" => "Нет в наличии",
"MESS_NOT_AVAILABLE_SERVICE" => "Недоступно",
"META_DESCRIPTION" => "-",
"META_KEYWORDS" => "-",
"OFFERS_FIELD_CODE" => array(
0 => "",
1 => "",
),
"OFFERS_LIMIT" => "5",
"OFFERS_SORT_FIELD" => "sort",
"OFFERS_SORT_FIELD2" => "id",
"OFFERS_SORT_ORDER" => "asc",
"OFFERS_SORT_ORDER2" => "desc",
"OFFER_ADD_PICT_PROP" => "-",
"PAGER_BASE_LINK_ENABLE" => "N",
"PAGER_DESC_NUMBERING" => "N",
"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
"PAGER_SHOW_ALL" => "N",
"PAGER_SHOW_ALWAYS" => "N",
"PAGER_TEMPLATE" => ".default",
"PAGER_TITLE" => "Товары",
"PAGE_ELEMENT_COUNT" => "18",
"PARTIAL_PRODUCT_PROPERTIES" => "N",
"PRICE_CODE" => array(
0 => "Опт1",
),
"PRICE_VAT_INCLUDE" => "Y",
"PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons",
"PRODUCT_DISPLAY_MODE" => "Y",
"PRODUCT_ID_VARIABLE" => "id",
"PRODUCT_PROPS_VARIABLE" => "prop",
"PRODUCT_QUANTITY_VARIABLE" => "quantity",
"PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false}]",
"PRODUCT_SUBSCRIPTION" => "Y",
"PROPERTY_CODE_MOBILE" => array(),
"RCM_PROD_ID" => $_REQUEST["PRODUCT_ID"],
"RCM_TYPE" => "similar_sell",
"SECTION_CODE" => "",
"SECTION_CODE_PATH" => $_REQUEST["SECTION_CODE_PATH"],
"SECTION_ID" => $_REQUEST["SECTION_ID"],
"SECTION_ID_VARIABLE" => "SECTION_ID",
"SECTION_URL" => "#SECTION_CODE#",
"SECTION_USER_FIELDS" => array(
0 => "",
1 => "",
),
"SEF_MODE" => "Y",
"SEF_RULE" => "#SECTION_ID#",
"SET_BROWSER_TITLE" => "Y",
"SET_LAST_MODIFIED" => "N",
"SET_META_DESCRIPTION" => "Y",
"SET_META_KEYWORDS" => "Y",
"SET_STATUS_404" => "N",
"SET_TITLE" => "Y",
"SHOW_404" => "N",
"SHOW_ALL_WO_SECTION" => "N",
"SHOW_CLOSE_POPUP" => "N",
"SHOW_DISCOUNT_PERCENT" => "N",
"SHOW_FROM_SECTION" => "N",
"SHOW_MAX_QUANTITY" => "N",
"SHOW_OLD_PRICE" => "N",
"SHOW_PRICE_COUNT" => "1",
"SHOW_SLIDER" => "Y",
"SLIDER_INTERVAL" => "3000",
"SLIDER_PROGRESS" => "N",
"TEMPLATE_THEME" => "site",
"USE_ENHANCED_ECOMMERCE" => "N",
"USE_MAIN_ELEMENT_SECTION" => "N",
"USE_PRICE_COUNT" => "N",
"USE_PRODUCT_QUANTITY" => "N",
),
false
);}?>

