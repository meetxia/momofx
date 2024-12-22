<?php
/**
 * 阅读文章页面
 */
if (!defined('EMLOG_ROOT')) {
    exit('error!');
}
$DB=MySql::getInstance();
$article= $DB->once_fetch_array("SELECT * FROM ".DB_PREFIX."blog WHERE gid ='$logid'");

?>
<section class="boxmoe_blog singlepage">      <div class="container">
        <div class="section-head">
            <span>Single</span></div>
    </div>
    <div class="boxmoe-blog-content">
        <div class="container-full">
            <div class="row">
                <div class="col-lg-<?php echo sidebaron();?> single">
                    <div class="<?php echo blog_border();?> single-card mb-4">
                        <div class="post-single">
                            <div class="post-header">
                                <p class="post-category">
                                    <?php blog_sort($sortid) ?>
                                </p>
                                <h3 class="post-title <?php wow_set()?>"><?php echo $log_title;?></h3>
                                <div class="post-meta thw-sept">
                                    <div class="post-auther-avatar">
                                        <img src="<?php index_author($author);?>" class="avatar avatar-50 photo" width="50" height="50" alt="avatar"></div>
                                    <div class="post-meta-info">
                        <span class="post-date">
                          <i class="fa fa-clock-o"></i>Post on <?= date('Y-n-j', $date) ?></span>
                                        <span class="post-view">
                          <i class="fa fa-street-view"></i><?= restyle_text($views) ?></span>
                                        <span class="post-comment">
                          <i class="fa fa-comments-o"></i><?php echo $comnum;?></span>
                                        <?php editflg($logid, $author) ?>
                                    </div>
                                </div>
                            </div>
                            <div class="post-content">
                                <?= lightbox_gall_replace(content($logid),$log_title)?>
                                <?php meow_down($logid)?>
                                <?php doAction('log_related', $logData); ?>

                            </div>

                            <div class="post-footer">
                                <div class="post-tags">
                                    <div class="article-categories">
                                        <?php blog_tag($logid) ?></div>
                                </div>
                            </div>
                        </div>
                        <?php if (_g('open_author_info')){?>
                        <div class="block_auther_post mb-4">
                            <div class="row">
                                <div class="col-lg-9">
                                    <div class="author align-items-center mb-2">
                                        <img src="<?php index_author($author);?>" class="avatar avatar-50 photo" width="50" height="50" alt="avatar">
                                        <div class="name ps-3">
                                            <span><?php author_title($author);?></span>
                                            <div class="stats">
                                                <?php author_des($author)?>					  </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 my-auto ml-auto text-lg-right">
                                    <a href="https://wpa.qq.com/msgrd?v=3&amp;uin=<?php echo _g('QQ');?>&amp;site=qq&amp;menu=yes" target="_blank" class="btn-social color-qq border-0  mr-2">
                                        <i class="fa fa-qq"></i></a>
                                    <a href="<?php echo _g('wx-img');?>" data-fancybox="images" data-fancybox-group="button" target="_blank" class="btn-social color-weixin border-0  mr-2">
                                        <i class="fa fa-weixin"></i></a>
                                    <a href="<?php echo _g('Github');?>" target="_blank" class="btn-social color-github border-0  mr-2">
                                        <i class="fa fa-github"></i></a>
                                </div>
                            </div>
                        </div>
                        <?php }?>
                        <nav class="post-navigation thw-sept">
                            <div class="row no-gutters">
                                <?php nextLog($logid,$sortid,'prev');?>
                                <?php nextLog($logid,$sortid,'next');?>
                            </div>
                        </nav>
                        <div class="thw-sept"> </div>
                        <?php if (!_g('comments_off')){?>
                        <?php blog_comments($comments,$logid) ?>
                        <div id="respond_com"></div>
                        <?php blog_comments_post($logid, $ckname, $ckmail, $ckurl, $verifyCode, $allow_remark) ?>
                    </div>
                        <?php }?>
                        <?php if (_g('post_related_s')){?>
                        <div class="container postrelated mt-5">
                            <div class="row">
                                <div class="thw-sept"> </div>
                                <?php list_logs($logData);?>
                            </div>
                        </div>
                        <?php }?>

                    </div>
                </div>


            <?php if (_g('sidebar_on')=='col-2'):?>
                <?php include View::getView('side') ?>
            <?php endif;?>
        </div>
    </div>

</section>
</div>
<?php include View::getView('footer') ?>
