<?php

/* @var $this yii\web\View */

use app\assets\AppAsset;
use yii\helpers\Url;

$this->title = 'Search';
$this->params['breadcrumbs'][] = $this->title;
AppAsset::register($this);
AppAsset::addCss($this, Yii::$app->request->baseUrl . "/css/bootstrap-datetimepicker.min.css");
AppAsset::addScript($this, Yii::$app->request->baseUrl . "/javascript/bootstrap-datetimepicker.min.js");
AppAsset::addScript($this, Yii::$app->request->baseUrl . "/javascript/bootstrap-datetimepicker.zh-CN.js");
?>
<div class="site-search-top">
    <form class="search-form">
        <div class="row">
            <div class="col-md-3 thumbnail">
                <div class="input-group">
                    <div class="input-group-btn">
                        <button name="index_button" type="button" class="btn btn-default dropdown-toggle"
                                data-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false"><span id="selected_index">Index</span> <span class="caret"></span>
                        </button>
                        <ul id="index-ul" class="dropdown-menu">
                            <li>
                                <input id="index-input" class="form-control" type="text" placeholder="查询Index"/>
                            </li>
                            <?php foreach ($index as $k => $value) { ?>
                                <li><a href="#"><?= $value ?></a></li>
                            <?php } ?>
                            <!--<li role="separator" class="divider"></li>-->
                        </ul>
                    </div>
                </div>
            </div>
            <div id="field_container" class="col-md-3 thumbnail">
                <div class="field_div input-group">
                    <div class="btn-group">
                        <button name="field_button" type="button" class="btn btn-default dropdown-toggle"
                                data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false"><span>字段</span> <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <!-- <li><a href="#">Separated link</a></li>-->
                        </ul>
                    </div>
                    <div class="btn-group">
                        <button name="filter_button" type="button" class="btn btn-default dropdown-toggle"
                                data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false"><span>条件</span> <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a href="#">=</a></li>
                            <li><a href="#"><</a></li>
                            <li><a href="#">></a></li>
                            <li><a href="#">>=</a></li>
                            <li><a href="#"><=</a></li>
                            <li><a href="#">like</a></li>
                            <li><a href="#">between</a></li>
                            <!-- <li><a href="#">must</a></li>
                             <li><a href="#">filter</a></li>
                            <li><a href="#">must_not</a></li>
                             <li><a href="#">should</a></li>
                             <li><a href="#">wildcard</a></li>
                             <li><a href="#">range</a></li>-->
                        </ul>
                    </div>
                    <div class="btn-group">
                        <input class="form-control" type="text" name="query" placeholder="查询关键词"/>
                    </div>
                    <div class="btn-group">
                        <button id="field_button" class="btn btn-default" type="button" title="添加查询条件">+</button>
                    </div>
                </div>
            </div>
            <div class="col-md-1 thumbnail">
                <select class="form-control" name="number">
                    <option value="10">10条</option>
                    <option value="30">30条</option>
                    <option value="50">50条</option>
                    <option value="100">100条</option>
                </select>
            </div>
            <div class="col-md-2 thumbnail">
                <button class="btn btn-default" type="button" onclick="doSearch()">搜索</button>
            </div>
        </div>
    </form>
</div>
<div class="search-tab panel panel-default">
    <ul class="nav nav-tabs">
        <li role="presentation" class="active"><a href="#">字段</a></li>
        <!-- <li role="presentation"><a href="#">历史</a></li> -->
    </ul>
    <!-- 字段标题 -->
    <div class="panel-body" id="spantitle">
    </div>
</div>

<div class="site-search-body panel panel-default">
    <div class="panel-heading">搜索结果</div>
    <div class="panel-body">未找到结果</div>
    <table class="search-table table table-bordered table-condensed table-responsive" >
        <thead></thead>
        <tbody></tbody>
    </table>
</div>

<div id="site-search-page">
    <nav aria-label="Page navigation">
        <ul class="pager">
            <li class="previous">
                <a href="#"><span aria-hidden="true">&larr;</span>上一页</a>
            </li>
            <li class="center-block">
                <span>0 / 0</span>
            </li>
            <li class="next">
                <a href="#">下一页 <span aria-hidden="true">&rarr;</span></a>
            </li>
        </ul>
    </nav>
</div>

<div id="MyModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">正在查询...</h4>
            </div>
            <div class="modal-body">
                <img class="img-responsive center-block"
                     src="<?php $img = 'images/load' . rand(0, 32) . '.gif';
                     echo Url::to($img); ?>"/>
            </div>
            <div class="modal-footer"></div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<script type="text/javascript">
    var pageCount = 0,
        pageNum = 1,
        index_json = <?= json_encode($index);?>;

    window.onload = function () {
        addFieldDiv(true);
        listenDropdown();
        //bindEvent();
        listenPage();
        listenIndexInput();
        initialize();

    }

    function initialize() {
        var last_index_text = localStorage.getItem('last_index_text'),
            last_search_text = localStorage.getItem('last_search_text'),
            last_access_tiem = localStorage.getItem('last_access_tiem');

        if (last_index_text) {
            $('#selected_index').text(last_index_text);
            doFillLab(last_index_text);
        }

        if (last_search_text) {
            IndexInput.fn(last_search_text);
        }

        if (last_access_tiem) {
            var access_tiem = new Date(last_access_tiem),
                time = new Date();
            access_tiem.setDate(access_tiem.getDate()+7);
            if(access_tiem <= time) localStorage.clear();

        } else {
            localStorage.setItem('last_access_tiem', new Date());
        }
    }

    function bindEvent() {
        $('.search-tab a').click(function (e) {
            e.preventDefault()
            $(this).tab('show')
        });
    }

    function addFieldDiv(init) {
        var serach_list = $('.field_div'),
            field_div = $(serach_list[0]),
            clone_obj = field_div.clone(),
            clone_button = $(clone_obj.find("#field_button"));

        if (init) {
            field_div.hide();
        } else {
            clone_obj.show();
            clone_button.text('-');
            clone_button.click(function () {
                $(this).parents('.field_div').remove();
            });
        }

        $('#field_container').append(clone_obj);

        if (init) {
            clone_obj.css('display', 'block');
            clone_button.click(function () {
                addFieldDiv();
                listenDropdown();
            });
        }
    }

    function listenDropdown() {
        $(".dropdown-menu > li > a").click(function () {
            var button = $($(this).parents('ul').prev(':button')),
                button_name = button.attr('name'),
                button_text = $(this).text();
            button.children('span')[0].innerText = $(this).text();

            if (button_name == 'filter_button') {
                var btn_group = $(this).parents(".field_div")[0].children,
                    input = $(btn_group[2]).children();

                if (button_text == 'between') {
                    if (input.length == 1) {
                        input_obj = input.clone();
                        input.after(input_obj);
                    }
                    if ($(btn_group[0]).find(':button span:first-child').text() == 'time') {
                        $(input).datetimepicker({
                            format: 'yyyy-mm-dd hh:ii:ss',
                            language: 'zh-CN'
                        });

                        $(input_obj).datetimepicker({
                            format: 'yyyy-mm-dd hh:ii:ss',
                            language: 'zh-CN'
                        });
                    }
                } else if (input.length == 2) {
                    input[1].remove();
                }
            }

            if (button_name == 'index_button') {
                doFillLab(button_text);
            }
        });
    }

    function doFillLab(index_text) {
        localStorage.setItem('last_index_text', index_text);
        postMethod(function (data) {
            var field_biv = $("#field_container").children(),
                btn_group = $(field_biv[0]).children(),
                field_ul = $(btn_group[0]).find('ul'),
                btn_group_first = $(field_biv[1]).children(),
                field_ul_first = $(btn_group_first[0]).find('ul'),
                spantitle = $('#spantitle');

            field_ul.empty();
            field_ul_first.empty();
            spantitle.empty();

            if (data.total != 0) {
                for (title in data.hits[0]._source) {
                    var li = document.createElement('li'),
                        a = document.createElement('a');
                    if (title == '@timestamp' || title == '@version') {//去除不需要的字段
                        continue
                    }
                    a.innerText = title;
                    a.href = '#';
                    li.appendChild(a);
                    var copy_li = $(li).clone();
                    copy_li.bind('click', function () {
                        $($(this).parents('ul').prev(':button')).children('span')[0].innerText = $(this).text();
                    });
                    field_ul.append(li);
                    field_ul_first.append(copy_li);

                    var span = document.createElement('span');
                    span.className = "label label-primary";
                    span.innerText = title;
                    spantitle.append(span);
                    span.onclick = function () {
                        if (this.className == "label label-primary") {
                            this.className = "label label-default";
                            $('.search-tab-field-' + this.innerText).hide();
                        } else {
                            this.className = "label label-primary";
                            var element = $('.search-tab-field-' + this.innerText);
                            element.length ? $('.search-tab-field-' + this.innerText).show() : doSearch();
                        }
                    }
                }
            }
        }, index_text);
    }

    function getFields() {
        var spantitle = $('#spantitle').children(),
            Fields = [];

        spantitle.each(function (i) {
            if (this.className == "label label-primary") {
                Fields.push(this.innerText)
            }
        })
        return Fields
    }

    function getFilters() {
        var container = $('.field_div'),
            Filters = [];

        container.each(function (i) {
            if (i === 0) return;
            var buttonValus = $(this).find(':button'),
                textValus = $(this).find(':text'),
                Field = {};

            Field.field = $.trim(buttonValus[0].innerText);
            Field.filter = $.trim(buttonValus[1].innerText);
            if (Field.field == 'time' && Field.filter == 'between') {
                Field.search = [textValus[0].value, textValus[1].value];
            } else {
                Field.search = textValus[0].value;
            }

            Filters.push(Field)
        });

        return Filters;
    }

    function postMethod(func, index, field, filters, size, page, sort) {
        $('#MyModal').modal();
        var json = {
            "index": index,
            "field": field,
            "condition": filters ? filters : {},
            "sort": sort ? sort : '',
            "size": size ? size : 1,
            "page": page ? page : 1
        };

        $.ajax({
            url: "<?= Url::toRoute(['search']); ?>",
            data: {json: JSON.stringify(json)},
            type: "post",
            dataType: "Json",
            success: function (data) {
                func(data);
            },
            complete: function (XMLHttpRequest, status) {
                $('#MyModal').modal('hide');
                if (status != 'success') alert("查询出错:" + status);
            }
        });

    }

    function doSearch() {
        $('.site-search-body > .panel-body').hide();
        $('.search-table > thead').empty();
        $('.search-table > tbody').empty();

        var index = $('#selected_index').text(),
            field = getFields(),
            filters = getFilters(),
            size = $("*[name='number']").val(),
            oEvent = arguments.callee.caller.arguments[0] || event,
            target = oEvent.srcElement || oEvent.target;

        postMethod(function (data) {
            if (data.hits == null || data.hits.length <= 0) {
                $('.panel-body').show();
                $('.panel-heading').text('搜索到0结果');
            }
            else {
                $('.panel-heading').text('搜索到' + data.total + '结果');
                var title_tr = document.createElement('tr');
                for (title in data.hits[0]._source) {
                    var th = document.createElement('th');
                    th.className = 'search-tab-field-' + title;
                    th.innerText = title;
                    title_tr.appendChild(th)
                }
                $('.search-table thead').append(title_tr);
                for (var i in data.hits) {
                    var tr = document.createElement('tr');
                    for (var j in data.hits[i]._source) {
                        var td = document.createElement('td');
                        td.className = 'search-tab-field-' + j;
                        var str = data.hits[i]._source[j];
                        if(str.length>10 && str.length<=50){
                            td.innerHTML = "<div style='width:100px;word-wrap:break-word;'>"+str+"</div>";
                        }else if(str.length>50 && str.length<=200){
                            td.innerHTML = "<div style='width:180px;word-wrap:break-word;'>"+str+"</div>";
                        } else if(str.length>200){
                            td.innerHTML = "<div style='width:360px;word-wrap:break-word;'>"+str+"</div>";
                        } else{
                            td.innerText = str;
                        }
                        tr.appendChild(td);
                    }
                    $('.search-table tbody').append(tr);
                }
            }
            $('.site-search-body').css('display', 'table');

            pageCount = Math.ceil(data.total / size);
            var element = $('#site-search-page'),
                span = element.find("span"),
                page = element.data('page') || 1;

            if (target.tagName == "BUTTON") {
                pageNum = 1;
                page = 1;
                element.data('page', pageNum);
            }

            span[1].innerText = page + " / " + pageCount;

        }, index, field, filters, size, pageNum);
    }
    function listenPage() {
        var element = $('#site-search-page');

        element.on('click', 'li', function () {
            var index = this.className,
                page = element.data('page') || 1;

            if (page != 1 || index != "previous") {
                if (index == "next") {
                    if (page == pageCount) {
                        alert('已经是最后一页');
                    } else {
                        pageNum = ++page;
                    }
                } else {
                    pageNum = (page == 1 ? 1 : (--page));
                }

                $('#site-search-page').data('page', pageNum);
            }
            else {
                alert('已经是第一页');
            }
            doSearch();
        });
    }

    function listenIndexInput() {
        if ('oninput' in IndexInput.index_input) {
            IndexInput.index_input.addEventListener("input", IndexInput.fn, false);
        } else {
            IndexInput.index_input.onpropertychange = IndexInput.fn;
        }
    }

    var IndexInput = {
        index_ul: document.getElementById('index-ul'),
        index_input: document.getElementById('index-input'),
        fn: function (str) {
            var find_str = IndexInput.index_input.value || str,
                text = '', len = index_json.length, selected = [];
            if (find_str.trim() != '' && typeof find_str == "string") {
                localStorage.setItem('last_search_text', find_str);
                IndexInput.cfn();
                for (var i = 0; i < len; i++) {
                    text = index_json[i];
                    if (text && text.indexOf(find_str) != -1) {
                        selected.push(text);
                    }
                }
            } else {
                selected = index_json;
            }
            IndexInput.tfn(selected);
        },
        cfn: function () {
            var child = $(IndexInput.index_ul).children(),
                len = child.length;
            for (var j = 0; j <= len; j++) {
                if (j == 0) continue;
                $(child[j]).remove();
            }
        },
        tfn: function (list) {
            for (var i = 0, len = list.length; i < len; i++) {
                var li = document.createElement('li'),
                    a = document.createElement('a');
                a.innerText = list[i];
                a.href = '#';
                li.appendChild(a);
                IndexInput.index_ul.appendChild(li);
            }
            listenDropdown();
        }
    };
</script>