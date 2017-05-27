<?php

namespace Douyasi\IdentityCard;
use PDO;
use PDOException;

/**
 * Class ID
 * 中国大陆地区身份证类
 * 根据 GB/T 2260-2007 (基准) 中华人民共和国行政区划代码 来验证国内公民身份证证号合法性.
 *
 * 310107 19970507 055 6
 * [6位地区码][8位生日码][3位随机码][1位校验码]
 *
 * @author raoyc <raoyc2009@gmail.com>
 */
class ID
{
    /**
     * PDO 对象
     * 
     * @var null
     */
    private $pdo = null;

    /**
     * 身份证证号对象
     * 
     * @var null
     */
    protected $pid = null;

    /** 
     * 星座（资料来自维基百科 https://zh.wikipedia.org/wiki/%E8%A5%BF%E6%B4%8B%E5%8D%A0%E6%98%9F%E8%A1%93 ）
     * 关于星座月日范围，会有上下 1-2 天的浮动，这里以维基百科资料为主
     * 
     * @var array
     */
    protected $aConstellations = array(
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
                            );

    /**
     * 星座边缘日切数据
     * 
     * @var array
     */
    protected $aConstellationEdgeDays = array(21, 20, 21, 20, 21, 22, 23, 23, 23, 24, 22, 21);

    /**
     * 中华人民共和国省级行政区划代码(不含港澳台地区)
     * 
     * @var array
     */
    protected $aProvinces = array(
        '11' => '北京',
        '12' => '天津',
        '13' => '河北',
        '14' => '山西',
        '15' => '内蒙古',
        '21' => '辽宁',
        '22' => '吉林',
        '23' => '黑龙江',
        '31' => '上海',
        '32' => '江苏',
        '33' => '浙江',
        '34' => '安徽',
        '35' => '福建',
        '36' => '江西',
        '37' => '山东',
        '41' => '河南',
        '42' => '湖北',
        '43' => '湖南',
        '44' => '广东',
        '45' => '广西',
        '46' => '海南',
        '50' => '重庆',
        '51' => '四川',
        '52' => '贵州',
        '53' => '云南',
        '54' => '西藏',
        '61' => '陕西',
        '62' => '甘肃',
        '63' => '青海',
        '64' => '宁夏',
        '65' => '新疆',
    );

    # -------------------------
    # private functions start
    # -------------------------
    /**
     * 使用 PDO 链接目标 sqlite 数据库
     * 
     * @return mixed
     */
    private function connect()
    {
        try {
            $pdo = new PDO('sqlite:'.dirname(__FILE__).'/db/database.sqlite');
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  // ERRMODE_WARNING | ERRMODE_EXCEPTION | ERRMODE_SILENT
            $this->pdo = $pdo;
        } catch (PDOException $e) {
            die('Whoops, could not connect to the SQLite database : '.$e->getMessage());
        }
    }

    /**
     * 从 mysqlite 库中获取 特定 行政区划代码 数据.
     * 
     * @param  string $id 行政区划代码
     * @return array
     */
    private function getDivision($id)
    {
        if ($this->pdo != null) {
            $stmt = $this->pdo->prepare('SELECT
                                    divisions.id,
                                    divisions.name,
                                    divisions.status,
                                    divisions.year
                                FROM
                                    divisions
                                WHERE
                                    divisions.id = ?');
            $stmt->execute(array(intval($id)));
            $division = $stmt->fetch();
            return $division;
        } else {
            $this->connect();
            return $this->getDivision($id);
        }
    }

    /**
     * 通过正则表达式初步检测身份证证号非法性.
     *
     * @param  string $pid 传入的个人身份证证号
     * @return bool        通过返回true，否则返回false
     */
    private function checkFirst($pid)
    {
        return preg_match('/^\d{6}(18|19|20)\d{2}(0[1-9]|1[012])(0[1-9]|[12]\d|3[01])\d{3}(\d|X)$/', $pid);
    }

    /**
     * 根据身份证前17位计算身份证最后一位校验码
     *
     * @param  string $idcard_base
     * @return string
     */
    private function getIDCardVerifyNumber($idcard_base)
    {
        // 加权因子
        $factor = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
        // 校验码对应值
        $verify_number_list = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
        $checksum = 0;
        for ($i = 0; $i < strlen($idcard_base); $i++) {
            $checksum += substr($idcard_base, $i, 1) * $factor[$i];
        }
        $mod = $checksum % 11;
        $verify_number = $verify_number_list[$mod];

        return $verify_number;
    }
    # -------------------------
    # private functions end
    # -------------------------

    /**
     * 验证身份证是否合法.
     *
     * @param  string $pid 个人身份证证号
     * @return bool       合法，则返回true，失败则返回false
     */
    public function validateIDCard($pid)
    {
        $pid = strtoupper($pid);
        if ($this->checkFirst($pid)) {
            //第一步正则检测
            $iYear  = substr($pid, 6, 4);
            $iMonth = substr($pid, 10, 2);
            $iDay   = substr($pid, 12, 2);
            if (checkdate($iMonth, $iDay, $iYear)) {
                //第二步检测身份证日期
                $idcard_base = substr($pid, 0, 17);  //身份证证号前17位
                if ($this->getIDCardVerifyNumber($idcard_base) != substr($pid, 17, 1)) {
                    //第三步校验和
                    return false;
                } else {
                    return true;
                }
            }
        } else {
            return false;
        }
    }

    /**
     * 根据身份证证号获取所在地区.
     *
     * @param  string $pid 个人身份证证号
     * @return array       结果数组
     */
    public function getArea($pid)
    {
        $provice       = substr($pid, 0, 2);
        $sufix_provice = substr($pid, 0, 2).'0000';  //获取省级行政区划代码
        $sufix_city    = substr($pid, 0, 4).'00';  //获取地市级行政区划代码
        $county        = substr($pid, 0, 6);  //获取县级行政区划代码
        $result        = '';
        if (array_key_exists($provice, $this->aProvinces)) {
            $_county = $this->getDivision($county);  // 县级
            if ($_county) {
                $_city          = $this->getDivision($sufix_city);  // 地市级
                $_province      = $this->getDivision($sufix_provice);  // 省级
                $_city_name     = isset($_city['name']) ? $_city['name'] : '';
                $_province_name = isset($_province['name']) ? $_province['name'] : '';
                $result         = $_province_name.' '.$_city_name.''.$_county['name'];
                return array(
                            'status'   => true,
                            'result'   => $result,
                            'province' => $_province_name,
                            'city'     => $_city_name,
                            'county'   => $_county['name'],
                            'using'    => $_county['status'],  // 行政区划代码是否仍在使用，1 是 0 否
                        );
            }
        }
        return array(
                        'status'   => false,
                        'result'   => '',
                        'province' => '',
                        'city'     => '',
                        'county'   => '',
                        'using'    => 0,
                    );
    }

    /**
     * 获取性别.
     *
     * @param  string $pid  个人身份证证号
     * @return string|bool  返回'f'表示女，返回'm'表示男，身份证未校验通过则返回false
     */
    public function getGender($pid)
    {
        if ($this->validateIDCard($pid)) {
            $gender = substr($pid, 16, 1);  //倒数第2位
            return ($gender % 2 == 0) ? 'f' : 'm';
        } else {
            return false;
        }
    }

    /**
     * 获取出生年月日信息.
     *
     * @param  string $pid    个人身份证证号
     * @param  string $format 日期格式 默认为'Y-m-d'
     * @return string|bool    返回特定日期格式的数据，如'1990-01-01'，身份证或出生年月日未校验通过则返回false
     */
    public function getBirth($pid, $format = 'Y-m-d')
    {
        if ($this->validateIDCard($pid)) {
            $iYear  = substr($pid, 6, 4);
            $iMonth = substr($pid, 10, 2);
            $iDay   = substr($pid, 12, 2);
            $str    = date($format, mktime(0, 0, 0, $iMonth, $iDay, $iYear));
            return $str;
        } else {
            return false;
        }
    }

    /**
     * 获取年龄.
     * 计算方法不是年份直接减，而是按秒算年，满年则加，未满向下取整，故实际算出的年龄可能比常规和虚岁小到1-2岁
     * 
     * @param  string $pid 个人身份证证号
     * @return int|bool    返回年龄，身份证或出生年月日未校验通过则返回false
     */
    public function getAge($pid)
    {
        if ($this->validateIDCard($pid)) {
            $birthday =  strtotime(substr($pid,6,8));
            $today    = strtotime('today');
            $diff     = floor(($today-$birthday)/86400/365);
            $age      = strtotime(substr($pid,6,8).' +'.$diff.'years') > $today ? ($diff+1) : $diff;
            return $age;
        } else {
            return false;
        }
    }

    /**
     * 获取星座.
     * 
     * @param  string $pid 个人身份证证号
     * @return string|bool 返回星座，身份证或出生年月日未校验通过则返回false
     */
    public function getConstellation($pid)
    {
        if ($this->validateIDCard($pid)) {
            $month = (int) substr($pid, 10, 2);
            $month = $month - 1;
            $day = (int) substr($pid, 12, 2);
            if ($day < $this->aConstellationEdgeDays[$month]) {
                $month = $month - 1;
            }
            if ($month > 0) {
                return $this->aConstellations[$month];
            }
            return $this->aConstellations[11];
        } else {
            return false;
        }
    }

}
