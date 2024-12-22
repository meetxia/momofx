var logcontent_wangeditor = null
var logcontent_toolbar = null
$(function () {
  // 笔记
  if ($('textarea[name="t"]').length > 0) return
  // 文章
  if (typeof Editor != 'undefined' && window.location.pathname.indexOf('article.php') != -1) {
    let logcontent_wrapper = $(`<div id="monie_logcontent">
  <div id="monie_logcontent-toolbar"><!-- 工具栏 --></div>
  <div id="monie_logcontent-editor"><!-- 编辑器 --></div>
  <textarea name="logcontent" id="logcontent" style="display: none"></textarea>
</div>
`)

    let editor_content = $('#logcontent textarea').val()
    // console.log(editor_content, 'editor_content')

    // 简要可能只是一段介绍 官方的 md 就好 简单方便
    let editor_excerpt = $('#logexcerpt textarea').val()

    $('#logcontent').after(logcontent_wrapper)

    Editor.editor.remove()

    const { createEditor, createToolbar, } = window.wangEditor

    // logcontent 配置
    const logcontent_editorConfig = {
      MENU_CONF: {},
      placeholder: 'Type here...',
      onChange (logcontent_wangeditor) {
        const html = logcontent_wangeditor.getHtml()
        // 也可以同步到 <textarea>
        $('#logcontent').val(html)
      }
    }
    logcontent_editorConfig.MENU_CONF['uploadImage'] = {
      server: '/admin/media.php?action=upload&editor=1',
      fieldName: 'editormd-image-file',
      allowedFileTypes: ['image/*'],
      customInsert (res, insertFn) {
        // res 即服务端的返回结果
        console.log(res)
        let url = res.url
        let href = res.href
        let alt = res.file_info.file_name
        // 从 res 中找到 url alt href ，然后插入图片
        insertFn(url, alt, href)
      },
      // customBrowseAndUpload (insertFn) {
      //   $('a[data-target="#mediaModal"]').click()
      // },
    }


    logcontent_wangeditor = createEditor({
      selector: '#monie_logcontent-editor',
      html: '<p><br></p>',
      config: logcontent_editorConfig,
      mode: 'default', // or 'simple'
    })
    const logcontent_toolbarConfig = {
      excludeKeys: ['uploadVideo']
    }
    logcontent_toolbar = createToolbar({
      editor: logcontent_wangeditor,
      selector: '#monie_logcontent-toolbar',
      config: logcontent_toolbarConfig,
      mode: 'default', // or 'simple'
    })
   logcontent_wangeditor.clear()
  // 保障用户html格式是否正确 都采用dangerouslyInsertHtml 
  
    // 解决 editor 初始化 报错
    setTimeout(function() {
        dangerouslyInsertHtml(editor_content)
    }, 300);
  }


})

function insert_media_img (fileurl, filethumurl) {
  dangerouslyInsertHtml(`<p><img src="${fileurl}"  data-href="${fileurl}" /></p>`)
  cocoMessage.success('图片插入编辑器成功!')
}

function insert_media (fileurl, filename) {
  if (fileurl.indexOf('.mp3') != -1 || fileurl.indexOf('.m4a') != -1) {
    dangerouslyInsertHtml('<audio src="' + fileurl + '" controls width="100%"></audio>')
    cocoMessage.success('音乐插入编辑器成功!')

  } else {
    dangerouslyInsertHtml('<a href="' + fileurl + '" target="_blank">' + filename + '</a>')
    cocoMessage.success('附件插入编辑器成功!')
  }
}

function insert_media_audio (fileurl) {
  dangerouslyInsertHtml('<audio src="' + fileurl + '" controls width="100%"></audio>')
  cocoMessage.success('音乐插入编辑器成功!')
}

function insert_media_video (fileurl) {
  dangerouslyInsertHtml('<video src="' + fileurl + '" controls width="100%"></video>')
  cocoMessage.success('音乐插入编辑器成功!')
}

/**
 * 暴露外部插入内容
 * @param html {string} https://www.wangeditor.com/v5/API.html#dangerouslyinserthtml
 * @param callback {function} 回调函数 
 * */
var dangerouslyInsertHtml = (html, callback) => {
  logcontent_wangeditor.focus() // 需要先 focus 聚焦
  logcontent_wangeditor.dangerouslyInsertHtml(html)

  typeof callback == 'function' && callback()
}

//去除字符串中的 html 标签
function delHtmlTag (html) {
  return html.replace(/<[^>]+>/g, "")
}