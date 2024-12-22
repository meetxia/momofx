<?php
/*
Plugin Name: wangeditor
Version: 1.1.3
Plugin URL: https://www.emlog.net/plugin/detail/650
Description: 开源 Web 富文本编辑器，开箱即用，配置简单
Author: yu_十三
Author URL: https://www.emlog.net/author/index/4742
*/
function wangeditor_link()
{ ?>
  <style>
    #monie_logcontent {
      border: 1px solid #ccc;
      z-index: 100;
    }

    #monie_logcontent-toolbar {
      border-bottom: 1px solid #ccc;
    }

    #monie_logcontent-editor {
      height: 500px;
    }
  </style>
  <link href="<?= BLOG_URL ?>content/plugins/wangeditor/assets/libs/wangeditor/wangeditor.css" rel="stylesheet">
<?php } ?>
<?php
function wangeditor_script()
{ ?>

  <script src="<?= BLOG_URL ?>admin/editor.md/lib/marked.min.js"></script>
  <!-- <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script> -->
  <script src="<?= BLOG_URL ?>content/plugins/wangeditor/assets/libs/wangeditor/wangeditor.js"></script>
  <script src="<?= BLOG_URL ?>content/plugins/wangeditor/assets/js/config.js"></script>
<?php } ?>
<?php
addAction('adm_head', 'wangeditor_link');
addAction('adm_footer', 'wangeditor_script');
