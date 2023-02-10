中国（大陆）公民身份证类
------------------------

>   中国（大陆）公民身份证类包，数据来自国标 `GB/T 2260-2007` (中华人民共和国行政区划代码 标准) 。  

[![Latest Stable Version](https://poser.pugx.org/douyasi/identity-card/v/stable.svg?format=flat-square)](https://packagist.org/packages/douyasi/identity-card)
[![Latest Unstable Version](https://poser.pugx.org/douyasi/identity-card/v/unstable.svg?format=flat-square)](https://packagist.org/packages/douyasi/identity-card)
[![License](https://poser.pugx.org/douyasi/identity-card/license?format=flat-square)](https://packagist.org/packages/douyasi/identity-card)
[![Total Downloads](https://poser.pugx.org/douyasi/identity-card/downloads?format=flat-square)](https://packagist.org/packages/douyasi/identity-card)

[ENGLISH README](readme_en.md)

### 其它语言实现版本

- [Node/Javascript 实现版本](https://github.com/ycrao/id.js)
- [Rust 实现版本](https://github.com/ycrao/idrs)
- 扩展与延伸：[省市县三级联动地址效果](https://raoyc.com/address-selector/)，使用的是最新 `2021` 年数据，需要做额外转换，转换好之后的 JS 文件大小约为 `99KB`，可以点 [此](https://raoyc.com/address-selector/divisions.js) 查看。

### 版本更新记录

- 2016年期间，发布 `1.0` - `1.2` 版本，数据来自 `GB/T 2260-2007` 标准，使用 `PHP` 内置数组存储。
- 2017年05月26日，发布 `2.x` 系列初版 `2.0` 版本，开始使用 `SQLite` 作为数据存储源，并公布采集脚本。
- 2017年09月，发布 `2.2` - `2.4` 版本，修复某些情况下返回地区（主要县级）信息为空、不准确的问题。
- 2017年12月03日，发布 `2.4` 版本，修复返回错误星座问题。
- 2018年03月27日，发布 `2.5` 版本，采集截止2018年01月的行政区划数据。
- 2018年05月13日，发布 `2.6` 版本，更新其他语言实现，如 `Node/Javascript`·与 `Rust`。
- 2019年03月31日，发布 `2.7` 版本，采集截止到2019年02月的行政区划数据，并修复错误年龄问题。下次（数据库）更新，预计在2020年03月底。
- 2020年06月29日，发布 `2.8` 版本，采集截止到2020年02月（可能受疫情影响，民政部官网也只更新到该月份）的行政区划数据。
- 2020年10月10日，发布 `2.9` 版本，采集截止到2020年08月的行政区划数据，同时移除采集脚本和历史归档数据（已剥离到 [china-divisions](https://github.com/douyasi/china-divisions) 新项目中），下次数据库与版本更新预计在2021年04月底。
- 2023年02月08日，发布 `2.10` 版本，采集并人工处理截止到2021年的行政区划数据。一直未发布新版本，原因并不在作者我，民政部数据源头在最近2年多更新很不及时，参考此[说明](https://github.com/douyasi/china-divisions/tree/master/db)。

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
composer require "douyasi/identity-card:~2.0"
```

特别提示：

本插件需要使用 `PDO` 连接 `sqlite` 数据库，故需要安装和开启 `pdo` 和 `pdo_sqlite` 组件。

### 使用说明

#### `Laravel` 示例代码

创建ID类的实例，然后调用其对应方法。`Laravel` 测试路由示例：

```php
Route::get('test', function() {
    $ID = new Douyasi\IdentityCard\ID();
    $pid = '42032319930606629x';
    $passed = $ID->validateIDCard($pid);
    $area = $ID->getArea($pid);
    $gender = $ID->getGender($pid);
    $birthday = $ID->getBirth($pid);
    $age = $ID->getAge($pid);
    $constellation = $ID->getConstellation($pid);
    return compact('passed', 'area', 'gender', 'birthday', 'age', 'constellation');
});
```

#### 结果集

上面测试路由将返回下面 `json` 数据响应：

```json
{
    "status": true,
    "result": {
        "is_pass": true,
        "area": {
            "status": true,
            "result": "湖北省 十堰市竹山县",
            "province": "湖北省",
            "city": "十堰市",
            "county": "竹山县",
            "using": 1
        },
        "gender": "m",
        "birthday": "1993-06-06",
        "age": 23,
        "constellation": "双子座"
    }
}
```

>   如果身份证证号校验通过，`passed` 返回 `true` ，否则返回 `false` 。其它字段（如 `eara` 、 `gender` 、 `birthday` 、 `age` 和 `constellation` ）顾名思义，就不做解释了。

>   `2.0+` 版本 `getArea()` 方法返回中新增 `using` 字段，表示行政区划代码当前是否还在使用中。如果为 `1` ，说明此身份证证号前6位数字代码（也就是行政区划代码，如 `420323` ）仍在使用，可以新签发（非续签非补办）此行政区划代码打头的身份证；为 `0` 表示行政区划已经发生变更（地名可能不变），不再新签发此行政区划代码打头的身份证。

### API列表

- `validateIDCard()` 获取身份证号码校验结果；
- `getArea()` 获取身份证号码原始地区信息；
- `getGender()` 获取性别，`m` 男性 `f` 女性；
- `getBirth()` 获取出生年月日；
- `getAge()` 获取年龄；
- `getConstellation()` 获取星座。

>   关于星座月日范围，会有上下 `1-2` 天的浮动，这里以维基百科资料为主。
>   维基百科地址： https://zh.wikipedia.org/wiki/%E8%A5%BF%E6%B4%8B%E5%8D%A0%E6%98%9F%E8%A1%93 .

```
'水瓶座',  // 1.21-2.19 [Aquarius]
'双鱼座',  // 2.20-3.20 [Pisces]
'白羊座',  // 3.21-4.19 [Aries]
'金牛座',  // 4.20-5.20 [Taurus]
'双子座',  // 5.21-6.21 [Gemini]
'巨蟹座',  // 6.22-7.22 [Cancer]
'狮子座',  // 7.23-8.22 [Leo]
'处女座',  // 8.23-9.22 [Virgo]
'天秤座',  // 9.23-10.23 [Libra]
'天蝎座',  // 10.24-11.21 [Scorpio]
'射手座',  // 11.22-12.20 [Sagittarius]
'魔羯座',  // 12.21-1.20 [Capricorn]
```

### 数据爬虫

请参考 `china-divisions` 项目 `crawler` 目录下 [`readme`](https://github.com/douyasi/china-divisions/tree/master/crawler) 文件。

### 参考资源

- 中华人民共和国国家统计局 [行政区划代码](http://www.stats.gov.cn/tjsj/tjbz/tjyqhdmhcxhfdm/)
- 民政部 [县级以上行政区划变更情况](http://xzqh.mca.gov.cn/description?dcpid=1)
- 民政部 [中华人民共和国行政区划代码](https://www.mca.gov.cn/article/sj/xzqh/1980/)



