<?php

/**
 * 行政区划代码历史沿革数据采集脚本
 *
 * @author raoyc <raoyc2009@gmail.com>
 */


function analyze_data($url, $key, &$places)
{
    $res = get_request($url);
    // 进行源码预处理工作
    $res = strip_tags($res);
    $res = str_replace(' ', '', $res);
    $res = str_replace('&nbsp;', '', $res);

    $res = str_replace('   ', '', $res);
    $res = preg_replace('/\r\n/', ' ', $res);
    $res = preg_replace('/\n/', ' ', $res);
    $res = preg_replace('/(.*)110000(.*)/', '110000${2}', $res);
    $res = preg_replace('/(.*)注(.*)/', '${1}', $res);
    $count = preg_match_all('/([\d]{6})\s+([\x{4e00}-\x{9fa5}]{3,})/imu', $res, $match, PREG_PATTERN_ORDER);
    $file = __DIR__.'/data/'.$key.'.txt';
    if (file_exists($file)) {
        unlink($file);
    }
    $content = '';
    if ($count > 0) {
        for ($i = 0; $i < $count; $i ++) {
            if (!array_key_exists($match[1][$i], $places)) {
                $places[$match[1][$i]] = $match[2][$i];
            }
            $content .= $match[1][$i].':'.$match[2][$i].PHP_EOL;
        }
    }
    file_put_contents($file, $content);
    return $places;
}

function get_request($url)
{
    $con = curl_init($url);
    curl_setopt($con, CURLOPT_HEADER, false);
    curl_setopt($con, CURLOPT_RETURNTRANSFER, true);
    $res = curl_exec($con);
    $errno = curl_errno($con);
    if ($errno) {
        throw \Exception('Curl error : '.curl_error($con)) ;
    }
    curl_close($con);
    return $res;
}

function main() {

    // 暂存所有行政区划代码
    $places = array();

    // 行政区划新老历史沿革 数据 网址
    $urls = array(
        '201703' => 'http://www.mca.gov.cn/article/sj/tjbz/a/2017/201703/201705051139.html',
        '201702' => 'http://www.mca.gov.cn/article/sj/tjbz/a/2017/0327/201705051134.html',
        '201701' => 'http://www.mca.gov.cn/article/sj/tjbz/a/2017/20170301/2017%E5%B9%B41%E6%9C%88%E5%8E%BF%E4%BB%A5%E4%B8%8A%E8%A1%8C%E6%94%BF%E5%8C%BA%E5%88%92%E4%BB%A3%E7%A0%81.html',
        '2016' => 'http://www.mca.gov.cn/article/sj/tjbz/a/2016/201702/201702231133.html',
        '2015' => 'http://www.mca.gov.cn/article/sj/tjbz/a/2015/up/201602/20160200880229.htm',
        '2014' => 'http://files2.mca.gov.cn/cws/201502/20150225163817214.html',
        '2013' => 'http://files2.mca.gov.cn/cws/201404/20140404125552372.htm',
        '2012' => 'http://files2.mca.gov.cn/cws/201308/20130821143408921.htm',
        '2010' => 'http://files2.mca.gov.cn/cws/201502/20150225155630452.htm',
        '2009' => 'http://files2.mca.gov.cn/cws/201502/20150225155548374.htm',
        '2008' => 'http://files2.mca.gov.cn/cws/201502/20150225155505816.htm',
        '2007' => 'http://files2.mca.gov.cn/cws/201502/20150225155431443.htm',
        '2006' => 'http://files2.mca.gov.cn/cws/201502/20150225155349475.htm',
        '2005' => 'http://files2.mca.gov.cn/cws/201502/20150225155305906.htm',
        '2004' => 'http://files2.mca.gov.cn/cws/201502/20150225155213857.htm',
        '2003' => 'http://files2.mca.gov.cn/cws/201502/20150225154652784.htm',
        '2002' => 'http://files2.mca.gov.cn/cws/201502/20150225154605585.htm',
        '2001' => 'http://files2.mca.gov.cn/cws/201502/20150225154526456.htm',
        '2000' => 'http://files2.mca.gov.cn/cws/201502/20150225154437380.htm',
        '1999' => 'http://files2.mca.gov.cn/www/201512/20151203095009488.htm',
        '1998' => 'http://files2.mca.gov.cn/www/201512/20151203094942775.htm',
        '1997' => 'http://files2.mca.gov.cn/www/201512/20151203094913384.htm',
        '1996' => 'http://files2.mca.gov.cn/www/201512/2015120309482673.htm',
        '1995' => 'http://files2.mca.gov.cn/www/201512/20151203094730991.htm',
        '1994' => 'http://files2.mca.gov.cn/www/201512/2015120309464500.htm',
        '1993' => 'http://files2.mca.gov.cn/www/201512/20151203094621380.htm',
        '1992' => 'http://files2.mca.gov.cn/www/201512/20151203094552341.htm',
        '1991' => 'http://files2.mca.gov.cn/www/201512/20151203094523451.htm',
        '1990' => 'http://files2.mca.gov.cn/www/201512/20151203094456319.htm',
        '1989' => 'http://files2.mca.gov.cn/www/201512/20151203094426140.htm',
        '1988' => 'http://files2.mca.gov.cn/www/201512/20151203094353258.htm',
        '1987' => 'http://files2.mca.gov.cn/www/201512/20151203094326909.htm',
        '1986' => 'http://files2.mca.gov.cn/www/201512/20151203094259720.htm',
        '1985' => 'http://files2.mca.gov.cn/www/201512/20151203094227472.htm',
        '1984' => 'http://files2.mca.gov.cn/www/201512/20151203094046186.htm',
        '1983' => 'http://files2.mca.gov.cn/www/201512/20151203093948255.htm',
        '1982' => 'http://files2.mca.gov.cn/www/201512/20151203093700735.htm',
        '1981' => 'http://files2.mca.gov.cn/www/201512/20151203093558121.htm',
        '1980' => 'http://files2.mca.gov.cn/www/201512/20151203093558121.htm',
    );
    foreach ($urls as $key => $url) {
        analyze_data($url, $key, $places);
    }
    ksort($places);
    $code = '<?php'.PHP_EOL.'// 硬编码数据 1980-2017 年行政区划历史变更数据'.PHP_EOL.'$aDivisions = array('.PHP_EOL;
    foreach ($places as $id => $name) {
        $code .= "    '".$id."' => '".$name."',".PHP_EOL;
    }
    $code .= ');'.PHP_EOL;
    $php_data_file = __DIR__.'/data/all.php';
    if (file_exists($php_data_file)) {
        unlink($php_data_file);
    }
    file_put_contents($php_data_file, $code);
    unset($places);
    echo 'Good, all have done!'.PHP_EOL;
}

main();