$(document).ready(function () {
    // 在 id 为 form_log 的表单中查找第一个 tbody 中的每个 tr
    $('#form_log tbody:first tr').each(function () {
        var secondLink = $(this).find('td:eq(1) a:first');
        var href = secondLink.attr('href');
        var gid = href.match(/gid=([^&]*)/)[1];
        secondLink.after('<span class="ml-2"><a id="copy2wechat" href="#wechatModal" data-id="' + gid + '" data-toggle="modal" data-target="#wechatModal"><img src="../content/plugins/em_to_wechat/assets/gongzhonghao.png" width="18"></a></span>');
    });

    $('#wechatModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var id = button.data('id')
        loadContent(id);
    });

    var clipboard = new ClipboardJS('.copy');
    clipboard.on('success', function (e) {
        alert("复制成功，请直接到微信公众号后台粘贴")
        console.log(e);
    });
    clipboard.on('error', function (e) {
        console.log(e);
    });
});

function loadContent(id) {
    $.ajax({
        type: 'GET',
        url: '../content/plugins/em_to_wechat/em_action.php',
        data: {
            action: "getArticle",
            id: id
        },
        success: function (resp) {
            if (resp.data) {
                contentele = $('#em_log_content');
                contentele.empty();
                contentele.append(resp.data);
            }
        },
        error: function (xhr, status, error) {
            console.error(error);
        }
    });
}