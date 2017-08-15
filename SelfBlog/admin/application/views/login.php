<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>SkyeInfo · Blog</title>
    <link rel="stylesheet" type="text/css" href="/public/admin/css/normalize.css" />
    <link rel="stylesheet" type="text/css" href="/public/admin/css/htmleaf-demo.css">
    <link rel="icon" href="/public/image/bro_ico.png">
    <style type="text/css">
        .login-page {
            width: 360px;
            padding: 5% 0 0;
            margin: auto;
        }
        .form {
            position: relative;
            z-index: 1;
            background: #FFFFFF;
            max-width: 360px;
            margin: 0 auto 100px;
            padding: 45px;
            text-align: center;
            box-shadow: 0 0 20px 0 rgba(0, 0, 0, 0.2), 0 5px 5px 0 rgba(0, 0, 0, 0.24);
        }
        .form input {
            font-family: "Roboto", sans-serif;
            outline: 0;
            background: #f2f2f2;
            width: 100%;
            border: 0;
            margin: 0 0 15px;
            padding: 15px;
            box-sizing: border-box;
            font-size: 14px;
        }
        .form button {
            font-family: "Microsoft YaHei","Roboto", sans-serif;
            text-transform: uppercase;
            outline: 0;
            background: #808080;
            width: 100%;
            border: 0;
            padding: 15px;
            color: #FFFFFF;
            font-size: 18px;
            -webkit-transition: all 0.3 ease;
            transition: all 0.3 ease;
            cursor: pointer;
        }
        .form button:hover,.form button:active,.form button:focus {
            background: #43A047;
        }
        .form .message a {
            color: #4CAF50;
            text-decoration: none;
        }
        .container .info h1 {
            margin: 0 0 15px;
            padding: 0;
            font-size: 36px;
            font-weight: 300;
            color: #1a1a1a;
        }
        .container .info span {
            color: #4d4d4d;
            font-size: 12px;
        }
        .container .info span a {
            color: #000000;
            text-decoration: none;
        }
        body {
            background: #000000; /* fallback for old browsers */
            background: -webkit-linear-gradient(right, #000000, #696969);
            background: -moz-linear-gradient(right, #000000, #696969);
            background: -o-linear-gradient(right, #000000, #696969);
            background: linear-gradient(to left, #000000, #696969);
            font-family: "Roboto", sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        @-webkit-keyframes shake {
            from, to {
                -webkit-transform: translate3d(0, 0, 0);
                transform: translate3d(0, 0, 0);
            }

            10%, 30%, 50%, 70%, 90% {
                -webkit-transform: translate3d(-10px, 0, 0);
                transform: translate3d(-10px, 0, 0);
            }

            20%, 40%, 60%, 80% {
                -webkit-transform: translate3d(10px, 0, 0);
                transform: translate3d(10px, 0, 0);
            }
        }

        @keyframes shake {
            from, to {
                -webkit-transform: translate3d(0, 0, 0);
                transform: translate3d(0, 0, 0);
            }

            10%, 30%, 50%, 70%, 90% {
                -webkit-transform: translate3d(-10px, 0, 0);
                transform: translate3d(-10px, 0, 0);
            }

            20%, 40%, 60%, 80% {
                -webkit-transform: translate3d(10px, 0, 0);
                transform: translate3d(10px, 0, 0);
            }
        }
        p.center{
            color: #fff;font-family: "Microsoft YaHei";
        }
    </style>
    <!--[if IE]><script src="https://cdn.bootcss.com/html5shiv/3.7.3/html5shiv.min.js"></script><![endif]-->
</head>
<body>
<div class="htmleaf-container">
    <header class="skye-header">
        <br><br><br>
        <span style="font-size: 50px">SkyeInfo · Blog</span>
        <br><br>
        <div>
            <a href="http://www.skyeinfo.com/" target="_blank"><img src="/public/image/skyeweb.png" width="60" height="60" title="个人主页"></a>
        </div>
    </header>
    <div id="wrapper" class="login-page">
        <div id="login_form" class="form">
            <form class="login-form" action="index.php?/login/checkAcc" id="loginform" method="post" accept-charset="utf-8">
                <input type="text" name="UserName" placeholder="用户名" id="userName" required autofocus/>
                <input type="password" name="Password" placeholder="密码" id="password" required/>
                <button id="login">登　录</button>
            </form>
            <div id="msg" style="color: red" >
                <?php
                if($this->session->userdata('msg')) {
                    echo $this->session->userdata('msg');
                }
                ?>
            </div>
        </div>
    </div>
</div>
</body>
</html>