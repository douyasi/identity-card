# crawler

本爬虫主要采集行政区划代码历史变更数据，为新老身份证数据校验提供底层数据支持。

原始数据来源于 [中华人民共和国民政部](http://www.mca.gov.cn/) 网站，由 `crawler` 脚本对采集过来的数据进行处理，处理之后的行政区划代码数据按年份放置到 `data` 目录下，可供第三方程序二次开发使用。

## 使用方法

在终端切换到本文档目录，然后执行下面命令：

```bash
php crawler.php
```

直到回显 `Good, all have done!` 信息，说明采集处理完成。

## 联系作者

[ycrao](https://github.com/ycrao)



