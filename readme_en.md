Chinese Identity Card package
-----------------------------

>   Chinese Identity Card (Mainland China) package, data from `GB/T 2260-2007`.  

[![Latest Stable Version](https://poser.pugx.org/douyasi/identity-card/v/stable.svg?format=flat-square)](https://packagist.org/packages/douyasi/identity-card)
[![Latest Unstable Version](https://poser.pugx.org/douyasi/identity-card/v/unstable.svg?format=flat-square)](https://packagist.org/packages/douyasi/identity-card)
[![License](https://poser.pugx.org/douyasi/identity-card/license?format=flat-square)](https://packagist.org/packages/douyasi/identity-card)
[![Total Downloads](https://poser.pugx.org/douyasi/identity-card/downloads?format=flat-square)](https://packagist.org/packages/douyasi/identity-card)

[简体中文读我](readme.md)

### Other Language Versions

- [Node/Javascript version](https://github.com/ycrao/id.js)
- [Rust version](https://github.com/ycrao/idrs)

### Change Log

- At 2016 year, version `1.0` - `1.2` published, data from China national standard `GB/T 2260-2007`, using PHP array to store them.
- 2017-05-26, First version (`2.0`) of 2.x published, starting use `SQLite` as datasource with crawler scripts opened.
- 2017-09, `2.2` to `2.4` published, fix return null or wrong division bugs.
- 2017-12-03, `2.4` published, fix get wrong constellation bug.
- 2018-03-27, `2.5` published, update China divisions data to `2018-01`.
- 2018-05-13, `2.6` published, implantations to `Node/Javascript` and `Rust` lang.
- 2019-03-31, `2.7` published, update China divisions data to `2019-02` and fix return wrong age. Next update will/may be at March 2020.
- 2020-06-29, `2.8` published, update China divisions data to `2020-02` (due to COVID-19). 
- 2020-10-10, `2.9` published, update China divisions data to `2020-08`, crawler scripts and history data are removed. Next update will/may be at April 2021.
- 2023-02-08, `2.10` published, update China divisions data to `2021` year.

### Installation

Get [Composer](https://getcomposer.org/), then run in terminal:

```bash
cd /path/to/your-project
composer require "douyasi/identity-card:~2.0"
```

Note：

Using `PDO` to connect `sqlite` database in this plugin, please make sure `pdo` and `pdo_sqlite` extensions have been installed.

### Usage and Example

#### Example in `Laravel`

You can using the following functions to get identity card information.

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

#### Result

You will get some `json` response data like below:

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

>   If the identity-card number is passed, the `passed` filed will return `true`, otherwise return `false`. The meanings of other fields (such as `area` `gender`   `birthday` `age` & `constellation`) tell themselves.

>   `getArea()` will return a new `using` filed in verison `2.0+` . If this administrative-division-code (the top six digital number in identity-card, like `420323` ) is still in use, it will be `1` , otherwise `0` .

### API List

- `validateIDCard()` validate identity-card and get result,
- `getArea()` get original area information from identity-card,
- `getGender()` get gender information，`m` for male, `f` for female,
- `getBirth()` get birthday,
- `getAge()` get age,
- `getConstellation()` get constellation.

>   The approximate dates of
Sun signs from WIKIPEDIA: https://zh.wikipedia.org/wiki/%E8%A5%BF%E6%B4%8B%E5%8D%A0%E6%98%9F%E8%A1%93 .

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

### Crawler

See [`readme`](https://github.com/douyasi/china-divisions/tree/master/crawler) file under `crawler` folder in `china-divisions` repo.

### Reference Resources (in Chinese)

- 中华人民共和国国家统计局 [行政区划代码](http://www.stats.gov.cn/tjsj/tjbz/tjyqhdmhcxhfdm/)
- 民政部 [县级以上行政区划变更情况](http://xzqh.mca.gov.cn/description?dcpid=1)
- 民政部 [中华人民共和国行政区划代码](https://www.mca.gov.cn/article/sj/xzqh/1980/)