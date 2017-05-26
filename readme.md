中国（大陆）公民身份证类
------------------------

>   中国（大陆）公民身份证类包，数据来自国标 `GB/T 2260-2007` (中华人民共和国行政区划代码 标准) 。  

[![Latest Stable Version](https://poser.pugx.org/douyasi/identity-card/v/stable.svg?format=flat-square)](https://packagist.org/packages/douyasi/identity-card)
[![Latest Unstable Version](https://poser.pugx.org/douyasi/identity-card/v/unstable.svg?format=flat-square)](https://packagist.org/packages/douyasi/identity-card)
[![License](https://poser.pugx.org/douyasi/identity-card/license?format=flat-square)](https://packagist.org/packages/douyasi/identity-card)
[![Total Downloads](https://poser.pugx.org/douyasi/identity-card/downloads?format=flat-square)](https://packagist.org/packages/douyasi/identity-card)

[ENGLISH README](readme_en.md)

### 安装说明

在 `composer` 中添加依赖：

```json
    "require": {
        "douyasi/identity-card": "~2.0"
    }
```

然后在命令行窗体里执行 `composer update` 命令，或者参考下面直接使用 `composer require` 命令：

```bash
cd /path/to/your-project
composer require "douyasi/identity-card:~1.0"
```

特别提示：

本插件需要使用 `PDO` 连接 `sqlite` 数据库，故需要安装和开启 `pdo` 和 `pdo_sqlite` 组件。

### 使用说明

#### `Laravel` 示例代码

创建ID类的实例，然后调用其对应方法。`Laravel 5` 测试路由示例：

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

#### 结果集

上面测试路由将返回下面 `json` 数据响应：

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

>   如果身份证证号校验通过，`passed` 返回 `true` ，否则返回 `false` 。其它字段（如 `eara` 、 `gender` 和 `birthday` ）顾名思义，就不做解释了。

>   `2.0+` 版本 `getArea()` 方法返回中新增 `using` 字段，表示行政区划代码当前是否还在使用中。如果为 `1` ，说明此身份证证号前6位数字代码（也就是行政区划代码，如 `420323` ）仍在使用，可以新签发（非续签非补办）此行政区划代码打头的身份证；为 `0` 表示行政区划已经发生变更（地名可能不变），不再新签发此行政区划代码打头的身份证。

#### 参考资源

- 中华人民共和国国家统计局 [行政区划代码](http://www.stats.gov.cn/tjsj/tjbz/xzqhdm/)
- 民政部 [县级以上行政区划变更情况](http://xzqh.mca.gov.cn/description?dcpid=1)
- 民政部 [中华人民共和国行政区划代码](http://www.mca.gov.cn/article/sj/tjbz/a/)



