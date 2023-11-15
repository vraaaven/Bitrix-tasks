function tabs(){
    $(function() {
        // Проверяем, есть ли сохраненное значение в localStorage
        var activeTab = localStorage.getItem('activeTab');
        if (activeTab) {
            // Устанавливаем активный таб
            $('ul.tabs-caption li').eq(activeTab).addClass('active-tab').siblings().removeClass('active-tab');
            $('div.tabs-content').eq(activeTab).addClass('active').siblings().removeClass('active');
        }

        $('ul.tabs-caption').on('click', 'li:not(.active-tab)', function() {
            $(this)
                .addClass('active-tab').siblings().removeClass('active-tab')
                .closest('div.tabs').find('div.tabs-content').removeClass('active').eq($(this).index()).addClass('active');

            // Сохраняем выбранный таб в localStorage
            localStorage.setItem('activeTab', $(this).index());
        });

    });
}
function list(){
    $(function() {
        $('.block-content').css("display", "none");
        $('.block-title').on('click', function(){
            $(".block-title").removeClass('active');
            $(".arrow").html('&#9658;');
            $(this).children('.arrow').html('&#9660;');
            $(this).addClass('active');
            $(".block-content").slideUp();
            // Скрываем или отображаем соседний элемент
            if ($(this).next(".block-content").is(":hidden")) {

                $(this).next(".block-content").slideDown();
            } else {
                $(this).children('.arrow').html('&#9658;');
                $(this).removeClass('active');
                $(this).next(".block-content").slideUp();
            }

        });
    });
}

function input() {
    $(function() {
        let counter = 2; // счетчик для уникальных атрибутов

        $('.btn-add').on('click', function() {
            let $clone = $(this).prev(".input-list").children('.input-item').last();
            $clone.clone().appendTo('.input-list').children('input').val('').attr('name', 'input-' + counter); // добавляем уникальный атрибут name
            counter++; // увеличиваем счетчик
        });

        $(document).on('click', '.btn-del', function() {
            let $parent = $(this).parent('.input-item');
            if ($parent.siblings().length) {
                $parent.remove();
            }
        });
    });
}



tabs();
list();
input();
