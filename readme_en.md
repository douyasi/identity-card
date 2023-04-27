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

- In 2016, versions 1.0 to 1.2 were published, using data from China national standard `GB/T 2260-2007` and storing them in an array in PHP.
- On May 26, 2017, the first version (`2.0`) of the 2.x series was published, using SQLite as the data source with open crawler scripts.
- From September 2017 to December 2017, versions `2.2` to `2.4` were published, fixing bugs that caused null returns or incorrect divisions.
- On December 3, 2017, version `2.4` was published, fixing a bug that caused incorrect constellation results.
- On March 27, 2018, version `2.5` was published, updating China divisions data to `2018-01`.
- On May 13, 2018, version `2.6` was published, with implementations in `Node/Javascript` and `Rust` languages.
- On March 31, 2019, version `2.7` was published, updating China divisions data to `2019-02` and fixing a bug that caused incorrect age returns. The next update will/may be in March 2020.
- On June 29, 2020, version `2.8` was published, updating China divisions data to `2020-02` due to the COVID-19 pandemic.
- On October 10, 2020, version `2.9` was published, updating China divisions data to `2020-08`. The crawler scripts and historical data were removed. The next update will/may be in April 2021.
- On February 8, 2023, version `2.10` was published, updating China divisions data to the year `2021`.

### Installation

To install this package, first make sure you have [Composer](https://getcomposer.org/) installed on your system, and then run the following command in your terminal:

```bash
cd /path/to/your-project
composer require "douyasi/identity-card:~2.0"
```

Please note that this package uses `PDO` to connect to an sqlite database. Therefore, make sure that the `pdo` and `pdo_sqlite` php extensions are installed on your system.

### Usage and Example

#### Example in `Laravel`

You can use the following functions to get identity card information:

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

You will receive a `JSON` response with the following data:

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

The `passed` field will return `true` if the identity-card number is valid, and `false` if it is not valid. The meaning of other fields such as `area`, `gender`, `birthday`, `age`, and `constellation` is self-explanatory.

Note that `getArea()` will return a new `using` field in version `2.0+`. If the administrative-division code (the top six digits of the identity-card number, e.g., `420323`) is still in use, the field will be `1`. Otherwise, it will be `0`.

### API List

- `validateIDCard()` - Validate identity-card number and get the result.
- `getArea()` - Get original area information from identity-card number.
- `getGender()` - Get gender information. Returns `m` for male and `f` for female.
- `getBirth()` - Get birthday from identity-card number.
- `getAge()` - Get age from identity-card number.
- `getConstellation()` - Get constellation from birth date.

Note: The approximate dates of Sun signs from [Wikipedia](https://zh.wikipedia.org/wiki/%E8%A5%BF%E6%B4%8B%E5%8D%A0%E6%98%9F%E8%A1%93) are used for `getConstellation()` method.

```
'Aquarius',  // 1.21-2.19 [水瓶座]
'Pisces',  // 2.20-3.20 [双鱼座]
'Aries',  // 3.21-4.19 [白羊座]
'Taurus',  // 4.20-5.20 [金牛座]
'Gemini',  // 5.21-6.21 [双子座]
'Cancer',  // 6.22-7.22 [巨蟹座]
'Leo',  // 7.23-8.22 [狮子座]
'Virgo',  // 8.23-9.22 [处女座]
'Libra',  // 9.23-10.23 [天秤座]
'Scorpio',  // 10.24-11.21 [天蝎座]
'Sagittarius',  // 11.22-12.20 [射手座]
'Capricorn'  // 12.21-1.20 [魔羯座]
```

### Crawler

See [`readme`](https://github.com/douyasi/china-divisions/tree/master/crawler) file under `crawler` folder in that [repo](https://github.com/douyasi/china-divisions).

### Reference Resources (only in Chinese)

- 中华人民共和国国家统计局 [行政区划代码](http://www.stats.gov.cn/tjsj/tjbz/tjyqhdmhcxhfdm/)
- 民政部 [县级以上行政区划变更情况](http://xzqh.mca.gov.cn/description?dcpid=1)
- 民政部 [中华人民共和国行政区划代码](https://www.mca.gov.cn/article/sj/xzqh/1980/)

### Special Thanks

![JetBrains Logo (Main) logo](https://resources.jetbrains.com/storage/products/company/brand/logos/jb_beam.svg)