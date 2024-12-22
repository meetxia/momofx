<?php
/**
 * 加密文章输入密码页面
 */
defined('EMLOG_ROOT') || exit('access denied!');
?>
<!doctype html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>请输入文章访问密码</title>
    <style>
        body{
            margin: 0px;
        }
        body {
            font-family: helvetica neue, Helvetica, Arial, sans-serif;
            position: relative;
            width: 100%;
            background: #fed6e3;
            overflow: hidden;
            z-index: 1;
            height: 100%;
            max-height: 100%;
        }
        .text-center {
            text-align: center !important;
        }
        .meow_style_logo {
            height: 80px;
            background-image: url( <?php echo _g('logo');?>);
            width: 180px;
            max-height: 100px;
            margin: 20px auto 15px;
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center center;
        }
        .card {
            margin-top: 3rem !important;
            margin-bottom: 3rem !important;
            position: relative;
            display: flex;
            flex-direction: column;
            min-width: 0;
            word-wrap: break-word;
            background-color: #fff;
            background-clip: border-box;
            border: 1px solid #e3e6f0;
            border-radius: 0.35rem;
            width: 20%;
            margin: 0 auto;
        }
        .card-body {
            flex: 1 1 auto;
            min-height: 1px;
            padding: 1.25rem;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        form #logpwd {
            border-radius: 0.35rem;
            font-size: 1rem;
        }

        .form-control {
            height: 30px;
            display: block;
            width: 80%;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
            color: #6e707e;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #d1d3e2;
            border-radius: 0.35rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }
        .form-control:focus {
            color: #6e707e;
            background-color: #fff;
            border-color: #bac8f3;
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }
        .btn {
            border: 1px solid transparent;
            vertical-align: middle;
            color: #ffffff;
            background-color: #2271b1;
            text-align: center;
            line-height: 1.5;
            transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }
        .btn:not(:disabled):not(.disabled) {
            cursor: pointer;
        }
        .btn-block {
            display: block;
            width: 100%;
        }
        .btn-user {
            border-radius: 0.35rem;
            font-size: 1rem;
            padding: 0.5rem 1rem;
        }
        a{
            text-decoration:none;
        }
        a:hover {
            color: #224abe;
            text-decoration: underline;
        }

        small, .small {
            font-size: 80%;
            font-weight: 400;
        }
        hr {
            margin-top: 1rem;
            margin-bottom: 1rem;
            border: 0;
            border-top: 1px solid rgba(0, 0, 0, 0.1);
        }
        @media (max-width: 768px) {
            .card{
                width: 100%;
                padding-left: 1.5rem;
                padding-right: 1.5rem;
                margin-right: auto;
                margin-left: auto;
            }
        }
    </style>
</head>
<body>
<div class="card">
<div class="card-body">
<form action="" method="post" class="input-group">
    <div class="text-center">
        <div class="meow_style_logo"></div>
    </div>
        <div class="form-group">
            <input type="password" class="form-control" id="logpwd" placeholder="请输入密码查看隐藏内容" name="logpwd" required autofocus>

        </div>
        <div class="form-group">
            <button type="submit" id="btn" class="btn btn-primary btn-user btn-block">确定</button>

        </div>
    <hr>
    <a class="small" href="<?= BLOG_URL ?>">&larr;返回首页</a>
</form>
</div>
</div>
</body>
</html>


