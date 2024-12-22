<div class="form-group">
    <label>下载链接：</label>
    <div class="toEverDownPage_form_addConfig">
        <a href="#" class="btn btn-sm btn-primary" data-backdrop="static" data-toggle="modal" data-target="#toEverDownPage_add"><i class="icofont-plus"></i> 添加链接</a>
        <span></span>
    </div>
    <script>
        var toEverDownPage = {
            linkCount: <?=!empty($options['linkCount']) ? $options['linkCount'] : 10?>
        };
    </script>
</div>
<div class="modal fade" id="toEverDownPage_add" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">下载管理</h5>
                <button type="button" class="close verify" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php if($action === 'edit' && !empty($data)):?>
                    <input type="hidden" name="teDown_id" value="<?=$data['id']?>">
                    <div class="form-row">
                        <div class="form-group col">
                            <label for="teDownForm_state">下载状态：<span class="badge badge-danger">默认关闭</span></label>
                            <select name="teDown_state" class="form-control" id="teDownForm_state">
                                <option value="y" <?=$data['state'] === 'y' ? 'selected' : ''?>>开启</option>
                                <option value="n" <?=$data['state'] === 'n' ? 'selected' : ''?>>关闭</option>
                            </select>
                        </div>
                        <div class="form-group col">
                            <label for="teDownForm_type">下载模式：</label>
                            <select name="teDown_type" class="form-control" id="teDownForm_type">
                                <option value="0" <?=$data['type'] == 0 ? 'selected' : ''?>>免费下载</option>
                                <option value="1" <?=$data['type'] == 1 ? 'selected' : ''?>>回复下载</option>
                                <option value="2" <?=$data['type'] == 2 ? 'selected' : ''?>>登录下载</option>
                                <option value="3" <?=$data['type'] == 3 ? 'selected' : ''?>>付费下载</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group" id="pay_toggle" <?php $data['type'] != 3 ? 'style="display: none;"' : ''?>>
                        <label for="teDownForm_money">购买金额：<span class="badge badge-danger">付费下载</span></label>
                        <input type="number" name="teDown_money" step="0.01" class="form-control" id="teDownForm_money" placeholder="输入购买金额￥" value="<?=$data['money']?>">
                    </div>
                    <div id="toEverDownPage_form_downloadList">
                    <?php foreach(json_decode($data['list'], true) as $key => $val):?>
                        <div class="form-group border" id="toEverDownPage_form_downloadList_<?=$key+1?>" data-id="<?=$key+1?>">
                            <div class="d-flex justify-content-between align-items-center info">
                                <span>配置-<?=$key+1?>：</span>
                                <a href="javascript: toEverDownPage_form_delConfig(<?=$key+1?>);" class="toEverDownPage_form_delConfig"><span class="badge badge-danger">删除</span></a>
                            </div>
                            <div class="main">
                                <div class="form-row">
                                    <div class="form-group col">
                                        <label for="teDownForm_source">来源：</label>
                                        <input type="text" name="teDown_list[<?=$key+1?>][source]" class="form-control" id="teDownForm_source" placeholder="自定义来源，例：蓝奏网盘" value="<?=$val['source']?>" required>
                                    </div>
                                    <div class="form-group col">
                                        <label for="teDownForm_password">密码：</label>
                                        <input type="text" name="teDown_list[<?=$key+1?>][password]" class="form-control" id="teDownForm_password" placeholder="自定义密码，例：1234" value="<?=$val['password']?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="teDownForm_url">下载地址：</label>
                                    <input type="text" name="teDown_list[<?=$key+1?>][url]" class="form-control" id="teDownForm_url" placeholder="输入下载地址，包括http(s)://" value="<?=$val['url']?>" required>
                                </div>
                            </div>
                        </div>
                    <?php endforeach;?>
                    </div>
                <?php else:?>
                    <input type="hidden" name="teDown_id" value="0">
                    <div class="form-row">
                        <div class="form-group col">
                            <label for="teDownForm_state">下载状态：<span class="badge badge-danger">默认关闭</span></label>
                            <select name="teDown_state" class="form-control" id="teDownForm_state">
                                <option value="y">开启</option>
                                <option value="n" selected>关闭</option>
                            </select>
                        </div>
                        <div class="form-group col">
                            <label for="teDownForm_type">下载模式：</label>
                            <select name="teDown_type" class="form-control" id="teDownForm_type">
                                <option value="0" <?=$options['select_defaultType'] == 0 ? 'selected' : ''?>>免费下载</option>
                                <option value="1" <?=$options['select_defaultType'] == 1 ? 'selected' : ''?>>回复下载</option>
                                <option value="2" <?=$options['select_defaultType'] == 2 ? 'selected' : ''?>>登录下载</option>
                                <option value="3" <?=$options['select_defaultType'] == 3 ? 'selected' : ''?>>付费下载</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group" id="pay_toggle" style="display: none;">
                        <label for="teDownForm_money">购买金额：<span class="badge badge-danger">付费下载</span></label>
                        <input type="number" name="teDown_money" step="0.01" class="form-control" id="teDownForm_money" placeholder="输入购买金额￥">
                    </div>
                    <div id="toEverDownPage_form_downloadList"></div>
                <?php endif;?>
            </div>
            <div class="modal-footer">
                <div class="form-group toEverDownPage_form_addConfig">
                    <span></span>
                    <a href="javascript: toEverDownPage_form_addConfig();" class="btn btn-sm btn-primary"><i class="icofont-plus"></i> 添加链接</a>
                    <a href="javascript: toEverDownPage_form_allDelConfig();" class="btn btn-sm btn-danger">全清下载</a>
                </div>
                <button type="button" class="btn btn-sm btn-success verify" data-dismiss="modal">确定</button>
            </div>
        </div>
    </div>
</div>