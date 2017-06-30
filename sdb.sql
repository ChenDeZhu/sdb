-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 2017-06-30 03:58:14
-- 服务器版本： 5.7.14
-- PHP Version: 7.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sdb`
--

-- --------------------------------------------------------

--
-- 表的结构 `s_admin`
--

CREATE TABLE `s_admin` (
  `id` smallint(6) UNSIGNED NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` char(32) NOT NULL,
  `nickname` varchar(20) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `lasttime` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `lastip` varchar(20) NOT NULL,
  `encrypt` char(6) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `s_admin`
--

INSERT INTO `s_admin` (`id`, `username`, `password`, `nickname`, `email`, `lasttime`, `lastip`, `encrypt`) VALUES
(1, 'admin', '0f8bf7cdd661d15d6d8a50daf24172d7', 'admin', '420021436@qq.com', 1498719627, '127.0.0.1', '6QsCIe');

-- --------------------------------------------------------

--
-- 表的结构 `s_article`
--

CREATE TABLE `s_article` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(200) DEFAULT '',
  `inputtime` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `updatetime` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `thumb` varchar(100) DEFAULT '',
  `keywords` varchar(100) DEFAULT '',
  `description` text,
  `listorder` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `hits` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `content` text,
  `catid` smallint(5) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `s_article`
--

INSERT INTO `s_article` (`id`, `title`, `inputtime`, `updatetime`, `status`, `thumb`, `keywords`, `description`, `listorder`, `hits`, `content`, `catid`) VALUES
(1, '测试文章', 1498178160, 1498178160, 1, '/uploads/20170623/0c0e05c7aeef8538dfa6f312b292f03e.jpg', '文章213', '111', 1, 1, NULL, 1);

-- --------------------------------------------------------

--
-- 表的结构 `s_article_data`
--

CREATE TABLE `s_article_data` (
  `id` int(10) UNSIGNED NOT NULL,
  `content` text,
  `gallery` text
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `s_article_data`
--

INSERT INTO `s_article_data` (`id`, `content`, `gallery`) VALUES
(1, '<p>文章213文章213文章213</p>', '{\\"0\\":\\"\\\\/uploads\\\\/20170623\\\\/2e56cf11f8e35057e64aa35a905a0c77.jpg\\",\\"1\\":\\"\\\\/uploads\\\\/20170623\\\\/dc735231bafcd3afacea31d7d17ff4d4.jpg\\"}');

-- --------------------------------------------------------

--
-- 表的结构 `s_category`
--

CREATE TABLE `s_category` (
  `catid` smallint(5) UNSIGNED NOT NULL,
  `catname` varchar(30) NOT NULL,
  `pid` smallint(6) UNSIGNED NOT NULL DEFAULT '0',
  `thumb` varchar(100) DEFAULT NULL,
  `description` text,
  `listorder` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `category` varchar(100) NOT NULL,
  `list` varchar(100) NOT NULL,
  `show` varchar(100) NOT NULL,
  `ispart` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `ishidden` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `content` text,
  `keywords` varchar(100) DEFAULT NULL,
  `pn` smallint(5) UNSIGNED NOT NULL DEFAULT '20'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `s_category`
--

INSERT INTO `s_category` (`catid`, `catname`, `pid`, `thumb`, `description`, `listorder`, `category`, `list`, `show`, `ispart`, `ishidden`, `content`, `keywords`, `pn`) VALUES
(1, '第一个栏目', 0, '/uploads/20170623/29c2ae5c19d1b17f2b3d64891b169527.jpg', '', 0, 'category.html', 'list.html', 'show.html', 0, 0, NULL, '', 20);

-- --------------------------------------------------------

--
-- 表的结构 `s_company`
--

CREATE TABLE `s_company` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `qq` int(11) DEFAULT NULL,
  `fax` varchar(255) DEFAULT NULL,
  `mobile` varchar(255) DEFAULT NULL,
  `tel` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `person` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `wx` varchar(255) DEFAULT NULL,
  `icp` varchar(255) DEFAULT NULL,
  `ga_icp` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `s_currency`
--

CREATE TABLE `s_currency` (
  `id` int(11) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL COMMENT '币种名称',
  `price` float NOT NULL COMMENT '0',
  `interest` float NOT NULL DEFAULT '0' COMMENT '利息率',
  `addtime` int(11) DEFAULT NULL,
  `rise` float NOT NULL DEFAULT '0' COMMENT '涨幅'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `s_currency`
--

INSERT INTO `s_currency` (`id`, `logo`, `name`, `price`, `interest`, `addtime`, `rise`) VALUES
(1, NULL, '狗狗币GOGE', 1.01, 0, 1498280563, 0),
(2, NULL, '比特币BTC', 19610.3, 0, 1498280563, 0),
(3, NULL, '未来币NXT', 1.2601, 0, 1498280563, 0),
(4, NULL, '恒星币XLM', 0.2856, 0, 1498280563, 0),
(5, NULL, '微币VASH', 117.38, 0, 1498280563, 0),
(8, NULL, '氪石币XCN', 0, 0, 1498280563, 0),
(9, '/uploads/20170624/a04686f1b07fa757eab84331b62752ff.jpg', '氪石币XCN', 0, 0, 1498280645, 0);

-- --------------------------------------------------------

--
-- 表的结构 `s_deal`
--

CREATE TABLE `s_deal` (
  `id` int(11) NOT NULL,
  `bid` int(11) NOT NULL DEFAULT '0' COMMENT '货币ID',
  `uid` int(11) NOT NULL COMMENT '用户ID',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0：买） （1：卖',
  `price` varchar(255) NOT NULL COMMENT '委托价格',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0未全部交易完成 1交易完成',
  `number` float(12,2) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT '总数量',
  `number_no` float(12,2) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT '交易未完成数量',
  `addtime` int(11) NOT NULL COMMENT '单据创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `s_deal`
--

INSERT INTO `s_deal` (`id`, `bid`, `uid`, `type`, `price`, `status`, `number`, `number_no`, `addtime`) VALUES
(1, 1, 1, 1, '300.00', 0, 1000.00, 990.00, 1498720627),
(2, 1, 1, 1, '200.4', 1, 10.00, 0.00, 1498789541),
(3, 1, 1, 0, '200.4', 1, 10.00, 0.00, 1498789542),
(4, 1, 1, 1, '200.4', 0, 10.00, 10.00, 1498789578),
(5, 1, 1, 0, '200.4', 1, 10.00, 0.00, 1498789580),
(6, 1, 1, 1, '300', 0, 500.00, 220.00, 1498790224),
(7, 1, 1, 0, '300', 1, 50.00, 0.00, 1498790229),
(8, 1, 1, 0, '300', 1, 50.00, 0.00, 1498790248),
(9, 1, 1, 0, '300', 1, 10.00, 0.00, 1498790271),
(10, 1, 1, 0, '300', 1, 10.00, 0.00, 1498790309),
(11, 1, 1, 0, '300', 1, 10.00, 0.00, 1498790630),
(12, 1, 1, 0, '300', 1, 20.00, 0.00, 1498790713),
(13, 1, 1, 0, '300', 1, 20.00, 0.00, 1498791250),
(14, 1, 1, 0, '300', 1, 20.00, 0.00, 1498791289),
(15, 1, 1, 0, '300', 1, 20.00, 0.00, 1498791313),
(16, 1, 1, 0, '200', 0, 10.00, 10.00, 1498791348),
(17, 1, 1, 0, '200', 0, 10.00, 10.00, 1498791350),
(18, 1, 1, 0, '300', 1, 10.00, 0.00, 1498791359),
(19, 1, 1, 0, '300', 1, 10.00, 0.00, 1498791360),
(20, 1, 1, 0, '300', 1, 10.00, 0.00, 1498791362),
(21, 1, 1, 0, '300', 1, 10.00, 0.00, 1498791362),
(22, 1, 1, 0, '300', 1, 10.00, 0.00, 1498791363),
(23, 1, 1, 0, '300', 1, 10.00, 0.00, 1498791363),
(24, 1, 1, 0, '300', 1, 10.00, 0.00, 1498792581),
(25, 1, 1, 0, '3001', 0, 10.00, 10.00, 1498792585),
(26, 1, 1, 0, '300', 1, 10.00, 0.00, 1498792599),
(27, 1, 1, 0, '300.00', 1, 10.00, 0.00, 1498795047),
(28, 1, 1, 0, '300', 1, 10.00, 0.00, 1498795055);

-- --------------------------------------------------------

--
-- 表的结构 `s_field`
--

CREATE TABLE `s_field` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `field` varchar(20) NOT NULL,
  `name` varchar(30) NOT NULL,
  `tips` text,
  `defaultvalue` text,
  `formtype` varchar(20) NOT NULL,
  `issystem` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `listorder` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `disabled` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `length` tinyint(3) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `s_field`
--

INSERT INTO `s_field` (`id`, `field`, `name`, `tips`, `defaultvalue`, `formtype`, `issystem`, `listorder`, `disabled`, `length`) VALUES
(5, 'title', '标题', '', '', 'text', 1, 0, 0, 200),
(1, 'inputtime', '发布时间', '', NULL, 'datetime', 1, 0, 0, 0),
(2, 'updatetime', '更新时间', '', NULL, 'datetime', 1, 0, 0, 0),
(8, 'thumb', '缩略图', '', NULL, 'image', 1, 0, 0, 100),
(6, 'keywords', '关键词', '', '', 'text', 1, 0, 0, 100),
(7, 'description', '描述', '', '', 'textarea', 1, 0, 0, 0),
(3, 'listorder', '排序', '', '0', 'number', 1, 0, 0, 0),
(4, 'hits', '浏览数', '', '0', 'number', 1, 0, 0, 0),
(9, 'content', '内容', '', NULL, 'editor', 0, 0, 0, 0),
(10, 'gallery', '组图', '', NULL, 'images', 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `s_flink`
--

CREATE TABLE `s_flink` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `url` varchar(200) DEFAULT NULL,
  `logo` varchar(200) DEFAULT NULL,
  `listorder` smallint(5) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `s_guestbook`
--

CREATE TABLE `s_guestbook` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `content` text,
  `addtime` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `name` varchar(20) DEFAULT NULL,
  `qq` varchar(20) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `replytime` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `replycontent` text
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `s_infomation`
--

CREATE TABLE `s_infomation` (
  `id` int(11) NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '类别 （0：小社区） （1：新闻 ）（2：常见问题）',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户UID',
  `title` varchar(255) NOT NULL,
  `img` varchar(255) DEFAULT NULL COMMENT '图片',
  `keyword` varchar(255) DEFAULT NULL COMMENT '关键词',
  `description` varchar(255) DEFAULT NULL COMMENT '描述',
  `content` text COMMENT '内容',
  `addtime` int(11) DEFAULT NULL,
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `s_infomation`
--

INSERT INTO `s_infomation` (`id`, `type`, `uid`, `title`, `img`, `keyword`, `description`, `content`, `addtime`, `sort`) VALUES
(1, 0, 0, '印度突然宣布比特币即将合法化', NULL, '货币', '货币', '印度比特币交易所的努力\r\n过去三年里，印度三大比特币交易所Zebpay、Coinsecure和Unocoin运营着自我监管的交易平台，严格遵守KYC和AML规则，尽管当时数字货币行业和市场缺乏监管。\r\n印度比特币交易所自我监管的努力使得印度政府开始思考比特币和数字货币行业，尽管一些政治家批评说加密货币的知识太缺乏。\r\n三月24日，印度BJP议会议员Kirit Somaiya遭到严厉批评，因为他将比特币描述为庞氏骗局。\r\n在致财政部和印度储备银行（Reserve Bank of India）的信中，Somaiya解释，比特币是庞氏骗局的迷宫。然而Somaiya却因为无法理解庞氏骗局和比特币之间结构等一些基本差异，而遭到批评。\r\n\r\n比特币在印度的合法化\r\n尽管一些政治家持消极态度，印度政府还是决定监管该市场，为比特币交易所提供公平的环境，这些交易所已经投入大量资源，进行市场和行业的标准化。\r\n四月份，印度最大比特币交易所之一Coinsecure首席执行官Mohit Kalra说，印度政府最终开始严肃对待比特币，考虑监管该市场。六月20日，CNBC India宣布印度政府委员会决定支持监管比特币，目前正在成立任务组，开始创建各种监管框架，计划短期内全面完成比特币合法化。\r\n印度政府宣布该消息之前，ARK Invest的加密货币总监Chris Burniske说，印度交易量在上涨。Burniske此前透露，印度比特币交易市场处理全球大约11％的比特币对美元交易', NULL, 1);

-- --------------------------------------------------------

--
-- 表的结构 `s_interest`
--

CREATE TABLE `s_interest` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `bid` int(11) DEFAULT NULL,
  `num` int(11) DEFAULT NULL,
  `money` double DEFAULT NULL,
  `addtime` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='获取利息的记录表';

-- --------------------------------------------------------

--
-- 表的结构 `s_loginlog`
--

CREATE TABLE `s_loginlog` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `login_time` int(11) NOT NULL,
  `login_ip` char(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户登陆记录';

-- --------------------------------------------------------

--
-- 表的结构 `s_mrecord`
--

CREATE TABLE `s_mrecord` (
  `id` int(11) NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1：充值）（2：提现',
  `bid` int(11) DEFAULT NULL,
  `money` double NOT NULL DEFAULT '0' COMMENT '金额',
  `addtime` int(11) DEFAULT NULL,
  `status` tinyint(1) NOT NULL COMMENT '0:失败 1：成功'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `s_notice`
--

CREATE TABLE `s_notice` (
  `uid` int(11) NOT NULL COMMENT '用户ID',
  `other_login` tinyint(1) NOT NULL DEFAULT '0' COMMENT '异地登陆（0:不通知）（ 1：通知）',
  `commissioned` tinyint(1) NOT NULL DEFAULT '0' COMMENT '现货计划委托（0:不通知 1：通知',
  `account` tinyint(1) NOT NULL DEFAULT '0' COMMENT '充值到账',
  `login` tinyint(1) NOT NULL DEFAULT '0' COMMENT '登陆',
  `login_lock` tinyint(1) NOT NULL DEFAULT '0' COMMENT '登陆锁定',
  `question` tinyint(1) NOT NULL DEFAULT '0' COMMENT '问题状态提醒',
  `safe` tinyint(1) NOT NULL DEFAULT '0' COMMENT '安全设置',
  `withdraw` tinyint(1) NOT NULL DEFAULT '0' COMMENT '批量撤单确认提示'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `s_page`
--

CREATE TABLE `s_page` (
  `id` int(11) NOT NULL,
  `img` varchar(255) DEFAULT NULL COMMENT '单页背景图',
  `name` varchar(255) NOT NULL COMMENT '单页名字',
  `url` varchar(255) DEFAULT NULL COMMENT '链接地址',
  `content` text COMMENT '内容'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `s_page`
--

INSERT INTO `s_page` (`id`, `img`, `name`, `url`, `content`) VALUES
(1, NULL, '联系我们', NULL, NULL),
(2, NULL, '关于我们', NULL, NULL),
(3, NULL, '用户支持', NULL, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `s_question`
--

CREATE TABLE `s_question` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL COMMENT '标题',
  `img` varchar(255) DEFAULT NULL COMMENT '问题截图',
  `description` text COMMENT '问题描述',
  `status` tinyint(4) DEFAULT '0' COMMENT '问题状态 0:未回复 （1：已回复）',
  `content` text COMMENT '客服回复内容',
  `addtime` int(11) DEFAULT NULL,
  `returntime` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `s_question`
--

INSERT INTO `s_question` (`id`, `uid`, `title`, `img`, `description`, `status`, `content`, `addtime`, `returntime`) VALUES
(1, 1, '我以前绑定的手机现在没用了，现在要解绑需要什么流程', NULL, '我以前绑定的手机现在没用了，现在要解绑需要什么流程', 1, '<p>您好，您可以在客服有问必答上传您手持身份证照片，核实相关信息后，我们可以帮您解绑手机号码，希望能帮到您，祝您生活愉快。</p>', NULL, 1498288924);

-- --------------------------------------------------------

--
-- 表的结构 `s_recruitment`
--

CREATE TABLE `s_recruitment` (
  `id` int(11) NOT NULL,
  `position` varchar(64) NOT NULL COMMENT '职位',
  `salary` double DEFAULT NULL COMMENT '薪水',
  `equirement` varchar(255) DEFAULT NULL COMMENT '要求',
  `content` text,
  `sort` int(11) NOT NULL DEFAULT '0',
  `click` int(11) NOT NULL DEFAULT '0',
  `addtime` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='招聘表';

--
-- 转存表中的数据 `s_recruitment`
--

INSERT INTO `s_recruitment` (`id`, `position`, `salary`, `equirement`, `content`, `sort`, `click`, `addtime`) VALUES
(1, 'IOS工程师', 10000, '1、负责iOS应用的设计与开发；', '<p>1、3年以上面向对象程序设计与开发经验；\r\n2、2年以上iOS应用开发经验，熟悉Objective-C、C或C++，熟练使用iOS SDK进行应用的开发与调试；\r\n3、熟悉iOS应用开发技术，包括UI编程，图形编程，多线程编程，网络编程等；\r\n4、熟悉iOS系统结构；\r\n5、熟悉常用的设计模式、数据结构；\r\n6、沟通协作能力强，自学能力强，能承担工作压力；\r\n7、有金融股票类软件开发经验。者优先</p>', 0, 0, NULL),
(3, 'Java工程师', 8000, '1、负责公司产品线核心功能详细设计和开发，保证开发质量；\r\n2、负责WEB业务项目的开发和维护；\r\n3、参与公司整体技术架构设计和实现。', '<p style="margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(101, 109, 120); line-height: 25px; font-family: &quot;Microsoft YaHei&quot;, Arial, Verdana, sans-serif, &quot;Segoe UI&quot;, Tahoma; white-space: normal;">1、统招本科及以上学历，计算机、软件工程、物理或数学专业；</p><p style="margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(101, 109, 120); line-height: 25px; font-family: &quot;Microsoft YaHei&quot;, Arial, Verdana, sans-serif, &quot;Segoe UI&quot;, Tahoma; white-space: normal;">2、一年以上互联网公司Java开发经验，代码编写规范，编程基础杂实，逻辑思维能力强；</p><p style="margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(101, 109, 120); line-height: 25px; font-family: &quot;Microsoft YaHei&quot;, Arial, Verdana, sans-serif, &quot;Segoe UI&quot;, Tahoma; white-space: normal;">3、精通java语言，了解c、c++、php等常见语言；</p><p style="margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(101, 109, 120); line-height: 25px; font-family: &quot;Microsoft YaHei&quot;, Arial, Verdana, sans-serif, &quot;Segoe UI&quot;, Tahoma; white-space: normal;">4、对整个 J2EE 解决方案有深刻的理解及熟练的应用，精通struts、spring、hibernate等开源框架；</p><p style="margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(101, 109, 120); line-height: 25px; font-family: &quot;Microsoft YaHei&quot;, Arial, Verdana, sans-serif, &quot;Segoe UI&quot;, Tahoma; white-space: normal;">5、熟悉Mysql、sqlserver、oracle等主流数据库中的一种；</p><p style="margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(101, 109, 120); line-height: 25px; font-family: &quot;Microsoft YaHei&quot;, Arial, Verdana, sans-serif, &quot;Segoe UI&quot;, Tahoma; white-space: normal;">6、熟悉html、xhtml、css、javascript、ajax等；</p><p style="margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(101, 109, 120); line-height: 25px; font-family: &quot;Microsoft YaHei&quot;, Arial, Verdana, sans-serif, &quot;Segoe UI&quot;, Tahoma; white-space: normal;">7、熟悉常见的数据结构和算法；</p><p style="margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(101, 109, 120); line-height: 25px; font-family: &quot;Microsoft YaHei&quot;, Arial, Verdana, sans-serif, &quot;Segoe UI&quot;, Tahoma; white-space: normal;">8、工作有激情，能承受压力。</p><p><br/></p>', 1, 2, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `s_system`
--

CREATE TABLE `s_system` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `keywords` varchar(200) DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL,
  `isthumb` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `width` smallint(5) UNSIGNED NOT NULL DEFAULT '320',
  `height` smallint(5) UNSIGNED NOT NULL DEFAULT '240',
  `iswater` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `pwater` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `template_pc` varchar(20) NOT NULL DEFAULT 'default',
  `template_wap` varchar(20) NOT NULL DEFAULT 'default'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `s_system`
--

INSERT INTO `s_system` (`id`, `title`, `keywords`, `description`, `isthumb`, `width`, `height`, `iswater`, `pwater`, `template_pc`, `template_wap`) VALUES
(1, '我的网站1', '我的网站', '我的网站', 0, 320, 240, 0, 0, 'redblog', 'default');

-- --------------------------------------------------------

--
-- 表的结构 `s_tag`
--

CREATE TABLE `s_tag` (
  `tagid` int(10) UNSIGNED NOT NULL,
  `tag` varchar(100) DEFAULT NULL,
  `count` int(11) NOT NULL DEFAULT '0',
  `hits` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `url` varchar(100) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `s_tag`
--

INSERT INTO `s_tag` (`tagid`, `tag`, `count`, `hits`, `url`) VALUES
(1, '文章213', 1, 1, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `s_tag_data`
--

CREATE TABLE `s_tag_data` (
  `id` int(10) UNSIGNED NOT NULL,
  `tagid` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `contentid` int(10) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `s_tag_data`
--

INSERT INTO `s_tag_data` (`id`, `tagid`, `contentid`) VALUES
(1, 1, 1);

-- --------------------------------------------------------

--
-- 表的结构 `s_u-b`
--

CREATE TABLE `s_u-b` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `bid` int(11) NOT NULL,
  `number` float(12,4) NOT NULL DEFAULT '0.0000' COMMENT '数量'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `s_u-b`
--

INSERT INTO `s_u-b` (`id`, `uid`, `bid`, `number`) VALUES
(1, 1, 1, 260.0000),
(2, 1, 2, 0.0000),
(3, 1, 3, 0.0000),
(4, 1, 4, 0.0000);

-- --------------------------------------------------------

--
-- 表的结构 `s_ucard`
--

CREATE TABLE `s_ucard` (
  `id` int(11) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `cid` varchar(255) DEFAULT NULL COMMENT '银行标识',
  `card_number` int(11) DEFAULT NULL COMMENT '卡号',
  `addtime` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `s_user`
--

CREATE TABLE `s_user` (
  `uid` int(11) NOT NULL,
  `mobile` varchar(255) DEFAULT NULL,
  `nickname` varchar(255) DEFAULT NULL,
  `money` varchar(255) NOT NULL DEFAULT '0.00' COMMENT '余额',
  `wechat` varchar(255) DEFAULT NULL,
  `qq` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `login_pwd` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `mobile_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '手机绑定',
  `email_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '邮箱验证',
  `deal_pwd` varchar(255) DEFAULT NULL,
  `id_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '身份证',
  `reg_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `s_user`
--

INSERT INTO `s_user` (`uid`, `mobile`, `nickname`, `money`, `wechat`, `qq`, `avatar`, `login_pwd`, `email`, `mobile_status`, `email_status`, `deal_pwd`, `id_status`, `reg_time`) VALUES
(0, NULL, '系统', '0.00', NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL),
(1, '18805813155', '陈得柱', '9036368.6', NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `s_admin`
--
ALTER TABLE `s_admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `s_article`
--
ALTER TABLE `s_article`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `s_article_data`
--
ALTER TABLE `s_article_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `s_category`
--
ALTER TABLE `s_category`
  ADD PRIMARY KEY (`catid`);

--
-- Indexes for table `s_company`
--
ALTER TABLE `s_company`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `s_currency`
--
ALTER TABLE `s_currency`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `s_deal`
--
ALTER TABLE `s_deal`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `s_field`
--
ALTER TABLE `s_field`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `s_flink`
--
ALTER TABLE `s_flink`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `s_guestbook`
--
ALTER TABLE `s_guestbook`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `s_infomation`
--
ALTER TABLE `s_infomation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `s_interest`
--
ALTER TABLE `s_interest`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `s_mrecord`
--
ALTER TABLE `s_mrecord`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `s_notice`
--
ALTER TABLE `s_notice`
  ADD PRIMARY KEY (`uid`);

--
-- Indexes for table `s_page`
--
ALTER TABLE `s_page`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `s_question`
--
ALTER TABLE `s_question`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `s_recruitment`
--
ALTER TABLE `s_recruitment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `s_system`
--
ALTER TABLE `s_system`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `s_tag`
--
ALTER TABLE `s_tag`
  ADD PRIMARY KEY (`tagid`),
  ADD KEY `keyword` (`tag`);

--
-- Indexes for table `s_tag_data`
--
ALTER TABLE `s_tag_data`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tagid` (`tagid`);

--
-- Indexes for table `s_u-b`
--
ALTER TABLE `s_u-b`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uid` (`uid`);

--
-- Indexes for table `s_user`
--
ALTER TABLE `s_user`
  ADD PRIMARY KEY (`uid`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `s_admin`
--
ALTER TABLE `s_admin`
  MODIFY `id` smallint(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- 使用表AUTO_INCREMENT `s_article`
--
ALTER TABLE `s_article`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- 使用表AUTO_INCREMENT `s_category`
--
ALTER TABLE `s_category`
  MODIFY `catid` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- 使用表AUTO_INCREMENT `s_company`
--
ALTER TABLE `s_company`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `s_currency`
--
ALTER TABLE `s_currency`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- 使用表AUTO_INCREMENT `s_deal`
--
ALTER TABLE `s_deal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
--
-- 使用表AUTO_INCREMENT `s_field`
--
ALTER TABLE `s_field`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- 使用表AUTO_INCREMENT `s_flink`
--
ALTER TABLE `s_flink`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `s_guestbook`
--
ALTER TABLE `s_guestbook`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `s_infomation`
--
ALTER TABLE `s_infomation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- 使用表AUTO_INCREMENT `s_interest`
--
ALTER TABLE `s_interest`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `s_mrecord`
--
ALTER TABLE `s_mrecord`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `s_page`
--
ALTER TABLE `s_page`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- 使用表AUTO_INCREMENT `s_question`
--
ALTER TABLE `s_question`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- 使用表AUTO_INCREMENT `s_recruitment`
--
ALTER TABLE `s_recruitment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- 使用表AUTO_INCREMENT `s_system`
--
ALTER TABLE `s_system`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- 使用表AUTO_INCREMENT `s_tag`
--
ALTER TABLE `s_tag`
  MODIFY `tagid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- 使用表AUTO_INCREMENT `s_tag_data`
--
ALTER TABLE `s_tag_data`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- 使用表AUTO_INCREMENT `s_u-b`
--
ALTER TABLE `s_u-b`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- 使用表AUTO_INCREMENT `s_user`
--
ALTER TABLE `s_user`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
