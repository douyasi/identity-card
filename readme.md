Identity Card（中国大陆）公民身份证类
-------------------------------------

>   （中国大陆）公民身份证类包，数据来自国标 `GB/T 2260-2007` (中华人民共和国行政区划代码 标准) 。  
>   Chinese Identity Card package, data from `GB/T 2260-2007`.  


[![Latest Stable Version](https://poser.pugx.org/douyasi/identity-card/v/stable.svg?format=flat-square)](https://packagist.org/packages/douyasi/identity-card)
[![Latest Unstable Version](https://poser.pugx.org/douyasi/identity-card/v/unstable.svg?format=flat-square)](https://packagist.org/packages/douyasi/identity-card)
[![License](https://poser.pugx.org/douyasi/identity-card/license?format=flat-square)](https://packagist.org/packages/douyasi/identity-card)
[![Total Downloads](https://poser.pugx.org/douyasi/identity-card/downloads?format=flat-square)](https://packagist.org/packages/douyasi/identity-card)


### 安装说明(Installation)

在 `composer` 中添加依赖：

```json
    "require": {
        "douyasi/identity-card": "~1.0"
    }
```

然后在命令行窗体里执行 `composer update` 命令。

Get [Composer](https://getcomposer.org/), then run in terminal:

```bash
cd /path/to/your-project
composer require "douyasi/identity-card:~1.0"
```

### 使用说明(Usage and Example)


#### Example in `Laravel 5` :

创建ID类的实例，然后调用其对应方法。`Laravel 5` 测试路由示例：

```php
Route::get('test', function() {
    $ID = new Douyasi\IdentityCard\ID;
    $is_pass = $ID->validateIDCard('42032319930606629x');
    $area = $ID->getArea('42032319930606629x');
    $gender = $ID->getGender('42032319930606629x');
    $birthday = $ID->getBirth('42032319930606629x');
    return compact('is_pass', 'area', 'gender', 'birthday');
});
```

#### Result (`json` response data) :

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

#### Fields:

结果中字段信息：

`is_pass` 与 `area.status` 表示校验是否通过，`true` 通过，`false` 失败；

`area.result` 完整地区信息，后续的 `area.province` 、 `area.city` 与 `are.county` 表示省市县三级地区名；

`gender` 表示性别，`m` 为男，`f` 为女；

`birthday` 表示出生年月日。

If the identity-card number is passed, the `is_pass` and `area.status` fileds will return `true`, otherwise return `false`. The meanings of other fields tell themselves.
