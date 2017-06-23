-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 2017-06-23 08:41:34
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
(1, 'admin', '0f8bf7cdd661d15d6d8a50daf24172d7', 'admin', '420021436@qq.com', 1498177194, '127.0.0.1', '6QsCIe');

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
(1, '测试文章', 1498178160, 1498178160, 1, '/uploads/20170623/0c0e05c7aeef8538dfa6f312b292f03e.jpg', '文章213', '111', 1, 0, NULL, 1);

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
  `name` varchar(255) DEFAULT NULL COMMENT '币种名称',
  `price` double DEFAULT NULL,
  `interest` float NOT NULL DEFAULT '0' COMMENT '利息率',
  `addtime` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `s_currency`
--

INSERT INTO `s_currency` (`id`, `name`, `price`, `interest`, `addtime`) VALUES
(1, '狗狗币', 1.01, 0, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `s_deal`
--

CREATE TABLE `s_deal` (
  `id` int(11) NOT NULL,
  `bid` int(11) NOT NULL DEFAULT '0' COMMENT '货币ID',
  `uid` int(11) DEFAULT NULL COMMENT '用户ID',
  `type` tinyint(1) DEFAULT NULL,
  `price` double DEFAULT NULL,
  `number` int(11) DEFAULT NULL,
  `addtime` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
  `img` varchar(255) DEFAULT NULL COMMENT '图片',
  `description` varchar(255) DEFAULT NULL COMMENT '描述',
  `content` text COMMENT '内容',
  `addtime` int(11) DEFAULT NULL,
  `sort` int(11) NOT NULL COMMENT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
  `returntme` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `s_recruitment`
--

CREATE TABLE `s_recruitment` (
  `id` int(11) NOT NULL,
  `position` varchar(64) NOT NULL COMMENT '职位',
  `salary` double DEFAULT NULL COMMENT '薪水',
  `equirement` varchar(255) DEFAULT NULL COMMENT '要求',
  `content` text
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='招聘表';

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
(1, '文章213', 1, 0, NULL);

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
  `uid` int(11) DEFAULT NULL,
  `bid` int(11) DEFAULT NULL,
  `num` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
  `wechat` varchar(255) DEFAULT NULL,
  `qq` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `login_pwd` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `email_status` tinyint(1) DEFAULT NULL,
  `deal_pwd` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- 使用表AUTO_INCREMENT `s_deal`
--
ALTER TABLE `s_deal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
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
-- 使用表AUTO_INCREMENT `s_question`
--
ALTER TABLE `s_question`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `s_recruitment`
--
ALTER TABLE `s_recruitment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
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
-- 使用表AUTO_INCREMENT `s_user`
--
ALTER TABLE `s_user`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
