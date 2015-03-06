Identity Card（中国大陆）公民身份证类
----

###安装说明

在 `composer` 中添加依赖：

```json
	"require": {
		"douyasi/identity-card": "dev-master"
	},
```

然后在命令行窗体里执行 `composer update` 命令。

###使用说明

创建ID类的实例，然后调用其对应方法。

`Laravel 5` 测试路由示例：

```php
Route::get('test', function(){
	$ID = new Douyasi\IdentityCard\ID;
	$is_pass = $ID->validateIDCard('42032319930606629x');  //校验身份证证号是否合法
	$area = $ID->getArea('42032319930606629x');  //获取身份证所在地信息 遵循GB/T 2260-2007中华人民共和国行政区划代码 标准
	$gender = $ID->getGender('42032319930606629x');  //获取性别 'f'表示女，'m'表示男，校验失败返回false
	$birthday = $ID->getBirth('42032319930606629x');  //获取出生日期
	return compact('is_pass', 'area', 'gender', 'birthday');
});
```

上面测试路由将返回下面 `json` 数据响应：

```json
{
    "is_pass":true,
    "area":{
            "status":true,
            "result":"湖北省 十堰市竹山县",
            "provice":"湖北省",
            "city":"十堰市",
            "county":"竹山县"
        },
    "gender":"m",
    "birthday":"1993-06-06"
}
```
