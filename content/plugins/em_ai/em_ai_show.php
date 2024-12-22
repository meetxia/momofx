<?php
defined('EMLOG_ROOT') || exit('access denied!');

/*
 * 插件前台展示页面
 * 前台显示地址为：https://yourdomain/?plugin=tips
 */

$acion = Input::getStrVar('action');

if ($acion === 'write') {
    if (!ISLOGIN) {
        Output::error('请先登录', 200);
    }
    $sid = Input::getIntVar('sid');
    $plugin_storage = Storage::getInstance('em_ai');
    $model = $plugin_storage->getValue('model');

    $Sort_Model = new Sort_Model();
    $sorts = $Sort_Model->getSorts();

    if (empty($sorts)) {
        Output::error('还未创建文章分类', 200);
    }

    $random_key = array_rand($sorts);

    if ($sid) {
        foreach ($sorts as $key => $value) {
            if ($value['sid'] == $sid) {
                $random_key = $key;
                break;
            }
        }
    }

    // 使用随机键获取对应的值
    $sort = $sorts[$random_key];

    $id = $sort['sid'];
    $name = $sort['sortname'];
    $description = $sort['description'];
    $kw = $sort['kw'];

    // 写文章
    $prompt = '请在文章分类 ' . $name . ' 中写一篇关于' . $description . '，关键词：' . $kw . '，的内容，请选取一个具体的案例、事物、故事，详细说明其背景、起因、经过和结果，要求段落清晰，易读，又不拘泥于特定格式，返回markdown格式';
    $data = EmAI::getInstance()->write($model, $prompt);
    $content = isset($data['result']) ? $data['result'] : '';
    if (empty($content)) {
        Output::error('创作文章失败，请检查插件设置或者网络', 200);
    }

    // 生成标题
    $data = EmAI::getInstance()->write($model, '为冒号后面的内容生成一个简洁吸引人的标题，不超过100个汉字' . $content);
    $title = isset($data['result']) ? $data['result'] : '这是文章默认标题';

    // 生成摘要
    $origContent = trim($content);
    $parseDown = new Parsedown();
    $excerpt = $parseDown->text($origContent);
    $excerpt = extractHtmlData($excerpt, 180);
    $excerpt = str_replace(["\r", "\n", "'", '"'], ' ', $excerpt);

    $logData = [
        'title'   => extractHtmlData(trim($title, "*#"), 120),
        'content' => $content,
        'excerpt' => $excerpt,
        'author'  => 1,
        'sortid'  => $id,
        'cover'   => '',
        'date'    => time(),
        'hide'    => 'n',
    ];

    $Log_Model = new Log_Model();
    $article_id = $Log_Model->addlog($logData);
    Output::ok($article_id);
}

if ($acion === 'text2img') {
    if (!ISLOGIN) {
        Output::error('请先登录', 200);
    }
    $prompt = Input::postStrVar('prompt');
    $file_path = EmAI::getInstance()->text2image($prompt);
    if (!$file_path) {
        Output::error('生成图片失败，请检查插件设置或者网络', 200);
    }

    $file_size = filesize(substr($file_path, 1));
    if ($file_size <= 0) {
        Output::error('生成图片失败', 200);
    }

    // 添加到媒体库
    $file_info = [
        'file_name' => basename($file_path),
        'size'      => $file_size,
        'file_path' => $file_path,
        'mime_type' => 'image/png',
        'width'     => 768,
        'height'    => 768,
    ];
    $Media_Model = new Media_Model();
    $Media_Model->addMedia($file_info, 0);

    $file_url = getFileUrl($file_path);
    Output::ok($file_url);
}
