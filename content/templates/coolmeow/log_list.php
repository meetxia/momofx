<?php
/**
 * 首页模板
 */
if (!defined('EMLOG_ROOT')) {
    exit('error!');
}
?>

<section class="boxmoe_blog ">
    <div class="container">
        <?php if(_g('hitokoto_on')){?>
        <?php if ($params[1]!='keyword'){?>
        <div class="site-main">
            <h1 class="main-title">
                <i class="fa fa-heartbeat"></i>
                <span id="hitokoto"></span></h1>
        </div>
            <script>$.get("https://v1.hitokoto.cn/?c=<?php echo _g('hitokoto_text'); ?>", {},
                    function(data) {
                        document.getElementById("hitokoto").innerHTML = data.hitokoto;
                    });</script>
        <?php }?>
        <div class="section-head">
            <span>
                <?php if ($pageurl == Url::logPage()){
                    echo 'Home';
                } elseif ($params[1]=='sort'){
                    echo 'Category';
                }elseif ($params[1]=='tag'){
                   ?>
                    All articles containing the tag   <b><?php echo htmlspecialchars(urldecode($params[2]));?></b>
                <?php
                } elseif ($params[1]=='keyword'){
                   ?>
                    Search
                <?php
                }elseif ($params[1]=='record'){
                    echo 'Record';
                }elseif ($params[1]=='author'){
                    echo 'Author';
                }
                ?>
                </span></div>
        <?php if ($params[1]=='keyword'){
            ?>
            <div class="site-main">
                <h1 class="main-title">
                    <i class="fa fa-search"></i>
                    <span>搜索:[<?php echo $params[2];?>]关键词 共<?php echo $lognum?>篇文章</span></h1>
            </div>
        <?php }?>
    </div>
    <?php }?>
    <div class="boxmoe-blog-content">
        <div class="container-full">
            <div class="row">
                <div class="col-lg-<?php echo sidebaron()?>">
                    <?php doAction('index_loglist_top');
                    if (!empty($logs)):
                        foreach ($logs as $value):
                            ?>
                            <div class="post-list-view <?php echo blog_border();?> <?php wow_set()?>">
                                 <?php if(_g('blog_new_style')){?>
                                <?php if (!is_today($value['date'],time())){
                                    ?>
                                    <i class="post-hello-cat"></i>
                                    <?php
                                }
                                ?>
                                 <?php }else{?>
                                     <i class="post-hello-cat"></i>
                                 <?php }?>


                                <div class="post-thumbnail featured-image">
                                    <?php if(_g('blog_new_style')){?>
                                        <?php if (is_today($value['date'],time())){
                                            ?>
                                            <div class="blog_new">
                                                <div class="blog_new_style">新文</div>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    <?php }?>

                                    <a href="<?= $value['log_url'] ?>" <?php if (_g('link—open')==1):?> target="_blank" <?php endif;?> title="<?= $value['log_title'] ?>" class="post-overlay">
                                        <img class="img-fluid" src="<?php echo kule_FirstIMGs($value['cover'], $value['content']);?>" alt="<?= $value['log_title'] ?>"></a>
                                </div>
                                <div class="post-list-content">
                                    <div class="post-list-header">
                                        <?php bloglist_sort($value['sortid']) ?>
                                        <h2 class="post-list-title">
                                            <a href="<?= $value['log_url'] ?>" <?php if (_g('link—open')==1):?> target="_blank" <?php endif;?>><?= $value['log_title'] ?></a></h2>
                                    </div>
                                    <div class="post-list-text"><?php echo Length_reduction(strip_tags($value['log_description']));?></div>
                                    <div class="post-list-info">
                                        <div class="post-list-avatar">
                                            <img src="<?php index_author($value['author']);?>" class="avatar avatar-50 photo" width="50" height="50" alt="avatar"></div>
                                        <div class="post-meta-info">
                        <span class="list-post-date">
                          <i class="fa fa-clock-o"></i>Post on <?= date('Y-n-j', $value['date']) ?></span>
                                            <span class="list-post-view">
                          <i class="fa fa-street-view"></i><?= restyle_text($value['views']) ?></span>
                                            <span class="list-post-comment">
                          <i class="fa fa-comments-o"></i><?= $value['comnum'] ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>                        <?php
                        endforeach;
                    else:
                        ?>
                        <p>抱歉，暂时还没有内容。</p>
                    <?php endif ?>
                    <div class="col-lg-12 col-md-12 pagenav">
                        <?= $page_url ?>
                    </div>
            </div>
               <?php if (_g('sidebar_on')=='col-2'):?>
                <?php include View::getView('side') ?>
                <?php endif;?>
        </div>
    </div>
</section>

<?php include View::getView('footer') ?>

