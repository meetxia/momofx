<script>
//跳转函数
window.iframe_jump = function(view, input){
    var viewData = '',
        inputData = '';
    if(typeof view !== 'undefined' && view !== null && view !== undefined && view !== ""){
        viewData = '&route=' + view;
    }
    if(typeof input !== 'undefined' && input !== null && input !== undefined && input !== ""){
        inputData = input;
    }
    parent.$('#toEver_iframe').attr('src', 'plugin.php?plugin=<?=TOEVER_DOWNPAGE_NAME?>' + viewData + inputData);
}
</script>
</body>
</html>