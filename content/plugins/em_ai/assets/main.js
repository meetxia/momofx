let partialData = '';
let aiSystem = "你是我的写作助手，擅长内容创作和改进。";

// 生成文章
function generateArticle(token, temper, api) {
    const title = document.getElementById('title').value;
    if (title.trim() === '') {
        alert("请先输入文章的标题");
        return
    }
    let prompt;
    if (useHtmlEditor()) {
        prompt = title + "\n\n根据以上标题，生成一篇段落清晰的文章，输出使用 html 排版。";
    } else {
        prompt = title + "\n\n根据以上标题, 生成一篇段落清晰的文章，使用 Markdown 对内容进行格式化，让内容排版更优美，仅输出修改后的结果，避免输出提示性内容。";
    }

    const data = {
        messages: [
            {
                role: "user",
                content: prompt,
            }
        ],
        temperature: temper,
        system: aiSystem,
        stream: true
    };

    fetchData(api + '?access_token=' + token, {
        method: 'POST',
        body: JSON.stringify(data)
    }, 'content');
}

// 二次创作
function regenerateArticle(token, temper, api) {
    let content = getContent();
    let prompt;
    if (useHtmlEditor()) {
        prompt = content + "\n\n润色以上文章内容，补充必要的细节，让文章段落更清晰，可读性更强，输出使用 html 排版。";
    } else {
        prompt = content + "\n\n润色以上文章内容，补充必要的细节，让文章段落更清晰，可读性更强，使用 Markdown 对内容进行格式化，让内容排版更优美，仅输出修改后的结果，避免输出提示性内容。";
    }

    const data = {
        messages: [
            {
                role: "user",
                content: prompt,
            }
        ],
        temperature: temper,
        system: aiSystem,
        stream: true
    };

    fetchData(api + '?access_token=' + token, {
        method: 'POST',
        body: JSON.stringify(data)
    }, 'content');
}

// 生成标题
function genTitle(token) {
    let content = getContent();
    let prompt = "===" + content + "===, 为3个等于号包裹的文章内容写一个吸引人的标题，保持单行输出，剔除html标签，不超过100个字。";

    const data = {
        messages: [
            {
                role: "user",
                content: prompt,
            }
        ],
        system: aiSystem,
        stream: true
    };

    fetchData('https://aip.baidubce.com/rpc/2.0/ai_custom/v1/wenxinworkshop/chat/eb-instant?access_token=' + token, {
        method: 'POST',
        body: JSON.stringify(data)
    }, 'title');
}

// 生成摘要
function refineArticle(token) {
    let content = getContent();
    let prompt = "===" + content + "===, 为3个等于号包裹的文章内容写一个吸引人的摘要，保持单行输出，剔除html标签，不超过300个字。";

    const data = {
        messages: [
            {
                role: "user",
                content: prompt,
            }
        ],
        system: aiSystem,
        stream: true
    };

    fetchData('https://aip.baidubce.com/rpc/2.0/ai_custom/v1/wenxinworkshop/chat/eb-instant?access_token=' + token, {
        method: 'POST',
        body: JSON.stringify(data)
    }, 'summary');
}

function genTags(token) {
    let content = getContent();
    let prompt = "===" + content + "===, 从三个等于号包裹的文章内容中提取三个关键词，输出 json 格式，不输出关键词之外的文字。";

    const data = {
        messages: [
            {
                role: "user",
                content: prompt,
            }
        ],
        system: aiSystem,
        stream: true
    };

    fetchData('https://aip.baidubce.com/rpc/2.0/ai_custom/v1/wenxinworkshop/chat/eb-instant?access_token=' + token, {
        method: 'POST',
        body: JSON.stringify(data)
    }, 'tags');
}

function genCover(token) {
    let apiUrl = "../?plugin=em_ai&action=text2img";
    let prompt = $('#logexcerpt').val();
    if (prompt.trim() === '') {
        alert("请先生成文章摘要，依赖摘要生成封面图片");
        return
    }

    $('#em_ai_message').html('<div class="alert alert-primary"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> 图片生成中...</div>');

    $.ajax({
        url: apiUrl,
        data: {
            prompt: prompt
        },
        method: 'POST',
        success: function (response) {
            if (response.code === 0) {
                $('#cover_image').attr('src', response.data);
                $('#cover').val(response.data);
                $('#em_ai_message').html('<div class="alert alert-success">封面生成成功，并已加入资源媒体库</div>');
            } else {
                $('#em_ai_message').html('<div class="alert alert-danger">生成失败: ' + response.msg + '</div>');
            }
        },
        error: function () {
            $('#em_ai_message').html('<div class="alert alert-danger">接口请求失败</div>');
        }
    });
}

function fetchData(url, options, type) {
    fetch(url, options)
        .then(response => {
            const reader = response.body.getReader();

            if (type === "content") {
                if (typeof tinymce !== 'undefined') {
                    tinymce.get(emlog_tinymce_area.id).setContent("")
                } else if (typeof logcontent_wangeditor !== 'undefined') {
                    logcontent_wangeditor.clear()
                } else if (typeof KindEditor !== 'undefined') {
                    KindEditor.html('#lanye_editor_content textarea', '');
                } else if (typeof monie_vditor !== 'undefined') {
                    monie_vditor.insertValue('', true)
                } else {
                    Editor.setValue("");
                }
            }

            if (type === "summary") {
                $("#logexcerpt").val("")
            }

            if (type === "tags") {
                $("#tag").val("")
            }

            if (type === "title") {
                $("#title").val("")
            }

            // 读取流式数据
            function read() {
                return reader.read().then(({value, done}) => {
                    if (done) {
                        if (type === "tags") {
                            const originalString = $("#tag").val()
                            const jsonRegex = /```json(.+?)```/;
                            // 使用正则表达式匹配并提取 JSON 部分
                            const match = originalString.match(jsonRegex);
                            if (match && match[1]) {
                                const jsonString = match[1];
                                const jsonData = JSON.parse(jsonString);
                                const keywordsArray = jsonData["关键词"];
                                const keywordsString = keywordsArray.join(', ');
                                $("#tag").val(keywordsString)
                            } else {
                                alert("未找到合适的关键词")
                            }
                        }
                        return;
                    }

                    // 将数据转换为字符串
                    const chunk = new TextDecoder().decode(value);
                    const jsonMatch = chunk.match(/data:\s*(\{.*\})/);

                    if (jsonMatch) {
                        const jsonData = JSON.parse(jsonMatch[1].trim());
                        const resultContent = jsonData.result;

                        if (type === "content") {
                            if (typeof logcontent_wangeditor !== 'undefined') {
                                logcontent_wangeditor.dangerouslyInsertHtml(resultContent)
                            } else if (typeof KindEditor !== 'undefined') {
                                KindEditor.insertHtml('#lanye_editor_content textarea', resultContent);
                            } else if (typeof monie_vditor !== 'undefined') {
                                monie_vditor.insertValue(resultContent, true)
                            } else {
                                Editor.insertValue(resultContent)
                            }
                        }

                        if (type === "summary") {
                            var $summary = $("#logexcerpt");
                            var v = $summary.val();
                            $summary.val(v + resultContent);
                        }
                        if (type === "tags") {
                            var $tag = $("#tag");
                            var tagValue = $tag.val();
                            $tag.val(tagValue + resultContent);
                        }
                        if (type === "title") {
                            var $title = $("#title");
                            var titleValue = $title.val();
                            $title.val(titleValue + resultContent);
                        }
                    } else {
                        console.log('无法提取有效的JSON数据。');
                    }
                    read();
                });
            }

            // 开始读取流式数据
            read();
        })
        .catch(error => console.error('Error:', error));
}

function useHtmlEditor() {
    return (typeof tinymce !== 'undefined') || (typeof logcontent_wangeditor !== 'undefined');
}

function getContent() {
    let content;

    if (typeof logcontent_wangeditor !== 'undefined') {
        content = logcontent_wangeditor.getHtml();
    } else {
        content = Editor.getValue();
    }

    if (content.trim() === '') {
        alert("文章内容不能为空");
        return null;
    }

    return content;
}