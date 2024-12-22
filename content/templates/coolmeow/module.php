<?php
/**
 * 侧边栏组件、页面模块
 */
if (!defined('EMLOG_ROOT')) {
    exit('error!');
}
?>
<?php
/**
 * 侧边栏：链接
 */
function widget_link($title) {
    global $CACHE;
    $link_cache = $CACHE->readCache('link');
    //if (!blog_tool_ishome()) return;#只在首页显示友链去掉双斜杠注释即可
    ?>
    <div class="mb-5 sidebar-border <?php echo blog_border();?> widget_recent_entries">
        <h3 class="widget-title"><?= $title ?></h3>
        <div class="tagcloud">
            <?php foreach ($link_cache as $value): ?>
                <a href="<?= $value['url'] ?>" <?php if (_g('link—open')==1):?> target="_blank" <?php endif;?> class="tag-cloud-link"><?= $value['link'] ?></a>
            <?php endforeach ?>
        </div>
    </div>
<?php }
/**
 * 侧边栏：最新微语
 */
function widget_twitter($title) {
    global $CACHE;
    $index_newtwnum = Option::get('index_newtwnum') ?: 10;
    $Twitter_Model = new Twitter_Model();
    $ts = $Twitter_Model->getTwitters('', 1, $index_newtwnum);
    $user_cache = $CACHE->readCache('user');
    ?>
    <div class="mb-5 sidebar-border <?php echo blog_border();?> widget_comments">
        <h3 class="widget-title"><?= $title ?></h3>
        <ul class="widget-latest-comment">
            <?php
            foreach ($ts as $value):
                ?>
                <li class="comment-listitem">
                    <div class="comment-user">
                          <span class="comment-avatar">
                            <img src="<?php echo qqimg($value['mail'],$value['author']);?>" class="avatar avatar-50 photo" width="50" height="50" alt="avatar"></span>
                        <div class="comment-author"><?= $user_cache[$value['author']]['name'] ?></div>
                        <span class="comment-date"><?= $value['date'] ?></span></div>
                    <div class="comment-content-link">
                        <a href="javascript:" title="<?= $value['title'] ?>" <?php if (_g('link—open')==1):?> target="_blank" <?php endif;?>>
                            <div class="comment-content"><?= emojistr($value['t']) ?></div></a>
                    </div>
                </li>
            <?php endforeach ?>
        </ul>
    </div>
<?php }
/**
 * 侧边栏：个人资料
 */
function widget_blogger($title) {
    global $CACHE;
    $user_cache = $CACHE->readCache('user');
    $name = $user_cache[1]['mail'] != '' ? "<a href=\"mailto:" . $user_cache[1]['mail'] . "\">" . $user_cache[1]['name'] . "</a>" : $user_cache[1]['name'] ?>
    <div class="mb-5 sidebar-border <?php echo blog_border();?> widget_recent_entries">
        <h3 class="widget-title"><?= $title ?></h3>
        <div class="unstyle-li bloggerinfo">
            <?php if (!empty($user_cache[1]['photo']['src'])): ?>
                <div>
                    <img class='bloggerinfo-img' src="<?= BLOG_URL . $user_cache[1]['photo']['src'] ?>" alt="blogger"/>
                </div>
            <?php endif ?>
            <div class='bloginfo-name'><b><?= $name ?></b></div>
            <div class='bloginfo-descript'><?= $user_cache[1]['des'] ?></div>
        </div>
    </div>
<?php }
/**
 * 侧边栏：日历
 */
function widget_calendar($title) { ?>
    <div class="mb-5 sidebar-border <?php echo blog_border();?> widget_recent_entries">
        <h3 class="widget-title"><?= $title ?></h3>
        <div class="unstyle-li">
            <div id="calendar"></div>
            <script>sendinfo('<?= Calendar::url() ?>', 'calendar');</script>
        </div>
    </div>
<?php }
/**
 * 侧边栏：标签
 */
function widget_tag($title) {
    global $CACHE;
    $i=0;
    $tag_cache = $CACHE->readCache('tags') ?>
    <div class="mb-5 sidebar-border <?php echo blog_border();?> widget_tag_cloud">
        <h3 class="widget-title"><?= $title ?></h3>
        <div class="tagcloud">
            <?php foreach ($tag_cache as $value): ?>
                <a href="<?= Url::tag($value['tagurl']) ?>" title="<?= $value['usenum'] ?> 篇文章" class='tag-cloud-link' <?php if (_g('link—open')==1):?> target="_blank" <?php endif;?>><?= $value['tagname'] ?></a>
            <?php
            $i++;
            if ($i>=_g('side_tag_num')){
               break;
            }
            endforeach ?>
        </div>
    </div>
<?php }
/**
 * 侧边栏：分类
 */
function widget_sort($title) {
    global $CACHE;
    $sort_cache = $CACHE->readCache('sort') ?>
    <div class="mb-5 sidebar-border <?php echo blog_border();?> widget_categories">
        <h3 class="widget-title"><?= $title ?></h3>
        <ul>
            <?php
            foreach ($sort_cache as $value):
                if ($value['pid'] != 0) continue;
                ?>
                <li>
                    <a href="<?= Url::sort($value['sid']) ?>" <?php if (_g('link—open')==1):?> target="_blank" <?php endif;?>><?= $value['sortname'] ?>&nbsp;&nbsp;<?= (($value['lognum']) > 0) ? '(' . ($value['lognum']) . ')' : '' ?></a>
                    <?php if (!empty($value['children']) && _g('side_nav_child')==1): ?>
                        <ul class="children">
                            <?php
                            $children = $value['children'];
                            foreach ($children as $key):
                                $value = $sort_cache[$key];
                                ?>
                                <li>
                                    <a href="<?= Url::sort($value['sid']) ?>" <?php if (_g('link—open')==1):?> target="_blank" <?php endif;?>>--&nbsp;&nbsp;<?= $value['sortname'] ?>
                                        &nbsp;&nbsp;(<?= $value['lognum'] ?>)</a>
                                </li>
                            <?php endforeach ?>
                        </ul>
                    <?php endif ?>
                </li>
            <?php endforeach ?>
        </ul>
    </div>
<?php }
/**
 * 侧边栏：最新评论
 */
function widget_newcomm($title) {
    global $CACHE;
    $com_cache = $CACHE->readCache('comment');
    $isGravatar = Option::get('isgravatar');
    ?>
    <div class="mb-5 sidebar-border <?php echo blog_border();?> widget_comments">
        <h3 class="widget-title"><?= $title ?></h3>
        <ul class="widget-latest-comment">
            <?php
            foreach ($com_cache as $value):
                $url = Url::comment($value['gid'], $value['page'], $value['cid']);
                ?>
                <li class="comment-listitem">
                    <div class="comment-user">
                          <span class="comment-avatar">
                            <img src="<?php echo qqimg($value['mail'],$value['uid']);?>" class="avatar avatar-50 photo" width="50" height="50" alt="avatar"></span>
                        <div class="comment-author"><?= $value['name'] ?></div>
                        <span class="comment-date"><?= smartDate($value['date']) ?></span></div>
                    <div class="comment-content-link">
                        <a href="<?= $url ?>" title="<?= $value['title'] ?>" <?php if (_g('link—open')==1):?> target="_blank" <?php endif;?>>
                            <div class="comment-content"><?= emojistr($value['content']) ?></div></a>
                    </div>
                </li>
            <?php endforeach ?>
        </ul>
    </div>
<?php }
/**
 * 侧边栏：最新文章
 */
function widget_newlog($title) {
    global $CACHE;
    $newLogs_cache = $CACHE->readCache('newlog');

    ?>
    <div class="mb-5 sidebar-border <?php echo blog_border();?> widget_recent_entries">
        <h3 class="widget-title"><?= $title ?></h3>
        <ul class="widget-latest-posts">
            <?php foreach ($newLogs_cache as $value):
                $db = Database::getInstance();
                $row = $db->once_fetch_array("SELECT * FROM ".DB_PREFIX."blog where gid='".$value['gid']."'");
                ?>
                <li class="last-post">
                    <div class="image">
                        <a href="<?= Url::log($value['gid']) ?>" <?php if (_g('link—open')==1):?> target="_blank" <?php endif;?>>
                            <img src="<?php echo kule_FirstIMGs($row['cover'],$row['content']);?>" alt="<?= $value['title'] ?>">
                        </a>
                    </div>
                    <div class="content">
                        <a href="<?= Url::log($value['gid']) ?>"><?= $value['title'] ?></a>
                        <small><i class="fa fa-clock-o"></i><?php echo date('Y-m-d',$row['date']);?></small></div></li>
            <?php endforeach ?>
        </ul>
    </div>
<?php }
/**
 * 侧边栏：热门文章
 */
function widget_hotlog($title) {
    $index_hotlognum = Option::get('index_hotlognum');
    $Log_Model = new Log_Model();
    $hotLogs = $Log_Model->getHotLog($index_hotlognum) ?>
    <div class="mb-5 sidebar-border <?php echo blog_border();?> widget_recent_entries">
        <h3 class="widget-title"><?= $title ?></h3>
        <ul class="widget-latest-posts">
            <?php foreach ($hotLogs as $value):
                $db = Database::getInstance();
                $row = $db->once_fetch_array("SELECT * FROM ".DB_PREFIX."blog where gid='".$value['gid']."'");?>
                <li class="last-post">
                    <div class="image">
                        <a href="<?= Url::log($value['gid']) ?>" <?php if (_g('link—open')==1):?> target="_blank" <?php endif;?>>
                            <img src="<?php echo kule_FirstIMGs($row['cover'],$row['content']);?>" alt="<?= $value['title'] ?>">
                        </a>
                    </div>
                    <div class="content">
                        <a href="<?= Url::log($value['gid']) ?>"><?= $value['title'] ?></a>
                        <small><i class="fa fa-clock-o"></i><?php echo date('Y-m-d',$row['date']);?></small></div></li>            <?php endforeach ?>
        </ul>
    </div>
<?php } ?>
<?php
/**
 * 侧边栏：搜索
 */
function widget_search($title) { ?>
    <div class="mb-5 sidebar-border <?php echo blog_border();?> widget_search">
        <h3 class="widget-title"><?= $title ?></h3>
        <div class="unstyle-li" style="text-align: center;">
            <form name="keyform" method="get" action="<?= BLOG_URL ?>index.php">
                <div>
                    <label class="screen-reader-text" for="s">搜索：</label>
                    <input type="text" value="" name="keyword">
                    <input type="submit" id="searchsubmit" value="搜索">
                </div>
            </form>
        </div>
    </div>
<?php }
/**
 * 侧边栏：归档
 */
function widget_archive($title) {
    $i=0;
    global $CACHE;
    $record_cache = $CACHE->readCache('record');
    ?>
    <div class="mb-5 sidebar-border <?php echo blog_border();?> widget_recent_entries">
        <h3 class="widget-title"><?= $title ?></h3>
        <ul class="unstyle-li">
            <?php foreach ($record_cache as $value): ?>
                <li><a href="<?= Url::record($value['date']) ?>" <?php if (_g('link—open')==1):?> target="_blank" <?php endif;?>><?= $value['record'] ?>(<?= $value['lognum'] ?>)</a></li>
            <?php
                $i++;
                if ($i>=_g('side_record_num')){
                    break;
                }
            endforeach ?>
        </ul>
    </div>
<?php } ?>
<?php
/**
 * 侧边栏：自定义组件
 */
function widget_custom_text($title, $content) { ?>
    <div class="mb-5 sidebar-border <?php echo blog_border();?> widget_recent_entries">
        <h3 class="widget-title"><?= $title ?></h3>
        <ul class="unstyle-li">
            <?= $content ?>
        </ul>
    </div>
<?php }
/**
 * 页顶：导航
 */
function blog_navi($sorturl) {
    global $CACHE;
    $navi_cache = $CACHE->readCache('navi');
    ?>
    <ul class="navbar-nav navbar-nav-hover mx-auto">
        <?php
        foreach ($navi_cache as $value):
            if ($value['pid'] != 0) {
                continue;
            }
            if ($value['url'] == 'admin' && (!User::isVistor())):
                ?>
                <li class="nav-item"><a href="<?= BLOG_URL ?>admin/" class="nav-link">管理</a></li>
                <li class="nav-item"><a href="<?= BLOG_URL ?>admin/account.php?action=logout" class="nav-link">退出</a></li>
                <?php
                continue;
            endif;
            $newtab = $value['newtab'] == 'y' ? 'target="_blank"' : '';
            $value['url'] = $value['isdefault'] == 'y' ? BLOG_URL . $value['url'] : trim($value['url'], '/');
            $current_tab = $sorturl == $value["url"] ? 'active' : '';
            ?>
            <?php if (!empty($value['children']) || !empty($value['childnavi'])) : ?>
            <li class="dropdown dropdown-hover nav-item">
                <?php if (!empty($value['children'])): ?>
                    <a class="nav-link  dropdown-toggle <?php echo $current_tab;?>" data-bs-toggle="dropdown" <?= $newtab ?>><?= $value['naviname'] ?> <b class="caret"></b></a>
                    <ul class="dropdown-menu  depth_0">
                        <?php foreach ($value['children'] as $row) {
                            echo '<li class="nav-item"><a class="dropdown-item" href="' . Url::sort($row['sid']) . '">' . $row['sortname'] . '</a></li>';
                        } ?>
                    </ul>
                <?php endif ?>
                <?php if (!empty($value['childnavi'])) : ?>
                    <a class='nav-link  dropdown-toggle <?php echo $current_tab;?>' data-bs-toggle="dropdown" <?= $newtab ?> ><?= $value['naviname'] ?><b class="caret"></b></a>
                    <ul class="dropdown-menu  depth_0">
                        <?php foreach ($value['childnavi'] as $row) {
                            $newtab = $row['newtab'] == 'y' ? 'target="_blank"' : '';
                            echo '<li class="nav-item"><a class="dropdown-item" href="' . $row['url'] . "\" $newtab >" . $row['naviname'] . '</a></li>';
                        } ?>
                    </ul>
                <?php endif ?>
            </li>
        <?php else: ?>
            <li class="nav-item"><a class="nav-link <?php echo $current_tab;?>" href="<?= $value['url'] ?>" <?= $newtab ?>><?= $value['naviname'] ?></a></li>
        <?php endif ?>
        <?php endforeach ?>
    <?php if( _g('sousuo')==1){?>
        <li class="nav-item"><a href="#search" class="nav-link"><i class="fa fa-search"></i>Search</a></li>
        <?php }?>
    </ul>
<?php }
/**
 * 文章列出卡片：置顶标志
 */
function topflg($top, $sortop = 'n', $sortid = null) {
    $ishome_flg = '<span title="首页置顶" class="log-topflg" >置顶</span>';
    $issort_flg = '<span title="分类置顶" class="log-topflg" >分类置顶</span>';
    if (blog_tool_ishome()) {
        echo $top == 'y' ? $ishome_flg : '';
    } elseif ($sortid) {
        echo $sortop == 'y' ? $issort_flg : '';
    }
}
?>
<?php
/**
 * 文章查看页：编辑链接
 */
function editflg($logid, $author) {
    $editflg = User::haveEditPermission() || $author == UID ? '<a href="' . BLOG_URL . 'admin/article.php?action=edit&gid=' . $logid . '" class="post-edit-link" target="_blank">[<span>编辑仅作者可见</span>]</a>' : '';
    echo $editflg;
}
/**
 * 文章查看页：分类
 */
function blog_sort($sortID) {
    $Sort_Model = new Sort_Model();
    $r = $Sort_Model->getOneSortById($sortID);
    $sortName = isset($r['sortname']) ? $r['sortname'] : '';
    ?>
    <?php if (!empty($sortName)) { ?>
        <a href="<?= Url::sort($sortID) ?>" <?php if (_g('link—open')==1):?> target="_blank" <?php endif;?> title="分类：<?= $sortName ?>" rel="category tag"><i class="fa fa-folder-o"></i><?= $sortName ?></a>
    <?php } else { ?>
        <a href="#" title="未分类" rel="category tag" <?php if (_g('link—open')==1):?> target="_blank" <?php endif;?>><i class="fa fa-folder-o"></i>无</a>
    <?php }
}
/**
 * 文章列出页：分类
 */
function bloglist_sort($sortID) {
    $Sort_Model = new Sort_Model();
    $r = $Sort_Model->getOneSortById($sortID);
    $sortName = isset($r['sortname']) ? $r['sortname'] : '';
    ?>
    <?php if (!empty($sortName)) { ?>
        <span  class="category-meta">
        <a href="<?= Url::sort($sortID) ?>" <?php if (_g('link—open')==1):?> target="_blank" <?php endif;?> title="查看《<?= $sortName ?>》下的所有文章 " rel="category tag">
            <i class="fa fa-folder-o"></i><?= $sortName ?></a>
</span>
    <?php }
} ?>
<?php
/**
 * 文章列出页和文章查看页：标签
 */
function blog_tag($blogid) {
    global $CACHE;
    $tag_model = new Tag_Model();

    $log_cache_tags = $CACHE->readCache('logtags');
    if (!empty($log_cache_tags[$blogid])) {
        $tag = '';
        foreach ($log_cache_tags[$blogid] as $value) {
            $tag .= "	<a href=\"" . Url::tag($value['tagurl']) . "\" class='tag'  title='标签' >" . $value['tagname'] . '</a>';
        }
        echo $tag;
    } else {
        $tag_ids = $tag_model->getTagIdsFromBlogId($blogid);
        $tag_names = $tag_model->getNamesFromIds($tag_ids);
        if (!empty($tag_names)) {
            foreach ($tag_names as $key => $value) {
                $tag .= "	<a href=\"" . Url::tag(rawurlencode($value)) . "\" class='tag' title='标签' >" . htmlspecialchars($value) . '</a>';
            }
            echo $tag;
        }
    }
}
/**
 * 文章列出页和文章查看页：作者
 */
function blog_author($uid) {
    global $CACHE;
    $user_cache = $CACHE->readCache('user');
    $author = $user_cache[$uid]['name'];
    $mail = $user_cache[$uid]['mail'];
    $des = $user_cache[$uid]['des'];
    $title = !empty($mail) || !empty($des) ? "title=\"$des $mail\"" : '';
    echo '<a href="' . Url::author($uid) . "\" $title>$author</a>";
}
/**
 * 文章查看页：相邻文章
 */
function neighbor_log($neighborLog) {
    extract($neighborLog) ?>
    <?php if ($prevLog): ?>
        <span class="prev-log"><a href="<?= Url::log($prevLog['gid']) ?>" <?php if (_g('link—open')==1):?> target="_blank" <?php endif;?> title="<?= $prevLog['title'] ?>">上一篇</a></span>
    <?php endif ?>
    <?php if ($nextLog): ?>
        <span class="next-log"><a href="<?= Url::log($nextLog['gid']) ?>" <?php if (_g('link—open')==1):?> target="_blank" <?php endif;?> title="<?= $nextLog['title'] ?>">下一篇</a></span>
    <?php endif ?>
<?php } ?>
<?php
/**
 * 文章查看页：评论列表
 */
function blog_comments($comments,$logid) {
    extract($comments);
    $DB = Database::getInstance();
    $blogcomnum = $DB->once_fetch_array("SELECT comnum FROM ".DB_PREFIX."blog WHERE gid = '$logid'");
    ?>
    <div id="comments" class="comments-area">
    <h3 class="comments-heading text-center">
        <span><i class="fa fa-comments"></i> 文章有（<?php echo $blogcomnum['comnum'];?>）条网友点评</span>                        </h3>
    <ul class="comments-list mx-auto">
        <?php
        $isGravatar = Option::get('isgravatar');

        foreach ($commentStacks as $cid):
            $comment = $comments[$cid];
            $comment['poster'] = $comment['url'] ? '<a href="' . $comment['url'] . '" target="_blank">' . $comment['poster'] . '</a>' : $comment['poster'];
            ?>

            <li class="commentsline"><div class="comment even thread-odd thread-alt depth-1" id="comment-<?= $comment['cid'] ?>">
                    <div class="author-box">
                        <div class="comment-autohr-img">
                            <div class="autohr-img-border">
                                <img src="<?php
                                echo qqimg($comment['mail'],$comment['uid']);?>" class="avatar avatar-60 photo img-fluid" alt="avatar">
                            </div>
                        </div>
                    </div>	<div class="comment-body">
                        <div class="meta-data">
                            <?php comment_user_info($comment['uid']); ?>
                            <a rel="nofollow" class="comment-reply-link" href="#comment-<?php echo $comment['cid']; ?>" onclick="commentReply(<?php echo $comment['cid']; ?>,this)">
                                <span class="pull-right"><i class="fa fa-commenting-o"></i> 回复 </span></a>
                            <span class="comment-author"><a href="<?php echo $comment['url']; ?>" rel="external nofollow" target="_blank"><?= $comment['poster'] ?></a></span>
                            <span class="comment-date"><?= $comment['date'] ?></span>
                        </div>
                        <div class="comment-content"><p><?= emojistr($comment['content']) ?></p>
                        </div>
                    </div>
                </div>
            </li>
            <?php blog_comments_children($comments, $comment['children']) ?>
        <?php endforeach ?>
    </ul>
     <?php if ($commentPageUrl){?>
    <div class="pagenav text-center"> <?= $commentPageUrl ?></div>

    <?php }
}
/**
 * 文章查看页：子评论
 */
function blog_comments_children($comments, $children) {
    $isGravatar = Option::get('isgravatar');
    foreach ($children as $child):
        $comment = $comments[$child];
        $comment['poster'] = $comment['url'] ? '<a href="' . $comment['url'] . '" target="_blank">' . $comment['poster'] . '</a>' : $comment['poster'];
        ?>
        <ul class="children">
            <li class="commentsline"><div class="comment byuser comment-author-mogu bypostauthor odd alt depth-2" id="comment-<?= $comment['cid'] ?>">
                    <div class="author-box">
                        <div class="comment-autohr-img">
                            <div class="autohr-img-border">
                                <img src="<?php
                                echo qqimg($comment['mail'],$comment['uid']);?>" class="avatar avatar-50 photo" width="50" height="50" alt="avatar">
                            </div>
                        </div>
                    </div>	<div class="comment-body">
                        <div class="meta-data">
                            <span class="commentsbadge <?php if($comment['uid']==1)echo 'blogger';else echo 'tourists';?>"><i class="fa fa-check-square"></i> <?php if($comment['uid']==1)echo '博主';else echo '游客';?></span>		<a rel="nofollow" class="comment-reply-link" href="#comment-<?php echo $comment['cid']; ?>" onclick="commentReply(<?php echo $comment['cid']; ?>,this)"><span class="pull-right"><i class="fa fa-commenting-o"></i> 回复 </span></a>
                            <span class="comment-author"><a href="<?php echo $comment['url'];?>" rel="external nofollow" <?php if (_g('link—open')==1):?> target="_blank" <?php endif;?>><?= $comment['poster'] ?></a></span>
                            <span class="comment-date"><?= $comment['date'] ?></span>
                        </div>
                        <div class="comment-content"><p> <?= emojistr($comment['content']) ?></p>
                        </div>
                    </div>
                </div>
            </li>
            <?php blog_comments_children($comments, $comment['children']) ?>
        </ul>
    <?php endforeach ?>
<?php }
/**
 * 文章查看页：评论表单
 */
function blog_comments_post($logid, $ckname, $ckmail, $ckurl, $verifyCode, $allow_remark) {
    $isNeedChinese = Option::get('comment_needchinese');
    if ($allow_remark == 'y'): ?>
        <div id="comment-place">
            <div id="respond" class="col-lg-10 col-md-10 mx-auto">
                <h5 class="title-normal text-center" id="respond_com">
                    <i class="fa fa-commenting"></i>发表评论
                    <a id="cancel-comment-reply-link" href="javascript:" onclick="cancelReply()" class="btn btn-sm btn-warning btn-comment" style="display: none;">取消回复</a></h5>
                <form id="commentform" name="commentform" action="<?php echo BLOG_URL;?>index.php?action=addcom" method="post">
                    <?php if (ROLE == ROLE_VISITOR): ?>
                        <div class="row" id="comment-author-info">
                            <div class="col-md-4">
                                <div class="input-group mb-4">
                                    <span class="input-group-text"><i class="fa fa-user-circle"></i></span>
                                    <input type="text" name="comname" id="author" class="form-control" value="" placeholder="昵称 *" tabindex="1">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group mb-4">
                                    <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                    <input type="email" name="commail" id="email" class="form-control" value="" placeholder="邮箱 *" tabindex="2">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group mb-4">
                                    <span class="input-group-text"><i class="fa fa-link"></i></span>
                                    <input type="text" name="comurl" id="url" class="form-control" value="" placeholder="网址" size="22" tabindex="3">
                                </div>
                            </div>
                            <?php if ($verifyCode != "") { ?>

                                <div class="col-md-12 comment-yanzhengma">
                                    <div class="input-group mb-4">
                                        <span class="input-group-text"><i class="fa fa-link"></i></span>
                                        <?php echo $verifyCode; ?>
                                    </div>
                                </div>
                            <?php }?>


                        </div>
                    <?php endif;?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group mb-4">
                                <textarea class="form-control" rows="4" name="comment" id="comment" tabindex="4" placeholder="<?php echo _g('diy_comment_text');?>"></textarea><div id="comment_loading" style="display: none;"><i class="fa fa-spinner fa-spin"></i> 正在提交, 请稍候...</div><div id="error" style="display: none;">#</div><div id="comment_loading" style="display: none;"><i class="fa fa-spinner fa-spin"></i> 正在提交, 请稍候...</div><div id="error" style="display: none;">#</div>
                            </div>
                        </div>
                        <div class="col-md-12 mx-auto clearfix text-center">
                            <div class="dropup">
                                <div class="comt-addsmilies" href="javascript:;" id="boxmoe_smilies" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  <span>
                                    <i class="fa fa-smile-o"></i>表情</span>
                                </div>
                                <div class="dropdown-menu" aria-labelledby="boxmoe_smilies">
                                    <div class="dropdown-smilie scroll-y">
                                        <a title="mrgreen" class="smilie-icon" href="javascript:grin(' :mrgreen: ')">
                                            <img src="<?php echo coolmeow_pic_src()?>/images/smilies/icon_mrgreen.gif">
                                        </a>
                                        <a title="razz" class="smilie-icon" href="javascript:grin(' :razz: ')">
                                            <img src="<?php echo coolmeow_pic_src()?>/images/smilies/icon_razz.gif">
                                        </a>
                                        <a title="sad" class="smilie-icon" href="javascript:grin(' :sad: ')">
                                            <img src="<?php echo coolmeow_pic_src()?>/images/smilies/icon_sad.gif">
                                        </a>
                                        <a title="smile" class="smilie-icon" href="javascript:grin(' :smile: ')">
                                            <img src="<?php echo coolmeow_pic_src()?>/images/smilies/icon_smile.gif">
                                        </a>
                                        <a title="oops" class="smilie-icon" href="javascript:grin(' :oops: ')">
                                            <img src="<?php echo coolmeow_pic_src()?>/images/smilies/icon_redface.gif">
                                        </a>
                                        <a title="grin" class="smilie-icon" href="javascript:grin(' :grin: ')">
                                            <img src="<?php echo coolmeow_pic_src()?>/images/smilies/icon_biggrin.gif">
                                        </a>
                                        <a title="eek" class="smilie-icon" href="javascript:grin(' :eek: ')">
                                            <img src="<?php echo coolmeow_pic_src()?>/images/smilies/icon_surprised.gif">
                                        </a>
                                        <a title="???" class="smilie-icon" href="javascript:grin(' :???: ')">
                                            <img src="<?php echo coolmeow_pic_src()?>/images/smilies/icon_confused.gif">
                                        </a>
                                        <a title="cool" class="smilie-icon" href="javascript:grin(' :cool: ')">
                                            <img src="<?php echo coolmeow_pic_src()?>/images/smilies/icon_cool.gif">
                                        </a>
                                        <a title="lol" class="smilie-icon" href="javascript:grin(' :lol: ')">
                                            <img src="<?php echo coolmeow_pic_src()?>/images/smilies/icon_lol.gif">
                                        </a>
                                        <a title="mad" class="smilie-icon" href="javascript:grin(' :mad: ')">
                                            <img src="<?php echo coolmeow_pic_src()?>/images/smilies/icon_mad.gif">
                                        </a>
                                        <a title="twisted" class="smilie-icon" href="javascript:grin(' :twisted: ')">
                                            <img src="<?php echo coolmeow_pic_src()?>/images/smilies/icon_twisted.gif">
                                        </a>
                                        <a title="roll" class="smilie-icon" href="javascript:grin(' :roll: ')">
                                            <img src="<?php echo coolmeow_pic_src()?>/images/smilies/icon_rolleyes.gif">
                                        </a>
                                        <a title="wink" class="smilie-icon" href="javascript:grin(' :wink: ')">
                                            <img src="<?php echo coolmeow_pic_src()?>/images/smilies/icon_wink.gif">
                                        </a>
                                        <a title="idea" class="smilie-icon" href="javascript:grin(' :idea: ')">
                                            <img src="<?php echo coolmeow_pic_src()?>/images/smilies/icon_idea.gif">
                                        </a>
                                        <a title="arrow" class="smilie-icon" href="javascript:grin(' :arrow: ')">
                                            <img src="<?php echo coolmeow_pic_src()?>/images/smilies/icon_arrow.gif">
                                        </a>
                                        <a title="neutral" class="smilie-icon" href="javascript:grin(' :neutral: ')">
                                            <img src="<?php echo coolmeow_pic_src()?>/images/smilies/icon_neutral.gif">
                                        </a>
                                        <a title="cry" class="smilie-icon" href="javascript:grin(' :cry: ')">
                                            <img src="<?php echo coolmeow_pic_src()?>/images/smilies/icon_cry.gif">
                                        </a>
                                        <a title="?" class="smilie-icon" href="javascript:grin(' :?: ')">
                                            <img src="<?php echo coolmeow_pic_src()?>/images/smilies/icon_question.gif">
                                        </a>
                                        <a title="evil" class="smilie-icon" href="javascript:grin(' :evil: ')">
                                            <img src="<?php echo coolmeow_pic_src()?>/images/smilies/icon_evil.gif">
                                        </a>
                                        <a title="shock" class="smilie-icon" href="javascript:grin(' :shock: ')">
                                            <img src="<?php echo coolmeow_pic_src()?>/images/smilies/icon_eek.gif">
                                        </a>
                                        <a title="!" class="smilie-icon" href="javascript:grin(' :!: ')">
                                            <img src="<?php echo coolmeow_pic_src()?>/images/smilies/icon_exclaim.gif">
                                        </a>                                </div>
                                </div>
                            </div>
                            <input type="hidden" name="gid" value="<?= $logid ?>"/>
                            <input type="hidden" name="pid" id="comment-pid" value="0" size="22" tabindex="1"/>
                            <button class="btn btn-outline-dark btn-comment" name="submit" type="submit" id="submit" tabindex="5"><?php echo _g('diy_comment_btn');?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    <?php endif ?>
<?php }
/**
 * 判断函数：是否是首页
 */
function blog_tool_ishome() {
    if (BLOG_URL . trim(Dispatcher::setPath(), '/') == BLOG_URL) {
        return true;
    } else {
        return FALSE;
    }
}
/**
 * 缩略图优先级输出函数
 */
function kule_FirstIMGs($cover, $content) {
    $pattern = '/\]\((.*?)\)/i';
    $search_pattern = '%<img[^>]*?src=[\'\"]((?:(?!\/admin\/|>).)+?)[\'\"][^>]*?>%s';
    if (_g('thumbnail_api')){
        $img=_g('thumbnail_api_url');
    }
    elseif(!empty($cover)) {
        $img = $cover;
    } elseif(preg_match($pattern, $content, $matchs)) {
        $img=$matchs[1];
    } elseif(preg_match($search_pattern, $content, $htmlmatch)) {
        $img=$htmlmatch[1];
    } else {
        $img=coolmeow_pic_src() . '/images/rand/' . rand(1, _g('thumbnail_rand_n')) . '.jpg';
    }
    return $img;
}
/**
 * 首页用户头像输出函数
 */
function index_author($uid)
{
    global $CACHE;
    $user_cache = $CACHE->readCache('user');
    if (empty($user_cache[$uid]['avatar'])) {
        $photo=coolmeow_pic_src() . '/images/rand/' . rand(1, 14) . '.jpg';
    } else {
        $photo = BLOG_URL . $user_cache[$uid]['avatar'];

    }
    echo $photo;
}
/**
 * 作者名称
 */
function author_title($uid)
{
    global $CACHE;
    $user_cache = $CACHE->readCache('user');
    $author = $user_cache[$uid]['name'];
    if (empty($user_cache[$uid]['name'])) {
        $name=$blogname;
    } else {
        $name =$user_cache[$uid]['name'];

    }
    echo $name;
}
/**
 * 作者描述
 */
function author_des($uid)
{
    global $CACHE;
    $user_cache = $CACHE->readCache('user');
    $author = $user_cache[$uid]['des'];
    if (empty($user_cache[$uid]['des'])) {
        $name='作者暂未设置个人描述。';
    } else {
        $name =$user_cache[$uid]['des'];

    }
    echo $name;
}
/**
 * 下一篇文章
 */
function nextLog($logid, $sortid, $flag, $pattern=0){
    $Log_Model = new Log_Model();
    if($flag == 'prev'){
        $sql = " AND gid < $logid ORDER BY gid DESC";$word = '<span><i class="fa fa-angle-left"></i> Previous Post</span>';$class='up';
    }else{
        $sql = " AND gid > $logid ORDER BY gid ASC";$word = '<span>Next Post <i class="fa fa-angle-right"></i></span>';$class='down';
    }
    $log = $Log_Model -> getLogsForHome(" AND sortid = $sortid "."$sql", 1, 1);
    if($log){
        foreach($log as $value):
            ?>
            <div class="col-12 col-md-6">
                <div class="post-previous">
                    <a href="<?php echo $value['log_url'];?>" <?php if (_g('link—open')==1):?> target="_blank" <?php endif;?> rel="next"><span> <?php echo $word;?><h4> <?php echo $value['log_title'];?></h4></a>                      </div>
            </div>
        <?php
        endforeach;
    }
}
/**
 * 相对链接处理绝对链接
 */
function opposite_link($link){
    $preg = "/^http(s)?:\\/\\/.+/";
    if(preg_match($preg,$link)){
        return $link;
    }else{
        return str_replace('../',BLOG_URL,$link);
    }

}
/**
 * 评论用户头像处理函数
 */
 function qqimg($commentemail,$uid)
{
    if ($uid>0){
        $db = Database::getInstance();
        $sql = "SELECT `photo` FROM ".DB_PREFIX."user WHERE uid=$uid";
        $row = $db->once_fetch_array($sql);
        if ($row['photo']!=''){
            return opposite_link($row['photo']);
        }
    }
    $hash       = md5(strtolower(trim($commentemail)));
    $gavatarurl = 'https://' . getavatar_host() . '/' . $hash;
    if (stripos($commentemail, "@qq.com")) //判断是否为QQ邮箱
    {
        $qq = str_ireplace("@qq.com", "", $commentemail);
        if (preg_match("/^\d+$/", $qq)) //正则过滤英文邮箱
        {
            $qqavatar = "https://" . qqavatar_host() . "/headimg_dl?dst_uin=" . $qq . "&spec=100";
            return $qqavatar;
        } else { //如果是英文QQ邮箱就调用Gravatar头像
            return $gavatarurl;
        }
    } else { //不是QQ邮箱
        return $gavatarurl;
    }
}
?>
<?php
function kule_allsort($sortid) {
    global $CACHE;
    $sort_cache = $CACHE->readCache('sort');
    $out = '';
    if(!empty($sort_cache[$sortid]['children'])) {
        foreach($sort_cache[$sortid]['children'] as $val) {
            $out .=','.$val;
        }
    }
    return $sortid.$out;
}
function list_logs($logData = array()) {
    $configfile = EMLOG_ROOT.'/content/plugins/related_log/related_log_config.php';
    if (is_file($configfile)) {
        require $configfile;
    } else {
        $related_log_type = 'sort';
        //相关日志类型，sort为分类，tag为日志；
        $related_log_sort = 'rand';
        //排列方式，views_desc 为点击数（降序）comnum_desc 为评论数（降序） rand 为随机 views_asc 为点击数（升序）comnum_asc 为评论数（升序）
        $related_log_num = _g('post_related_n');
        $related_inrss = 'n';
        //是否显示在rss订阅中，y为是，其它值为否
    }
    global $value;
    $DB = Database::getInstance();
    $CACHE = Cache::getInstance();
    extract($logData);
    if($value) {
        $logid = $logData[logid];
        $sortid = $logData[sortid];
        global $abstract;
    }
    $sql ="SELECT gid,title,content,date,cover,content FROM ".DB_PREFIX."blog WHERE hide='n' AND type='blog'";
    if($related_log_type == 'tag') {
        $log_cache_tags = $CACHE->readCache('logtags');
        $Tag_Model = new Tag_Model();
        $related_log_id_str = '0';
        foreach($log_cache_tags[$logid] as $key => $val) {
            $related_log_id_str .= ','.$Tag_Model->getTagByName($val['tagname']);
        }
        $sql .= " AND gid!=$logid AND gid IN ($related_log_id_str)";
    } else {
        $allsort = kule_allsort($sortid);
        $sql .= " AND gid!=$logid AND sortid in ($allsort)";
    }
    switch ($related_log_sort) {
        case 'views_desc': {
            $sql .= " ORDER BY views DESC";
            break;
        }
        case 'views_asc': {
            $sql .= " ORDER BY views ASC";
            break;
        }
        case 'comnum_desc': {
            $sql .= " ORDER BY comnum DESC";
            break;
        }
        case 'comnum_asc': {
            $sql .= " ORDER BY comnum ASC";
            break;
        }
        case 'rand': {
            $sql .= " ORDER BY rand()";
            break;
        }
    }
    $sql .= " LIMIT 0,$related_log_num";
    $list_logs = array();
    $query = $DB->query($sql);
    while($row = $DB->fetch_array($query)) {
        $row['gid'] = intval($row['gid']);
        $row['title'] = htmlspecialchars($row['title']);
        $list_logs[] = $row;
    }
    $out = '';
    if(!empty($list_logs)) {
        foreach($list_logs as $val) {
            $logimg=kule_FirstIMGs($val['cover'],$val['content']);
            $out .= "
<div class='col-lg-4'>
               <div class='card card-plain card-blog'>
                    <div class='card-image border-radius-lg position-relative cursor-pointer'>
                         <div class='blur-shadow-image'>
                             <a href=\"".Url::log($val['gid'])."\">
                                 <img class='img img-raised' src=\"".$logimg."\" alt=\"{$val['title']}\">
                                      </a>
                                      </div>
                              </div>
                              <div class='card-body px-0'>
                                             <p>{$val['title']}</p><a href=\"".Url::log($val['gid'])."\" title='{$val['title']}' class='text-info icon-move-right'>阅读更多<i class='fa fa-arrow-right text-sm ms-1'></i></a>
                              </div>
               </div>
</div>";
        }
        $out .= "";
    }
    if(!empty($value['content'])) {
        if($related_inrss == 'y') {
            $abstract .= $out;
        }
    } else {
        echo $out;
    }
}?>
<?php
//blog：链接
function ilinks() {
    global $CACHE;
    $link_cache = $CACHE->readCache('link');
    ?>
    <?php foreach($link_cache as $value): ?>
        <li class="wow slideInUp animated" style="visibility: visible; animation-name: slideInUp;">
            <a rel="" title="" <?php if (_g('link—open')==1):?> target="_blank" <?php endif;?> href="<?php echo $value['url'];?>">
                <div><img src="<?php echo $value['url'];?>/favicon.ico" alt="<?php if($value['des'] == ""){echo $value['link'];}else{echo $value['des'];}?>"><?php echo $value['link'];
                    ?></div>
                <div><?php if($value['des']=='') echo '这站长太懒了什么也没留下' ;else echo $value['des'];?></div></a></li>
    <?php endforeach;
    ?>
    <?php
}
?>
<?php
function page_tag_key($blogid) {
    $Tag_Model = new Tag_Model();
    $tags = $Tag_Model->getTag($blogid);
    if (!empty($tags)) {
        foreach ($tags as $value) {
            $tag .= $value['tagname'].',';
        }
        echo substr($tag,0,-1);
    }
}
/*
 * 表情重写函数
 * */
function emojistr($comment){
    $tempurl=coolmeow_pic_src();
    $str1 = array(
        ':mrgreen:',
        ':razz:',
        ':sad:',
        ':smile:',
        ':oops:',
        ':grin:',
        ':eek:',
        ':???:',
        ':cool:',
        ':lol:',
        ':mad:',
        ':twisted:',
        ':roll:',
        ':wink:',
        ':idea:',
        ':arrow:',
        ':neutral:',
        ':cry:',
        ':?:',
        ':evil:',
        ':shock:',
        ':!:',
    );
    $str2=array(
        "<img src='$tempurl/images/smilies/icon_mrgreen.gif' alt='emoji' class='wp-smiley' style='height: 1em; max-height: 1em;'>",
        "<img src='$tempurl/images/smilies/icon_razz.gif' alt='emoji' class='wp-smiley' style='height: 1em; max-height: 1em;'>",
        "<img src='{$tempurl}/images/smilies/icon_sad.gif' alt='emoji' class='wp-smiley' style='height: 1em; max-height: 1em;'>",
        "<img src='{$tempurl}/images/smilies/icon_smile.gif' alt='emoji' class='wp-smiley' style='height: 1em; max-height: 1em;'>",
        "<img src='{$tempurl}/images/smilies/icon_redface.gif' alt='emoji' class='wp-smiley' style='height: 1em; max-height: 1em;'>",
        "<img src='{$tempurl}/images/smilies/icon_biggrin.gif' alt='emoji' class='wp-smiley' style='height: 1em; max-height: 1em;'>",
        "<img src='{$tempurl}/images/smilies/icon_surprised.gif' alt='emoji' class='wp-smiley' style='height: 1em; max-height: 1em;'>",
        "<img src='{$tempurl}/images/smilies/icon_confused.gif' alt='emoji' class='wp-smiley' style='height: 1em; max-height: 1em;'>",
        "<img src='{$tempurl}/images/smilies/icon_cool.gif' alt='emoji' class='wp-smiley' style='height: 1em; max-height: 1em;'>",
        "<img src='{$tempurl}/images/smilies/icon_lol.gif' alt='emoji' class='wp-smiley' style='height: 1em; max-height: 1em;'>",
        "<img src='{$tempurl}/images/smilies/icon_mad.gif' alt='emoji' class='wp-smiley' style='height: 1em; max-height: 1em;'>",
        "<img src='{$tempurl}/images/smilies/icon_twisted.gif' alt='emoji' class='wp-smiley' style='height: 1em; max-height: 1em;'>",
        "<img src='{$tempurl}/images/smilies/icon_rolleyes.gif' alt='emoji' class='wp-smiley' style='height: 1em; max-height: 1em;'>",
        "<img src='{$tempurl}/images/smilies/icon_wink.gif' alt='emoji' class='wp-smiley' style='height: 1em; max-height: 1em;'>",
        "<img src='{$tempurl}/images/smilies/icon_idea.gif' alt='emoji' class='wp-smiley' style='height: 1em; max-height: 1em;'>",
        "<img src='{$tempurl}/images/smilies/icon_arrow.gif' alt='emoji' class='wp-smiley' style='height: 1em; max-height: 1em;'>",
        "<img src='{$tempurl}/images/smilies/icon_neutral.gif' alt='emoji' class='wp-smiley' style='height: 1em; max-height: 1em;'>",
        "<img src='{$tempurl}/images/smilies/icon_cry.gif' alt='emoji' class='wp-smiley' style='height: 1em; max-height: 1em;'>",
        "<img src='{$tempurl}/images/smilies/icon_question.gif' alt='emoji' class='wp-smiley' style='height: 1em; max-height: 1em;'>",
        "<img src='{$tempurl}/images/smilies/icon_evil.gif' alt='emoji' class='wp-smiley' style='height: 1em; max-height: 1em;'>",
        "<img src='{$tempurl}/images/smilies/icon_eek.gif' alt='emoji' class='wp-smiley' style='height: 1em; max-height: 1em;'>",
        "<img src='{$tempurl}/images/smilies/icon_exclaim.gif' alt='emoji' class='wp-smiley' style='height: 1em; max-height: 1em;'>",

    );
    $res=str_replace($str1,$str2,$comment);
    return $res;


}
/*
 * 文章内容重写函数
 * */
function content($logid)
{
    $db = Database::getInstance();
    $sql = "SELECT `content` FROM ".DB_PREFIX."blog WHERE gid=$logid";
    $row = $db->once_fetch_array($sql);
    $log_content = $row['content'];
    if($_COOKIE['postermail'] && $_COOKIE['postermail'] != ''){
        $r = MySql::getInstance();
        $row=$r->once_fetch_array("SELECT * FROM  `".DB_NAME."`.`".DB_PREFIX."comment` WHERE `mail` =  '".$_COOKIE['postermail']."' and `gid` = '".$logid."' ORDER BY `date` DESC");
    }else if($_COOKIE['posterurl'] && $_COOKIE['posterurl'] != ''){
        $r = MySql::getInstance();
        $row=$r->once_fetch_array("SELECT * FROM  `".DB_NAME."`.`".DB_PREFIX."comment` WHERE `url` =  '".$_COOKIE['posterurl']."' and `gid` = '".$logid."' ORDER BY `date` DESC");
    }
    if($row && (time()-$row['date']) <= 3600*24 && $row['hide'] == 'n'){
        $log_content = preg_replace("|\\[pwd_protected_post](.*?)\\[/pwd_protected_post]|i", "<div class='alerts'><strong>隐藏内容:</strong>$1</div>", $log_content);
    }else{
        $log_content = preg_replace("|\\[pwd_protected_post](.*?)\\[/pwd_protected_post]|i", "<div class='alerts error'><strong>当前隐藏内容需要评论当前文章即可浏览！</strong></div>", $log_content);
    }
    if($_COOKIE['postermail'] && $_COOKIE['postermail'] != ''){
        $r = MySql::getInstance();
        $row=$r->once_fetch_array("SELECT * FROM  `".DB_NAME."`.`".DB_PREFIX."comment` WHERE `mail` =  '".$_COOKIE['postermail']."' and `gid` = '".$logid."' ORDER BY `date` DESC");
    }else if($_COOKIE['posterurl'] && $_COOKIE['posterurl'] != ''){
        $r = MySql::getInstance();
        $row=$r->once_fetch_array("SELECT * FROM  `".DB_NAME."`.`".DB_PREFIX."comment` WHERE `url` =  '".$_COOKIE['posterurl']."' and `gid` = '".$logid."' ORDER BY `date` DESC");
    }
    if($row && (time()-$row['date']) <= 3600*24 && $row['hide'] == 'n'){
        $log_content = preg_replace("|\\[pwd_protected_style](.*?)\\[/pwd_protected_style]|i", "<div class='alerts'><strong>隐藏内容:</strong>$1</div>", $log_content);
    }else{
        $log_content = preg_replace("|\\[pwd_protected_style](.*?)\\[/pwd_protected_style]|i", "<div class='alert alert-warning alert-dismissible text-white fade show' role='alert'>
		    <strong>注意！</strong>当前隐藏内容需要评论当前文章即可浏览！
		    <button type='button' class='btn-close text-lg py-3 opacity-10' data-bs-dismiss='alert' aria-label='Close'>
		    <span aria-hidden='true'>&times;</span>
		    </button>
		    </div>", $log_content);
    }
    if(!UID){
        $log_content = preg_replace("|\\[userreading](.*?)\\[/userreading]|i", "<div class='alerts error'><strong>该段内容只有登录才可以查看</strong></div>", $log_content);

    }
    else{
        $log_content = preg_replace("|\\[userreading](.*?)\\[/userreading]|i", "<div class='alerts'><strong>隐藏内容:</strong>$1</div>", $log_content);

    };
    if(!UID){
        $log_content = preg_replace("|\\[userstyle](.*?)\\[/userstyle]|i", " <div class='alert alert-warning alert-dismissible text-white fade show' role='alert'>
		    <strong>注意！</strong>该段内容只有登录才可以查看!
		    <button type='button' class='btn-close text-lg py-3 opacity-10' data-bs-dismiss='alert' aria-label='Close'>
		    <span aria-hidden='true'>&times;</span>
		    </button>
		    </div>", $log_content);

    }
    else{
        $log_content = preg_replace("|\\[userstyle](.*?)\\[/userstyle]|i", "<div class='alerts'><strong>隐藏内容:</strong>$1</div>", $log_content);

    };
    $log_content = preg_replace("|\\[blockquote1 name='(.*?)'](.*?)\\[/blockquote1]|i", "<div class='quote'><blockquote><p>$2</p><cite>$1</cite></blockquote></div>", $log_content);
    $log_content = preg_replace("|\\[blockquote2 name='(.*?)'](.*?)\\[/blockquote2]|i", "<div class='animated-border-quote'><blockquote><p>$2</p><cite>$1</cite></blockquote></div>", $log_content);
    $log_content = preg_replace("|\\[h2set](.*?)\\[/h2set]|i", "<h2 class='section-title'><span><i class='fa fa-gear'></i>$1</span></h2>", $log_content);
    $log_content = preg_replace("|\\[h2down](.*?)\\[/h2down]|i", "<h2 class='section-title'><span><i class='fa fa-cloud-download'></i>$1</span></h2>", $log_content);
    $log_content = preg_replace("|\\[downloadbtn link='(.*?)'](.*?)\\[/downloadbtn]|i", "<a href='$1' rel='noopener' target='_blank' class='download_btn btn-tooltip'>$2</a>", $log_content);
    $log_content = preg_replace("|\\[linksbtn link='(.*?)'](.*?)\\[/linksbtn]|i", "<a href='$1' rel='noopener' target='_blank' class='links_btn'>$2</a>", $log_content);
    $log_content = preg_replace("|\\[yaowan style='(.*?)'](.*?)\\[/yaowan]|i", "<span class='badge badge-style$1 mb-1 mt-1'>$2</span>", $log_content);
    $log_content = preg_replace("|\\[alert style='(.*?)'](.*?)\\[/alert]|i", "<div class='alert alert-style$1' role='alert'>$2</div>", $log_content);
    $log_content = preg_replace("|\\[iframe link='(.*?)'](.*?)\\[/iframe]|i", "<a href='javascript:;' data-fancybox data-type='iframe' data-src='$1'>$2</a>", $log_content);
    $log_content = preg_replace("|\\[flybtn](.*?)\\[/flybtn]|i", "<div class='link-title wow rollIn'>$1</div>", $log_content);
    $log_content = preg_replace("|\\[h2pen](.*?)\\[/h2pen]|i", "<h2 class='section-title'><span><i class='fa fa-paint-brush'></i>$1</span></h2>", $log_content);
    $log_content = preg_replace("|\\[h2wechat](.*?)\\[/h2wechat]|i", "<h2 class='section-title'><span><i class='fa fa-wechat'></i>$1</span></h2>", $log_content);
    $log_content = preg_replace("|\\[h2mht](.*?)\\[/h2mht]|i", "<h2 class='section-title'><span><i class='fa fa-qq'></i>$1</span></h2>", $log_content);
    $log_content = preg_replace("|\\[h2](.*?)\\[/h2]|i", "<h2 class='h-title'>$1</h2>", $log_content);
    $log_content = preg_replace("|\\[h3](.*?)\\[/h3]|i", "<h3 class='h-title'>$1</h3>", $log_content);
    $log_content = preg_replace("|\\[code](.*?)\\[/code]|i", "<code>$1</code>", $log_content);
    $log_content = preg_replace("|\\[audio link='(.*?)'](.*?)\\[/audio]|i", "<audio preload='none' controls='controls'><source type='audio/mpeg' src='$1'></audio>", $log_content);
    $log_content = preg_replace("|\\[video link='(.*?)'](.*?)\\[/video]|i", "<video preload='none' controls='controls' width='1080' height='1920'><source type='video/mp4' src='$1'></video>", $log_content);
    $Parsedown = new Parsedown();
    $Parsedown->setBreaksEnabled(true);

    $log_content=$Parsedown->text($log_content);
    $log_content=prettify_esc_html(prettify_replace($log_content));
        $log_content = preg_replace('/<code class=".*?">([^\<]+)<\/code>/', "$1", $log_content);

    return $log_content;
}

// 添加开始时间函数
function runStartTime(){
    define('RUN_STARTTIME', microtime(true));
}
// 计算耗时和查询数据库次数并输出函数
function setAndShowFoot(){
    $runStopTime = microtime(true);
    $timeCount = round($runStopTime - RUN_STARTTIME, 3);  $databaseLink = MySql::getInstance();
    $queryNum = $databaseLink->getQueryCount();
    echo "".$queryNum." queries in ".$timeCount." s";
}
function wow_set(){
    if( _g('wow_on')) {echo 'wow zoomIn';}else{}
}
function comment_user_info($uid){
    if($uid > 1){
        echo '<span class="commentsbadge members"><i class="fa fa-heart"></i> '._g('comnanesu').'</span>';
    }if($uid == 1){
        echo '<span class="commentsbadge blogger"><i class="fa fa-check-square"></i> '._g('comnanes').'</span>';
    }if($uid == 0){
        echo '<span class="commentsbadge tourists"><i class="fa fa-globe"></i> '._g('comnaness').'</span>'; }
}
function blog_border($border='blog-border'){
    if(_g('sidebar_on') == 'col-1' ){
        if(_g('blog_border') == 'border1' ){
                $border='blog-border';
        }
        if(_g('blog_border') == 'border2'){

                $border='blog-card';

        }
    }else if(_g('sidebar_on') == 'col-2' ){
        if(_g('blog_border') == 'border1' ){
            $border='blog-border';
        }
        if(_g('blog_border') == 'border2'){
            $border='blog-card';
        }
    }

    return  $border;
}
function sidebaron() {
    $sidebar_on = _g('sidebar_on');
    if(!empty($sidebar_on)) {
        if(_g('sidebar_on') == 'col-1' ){
            $sidebar='10 mx-auto';
        }else{
            $sidebar='8';
        }
    }else{
        $sidebar = '10 mx-auto';
    }
    return  $sidebar;
}
//底部社交输出
function footer_socialize() {
    echo '<div class="col-lg-3 text-end">'."\n";
    if(_g('QQ')){
        echo '<a href="https://wpa.qq.com/msgrd?v=3&amp;uin='._g('QQ').'&amp;site=qq&amp;menu=yes" data-bs-toggle="tooltip" data-bs-placement="top" title="博主QQ" target="_blank" class="text-secondary me-xl-4 me-4">
          <i class="fa fa-qq"></i></a>';
    }
    if(_g('boxmoe_wechat')){
        echo '<a href="'._g('boxmoe_wechat').'" data-bs-toggle="tooltip" data-bs-placement="top" title="博主微信" data-fancybox="images" data-fancybox-group="button" class="text-secondary me-xl-4 me-4">
          <i class="fa fa-wechat"></i></a>';
    }
    if(_g('weibo')){
        echo '<a href="'._g('weibo').'" data-bs-toggle="tooltip" data-bs-placement="top" title="博主微博" target="_blank" class="text-secondary me-xl-4 me-4">
          <i class="fa fa-weibo"></i></a>';
    }
    if(_g('github')){
        echo '<a href="'._g('github').'" data-bs-toggle="tooltip" data-bs-placement="top" title="博主Github" target="_blank" class="text-secondary me-xl-4 me-4">
          <i class="fa fa-github"></i></a>';
    }
    if(_g('email')){
        echo '<a href="http://mail.qq.com/cgi-bin/qm_share?t=qm_mailme&email='._g('email').'" data-bs-toggle="tooltip" data-bs-placement="top" title="博主邮箱" target="_blank" class="text-secondary me-xl-4 me-4">
          <i class="fa fa-envelope"></i></a>';
    }
    echo '</div>'."\n";
}
//网站运行时间
function blog_run_time(){
$blog_reg_time_str = _g('blog_reg_time');
$blog_reg_date = DateTime::createFromFormat('m/d/Y', $blog_reg_time_str);
$current_date = new DateTime();
$interval = $current_date->diff($blog_reg_date);
if ($interval->invert) {
    $interval->y = -$interval->y;
    $interval->m = -$interval->m;
    $interval->d = -$interval->d;
}
$days_since_registration = $interval->format('%a');
echo '本站已稳定运行了'.$days_since_registration . ' 天';
}
//底部信息输出
function footer_info() {
    echo '<p class="mb-0 copyright">';
    echo 'Copyright © '.date('Y').' <a href="'.BLOG_URL.'" target="_blank">'.$blogname.'</a> | Theme by
                <a href="https://www.emlog.net/template/detail/1033" target="_blank">coolmeow</a><br>';
    if( _g('footer_info') ) {
        echo _g('footer_info').'<br>';
    }
    if (_g('blog_run_time')){
        ?>
        <?php blog_run_time(); ?><br>
        <?php
    }
    if( _g('runtime') ) {
        echo setAndShowFoot().'<br>';
    }
    if( _g('trackcode') ) {
        echo '<div style="display:none;">'._g('trackcode').'</div>';
    }
    echo '</p>'."\n";
}
//底部链接输出
function footer_seo() {
    if( _g('footer_seo') ) {
        echo '<ul class="nav flex-row align-items-center mt-sm-0 justify-content-center nav-footer">';
        echo _g('footer_seo');
        echo '</ul>';
    }else{

    }
}
//banner参数
function banner() {
    $banner_rand = _g('banner_rand');
    $banner_api_on =  _g('banner_api_on');
    if( !empty($banner_api_on)) {
        $banner_dir = 'style="background-image: url(\''._g('banner_api_url').'?'.token(6).'\');"';
    }
    else if( !empty($banner_rand) ) {
        $banner_no = _g('banner_rand_n');
        $temp_no = rand(1,$banner_no);
        $banner_dir = 'style="background-image: url(\''.TEMPLATE_URL.'assets/images/banner/'.$temp_no.'.jpg\');" ';
    }
    else if	( _g('banner_url') ) {
        $banner_dir = 'style="background-image: url(\''._g('banner_url').'\');"';}
    else {
        $banner_dir = 'style="background-image: url(\''.TEMPLATE_URL.'/assets/images/banner/1.jpg\');"';
    }
    return  $banner_dir;
}
//随机字符串
function token($length){
    $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
    $randStr = str_shuffle($str);
    $rands= substr($randStr,0,$length);
    return $rands;
}
//Gravatar头像加速
function getavatar_host() {
    $gravatarUrl = 'gravatar.loli.net/avatar';
    switch (_g('gravatar_url')) {
        case 'cn':
            $gravatarUrl = 'cn.gravatar.com/avatar';
            break;
        case 'ssl':
            $gravatarUrl = 'secure.gravatar.com/avatar';
            break;
        case 'lolinet':
            $gravatarUrl = 'gravatar.loli.net/avatar';
            break;
        case 'qiniu':
            $gravatarUrl = 'dn-qiniu-avatar.qbox.me/avatar';
            break;
        case 'geekzu':
            $gravatarUrl = 'fdn.geekzu.org/avatar';
            break;
        case 'cravatar':
            $gravatarUrl = 'cravatar.cn/avatar';
            break;
        default:
            $gravatarUrl = 'cravatar.cn/avatar';
    }
    return $gravatarUrl;
}
//QQ头像节点
function qqavatar_host() {
    $qqravatarUrl = 'q2.qlogo.cn';
    switch (_g('qqavatar_url')) {
        case 'Q1':
            $qqravatarUrl = 'q1.qlogo.cn';
            break;
        case 'Q2':
            $qqravatarUrl = 'q2.qlogo.cn';
            break;
        case 'Q3':
            $qqravatarUrl = 'q3.qlogo.cn';
            break;
        case 'Q4':
            $qqravatarUrl = 'q4.qlogo.cn';
            break;
        default:
            $qqravatarUrl = 'q2.qlogo.cn';
    }
    return $qqravatarUrl;
}
function favicon() {
    $src = _g('favicon_src');
    if( !empty($src) ) {
        $src = '<link rel="shortcut icon" href="'.$src.'" />';
    }
    echo $src;
}
function load_lantern() {
    if (_g('lantern') ){?>
        <div id="wp"class="wp"><div class="xnkl"><div class="deng-box2"><div class="deng"><div class="xian"></div><div class="deng-a"><div class="deng-b"><div class="deng-t"><?php echo _g('lanternfont2','度')?></div></div></div><div class="shui shui-a"><div class="shui-c"></div><div class="shui-b"></div></div></div></div><div class="deng-box3"><div class="deng"><div class="xian"></div><div class="deng-a"><div class="deng-b"><div class="deng-t"><?php echo _g('lanternfont1','欢')?></div></div></div><div class="shui shui-a"><div class="shui-c"></div><div class="shui-b"></div></div></div></div><div class="deng-box1"><div class="deng"><div class="xian"></div><div class="deng-a"><div class="deng-b"><div class="deng-t"><?php echo _g('lanternfont4','春')?></div></div></div><div class="shui shui-a"><div class="shui-c"></div><div class="shui-b"></div></div></div></div><div class="deng-box"><div class="deng"><div class="xian"></div><div class="deng-a"><div class="deng-b"><div class="deng-t"><?php echo _g('lanternfont3','新')?></div></div></div><div class="shui shui-a"><div class="shui-c"></div><div class="shui-b"></div></div></div></div></div>
    <?php }else{}
}
function Version(): string
{
    return 'Version=2.7.5';
}
function moew_sql(){
    $db = MySql::getInstance();
    $moew=$db->num_rows($db->query("show tables like '".DB_PREFIX."meowdown'"));
    if($moew == 0){
        $db->query("CREATE TABLE `".DB_PREFIX."meowdown`  (
       `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
        `logid` int(10) unsigned NOT NULL,
        `down1` varchar(255) NOT NULL,
        `down2` varchar(255) NOT NULL,
		`down3` varchar(255) NOT NULL,
   PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;");
    }
}
moew_sql();
function meow_down($logid){
    $db = MySql::getInstance();
    $data = $db->query("SELECT * FROM ".DB_PREFIX."meowdown WHERE logid ='$logid'");
    $row = $db->fetch_array($data);
    if($row['down1']!='' or $row['down2']!='' or $row['down3']!=''):?>
        <h2 class="section-title"><span><i class="fa fa-cloud-download"></i>下载</span></h2>
    <?php endif;?>
    <?php if($row['down1']!=''):?>
        <a href="<?php echo $row['down1'];?>" rel="nofollow" target="_blank" class="download_btn btn-tooltip">高速下载</a>
    <?php endif;?>
    <?php if($row['down2']!=''):?>
        <a href="<?php echo $row['down2'];?>" rel="nofollow" target="_blank" class="download_btn btn-tooltip">备用下载</a>
    <?php endif;?>
    <?php if($row['down3']!=''):?>
        <a href="<?php echo $row['down3'];?>" rel="nofollow" target="_blank" class="download_btn btn-tooltip">网盘下载</a>
    <?php endif;?>
<?php

}
function coolmeow_pic_src() {
	if(_g('ui_cdn') == 'local'){
		$picurlload=TEMPLATE_URL.'assets';
	}
	$diy_cdn_src=_g('diy_cdn_src','');
	if(_g('ui_cdn') == 'diy_cdn'){
		if(!empty($diy_cdn_src)){
			$picurlload=$diy_cdn_src;
		}else{
			$picurlload=TEMPLATE_URL.'assets';
		}
	}
	return $picurlload;
	}
    //文章点击数换算K
function restyle_text($number){
  if ($number >= 1000) {
                return round($number / 1000, 2) . 'k';
            } else {
                return $number;
            }
}
//文章缩短
function Length_reduction($excerpt,$limit = 60) {
	if (mb_strlen($excerpt) > $limit) {
		$excerpt=mb_substr(strip_tags($excerpt), 0,60);
        $excerpt.='...';
        return $excerpt;
	} else {
		return $excerpt;
	}
}
function lightbox_gall_replace ($content,$alt) {
    $content = str_replace('\\', '', (string) $content);
    preg_match_all('/<\s*img\s+[^>]*?src\s*=\s*(\'|\")(.*?)\\1[^>]*?\/?\s*>/i', $content, $matches);
    $origin = array();
    $replace = array();
    foreach ($matches[2] as $key => $value) {
        $origin[] = $matches[0][$key];
        $replace[] = "<a href='$value' class='fancybox' data-fancybox='images' data-fancybox-group='button'><img src='$value' alt='$alt' /></a>";
    }
    $content = str_replace($origin, $replace, $content);
      preg_match_all('/<[img|IMG].*?src=[\'|"](.*?(?:[\.gif|\.jpg|\.png\.bmp]))[\'|"].*?[\/]?>/', $content, $images);
  if(!empty($images)) {
    foreach($images[0] as $index => $value){
      $new_img = preg_replace('/(width|height)="\d*"\s/', "", $images[0][$index]);
      $content = str_replace($images[0][$index], $new_img, $content);
    }
  }
    return  $content;
}
function is_today($time1,$time2){
    $time1=date('Y-m-d',$time1);
    $time2=date('Y-m-d',$time2);
    if($time1==$time2) return true;
    return false;
}
//防止代码转义

function prettify_esc_html($content){
    $regex = '/(<pre\s+[^>]*?class\s*?=\s*?[",\'].*?prettyprint.*?[",\'].*?>)(.*?)(<\/pre>)/sim';
    return preg_replace_callback($regex, 'prettify_esc_callback', $content);}
function prettify_esc_callback($matches){
    $tag_open = $matches[1];
    $content = $matches[2];
    $tag_close = $matches[3];
    return $tag_open . $content . $tag_close;}

//强制兼容
function prettify_replace($text){
	$replace = array( '<pre>' => '<pre class="prettyprint linenums" >','<pre class="prettyprint">' => '<pre class="prettyprint linenums" >' );
	$text = str_replace(array_keys($replace), $replace, $text);
	return $text;}

