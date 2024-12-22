<style>
#wrapper #content-wrapper #content{height: 100%;}
.container-fluid{height: calc(100% - 40px - 3rem);}
footer{display: none;}
</style>
<iframe 
    id="toEver_iframe"
    src="plugin.php?plugin=<?=TOEVER_DOWNPAGE_NAME?>&route=admin_index"
    frameborder="no"
    border="0"
    marginwidth="0"
    marginheight="0"
    style="width: 100%;height: 100%;"
    allowfullscreen="true">
</iframe>
<script type="text/javascript">$("#<?=TOEVER_DOWNPAGE_NAME?>").addClass('active');</script>