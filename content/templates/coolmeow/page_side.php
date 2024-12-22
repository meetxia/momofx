<?php
/*@name 二次元--侧栏页面*/


if (!defined('EMLOG_ROOT')) {
    exit('error!');
}

?>
<section class="boxmoe_blog singlepage">      <div class="container">
        <div class="section-head">
            <span>Page</span></div>
    </div>
    <div class="boxmoe-blog-content">
        <div class="container-full">
            <div class="row">
                <div class="col-lg-8">
                    <div class="<?php echo blog_border();?> single-card mb-4">
                        <div class="post-single">
                            <div class="post-header">
                                <h3 class="post-title"><?php echo $log_title;?></h3>
                                <div class="thw-sept">
                                </div>
                            </div>
                            <div class="post-content">
                               <?= content($logid) ?>
                            </div>
                        </div>

                        <div class="thw-sept"> </div>
                        <?php blog_comments($comments,$logid) ?>
                        <div id="respond_com"></div>
                        <?php blog_comments_post($logid, $ckname, $ckmail, $ckurl, $verifyCode, $allow_remark) ?>

                    </div>
                </div>

            </div>
            <?php include View::getView('side') ?>
        </div>
    </div>

</section>
</div>
<?php include View::getView('footer') ?>
