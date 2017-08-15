<style>
    .li a{color: #ff0000}
</style>
</head>
<body>
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <ul class="nav navbar-nav">
                <img src="/public/image/home.jpg" class="navbar-brand">
                <?php echo anchor('home', '后台管理', array('title' => '后台管理','class' => 'navbar-brand')); ?>

            </ul>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
                <li><?php echo anchor('home', "欢迎：".$_SESSION['LoginUser']); ?></li>
                <li><?php echo anchor('login/logout', '登出', array('title' => '登出')); ?></li>
                <li><?php echo anchor('home', '本网站', array('title' => '本网站')); ?></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-3 col-md-2 sidebar" style="background-color: #080808">
            <ul class="nav nav-sidebar">
                <li style="font-size: 16px" class="li"><?php echo anchor('article/createArticle', '新增文章', array('title' => '新建文章',)); ?></li>
                <li style="font-size: 16px" class="li"><?php echo anchor('article', '所有文章', array('title' => '所有文章',)); ?></li>
                <li style="font-size: 16px" class="li"><?php echo anchor('category', '分类标签', array('title' => '分类目录',)); ?></li>
                <li style="font-size: 16px" class="li"><?php echo anchor('user', '用户管理', array('title' => '个人资料',)); ?></li>
                <li style="font-size: 16px" class="li"><?php echo anchor('links', '链接管理', array('title' => '链接管理',)); ?></li>
                <li style="font-size: 16px" class="li"><?php echo anchor('about', '“关于”设置', array('title' => '关于设置',)); ?></li>
                <li style="font-size: 16px" class="li"><a href="http://www.wumii.com/site/cw/index" target="_blank">评论管理</a></li>
            </ul>
        </div>