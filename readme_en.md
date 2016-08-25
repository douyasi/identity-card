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
composer require "douyasi/identity-card:~1.0"
```

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
            "provice":"湖北省",
            "city":"十堰市",
            "county":"竹山县"
        },
    "gender":"m",
    "birthday":"1993-06-06"
}
```

>   If the identity-card number is passed, the `passed` filed will return `true`, otherwise return `false`. The meanings of other fields (such as `area` `gender` & `birthday`) tell themselves.


