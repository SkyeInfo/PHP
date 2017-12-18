# Linux-curl

## 参数配置



### 1.cookie

curl -c tmpfile/path url //把cookieb保存在tmpfile/path下

curl -b "key1=val1;key2=val2;" url  //发送cookie键值对

curl -b /tmp/cookies url //从文件中读取cookies

### 2.GET & POST

curl -G -d "name=value&name2=value2" url //get形式提交

curl -d "name=value&name2=value2" url //post形式提交

### 3.Header

*Request*

curl -A "Mozilla/5.0 Firefox/21.0" url //设置http请求头User-Agent

curl -e url1 url2     //设置http请求头Referer

curl -H "Connection:keep-alive \n User-Agent: Mozilla/5.0" url

*Reponse*

curl -I url //仅仅返回header

curl -D /tmpfile/path/header url //将http header保存到/tmp/header文件