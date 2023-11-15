<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("test");
?>
    <div class="tabs">
        <ul class="tabs-caption">
            <li class="active-tab">1-я вкладка</li>
            <li>2-я вкладка</li>
        </ul>
        <div class="tabs-content active">
            Содержимое первого блока
        </div>
        <div class="tabs-content">
            Содержимое второго блока
        </div>
    </div>
    <div class="list">
        <div class="block">
            <div class="block-title">
                <span class="arrow">►</span> Section 1
            </div>
            <div class="block-content" style="display: none;">
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusantium aliquid consequuntur cum delectus
                dignissimos explicabo, ipsa quidem quod unde. Ab blanditiis consectetur delectus ea incidunt ipsum
                possimus quasi? Quae, veniam?
            </div>
        </div>
        <div class="block">
            <div class="block-title">
                <span class="arrow">►</span> Section 2
            </div>
            <div class="block-content" style="display: none;">
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Commodi cupiditate debitis, exercitationem
                maxime natus quo vero voluptates. Ad aspernatur at deleniti dicta distinctio enim, facere ipsam libero
                necessitatibus vel velit!
            </div>
        </div>
        <div class="block">
            <div class="block-title">
                <span class="arrow">►</span> Section 3
            </div>
            <div class="block-content" style="display: none;">
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Assumenda consequuntur culpa debitis, et
                facere, facilis iusto laudantium minus nisi, numquam placeat quia quibusdam quis repellat saepe
                temporibus unde velit veniam?
            </div>
        </div>
    </div>
    <div class="input-block">
        <div class="input-list">
            <div class="input-item">
                <input type="text" name="input-1">
                <button class="btn-del">x</button>
            </div>
        </div>
        <button class="btn-add">+</button>
    </div>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>