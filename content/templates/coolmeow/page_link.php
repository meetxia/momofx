<?php
/*@name 二次元--友情链接页面*/
if (!defined('EMLOG_ROOT')) {
    exit('error!');
}
?>
<section class="boxmoe_blog singlepage">      <div class="container">
        <div class="section-head">
            <span>Links</span></div>
    </div>
    <div class="boxmoe-blog-content">
        <div class="container-full">
            <div class="row">
                <div class="col-lg-12 mx-auto single">
                            <div class="post-single">
                                <h3 class="post-title wow zoomIn animated" style="visibility: visible; animation-name: zoomIn;">友情链接</h3>
                                <div class="post-content">
                                   <?= content($logid) ?>
                                </div>
                            </div>
                    <div class="link-title wow rollIn animated" style="visibility: visible; animation-name: rollIn;">友情链接</div>
                    <ul class="readers-list clearfix">
<?php ilinks();?>
                    </ul>

                        <div class="thw-sept"> </div>
                        <?php blog_comments($comments,$logid) ?>
                        <div id="respond_com"></div>
                        <?php blog_comments_post($logid, $ckname, $ckmail, $ckurl, $verifyCode, $allow_remark) ?>

                </div>

            </div>
        </div>
    </div>

</section>
</div>
<?php include View::getView('footer') ?>
