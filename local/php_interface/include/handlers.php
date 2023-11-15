<?php

class CIBLockHandler
{
    public static function AddArticleToElementName(&$arFields)
    {
        if ($arFields["IBLOCK_ID"] == CATALOG_CLOTH_ID || $arFields["IBLOCK_ID"] == CATALOG_CLOTH_OFFERS_ID) {
            $elementName = $arFields["NAME"];
            //Получаем артикул товара
            foreach ($arFields["PROPERTY_VALUES"][26] as $value) {
                $article = $value["VALUE"];
            }
            if (strpos($elementName, $article)===false) {
                $elementName = $elementName . "-" . $article;
                $arFields["NAME"] = $elementName;
            }
        }
    }
}

class FormHandler
{
    static function SendFormResult($webFormId, $resultId)
    {
        if (CModule::IncludeModule("iblock") && CModule::IncludeModule("form") ) {

            if ($webFormId == 2) {
                $arFields = []; //Массив данных для шаблона почты;
                $arResult = []; //поля формы
                $arAnswers = []; //ответы формы
                $arFormRes = CFormResult::GetDataByID($resultId, [], $arResult, $arAnswers);
                //Bitrix\Main\Diag\Debug::dumpToFile($arAnswers["PRODUCT"]["7"]["USER_TEXT"]);
                $arFields["NAME"] = $arAnswers["NAME"]["6"]["USER_TEXT"];  // Имя пользователя из инпута
                $arFields["PHONE"] = $arAnswers["PHONE"]["5"]["USER_TEXT"]; // Телефон пользователя из инпута
                $productId = ""; // ID товара
                preg_match_all("/\[(.*?)\]/", $arAnswers["PRODUCT"]["7"]["USER_TEXT"], $productId); // Получаем ID товара из квадратных скобок;
                //Bitrix\Main\Diag\Debug::dumpToFile($arAnswers);
                $productResult = CIBlockElement::GetByID($productId[1][0]); // Забираем данные о продукте из БД
                $product = $productResult->GetNextElement();

                $arFields["PRODUCT_NAME"] = $product->GetFields()["NAME"]; // Наименование товаров
                $arFields["PRODUCT_ARTICLE"] = $product->GetProperties()["CML2_ARTICLE"]["VALUE"]; // Артикул товара
                // получение названия родительской секции в которой находится товар
                $arFields["SECTION_PRODUCT_NAME"] = CIBlockSection::GetByID($product->GetFields()["IBLOCK_SECTION_ID"])->GetNext()["NAME"];
                $productType = CCatalogProduct::GetByID($productId[1][0])["TYPE"]; // Получаем тип продукта через запрос к БД
                switch ($productType) {
                    case "1":
                        $arFields["PRODUCT_NAME"] .= " (простой товар)";
                        $arFields["PRODUCT_QUANTITY"] = CCatalogProduct::GetByID($productId[1][0])["QUANTITY"];
                        break;
                    case "3":
                        $arFields["PRODUCT_NAME"] .= " (товар с торговым предложением)";
                        $quantity = 0;
                        // Получаем массив торговых предложений
                        $offers = CIBlockPriceTools::GetOffersArray($product->GetFields()["IBLOCK_ID"], $productId[1][0], [],
                            [], [], 0, [], 1);
                        foreach ($offers as $offer) {
                            $quantity += (int)$offer["CATALOG_QUANTITY"];
                        }
                        $arFields["PRODUCT_QUANTITY"] = $quantity;
                        break;
                }
                if (!empty($arAnswers["SIMPLE_QUESTION_351"]["5"]["USER_TEXT"])) { // проверка авторизации пользователя
                    $arFields["USER_ID"] = $arAnswers["USER"]["8"]["USER_TEXT"];
                    $arFields["USER_EMAIL"] = CUser::GetByID($arAnswers["USER"]["8"]["USER_TEXT"])->GetNext()["EMAIL"];
                }
                \Bitrix\Main\Mail\Event::send(array(
                    "EVENT_NAME" => "ONE_CLICK_ORDER",
                    'MESSAGE_ID' => MESSAGE_ONE_CLICK_ORDER,
                    "LID" => "s1",
                    "C_FIELDS" => $arFields,
                ));
            }
        }
    }
}

class OrderNewSendHandler
{
    public static function SendNewOrder($orderId, &$eventName, &$arFields)
    {

        $order = \Bitrix\Sale\Order::load($orderId);
        $basket = $order->getBasket();
        $basketItems = $basket->getBasketItems();
        $arFields["PRICE"] = $basket->getPrice();
        $arFields["COST_OF_DELIVERY"] = $order->getDeliveryPrice();
        $arFields["ORDER_LIST"] =
            "<table style='border: 1px solid black;'>
                                    <thead>    
                                        <tr>
                                          <th>ID товара</th> <th>Название</th> <th>Цвет</th> <th>Размер</th> <th>Цена</th> <th>Количество</th> <th>Сумма</th>
                                        </tr>
                                    </thead>
                                    <tbody>\n";
        foreach($basketItems as $item){
            $arFields["ORDER_LIST"].="\t\t<tr>\n";
            $arFields["ORDER_LIST"].="\t\t\t<td>".$item->getProductId()."</td>";
            $arFields["ORDER_LIST"].="<td>".$item->getField('NAME')."</td>";
            $properties = $item->getPropertyCollection()->getPropertyValues();
            $arFields["ORDER_LIST"].="<td>";
            if (!empty($properties["TSVET"]["VALUE"])) {
                $arFields["ORDER_LIST"].=$properties["TSVET"]["VALUE"];
            }
            else{
                $arFields["ORDER_LIST"].="-";
            }
            $arFields["ORDER_LIST"].="</td><td>";
            if (!empty($properties["RAZMER"]["VALUE"])) {
                $arFields["ORDER_LIST"].=$properties["RAZMER"]["VALUE"];
            }
            else{
                $arFields["ORDER_LIST"].="-";
            }
            $arFields["ORDER_LIST"].="</td>";
            $arFields["ORDER_LIST"].="<td>".$item->getPrice()."</td>";
            $arFields["ORDER_LIST"].="<td>".$item->getQuantity()."</td>";
            $arFields["ORDER_LIST"].="<td>".$item->getFinalPrice()."</td>\n";
            $arFields["ORDER_LIST"].="\t\t</tr>\n";
        }
        $arFields["ORDER_LIST"].="\n</tbody></table>";
    }
}
