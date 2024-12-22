<?php require_once toEverDownPage::getInstance()->view_path('admin_header')?>
<div class="layui-tab layui-tab-card">
    <ul class="layui-tab-title">
        <li onclick="iframe_jump('admin_index')">链接管理</li>
        <li class="layui-this">配置修改</li>
    </ul>
    <div class="layui-tab-content">
        <form class="layui-form">
            <div class="layui-collapse" lay-accordion>
                <div class="layui-colla-item">
                    <div class="layui-colla-title">全局配置</div>
                    <div class="layui-colla-content layui-show">
                        <div class="layui-form-item">
                            <label class="layui-form-label">全局开关</label>
                            <div class="layui-input-inline">
                                <input type="checkbox" name="options[switch_main]" value="1" lay-skin="switch" title="开启|关闭" <?= $options['switch_main'] == 1 ? 'checked' : ''?>>
                            </div>
                            <div class="layui-form-mid layui-text-em">关闭后所有页面的下载链接将关闭显示</div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">显示模式</label>
                            <div class="layui-input-block">
                                <input type="radio" name="options[radio_displayType]" value="1" title="页内模式" <?= $options['radio_displayType'] == 1 ? 'checked' : ''?>>
                                <input type="radio" name="options[radio_displayType]" value="2" title="单页模式" <?= $options['radio_displayType'] == 2 ? 'checked' : ''?>>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">默认模式</label>
                            <div class="layui-input-inline">
                                <select name="options[select_defaultType]">
                                    <option value="0" <?= $options['select_defaultType'] == 0 ? 'selected' : ''?>>免费下载</option>
                                    <option value="1" <?= $options['select_defaultType'] == 1 ? 'selected' : ''?>>回复下载</option>
                                    <option value="2" <?= $options['select_defaultType'] == 2 ? 'selected' : ''?>>登录下载</option>
                                    <option value="3" <?= $options['select_defaultType'] == 3 ? 'selected' : ''?>>付费下载</option>
                                </select>
                            </div>
                            <div class="layui-form-mid layui-text-em">选择默认的下载模式</div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">标题显示</label>
                            <div class="layui-input-inline">
                                <input type="checkbox" name="options[switch_tips_title]" value="1" lay-skin="switch" title="开启|关闭" <?= $options['switch_tips_title'] == 1 ? 'checked' : ''?>>
                            </div>
                            <div class="layui-form-mid layui-text-em">下载按钮上方的【附件下载】标题显示开关</div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">自定义标题</label>
                            <div class="layui-input-inline">
                                <input type="text" name="options[tips_title_text]" value="<?=$options['tips_title_text']?>" placeholder="附件下载" class="layui-input">
                            </div>
                            <div class="layui-form-mid layui-text-em">自定义标题显示内容，默认【附件下载】</div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">数量限制</label>
                            <div class="layui-input-inline layui-input-group">
                                <input type="number" name="options[linkCount]" value="<?=$options['linkCount']?>" lay-affix="number" placeholder="" step="1" class="layui-input">
                                <div class="layui-input-suffix">个</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="layui-colla-item">
                    <div class="layui-colla-title">全局样式配置，包含（内页、单页双模式的样式）</div>
                    <div class="layui-colla-content">
                        <div class="layui-form-item">
                            <label class="layui-form-label">图标开关</label>
                            <div class="layui-input-inline">
                                <input type="checkbox" name="options[switch_button_icon]" value="1" lay-skin="switch" title="开启|关闭" <?= $options['switch_button_icon'] == 1 ? 'checked' : ''?>>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">按钮背景色</label>
                            <div class="layui-input-inline" style="display: flex;">
                                <input type="text" name="options[button_color]" value="<?=$options['button_color']?>" placeholder="请选择颜色" class="layui-input">
                                <div class="colorpicker" lay-options="{color: '<?=$options['button_color']?>'}"></div>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">按钮文字色</label>
                            <div class="layui-input-inline" style="display: flex;">
                                <input type="text" name="options[button_text_color]" value="<?=$options['button_text_color']?>" placeholder="请选择颜色" class="layui-input">
                                <div class="colorpicker" lay-options="{color: '<?=$options['button_text_color']?>'}"></div>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">按钮圆角</label>
                            <div class="layui-input-inline layui-input-group">
                                <input type="number" name="options[button_border_radius]" value="<?=$options['button_border_radius']?>" lay-affix="number" step="1" min="0" class="layui-input">
                                <div class="layui-input-suffix">px</div>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">边框色</label>
                            <div class="layui-input-inline" style="display: flex;">
                                <input type="text" name="options[reply_border_color]" value="<?=$options['reply_border_color']?>" placeholder="请选择颜色" class="layui-input">
                                <div class="colorpicker" lay-options="{color: '<?=$options['reply_border_color']?>'}"></div>
                            </div>
                            <div class="layui-form-mid layui-text-em">回复/登录下载模式有效</div>
                        </div>
                    </div>
                </div>
                <div class="layui-colla-item">
                    <div class="layui-colla-title">单页样式配置</div>
                    <div class="layui-colla-content">
                        <div class="layui-form-item">
                            <div class="layui-inline">
                                <label class="layui-form-label">背景渐变色</label>
                                <div class="layui-input-inline" style="display: flex;">
                                    <input type="text" name="options[page_background_color_1]" value="<?=$options['page_background_color_1']?>" placeholder="请选择颜色" class="layui-input">
                                    <div class="colorpicker" lay-options="{color: '<?=$options['page_background_color_1']?>'}"></div>
                                </div>
                                <div class="layui-form-mid">-</div>
                                <div class="layui-input-inline" style="display: flex;">
                                    <input type="text" name="options[page_background_color_2]" value="<?=$options['page_background_color_2']?>" placeholder="请选择颜色" class="layui-input">
                                    <div class="colorpicker" lay-options="{color: '<?=$options['page_background_color_2']?>'}"></div>
                                </div>
                            </div>
                        </div>
                        <div class="layui-form-item layui-form-text">
                            <label class="layui-form-label">下载说明</label>
                            <div class="layui-input-block">
                                <textarea name="options[download_des]" placeholder="请输入内容" class="layui-textarea" rows="8"><?=$options['download_des']?></textarea>
                                <div class="layui-form-mid layui-text-em">支持html代码</div>
                            </div>
                        </div>
                        <div class="layui-form-item layui-form-text">
                            <label class="layui-form-label">站长声明</label>
                            <div class="layui-input-block">
                                <textarea name="options[station_master_des]" placeholder="请输入内容" class="layui-textarea" rows="8"><?=$options['station_master_des']?></textarea>
                                <div class="layui-form-mid layui-text-em">支持html代码</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="layui-colla-item">
                    <div class="layui-colla-title">支付接口配置 <span class="layui-badge">NEW</span></div>
                    <div class="layui-colla-content">
                        <div class="layui-collapse" style="margin-bottom: 0;" lay-accordion>
                            <div class="layui-colla-item">
                                <div class="layui-colla-title">支付宝 <span class="layui-badge">NEW</span></div>
                                <div class="layui-colla-content layui-show">
                                    <blockquote class="layui-elem-quote layui-text" style="font-size: 12px;">
                                        <p>支付宝官网接口</p>
                                        <ul>
                                            <li>支付宝公钥为必填项目(公钥错误可以成功付款，但会回调失败)</li>
                                            <li>回调地址：<?= BLOG_URL ?></li>
                                            <!--<li>同时填写了企业支付以及当面付参数，则优先使用当面付</li>-->
                                            <li>申请地址：<a target="_blank" href="https://b.alipay.com/signing/productDetailV2.htm?productId=I1011000290000001003">点击跳转</a></li>
                                        </ul>
                                    </blockquote>
                                    <div class="layui-form-item layui-form-text">
                                        <label class="layui-form-label">支付宝公钥<p style="color: #999;font-size: 12px;white-space: nowrap;">publickey(必填)</p></label>
                                        <div class="layui-input-block">
                                            <textarea name="options[alipay][publickey]" placeholder="" class="layui-textarea" rows="3"><?=$options['alipay']['publickey']?></textarea>
                                        </div>
                                    </div>
                                    <blockquote class="layui-elem-quote layui-text" style="font-size: 12px;">
                                        <p>支付宝当面付：个人可申请，申请难度低</p>
                                        <ul>
                                            <li>支持PC端扫码支付</li>
                                            <li><b>支持移动端H5支付</b></li>
                                        </ul>
                                    </blockquote>
                                    <div class="layui-form-item layui-form-text">
                                        <label class="layui-form-label">当面付：APPID</label>
                                        <div class="layui-input-block">
                                            <input type="text" name="options[alipay][appid]" value="<?=$options['alipay']['appid']?>" placeholder="" class="layui-input">
                                        </div>
                                    </div>
                                    <div class="layui-form-item layui-form-text">
                                        <label class="layui-form-label">当面付：应用私钥<p style="color: #999;font-size: 12px;white-space: nowrap;">privatekey</p></label>
                                        <div class="layui-input-block">
                                            <textarea name="options[alipay][privatekey]" placeholder="" class="layui-textarea" rows="3"><?=$options['alipay']['privatekey']?></textarea>
                                        </div>
                                    </div>
                                    <!--<blockquote class="layui-elem-quote layui-text" style="font-size: 12px;">
                                        <p>支付宝企业支付：官方接口，商家可申请，需签约<b>电脑网站支付</b></p>
                                    </blockquote>
                                    <div class="layui-form-item layui-form-text">
                                        <label class="layui-form-label">网站应用：APPID</label>
                                        <div class="layui-input-block">
                                            <input type="text" name="options[alipay][webappid]" value="<?=$options['alipay']['webappid']?>" placeholder="" class="layui-input">
                                        </div>
                                    </div>
                                    <div class="layui-form-item layui-form-text">
                                        <label class="layui-form-label">网站应用：应用私钥<p style="color: #999;font-size: 12px;white-space: nowrap;">appPrivateKey</p></label>
                                        <div class="layui-input-block">
                                            <textarea name="options[alipay][webprivatekey]" placeholder="" class="layui-textarea" rows="3"><?=$options['alipay']['webprivatekey']?></textarea>
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label">开启H5支付</label>
                                        <div class="layui-input-inline">
                                            <input type="checkbox" name="options[alipay][switch_h5]" value="1" lay-skin="switch" title="开启|关闭" <?= $options['alipay']['switch_h5'] == 1 ? 'checked' : ''?>>
                                        </div>
                                        <div class="layui-form-mid layui-text-em">移动端自动跳转到支付宝APP支付，需签约手机网站支付</div>
                                    </div>-->
                                </div>
                            </div>
                            <!--<div class="layui-colla-item">
                                <div class="layui-colla-title">易支付 <span class="layui-badge">NEW</span></div>
                                <div class="layui-colla-content">
                                    <blockquote class="layui-elem-quote layui-text" style="font-size: 12px;">
                                        <ul>
                                            <li>易支付是一个常见的支付系统源码，任何人都可以下载并搭建</li>
                                            <li>同时市面上多数的小规模支付平台都是由易支付修改而来，例如部分码支付、源支付等等</li>
                                            <li style="color: red;">由于此接口服务商众多，主题只负责技术接入，平台可靠性请自行斟酌</li>
                                        </ul>
                                    </blockquote>
                                    <div class="layui-form-item layui-form-text">
                                        <label class="layui-form-label">API接口网址</label>
                                        <div class="layui-input-block">
                                            <input type="text" name="options[epay][apiurl]" value="<?=$options['epay']['apiurl']?>" placeholder="" class="layui-input">
                                            <div class="layui-form-mid layui-text-em">请填写接口地址，例如：https://pay.xxxxxxx.cn/</div>
                                        </div>
                                    </div>
                                    <div class="layui-form-item layui-form-text">
                                        <label class="layui-form-label">商户号</label>
                                        <div class="layui-input-block">
                                            <input type="text" name="options[epay][partner]" value="<?=$options['epay']['partner']?>" placeholder="" class="layui-input">
                                        </div>
                                    </div>
                                    <div class="layui-form-item layui-form-text">
                                        <label class="layui-form-label">商户秘钥</label>
                                        <div class="layui-input-block">
                                            <input type="text" name="options[epay][key]" value="<?=$options['epay']['key']?>" placeholder="" class="layui-input">
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label">PC端扫码支付</label>
                                        <div class="layui-input-inline">
                                            <input type="checkbox" name="options[alipay][switch_qrcode]" value="1" lay-skin="switch" title="开启|关闭" <?= $options['alipay']['switch_qrcode'] == 1 ? 'checked' : ''?>>
                                        </div>
                                        <div class="layui-form-mid layui-text-em">PC端免跳转直接扫码支付（需要接口以及接入方式支持，开启后如果出现请求失败，请关闭）</div>
                                    </div>
                                </div>
                            </div>-->
                        </div>
                    </div>
                </div>
                <div class="layui-colla-item">
                    <div class="layui-colla-title">广告位-ID-1</div>
                    <div class="layui-colla-content">
                        <div class="layui-form-item layui-form-text">
                            <label class="layui-form-label">文字</label>
                            <div class="layui-input-block">
                                <textarea name="options[ad_text_1]" placeholder="请输入内容" class="layui-textarea" rows="8"><?=$options['ad_text_1']?></textarea>
                                <div class="layui-form-mid layui-text-em">1x5，支持html代码，例子：<span>&lt;a href=&quot;javascript: window.open('跳转地址');&quot;&gt;火爆广告位招租！！！&lt;/a&gt;</span></div>
                            </div>
                        </div>
                        <div class="layui-form-item layui-form-text">
                            <label class="layui-form-label">图片</label>
                            <div class="layui-input-block">
                                <textarea name="options[ad_pic_1]" placeholder="请输入内容" class="layui-textarea" rows="8"><?=$options['ad_pic_1']?></textarea>
                                <div class="layui-form-mid layui-text-em">1x1，支持html代码，例子：&lt;a href=&quot;javascript: window.open('跳转链接');&quot;&gt;&lt;img src=&quot;https://s1.ax1x.com/2023/03/26/ppsF0AK.png&quot;&gt;&lt;/a&gt;</div>
                            </div>
                        </div>
                        <div class="layui-form-item layui-form-text">
                            <label class="layui-form-label">图片</label>
                            <div class="layui-input-block">
                                <textarea name="options[ad_pic_bisection_1]" placeholder="请输入内容" class="layui-textarea" rows="8"><?=$options['ad_pic_bisection_1']?></textarea>
                                <div class="layui-form-mid layui-text-em">1x2，支持html代码，例子：&lt;a href=&quot;javascript: window.open('跳转地址');&quot;&gt;&lt;img src=&quot;https://s1.ax1x.com/2023/03/26/ppsFd76.png&quot;&gt;&lt;/a&gt;</div>
                            </div>
                        </div>
                        <div class="layui-form-item layui-form-text">
                            <label class="layui-form-label">图片</label>
                            <div class="layui-input-block">
                                <textarea name="options[ad_pic_block_1]" placeholder="请输入内容" class="layui-textarea" rows="8"><?=$options['ad_pic_block_1']?></textarea>
                                <div class="layui-form-mid layui-text-em">1x6，支持html代码，例子：&lt;a href=&quot;javascript: window.open('跳转地址');&quot;&gt;&lt;img src=&quot;https://s1.ax1x.com/2023/03/26/ppskavj.png&quot;&gt;&lt;/a&gt;</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="layui-colla-item">
                    <div class="layui-colla-title">广告位-ID-2</div>
                    <div class="layui-colla-content">
                        <div class="layui-form-item layui-form-text">
                            <label class="layui-form-label">文字</label>
                            <div class="layui-input-block">
                                <textarea name="options[ad_text_2]" placeholder="请输入内容" class="layui-textarea" rows="8"><?=$options['ad_text_2']?></textarea>
                                <div class="layui-form-mid layui-text-em">1x5，支持html代码，例子：<span>&lt;a href=&quot;javascript: window.open('跳转地址');&quot;&gt;火爆广告位招租！！！&lt;/a&gt;</span></div>
                            </div>
                        </div>
                        <div class="layui-form-item layui-form-text">
                            <label class="layui-form-label">图片</label>
                            <div class="layui-input-block">
                                <textarea name="options[ad_pic_2]" placeholder="请输入内容" class="layui-textarea" rows="8"><?=$options['ad_pic_2']?></textarea>
                                <div class="layui-form-mid layui-text-em">1x1，支持html代码，例子：&lt;a href=&quot;javascript: window.open('跳转链接');&quot;&gt;&lt;img src=&quot;https://s1.ax1x.com/2023/03/26/ppsF0AK.png&quot;&gt;&lt;/a&gt;</div>
                            </div>
                        </div>
                        <div class="layui-form-item layui-form-text">
                            <label class="layui-form-label">图片</label>
                            <div class="layui-input-block">
                                <textarea name="options[ad_pic_bisection_2]" placeholder="请输入内容" class="layui-textarea" rows="8"><?=$options['ad_pic_bisection_2']?></textarea>
                                <div class="layui-form-mid layui-text-em">1x2，支持html代码，例子：&lt;a href=&quot;javascript: window.open('跳转地址');&quot;&gt;&lt;img src=&quot;https://s1.ax1x.com/2023/03/26/ppsFd76.png&quot;&gt;&lt;/a&gt;</div>
                            </div>
                        </div>
                        <div class="layui-form-item layui-form-text">
                            <label class="layui-form-label">图片</label>
                            <div class="layui-input-block">
                                <textarea name="options[ad_pic_block_2]" placeholder="请输入内容" class="layui-textarea" rows="8"><?=$options['ad_pic_block_2']?></textarea>
                                <div class="layui-form-mid layui-text-em">1x6，支持html代码，例子：&lt;a href=&quot;javascript: window.open('跳转地址');&quot;&gt;&lt;img src=&quot;https://s1.ax1x.com/2023/03/26/ppskavj.png&quot;&gt;&lt;/a&gt;</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div style="text-align: center;">
                <button type="submit" class="layui-btn layui-btn-sm layui-btn-normal" lay-submit>保存</button>
                <button type="reset" class="layui-btn layui-btn-sm layui-btn-primary">重置</button>
                <button type="button" class="layui-btn layui-btn-sm layui-btn-danger" id="default-config">恢复默认配置</button>
            </div>
        </form>
    </div>
</div>
<script>
layui.use(function() {

    var $               = layui.jquery,
        form            = layui.form,
        layer           = layui.layer,
        colorpicker     = layui.colorpicker;

    //提交配置
    form.on('submit', function(data) {
        $.get('plugin.php?plugin=<?=TOEVER_DOWNPAGE_NAME?>&route=admin_config', data.field, function(res){
            console.log(res);
            if(res.code == 0){
                layer.msg('保存成功');
            }
        });
        return false;
    });

    //渲染颜色选择器
    colorpicker.render({
        elem: '.colorpicker',
        done: function(color){
            $(this.elem).siblings('input').val(color);
        }
    });
    
    $('#default-config').click(function() {
        layer.confirm('恢复后将清空全部配置恢复到默认状态，请保存好重要配置信息，是否执行恢复？', {
            btn: ['确定', '关闭']
        }, function(){
            $.get('plugin.php?plugin=<?=TOEVER_DOWNPAGE_NAME?>&route=admin_config_default_api', function(res){
                if(res.code == 0){
                    layer.msg('恢复成功');
                }
            });
        });
    });
});
</script>
<?php require_once toEverDownPage::getInstance()->view_path('admin_footer')?>