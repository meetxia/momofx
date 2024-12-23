<?php defined('EMLOG_ROOT') || exit('access denied!'); ?>
<?php if (isset($_GET['add_shortcut_suc'])): ?>
    <div class="alert alert-success">设置成功</div><?php endif ?>
    <div class="d-flex align-items-center mb-3">
        <div class="flex-shrink-0">
            <a class="mr-2" href="blogger.php">
                <img src="<?= empty($user_cache[UID]['avatar']) ? './views/images/avatar.svg' : '../' . $user_cache[UID]['avatar'] ?>"
                     alt="avatar" class="img-fluid rounded-circle border border-mute border-3"
                     style="width: 56px;">
            </a>
        </div>
        <div class="flex-grow-1 ms-3">
            <div class="align-items-center mb-3">
                <p class="mb-0 m-2"><a class="mr-2" href="blogger.php"><?= $user_cache[UID]['name'] ?></a></p>
                <p class="mb-0 m-2 small"><?= $role_name ?></p>
            </div>
        </div>
    </div>
    <div class="row ml-1 mb-1"><?php doAction('adm_main_top') ?></div>
    <div class="row">
        <div class="col-lg-12 mb-3">
            <div class="card shadow mb-3">
                <div class="card-body">
                    快捷入口：
                    <a href="./article.php?action=write" class="mr-2">写文章</a>
                    <a href="article.php" class="mr-2">文章</a>
                    <a href="article.php?draft=1" class="mr-2">草稿</a>
                    <?php foreach ($shortcut as $item): ?>
                        <a href="<?= $item['url'] ?>" class="mr-2"><?= $item['name'] ?></a>
                    <?php endforeach; ?>
                    <span class="text-gray-300 mr-2">|</span>
                    <a href="#" class="my-1" data-toggle="modal" data-target="#shortcutModal"><i class="icofont-plus"></i></a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 mb-3">
            <div class="card shadow mb-3">
                <h6 class="card-header">站点信息</h6>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <a href="./article.php?checked=n">待审文章</a>
                            <a href="./article.php?checked=n"><span class="badge badge-danger badge-pill"><?= $sta_cache['checknum'] ?></span></a>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <a href="./comment.php?hide=y">待审评论</a>
                            <a href="./comment.php?hide=y"><span class="badge badge-warning badge-pill"><?= $sta_cache['hidecomnum'] ?></span></a>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <a href="./user.php">用户</a>
                            <a href="./user.php"><span class="badge badge-success badge-pill"><?= count($user_cache) ?></span></a>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <a href="./article.php">文章</a>
                            <a href="./article.php"><span class="badge badge-primary badge-pill"><?= $sta_cache['lognum'] ?></span></a>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <a href="./twitter.php?all=y">微语</a>
                            <a href="./twitter.php?all=y"><span class="badge badge-primary badge-pill"><?= $sta_cache['note_num'] ?></span></a>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <a href="./comment.php">评论</a>
                            <a href="./comment.php"><span class="badge badge-primary badge-pill"><?= $sta_cache['comnum_all'] ?></span></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <?php if (User::isAdmin()): ?>
        <div class="col-lg-6 mb-3">
            <div class="card shadow mb-3">
                <h6 class="card-header">软件信息</h6>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            PHP
                            <span class="small"><?= $php_ver ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            数据库
                            <span class="small">MySQL <?= $mysql_ver ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Web服务
                            <span class="small"><?= $server_app ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            操作系统
                            <span class="small"><?= $os ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            系统时区
                            <span class="small"><?= Option::get('timezone') ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <?php if (!Register::isRegLocal()) : ?>
                                    <a href="auth.php"><span class="badge badge-secondary">Emlog <?= Option::EMLOG_VERSION ?> 未注册，点击注册</span></a>
                                <?php else: ?>
                                    <a href="https://www.emlog.net.cn" target="_blank"><span class="badge badge-success">Emlog <?= ucfirst(Option::EMLOG_VERSION) ?></span></a>
                                    <?php if (Register::getRegType() === 2): ?>
                                        <a href="https://www.emlog.net.cn/register" target="_blank" class="badge badge-warning">铁杆SVIP</a>
                                    <?php elseif (Register::getRegType() === 1): ?>
                                        <a href="https://www.emlog.net.cn/register" target="_blank" class="badge badge-success">友情VIP</a>
                                    <?php else: ?>
                                        <a href="https://www.emlog.net.cn/register" target="_blank" class="badge badge-success">已注册</a>
                                    <?php endif ?>
                                <?php endif; ?>
                            </div>
                            <div>
                                <a id="ckup" href="javascript:checkUpdate();" class="badge badge-success d-flex align-items-center"><span>更新</span></a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <?php if (!Register::isRegLocal()) : ?>
            <div class="col-lg-6 mb-3">
                <div class="card shadow">
                    <div class="card-header bg-danger text-white">
                        <h6 class="my-0">您安装的emlog尚未注册，完成注册可使用全部功能，包括如下：</h6>
                    </div>
                    <div class="card-body">
                        <p>1. 解锁在线升级功能，一键升级到最新版本，获得来自官方的安全和功能更新</p>
                        <p>2. 解锁应用商店，获得更多模板和插件，并支持应用在线一键更新</p>
                        <p>3. 去除所有未注册提示及功能限制，加入专属Q群，获得官方技术指导问题解答</p>
                        <p>4. 专属应用，20多款收费应用（限铁杆SVIP）</p>
                    </div>
                    <div class="card-footer text-center">
                        <a href="auth.php" class="btn btn-sm btn-primary shadow-lg">去注册</a>
                        <a href="https://emlog.net.cn/register" target="_blank" class="btn btn-sm btn-success shadow-lg">获取注册码-></a>
                    </div>
                </div>
            </div>
        <?php endif ?>
    </div>
    <div class="modal fade" id="update-modal" tabindex="-1" role="dialog" aria-labelledby="update-modal-label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="update-modal-label">检查更新</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="update-modal-loading"></div>
                    <div id="update-modal-msg" class="text-center"></div>
                    <div id="update-modal-changes"></div>
                    <div id="update-modal-btn" class="mt-2 text-right"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="shortcutModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="shortcutModalLabel">快捷入口</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="index.php?action=add_shortcut" method="post">
                    <div class="modal-body">
                        <?php foreach ($shortcutAll as $k => $v):
                            $checked = in_array($v, $shortcut) ? 'checked' : '';
                            ?>
                            <input type="checkbox" name="shortcut[]" id="shortcut-<?= $k ?>" value="<?= $v['name'] ?>||<?= $v['url'] ?>" <?= $checked ?>>
                            <label class="mr-2" for="shortcut-<?= $k ?>"><?= $v['name'] ?></label>
                        <?php endforeach; ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">取消</button>
                        <button type="submit" class="btn btn-sm btn-success">保存</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <script>
        setTimeout(hideActived, 3600);
        const menuPanel = $("#menu_panel").addClass('active');

        // auto check update
        $.get("./upgrade.php?action=check_update", function (result) {
            if (result.code === 200) {
                $("#ckup").append('<span class="badge bg-danger ml-1">!</span>');
            }
        });
    </script>
<?php endif ?>
<?php if (User::isAdmin()): ?>
    <div class="row">
        <?php doAction('adm_main_content') ?>
    </div>
<?php endif; ?>