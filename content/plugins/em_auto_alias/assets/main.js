$(document).ready(function () {
    var newElement = $('<a href="#" class="small" id="auto_alias">自动别名</a>');
    $("label:contains('链接别名')").after(newElement);
    $("label:contains('链接别名')").find('small').after(newElement);

    $('#auto_alias').on('click', function () {
        gen_em_alias();
    });
});

function gen_em_alias() {
    var title = document.getElementById('title').value;
    if (title.trim() === '') {
        alert("请先输入文章的标题");
        return
    }
    if (title.length > 16) {
        title = pinyinUtil.getFirstLetter(title);
    } else {
        title = pinyinUtil.getPinyin(title);
    }
    title = title.replace(/\s+/g, '-');// 空格替换为-
    title = title.replace(/[‘’'“”"，、。《》]/g, ''); // 替换中文标点符号为空字符串
    title = title.toLowerCase();
    $("#alias").val(title);
}