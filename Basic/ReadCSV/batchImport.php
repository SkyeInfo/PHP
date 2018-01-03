<?php
/**
 * 批量导入csv文件
 * @author skyeinfo@qq.com
 * @lastModifyTime 2018/1/2
 * @lastModify skyeinfo@qq.com
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>批量导入CSV文件</title>
</head>
<body>
<div style="margin: 10px auto 12px;padding-left: 50px;clear: both;">
    <span style="position: relative;margin-right: 8px;border-radius: 5px;display: inline-block;width: 300px;height: 30px;border: 1px solid #bbb;">
        <input type="text" id="text-input" style="margin: 0;width: 286px;height: 28px;border: none;" placeholder= "选择文件"/>
        <input type="file" id="file-input" style="position: absolute;margin: 0;top: 0;left: 0;width: 300px;height: 30px;filter:alpha(opacity=0);-moz-opacity:0.0;-khtml-opacity: 0.0;opacity: 0.0;cursor:pointer;" title="点击上传"/>
    </span>
    <button id="batch-import" style="width: 100px; height: 30px; border-radius: 5px">导入数据</button>
</div>
<div style="margin: 10px auto 12px;padding-left: 50px;clear: both;">
    <p id="import-status">待导入...</p>
    <div style="width: 370px;height: 16px;border: 1px solid #ccc;border-radius: 10px;float: left;">
        <span id="progress-status" style="display: inline-block;background: #85c440;border-radius: 10px;height: 18px;"></span>
    </div>
    <div id="progress-text" style="margin-left:3px; width: 40px; text-align:right; height:18px;line-height:18px;float:left;">0%</div>
    <div style="padding-left: 50px;clear: both;">
        <ul style="margin-top: 12px;padding: 6px 0 4px;line-height: 30px;" id="error-list"></ul>
    </div>
</div>

<script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('#batch-import').bind('click', startImport);
        $('#file-input').change(function(){
            $('#text-input').val(this.value);
        });
    });

    var pushData = [];
    var dataLength = 0;
    var statusHint = $('#import-status');

    function startImport() {
        if (typeof FileReader === "undefined"){
            alert("请使用最新版Chrome浏览器");
            return;
        }
        var fileInput = $('#file-input');//获取文件组件对象
        if (fileInput.length < 1 || typeof fileInput[0].files === "undefined"){
            alert("没有找到文件上传控件");
            return;
        }
        if (fileInput[0].files.length < 1){
            alert("请选择要导入的文件");
            return;
        }
        var rltFile = fileInput[0].files[0];//获取文件信息对象
        var ext = /\.[^\.]+/.exec(rltFile.name);
        if (ext[0] !== ".csv"){
            alert("文件格式不正确，请导入csv文件");
            return;
        }
        var buttonObj = $(this); //按钮对象
        buttonObj.attr("disabled", true);
        statusHint.text('导入中...');

        var fileReader = new FileReader();//new一个文件读取对象
        fileReader.readAsText(rltFile, "GB2312");
        fileReader.onload = function (ev) {
            var lines = fileReader.result.split("\n");

            for (var i = 1; i <= lines.length; i++){ //从1开始，不读表头
                if (!lines[i] || typeof lines[i].split === "undefined"){ continue; }
                var tempData = lines[i].split(",");
                var data = {
                    "name"  : tempData[0],
                    "age"   : tempData[1],
                    "gender": tempData[2]
                };
                // TODO check 数据 chenk(data);
                pushData.push(data)
            }
            dataLength = pushData.length;

            startPush();
        };
    }

    function startPush() {
        if (dataLength < 0) {
            statusHint.text("没有数据被导入");
            return;
        }
        var progress = $('#progress-status');
        var pt = $('#progress-text');
        var eul = $('#error-list');
        if (pushData.length <= 0) {
            statusHint.text('导入完成！');
            progress.css('width', '100%');
            pt.text('100%');
            $('#btn-import').attr('disabled', false);
            return;
        } else {
            var w = Math.round(((dataLength - pushData.length) / dataLength) * 100);
            progress.css('width', w + '%');
            pt.text(w + '%');
        }
        var item = pushData.shift(); //删除一个元素并返回元素的值
        $.ajax({
            url     : './import.php',
            type    : 'POST',
            data    : item,
            dataType: 'json',
            success : function(d){
                if(0 == parseInt(d.errno)){
                    // TODO something
                } else {
                    eul.append($('<li>'+item.name+':'+item.age+'['+d.msg+']</li>'));
                }
                startPush();
            }
        });
    }
</script>
</body>
</html>
