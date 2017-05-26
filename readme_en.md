Chinese Identity Card package
-----------------------------

>   Chinese Identity Card (Mainland China) package, data from `GB/T 2260-2007`.  

[![Latest Stable Version](https://poser.pugx.org/douyasi/identity-card/v/stable.svg?format=flat-square)](https://packagist.org/packages/douyasi/identity-card)
[![Latest Unstable Version](https://poser.pugx.org/douyasi/identity-card/v/unstable.svg?format=flat-square)](https://packagist.org/packages/douyasi/identity-card)
[![License](https://poser.pugx.org/douyasi/identity-card/license?format=flat-square)](https://packagist.org/packages/douyasi/identity-card)
[![Total Downloads](https://poser.pugx.org/douyasi/identity-card/downloads?format=flat-square)](https://packagist.org/packages/douyasi/identity-card)

[简体中文读我](readme.md)

### Installation

Get [Composer](https://getcomposer.org/), then run in terminal:

```bash
cd /path/to/your-project
composer require "douyasi/identity-card:~2.0"
```

Note：

Using `PDO` to connect `sqlite` database in this plugin, please make sure `pdo` and `pdo_sqlite` extensions have been installed.

### Usage and Example

#### Example in `Laravel 5` :

You can using the following functions to get identity card information.

```php
Route::get('test', function() {
    $ID = new Douyasi\IdentityCard\ID;
    $passed = $ID->validateIDCard('42032319930606629x');
    $area = $ID->getArea('42032319930606629x');
    $gender = $ID->getGender('42032319930606629x');
    $birthday = $ID->getBirth('42032319930606629x');
    return compact('passed', 'area', 'gender', 'birthday');
});
```

#### Result :

You will get some `json` response data like below:

```json
{
    "passed":true,
    "area":{
            "status":true,
            "result":"湖北省 十堰市竹山县",
            "province":"湖北省",
            "city":"十堰市",
            "county":"竹山县",
            "using": 1
        },
    "gender":"m",
    "birthday":"1993-06-06"
}
```

>   If the identity-card number is passed, the `passed` filed will return `true`, otherwise return `false`. The meanings of other fields (such as `area` `gender` & `birthday`) tell themselves.

>   `getArea()` will return a new `using` filed in verison `2.0+` . If this administrative-division-code (the top six digital number in identity-card, like `420323` ) is still in use, it will be `1` , otherwise `0` .

#### Reference Resources (in Chinese)

- 中华人民共和国国家统计局 [行政区划代码](http://www.stats.gov.cn/tjsj/tjbz/xzqhdm/)
- 民政部 [县级以上行政区划变更情况](http://xzqh.mca.gov.cn/description?dcpid=1)
- 民政部 [中华人民共和国行政区划代码](http://www.mca.gov.cn/article/sj/tjbz/a/)