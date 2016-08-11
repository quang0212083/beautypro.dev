CREATE TABLE IF NOT EXISTS `#__categoryblock` (
  `id` int(10) NOT NULL auto_increment,
  `profilename` varchar(50) NOT NULL,
  `showtitle` tinyint(1) NOT NULL,
  `showcatdesc` tinyint(1) NOT NULL,
  `columns` smallint(6) NOT NULL,
  `padding` smallint(6) NOT NULL,
  `orderby` varchar(20) NOT NULL,
  `orderdirection` varchar(10) NOT NULL,
  `thelimit` smallint(6) NOT NULL,
  `skipnarticles` smallint(6) NOT NULL,
  `targetwindow` varchar(10) NOT NULL,
  `wordcount` smallint(6) NOT NULL,
  `charcount` smallint(6) NOT NULL,
  `imagewidth` smallint(6) NOT NULL,
  `imageheight` smallint(6) NOT NULL,
  `modulecssstyle` varchar(255) NOT NULL,


  `pagination` smallint(6) NOT NULL,
  `cleanbraces` tinyint(1) NOT NULL,
  `default_image` varchar(255) NOT NULL,
  `modulewidth` smallint(6) NOT NULL,
  `moduleheight` smallint(6) NOT NULL,
  `overflow` varchar(20) NOT NULL,
  `showfeaturedonly` tinyint(1) NOT NULL,
  `recursive` int(1) NOT NULL DEFAULT '0',
  `randomize` int(1) NOT NULL DEFAULT '0',
  `orientation` tinyint(1) NOT NULL,

  `blocklayout` smallint(6) NOT NULL,

  `customblocklayout` text NOT NULL,

  `blockcssstyle` varchar(255) NOT NULL,
  `showarticletitle` tinyint(1) NOT NULL,
  `titlecssstyle` varchar(255) NOT NULL,
  `imagecssstyle` varchar(255) NOT NULL,
  `descriptioncssstyle` varchar(255) NOT NULL,
  `showcreationdate` tinyint(1) NOT NULL,
  `dateformat` varchar(50) NOT NULL,
  `datecssstyle` varchar(255) NOT NULL,
  `showreadmore` tinyint(1) NOT NULL,
  `readmorestyle` varchar(255) NOT NULL,
  `gotocomment` tinyint(1) NOT NULL,
  `titleimagepos` varchar(20) NOT NULL,
  `contentsource` smallint(6) NOT NULL,

  `connectwithmenu` tinyint(1) NOT NULL,

  `customblocklayouttop` text NOT NULL,
  `customblocklayoutbottom` text NOT NULL,

  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
  
