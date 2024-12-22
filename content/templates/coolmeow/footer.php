<?php
/**
 * 站点底部模板
 */
if (!defined('EMLOG_ROOT')) {
    exit('error!');
}
?>
</div>
<footer class="footer pb-3 pt-3 position-relative">

    <div class="container">
        <hr class="horizontal dark">
        <div class="row">
            <div class="col-lg-3">
                <h6 class="font-weight-bolder mb-lg-4 mb-3"><i class="fa fa-sign-language"></i> <?php echo $blogname;?></h6>
            </div>
            <div class="col-lg-6 text-center">
                <?php footer_seo();?>
                <?php footer_info()?>
            </div>
            <?php footer_socialize()?>
            <?php doAction('index_footer') ?>
        </div>
    </div>
</footer>
<div id="search">
    <span class="close">X</span>
    <form role="search" id="searchform" method="get" action="<?php echo BLOG_URL;?>index.php">
        <div class="search_form_inner  animate slideUp">
            <div class="search-bar">
                <i class="fa fa-search"></i>
                <input type="search" name="keyword" value="" placeholder="输入搜索关键词..." /></div>
        </div>
    </form>
</div>
<?php if(_g('lolijump')):?>
<div id="lolijump"><img src="<?php echo coolmeow_pic_src()?>/images/top/<?php echo _g('lolijumpsister'); ?>.gif"></div>
<?php endif;?>
<script src="<?php echo coolmeow_pic_src()?>/js/theme.js" type="text/javascript"></script>
<script src="<?php echo coolmeow_pic_src()?>/js/comments.js" type="text/javascript"></script>
<script src="<?php echo coolmeow_pic_src()?>/js/lolimeow.js" type="text/javascript" id="boxmoe_script"></script>
<?php if (_g('sakura')):?>
<script src="<?php echo coolmeow_pic_src()?>/js/sakura.js"></script>

<?php endif;?>

<?php echo _g('diy_code_footer'); ?>
<script type="text/javascript">
    $(document).ready(function(){
        <?php
        $navico = explode('|',_g('nav_ico'));
        $iconums = 0;
        foreach($navico as $v):$iconums++;?>
        $('.navbar-nav>.nav-item:nth-child(<?php echo $iconums;?>) a').prepend('<i class="fa <?php echo $v;?>"></i>');
        <?php endforeach;?>
        $('.dropdown-menu>.nav-item>a>i').remove();
    });
</script>

<?php if (_g('music-open')):?>
    <script src="<?php echo coolmeow_pic_src()?>/js/APlayer.min.js" type="text/javascript"></script>
    <meting-js server="<?php echo _g('music-type');?>" type="playlist" autoplay="<?php echo _g('music-autoplay');?>" id="<?php echo _g('music-id');?>" fixed="true" order="<?php echo _g('music-list');?>" preload="auto" list-folded="ture" lrc-type="1"></meting-js>
<?php endif;?>
</body>
</html>