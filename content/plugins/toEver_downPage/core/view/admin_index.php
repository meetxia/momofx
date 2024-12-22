<?php require_once toEverDownPage::getInstance()->view_path('admin_header')?>
<div class="layui-tab layui-tab-card">
    <ul class="layui-tab-title">
        <li class="layui-this">链接管理</li>
        <li onclick="iframe_jump('admin_config')">配置修改</li>
    </ul>
    <div class="layui-tab-content">
        <table class="layui-hide" id="toEver_downPage" lay-filter="toEver_downPage"></table>
    </div>
</div>
<script type="text/html" id="toolbarDemo">
    <button type="button" class="layui-btn layui-btn-sm layui-btn-danger" lay-event="del_all">批量删除</button>
</script>
<script>
layui.use(function(){

    var table   = layui.table,
        form    = layui.form,
        layer   = layui.layer;
    
    table.render({
        id: 'toEver_downPage',
        elem: '#toEver_downPage',
        url: 'plugin.php?plugin=<?=TOEVER_DOWNPAGE_NAME?>&route=admin_index',
        toolbar: '#toolbarDemo',
        defaultToolbar: ['exports'],
        cellMinWidth: 80,
        page: true,
        limit: 30,
        limits: [30, 50, 100, 200, 500, 1000],
        cols: [[
            {type: 'checkbox', fixed: 'left'},
            {field: 'gid', title: '文章ID', width: 80, maxWidth: 100, align: 'center'},
            {field: 'name', title: '所属文章', minWidth: 200,
                templet: function(d){
                    return `<div>
                        <a href="`+d.log_url+`" target="_blank">`+d.name+`</a>
                    </div>`;
                }
            },
            {field: 'type', title: '下载模式', width: 120, align: 'center',
                templet: function(d){
                    let typeTitle = '';
                    d.type = parseInt(d.type);
                    switch (d.type) {
                        case 0:
                            typeTitle = '免费下载';
                        break;
                        case 1:
                            typeTitle = '回复下载';
                        break;
                        case 2:
                            typeTitle = '登录下载';
                        break;
                        case 3:
                            typeTitle = '付费下载';
                        break;
                    }
                    return '<div class="layui-btn layui-btn-primary layui-btn-xs">'+typeTitle+'</div>';
                }
            },
            {field: 'state', title: '下载状态', width: 120, align: 'center',
                templet: function(d){  
                    let checked = d.state === 'y' ? 'checked' : '';
                    return '<div><input type="checkbox" name="state" title="开启|关闭" lay-skin="switch" lay-filter="stateFilter" value="'+d.id+'" '+checked+'></div>';
                }
            },
            {fixed: 'right', title:'操作', width: 120, align: 'center',
                templet: function(d){
                    return `<div>
                        <div class="layui-btn-group">
                            <button type="button" class="layui-btn layui-btn-normal layui-btn-xs" onClick="parent.location.href='`+d.edit_url+`';">修改</button>
                            <button type="button" class="layui-btn layui-btn-danger layui-btn-xs" lay-event="all">删除</button>
                        </div>
                    </div>`;
                }
            }
        ]],
        done: function(){
            
        }
    });
    
    // checkbox 事件
    form.on('switch(stateFilter)', function(data){
        let checked = data.elem.checked ? 'y' : 'n',
            value = data.elem.value;
            console.log(value);
        $.get('plugin.php?plugin=<?=TOEVER_DOWNPAGE_NAME?>&route=admin_modify_state_api', {id: value, state: checked}, function(res){
            if(res.code == 0){
                layer.msg('修改成功');
            }else{
                layer.msg(res.msg, function(){
                    table.reload('toEver_downPage');
                });
            }
        }, 'json').error(function(xhr){
            layer.msg(xhr.responseJSON.msg, function(){
                table.reload('toEver_downPage');
            });
            return false;
        });
    });

    table.on('toolbar(toEver_downPage)', function(obj){
        switch (obj.event) {
            case 'del_all':
                let checkStatus = table.checkStatus(obj.config.id),
                data = checkStatus.data;
                if(data.length <= 0){
                    layer.msg('请勾选需要删除的数据');
                    return false;
                }
                var ids = [];
                $.each(data, function (i, v){
                    ids.push(v.id);
                });
                layer.confirm('确定删除？', function(){
                    $.get('plugin.php?plugin=<?=TOEVER_DOWNPAGE_NAME?>&route=admin_delete_api', {id: ids}, function(res){
                        if(res.code == 0){
                            layer.msg('删除成功', function(){
                                table.reload('toEver_downPage');
                            });
                        }else{
                            layer.msg(res.msg);
                        }
                    }, 'json').error(function(xhr){
                        layer.msg(xhr.responseJSON.msg);
                        return false;
                    });
                });
            break;
        };
    });

    table.on('tool(toEver_downPage)', function(obj){
        switch (obj.event) {
            case 'all':
                layer.confirm('确定删除？', function(){
                    $.get('plugin.php?plugin=<?=TOEVER_DOWNPAGE_NAME?>&route=admin_delete_api', {id: obj.data.id}, function(res){
                        if(res.code == 0){
                            layer.msg('删除成功', function(){
                                table.reload('toEver_downPage');
                            });
                        }else{
                            layer.msg(res.msg);
                        }
                    }, 'json').error(function(xhr){
                        layer.msg(xhr.responseJSON.msg);
                        return false;
                    });
                });
            break;
        }
    });

});
</script>
<?php require_once toEverDownPage::getInstance()->view_path('admin_footer')?>