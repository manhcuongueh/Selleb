DROP TABLE IF EXISTS `shop_banner`;
CREATE TABLE IF NOT EXISTS `shop_banner` (
  `index_no` int(11) NOT NULL auto_increment,
  `mb_id` varchar(20) NOT NULL,
  `bn_theme` varchar(255) NOT NULL default 'basic',
  `bn_mobile_theme` varchar(255) NOT NULL default 'basic',
  `bn_code` tinyint(4) NOT NULL default '0',
  `bn_file` varchar(255) NOT NULL,
  `bn_link` varchar(255) NOT NULL,
  `bn_target` varchar(10) NOT NULL,
  `bn_width` int(11) NOT NULL default '0',
  `bn_height` int(11) NOT NULL default '0',
  `bn_bg` varchar(10) NOT NULL,
  `bn_text` varchar(255) NOT NULL,
  `bn_use` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`index_no`),
  KEY `mb_id` (`mb_id`),
  KEY `bn_code` (`bn_code`),
  KEY `bn_use` (`bn_use`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=22 ;

INSERT INTO `shop_banner` (`index_no`, `mb_id`, `bn_theme`, `bn_mobile_theme`, `bn_code`, `bn_file`, `bn_link`, `bn_target`, `bn_width`, `bn_height`, `bn_bg`, `bn_text`, `bn_use`) VALUES
(1, 'admin', 'basic', 'basic', 3, 'Wu8kdNjC5zMm6HRzVtzmZ8tg1CRfb6.jpg', '', '_self', 280, 400, '', '', 0),
(2, 'admin', 'basic', 'basic', 2, '6hTUUw67mkPz6ZJkf74TBdDBk2bqZG.gif', '', '_self', 160, 60, '', '', 0),
(3, 'admin', 'basic', 'basic', 100, '9Gwm8PfnrTj5KtHuqYnwwJHBTpQkAM.jpg', '', '_self', 960, 120, '', '', 0),
(4, 'admin', 'basic', 'basic', 101, 'LYdCxA4wbDKpk3vpXYC7ZnZ45WRsZ9.jpg', '', '_self', 475, 270, '', '', 0),
(5, 'admin', 'basic', 'basic', 4, 'AVnBX5paYMDMhc6kr5ndWq7vnEBjHK.jpg', '', '_self', 420, 195, '', '', 0),
(6, 'admin', 'basic', 'basic', 5, 'NGgjGw9KKy1Uc5bXteFsRFYeh16bw4.jpg', '', '_self', 420, 195, '', '', 0),
(7, 'admin', 'basic', 'basic', 6, 'dC5wjsjhj5CyM6RWReRp8NJCm9SsJt.jpg', '', '_self', 1000, 200, '', '', 0),
(8, 'admin', 'basic', 'basic', 7, 'eDgCtJytyjdaAt6s3NpC4ZGE1UZV74.jpg', '', '_self', 1920, 2880, '', '배경은 7번 배너이미지 입니다. <br>여기는 배너 텍스트를 넣어주세요.', 0),
(9, 'admin', 'basic', 'basic', 8, 'jfrGcAfEHjxUSJudVWJSzX2ZuErmJZ.jpg', '', '_self', 480, 290, '', '', 0),
(10, 'admin', 'basic', 'basic', 9, 'Hmgye7tWqqelSug53Fql3c7tMhKQ96.jpg', '', '_self', 200, 290, '', '', 0),
(11, 'admin', 'basic', 'basic', 11, 'zxBj8DMqbQG364XuKMxSaUSx9RLm7M.jpg', '', '_self', 300, 500, '', '', 0),
(12, 'admin', 'basic', 'basic', 10, 'ASChUd9h6Vl5UxlsHhqzETzy7NnRsm.jpg', '', '_self', 690, 200, '', '', 0),
(13, 'admin', 'basic', 'basic', 1, 'szEreKjpPshELPSNnS8esg8RXvRd5T.jpg', '', '_self', 1000, 70, '#f2877f', '', 0),
(14, 'admin', 'basic', 'basic', 90, 'EPULGkW4hHbVD93V3GqblQpurxErgz.png', '', '_self', 80, 80, '', '', 0),
(15, 'admin', 'basic', 'basic', 90, 'rkBbmRPAfzbav4j2u2msEszx4LZeXX.png', '', '_self', 80, 80, '', '', 0),
(16, 'admin', 'basic', 'basic', 102, 'VFavnYbGvhGE4rjwedA5V4p5wYYGkT.jpg', '', '_self', 475, 270, '', '', 0),
(17, 'admin', 'basic', 'basic', 103, 'dStrtRCPZ3FhRr7MLA7pxdyMrWK5lM.jpg', '', '_self', 960, 233, '', '', 0),
(18, 'admin', 'basic', 'basic', 104, 'uP2P9k8F4uk5bUctjwb72kJzX8kvtN.jpg', '', '_self', 960, 300, '', '', 0),
(19, 'admin', 'basic', 'basic', 105, 'HPdwLbj1hguccmtZFXCVQTwfaganyL.jpg', '', '_self', 960, 300, '', '', 0),
(20, 'admin', 'basic', 'basic', 106, 'fHw1ceKKy3jXusysbVA4gUC3ky1G29.jpg', '', '_self', 960, 300, '', '', 0),
(21, 'admin', 'basic', 'basic', 107, 'AKepcVeCNBCx7Htd9WfkRWZ5xvkzeK.jpg', '', '_self', 960, 300, '', '', 0);


DROP TABLE IF EXISTS `shop_banner_intro`;
CREATE TABLE IF NOT EXISTS `shop_banner_intro` (
  `bn_id` int(11) NOT NULL auto_increment,
  `mb_id` varchar(20) NOT NULL,
  `bn_file` varchar(255) NOT NULL,
  `bn_code` tinyint(4) NOT NULL default '0',
  `bn_link` varchar(255) NOT NULL,
  `bn_target` varchar(10) NOT NULL,
  `bn_width` int(11) NOT NULL default '0',
  `bn_height` int(11) NOT NULL default '0',
  `bn_use` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`bn_id`),
  KEY `mb_id` (`mb_id`),
  KEY `bn_code` (`bn_code`),
  KEY `bn_use` (`bn_use`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

INSERT INTO `shop_banner_intro` (`bn_id`, `mb_id`, `bn_file`, `bn_code`, `bn_link`, `bn_target`, `bn_width`, `bn_height`, `bn_use`) VALUES
(1, 'admin', 'inbn_11499.gif', 1, '', '_self', 410, 410, 0);


DROP TABLE IF EXISTS `shop_banner_slider`;
CREATE TABLE IF NOT EXISTS `shop_banner_slider` (
  `index_no` int(11) NOT NULL auto_increment,
  `mb_id` varchar(20) NOT NULL,
  `bn_device` varchar(10) NOT NULL default 'pc',
  `bn_theme` varchar(255) NOT NULL default 'basic',
  `bn_mobile_theme` varchar(255) NOT NULL default 'basic',
  `bn_rank` tinyint(4) NOT NULL default '0',
  `bn_file` varchar(255) NOT NULL,
  `bn_link` varchar(255) NOT NULL,
  `bn_target` varchar(10) NOT NULL,
  `bn_width` int(11) NOT NULL default '0',
  `bn_height` int(11) NOT NULL default '0',
  `bn_bg` varchar(10) NOT NULL,
  `bn_text` varchar(255) NOT NULL,
  `bn_use` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`index_no`),
  KEY `mb_id` (`mb_id`),
  KEY `bn_use` (`bn_use`),
  KEY `bn_rank` (`bn_rank`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

INSERT INTO `shop_banner_slider` (`index_no`, `mb_id`, `bn_device`, `bn_theme`, `bn_mobile_theme`, `bn_rank`, `bn_file`, `bn_link`, `bn_target`, `bn_width`, `bn_height`, `bn_bg`, `bn_text`, `bn_use`) VALUES
(1, 'admin', 'pc', 'basic', 'basic', 1, 'Q9Kb37h7rETJmvCA8R31bkkxrjUq6Z.jpg', '', '_self', 1000, 400, '#eeeeee', '1번 텍스트', 0),
(2, 'admin', 'pc', 'basic', 'basic', 2, 'c4nVXYmE6PnNnGtz2vHLmaZdXWJtE3.jpg', '', '_self', 1000, 400, '#e7edfa', '2번 텍스트', 0),
(3, 'admin', 'pc', 'basic', 'basic', 3, 'eCx2X32v8tmnS2drdKQgCAjWYF8nfF.jpg', '', '_self', 1000, 400, '#fee3df', '3번 텍스트', 0),
(4, 'admin', 'mobile', 'basic', 'basic', 1, '5Zn5J4Mzn3wUelnjKkWVj5FAvqahMG.jpg', '', '_self', 960, 720, '', '', 0),
(5, 'admin', 'mobile', 'basic', 'basic', 2, 'W9ssZMNNKc3RbASPejwLJG2qHLlpM4.jpg', '', '_self', 960, 720, '', '', 0),
(6, 'admin', 'mobile', 'basic', 'basic', 3, 'gcrqZeHbZzVmUeufhrfTmr9wHbx5GW.jpg', '', '_self', 960, 720, '', '', 0);


DROP TABLE IF EXISTS `shop_board_13`;
CREATE TABLE IF NOT EXISTS `shop_board_13` (
  `index_no` int(11) unsigned NOT NULL auto_increment COMMENT '주키',
  `btype` int(11) unsigned NOT NULL default '0' COMMENT '형식 ("1":공지,"2":회원글)',
  `fid` int(11) unsigned NOT NULL default '0' COMMENT '글등록 순서',
  `ca_name` varchar(100) NOT NULL COMMENT '분류',
  `issecret` char(1) NOT NULL COMMENT '비밀글("Y":비밀글,"N":공개)',
  `havehtml` char(1) NOT NULL COMMENT 'HTML 사용여부(미사용)',
  `writer` int(11) unsigned NOT NULL default '0' COMMENT '회원 테이블 주키',
  `writer_s` varchar(50) NOT NULL COMMENT '회원(작성자)명',
  `subject` varchar(200) NOT NULL COMMENT '글제목',
  `memo` text NOT NULL COMMENT '글내용',
  `fileurl1` varchar(50) NOT NULL COMMENT '파일첨부,1',
  `fileurl2` varchar(50) NOT NULL COMMENT '파일첨부,2',
  `readcount` int(11) unsigned NOT NULL default '0' COMMENT '글 조회수',
  `tailcount` int(11) unsigned NOT NULL default '0' COMMENT '댓글 카운터',
  `wdate` int(11) unsigned NOT NULL default '0' COMMENT '등록일',
  `wip` varchar(20) NOT NULL COMMENT '작성자 아이피',
  `thread` varchar(255) NOT NULL COMMENT '답글체크',
  `passwd` varchar(20) NOT NULL COMMENT '글작성 패스워드',
  `average` char(1) NOT NULL COMMENT '구매후기 게시판전용 (상품평점)',
  `product` varchar(50) NOT NULL COMMENT '구매후기 게시판전용 (상품번호 및 주문번호)',
  `pt_id` varchar(20) NOT NULL COMMENT '가맹점ID',
  PRIMARY KEY  (`index_no`),
  KEY `btype` (`btype`,`fid`,`wdate`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='공지사항' AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `shop_board_13_tail`;
CREATE TABLE IF NOT EXISTS `shop_board_13_tail` (
  `index_no` int(11) unsigned NOT NULL auto_increment COMMENT '주키',
  `board_index` int(11) unsigned NOT NULL default '0' COMMENT '게시판 주키',
  `writer` int(11) unsigned NOT NULL default '0' COMMENT '회원 테이블주키',
  `writer_s` varchar(30) NOT NULL COMMENT '회원(작성자)명',
  `memo` text NOT NULL COMMENT '글내용',
  `wdate` int(11) unsigned NOT NULL default '0' COMMENT '작성일',
  `wip` varchar(20) NOT NULL COMMENT '작성자 아이피',
  `passwd` varchar(20) NOT NULL COMMENT '작성자 패스워드',
  PRIMARY KEY  (`index_no`),
  KEY `board_index` (`board_index`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `shop_board_20`;
CREATE TABLE IF NOT EXISTS `shop_board_20` (
  `index_no` int(11) unsigned NOT NULL auto_increment COMMENT '주키',
  `btype` int(11) unsigned NOT NULL default '0' COMMENT '형식 ("1":공지,"2":회원글)',
  `fid` int(11) unsigned NOT NULL default '0' COMMENT '글등록 순서',
  `ca_name` varchar(100) NOT NULL COMMENT '분류',
  `issecret` char(1) NOT NULL COMMENT '비밀글("Y":비밀글,"N":공개)',
  `havehtml` char(1) NOT NULL COMMENT 'HTML 사용여부(미사용)',
  `writer` int(11) unsigned NOT NULL default '0' COMMENT '회원 테이블 주키',
  `writer_s` varchar(50) NOT NULL COMMENT '회원(작성자)명',
  `subject` varchar(200) NOT NULL COMMENT '글제목',
  `memo` text NOT NULL COMMENT '글내용',
  `fileurl1` varchar(50) NOT NULL COMMENT '파일첨부,1',
  `fileurl2` varchar(50) NOT NULL COMMENT '파일첨부,2',
  `readcount` int(11) unsigned NOT NULL default '0' COMMENT '글 조회수',
  `tailcount` int(11) unsigned NOT NULL default '0' COMMENT '댓글 카운터',
  `wdate` int(11) unsigned NOT NULL default '0' COMMENT '등록일',
  `wip` varchar(20) NOT NULL COMMENT '작성자 아이피',
  `thread` varchar(255) NOT NULL COMMENT '답글체크',
  `passwd` varchar(20) NOT NULL COMMENT '글작성 패스워드',
  `average` char(1) NOT NULL COMMENT '구매후기 게시판전용 (상품평점)',
  `product` varchar(50) NOT NULL COMMENT '구매후기 게시판전용 (상품번호 및 주문번호)',
  `pt_id` varchar(20) NOT NULL COMMENT '가맹점ID',
  PRIMARY KEY  (`index_no`),
  KEY `btype` (`btype`,`fid`,`wdate`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='업체 공지사항' AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `shop_board_20_tail`;
CREATE TABLE IF NOT EXISTS `shop_board_20_tail` (
  `index_no` int(11) unsigned NOT NULL auto_increment COMMENT '주키',
  `board_index` int(11) unsigned NOT NULL default '0' COMMENT '게시판 주키',
  `writer` int(11) unsigned NOT NULL default '0' COMMENT '회원 테이블주키',
  `writer_s` varchar(30) NOT NULL COMMENT '회원(작성자)명',
  `memo` text NOT NULL COMMENT '글내용',
  `wdate` int(11) unsigned NOT NULL default '0' COMMENT '작성일',
  `wip` varchar(20) NOT NULL COMMENT '작성자 아이피',
  `passwd` varchar(20) NOT NULL COMMENT '작성자 패스워드',
  PRIMARY KEY  (`index_no`),
  KEY `board_index` (`board_index`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `shop_board_21`;
CREATE TABLE IF NOT EXISTS `shop_board_21` (
  `index_no` int(11) unsigned NOT NULL auto_increment COMMENT '주키',
  `btype` int(11) unsigned NOT NULL default '0' COMMENT '형식 ("1":공지,"2":회원글)',
  `fid` int(11) unsigned NOT NULL default '0' COMMENT '글등록 순서',
  `ca_name` varchar(100) NOT NULL COMMENT '분류',
  `issecret` char(1) NOT NULL COMMENT '비밀글("Y":비밀글,"N":공개)',
  `havehtml` char(1) NOT NULL COMMENT 'HTML 사용여부(미사용)',
  `writer` int(11) unsigned NOT NULL default '0' COMMENT '회원 테이블 주키',
  `writer_s` varchar(50) NOT NULL COMMENT '회원(작성자)명',
  `subject` varchar(200) NOT NULL COMMENT '글제목',
  `memo` text NOT NULL COMMENT '글내용',
  `fileurl1` varchar(50) NOT NULL COMMENT '파일첨부,1',
  `fileurl2` varchar(50) NOT NULL COMMENT '파일첨부,2',
  `readcount` int(11) unsigned NOT NULL default '0' COMMENT '글 조회수',
  `tailcount` int(11) unsigned NOT NULL default '0' COMMENT '댓글 카운터',
  `wdate` int(11) unsigned NOT NULL default '0' COMMENT '등록일',
  `wip` varchar(20) NOT NULL COMMENT '작성자 아이피',
  `thread` varchar(255) NOT NULL COMMENT '답글체크',
  `passwd` varchar(20) NOT NULL COMMENT '글작성 패스워드',
  `average` char(1) NOT NULL COMMENT '구매후기 게시판전용 (상품평점)',
  `product` varchar(50) NOT NULL COMMENT '구매후기 게시판전용 (상품번호 및 주문번호)',
  `pt_id` varchar(20) NOT NULL COMMENT '가맹점ID',
  PRIMARY KEY  (`index_no`),
  KEY `btype` (`btype`,`fid`,`wdate`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='업체 질문과답변' AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `shop_board_21_tail`;
CREATE TABLE IF NOT EXISTS `shop_board_21_tail` (
  `index_no` int(11) unsigned NOT NULL auto_increment COMMENT '주키',
  `board_index` int(11) unsigned NOT NULL default '0' COMMENT '게시판 주키',
  `writer` int(11) unsigned NOT NULL default '0' COMMENT '회원 테이블주키',
  `writer_s` varchar(30) NOT NULL COMMENT '회원(작성자)명',
  `memo` text NOT NULL COMMENT '글내용',
  `wdate` int(11) unsigned NOT NULL default '0' COMMENT '작성일',
  `wip` varchar(20) NOT NULL COMMENT '작성자 아이피',
  `passwd` varchar(20) NOT NULL COMMENT '작성자 패스워드',
  PRIMARY KEY  (`index_no`),
  KEY `board_index` (`board_index`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `shop_board_22`;
CREATE TABLE IF NOT EXISTS `shop_board_22` (
  `index_no` int(11) unsigned NOT NULL auto_increment COMMENT '주키',
  `btype` int(11) unsigned NOT NULL default '0' COMMENT '형식 ("1":공지,"2":회원글)',
  `fid` int(11) unsigned NOT NULL default '0' COMMENT '글등록 순서',
  `ca_name` varchar(100) NOT NULL COMMENT '분류',
  `issecret` char(1) NOT NULL COMMENT '비밀글("Y":비밀글,"N":공개)',
  `havehtml` char(1) NOT NULL COMMENT 'HTML 사용여부(미사용)',
  `writer` int(11) unsigned NOT NULL default '0' COMMENT '회원 테이블 주키',
  `writer_s` varchar(50) NOT NULL COMMENT '회원(작성자)명',
  `subject` varchar(200) NOT NULL COMMENT '글제목',
  `memo` text NOT NULL COMMENT '글내용',
  `fileurl1` varchar(50) NOT NULL COMMENT '파일첨부,1',
  `fileurl2` varchar(50) NOT NULL COMMENT '파일첨부,2',
  `readcount` int(11) unsigned NOT NULL default '0' COMMENT '글 조회수',
  `tailcount` int(11) unsigned NOT NULL default '0' COMMENT '댓글 카운터',
  `wdate` int(11) unsigned NOT NULL default '0' COMMENT '등록일',
  `wip` varchar(20) NOT NULL COMMENT '작성자 아이피',
  `thread` varchar(255) NOT NULL COMMENT '답글체크',
  `passwd` varchar(20) NOT NULL COMMENT '글작성 패스워드',
  `average` char(1) NOT NULL COMMENT '구매후기 게시판전용 (상품평점)',
  `product` varchar(50) NOT NULL COMMENT '구매후기 게시판전용 (상품번호 및 주문번호)',
  `pt_id` varchar(20) NOT NULL COMMENT '가맹점ID',
  PRIMARY KEY  (`index_no`),
  KEY `btype` (`btype`,`fid`,`wdate`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='회사홈페이지 공지사항' AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `shop_board_22_tail`;
CREATE TABLE IF NOT EXISTS `shop_board_22_tail` (
  `index_no` int(11) unsigned NOT NULL auto_increment COMMENT '주키',
  `board_index` int(11) unsigned NOT NULL default '0' COMMENT '게시판 주키',
  `writer` int(11) unsigned NOT NULL default '0' COMMENT '회원 테이블주키',
  `writer_s` varchar(30) NOT NULL COMMENT '회원(작성자)명',
  `memo` text NOT NULL COMMENT '글내용',
  `wdate` int(11) unsigned NOT NULL default '0' COMMENT '작성일',
  `wip` varchar(20) NOT NULL COMMENT '작성자 아이피',
  `passwd` varchar(20) NOT NULL COMMENT '작성자 패스워드',
  PRIMARY KEY  (`index_no`),
  KEY `board_index` (`board_index`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `shop_board_36`;
CREATE TABLE IF NOT EXISTS `shop_board_36` (
  `index_no` int(11) unsigned NOT NULL auto_increment COMMENT '주키',
  `btype` int(11) unsigned NOT NULL default '0' COMMENT '형식 ("1":공지,"2":회원글)',
  `fid` int(11) unsigned NOT NULL default '0' COMMENT '글등록 순서',
  `ca_name` varchar(100) NOT NULL COMMENT '분류',
  `issecret` char(1) NOT NULL COMMENT '비밀글("Y":비밀글,"N":공개)',
  `havehtml` char(1) NOT NULL COMMENT 'HTML 사용여부(미사용)',
  `writer` int(11) unsigned NOT NULL default '0' COMMENT '회원 테이블 주키',
  `writer_s` varchar(50) NOT NULL COMMENT '회원(작성자)명',
  `subject` varchar(200) NOT NULL COMMENT '글제목',
  `memo` text NOT NULL COMMENT '글내용',
  `fileurl1` varchar(50) NOT NULL COMMENT '파일첨부,1',
  `fileurl2` varchar(50) NOT NULL COMMENT '파일첨부,2',
  `readcount` int(11) unsigned NOT NULL default '0' COMMENT '글 조회수',
  `tailcount` int(11) unsigned NOT NULL default '0' COMMENT '댓글 카운터',
  `wdate` int(11) unsigned NOT NULL default '0' COMMENT '등록일',
  `wip` varchar(20) NOT NULL COMMENT '작성자 아이피',
  `thread` varchar(255) NOT NULL COMMENT '답글체크',
  `passwd` varchar(20) NOT NULL COMMENT '글작성 패스워드',
  `average` char(1) NOT NULL COMMENT '구매후기 게시판전용 (상품평점)',
  `product` varchar(50) NOT NULL COMMENT '구매후기 게시판전용 (상품번호 및 주문번호)',
  `pt_id` varchar(20) NOT NULL COMMENT '가맹점ID',
  PRIMARY KEY  (`index_no`),
  KEY `btype` (`btype`,`fid`,`wdate`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='회사홈페이지 질문과답변' AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `shop_board_36_tail`;
CREATE TABLE IF NOT EXISTS `shop_board_36_tail` (
  `index_no` int(11) unsigned NOT NULL auto_increment COMMENT '주키',
  `board_index` int(11) unsigned NOT NULL default '0' COMMENT '게시판 주키',
  `writer` int(11) unsigned NOT NULL default '0' COMMENT '회원 테이블주키',
  `writer_s` varchar(30) NOT NULL COMMENT '회원(작성자)명',
  `memo` text NOT NULL COMMENT '글내용',
  `wdate` int(11) unsigned NOT NULL default '0' COMMENT '작성일',
  `wip` varchar(20) NOT NULL COMMENT '작성자 아이피',
  `passwd` varchar(20) NOT NULL COMMENT '작성자 패스워드',
  PRIMARY KEY  (`index_no`),
  KEY `board_index` (`board_index`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `shop_board_41`;
CREATE TABLE IF NOT EXISTS `shop_board_41` (
  `index_no` int(10) unsigned NOT NULL auto_increment COMMENT '주키',
  `btype` int(10) unsigned NOT NULL default '0' COMMENT '형식 (1:공지,2:회원글)',
  `fid` int(10) unsigned NOT NULL default '0' COMMENT '글등록 순서',
  `ca_name` varchar(100) NOT NULL COMMENT '분류',
  `issecret` char(1) NOT NULL COMMENT '비밀글(Y:비밀글,N:공개)',
  `havehtml` char(1) NOT NULL COMMENT 'HTML 사용여부(미사용)',
  `writer` int(10) unsigned NOT NULL default '0' COMMENT '회원 테이블 주키',
  `writer_s` varchar(50) NOT NULL COMMENT '회원(작성자)명',
  `subject` varchar(200) NOT NULL COMMENT '글제목',
  `memo` text NOT NULL COMMENT '글내용',
  `fileurl1` varchar(50) NOT NULL COMMENT '파일첨부,1',
  `fileurl2` varchar(50) NOT NULL COMMENT '파일첨부,2',
  `readcount` int(10) unsigned NOT NULL default '0' COMMENT '글 조회수',
  `tailcount` int(10) unsigned NOT NULL default '0' COMMENT '댓글 카운터',
  `wdate` int(10) unsigned NOT NULL default '0' COMMENT '등록일',
  `wip` varchar(20) NOT NULL COMMENT '작성자 아이피',
  `thread` varchar(255) NOT NULL COMMENT '답글체크',
  `passwd` varchar(20) NOT NULL COMMENT '글작성 패스워드',
  `average` char(1) NOT NULL COMMENT '구매후기 게시판전용 (상품평점)',
  `product` varchar(50) NOT NULL COMMENT '구매후기 게시판전용 (상품번호 및 주문번호)',
  `pt_id` varchar(20) NOT NULL COMMENT '가맹점ID',
  PRIMARY KEY  (`index_no`),
  KEY `btype` (`btype`,`fid`,`wdate`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='공지사항' AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `shop_board_41_tail`;
CREATE TABLE IF NOT EXISTS `shop_board_41_tail` (
  `index_no` int(10) unsigned NOT NULL auto_increment COMMENT '주키',
  `board_index` int(10) unsigned NOT NULL default '0' COMMENT '게시판 주키',
  `writer` int(10) unsigned NOT NULL default '0' COMMENT '회원 테이블주키',
  `writer_s` varchar(30) NOT NULL COMMENT '회원(작성자)명',
  `memo` text NOT NULL COMMENT '글내용',
  `wdate` int(10) unsigned NOT NULL default '0' COMMENT '작성일',
  `wip` varchar(20) NOT NULL COMMENT '작성자 아이피',
  `passwd` varchar(20) NOT NULL COMMENT '작성자 패스워드',
  PRIMARY KEY  (`index_no`),
  KEY `board_index` (`board_index`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `shop_board_conf`;
CREATE TABLE IF NOT EXISTS `shop_board_conf` (
  `index_no` int(11) NOT NULL auto_increment,
  `gr_id` varchar(100) NOT NULL,
  `skin` varchar(50) NOT NULL,
  `list_skin` varchar(50) NOT NULL,
  `boardname` varchar(255) NOT NULL,
  `list_priv` tinyint(4) NOT NULL default '0',
  `read_priv` tinyint(4) NOT NULL default '0',
  `write_priv` tinyint(4) NOT NULL default '0',
  `reply_priv` tinyint(4) NOT NULL default '0',
  `tail_priv` tinyint(4) NOT NULL default '0',
  `topfile` varchar(255) NOT NULL,
  `downfile` varchar(255) NOT NULL,
  `use_secret` tinyint(4) NOT NULL default '0',
  `use_category` tinyint(4) NOT NULL default '0',
  `usecate` varchar(255) NOT NULL,
  `usefile` char(1) NOT NULL,
  `usereply` char(1) NOT NULL,
  `usetail` char(1) NOT NULL,
  `width` int(11) NOT NULL default '0',
  `page_num` tinyint(4) NOT NULL default '0',
  `read_list` tinyint(4) NOT NULL default '0',
  `list_cut` int(11) NOT NULL default '0',
  `content_head` text NOT NULL,
  `content_tail` text NOT NULL,
  `insert_content` text NOT NULL,
  `fileurl1` varchar(50) NOT NULL,
  `fileurl2` varchar(50) NOT NULL,
  PRIMARY KEY  (`index_no`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=42 ;

INSERT INTO `shop_board_conf` (`index_no`, `gr_id`, `skin`, `list_skin`, `boardname`, `list_priv`, `read_priv`, `write_priv`, `reply_priv`, `tail_priv`, `topfile`, `downfile`, `use_secret`, `use_category`, `usecate`, `usefile`, `usereply`, `usetail`, `width`, `page_num`, `read_list`, `list_cut`, `content_head`, `content_tail`, `insert_content`, `fileurl1`, `fileurl2`) VALUES
(13, 'gr_mall', 'basic', 'basic', '공지사항', 99, 99, 9, 1, 1, './board_head.php', './board_tail.php', 0, 0, '', 'Y', 'Y', 'Y', 100, 20, 2, 56, '', '', '', '', ''),
(20, 'gr_item', 'basic', 'basic', '공지사항', 9, 9, 1, 1, 1, '../mypage/board_head.php', '../mypage/board_tail.php', 0, 0, '', 'Y', 'Y', '', 890, 25, 1, 100, '', '', '', '', ''),
(21, 'gr_item', 'basic', 'basic', '질문과답변', 9, 9, 9, 1, 1, '../mypage/board_head.php', '../mypage/board_tail.php', 0, 0, '', '', 'Y', 'Y', 890, 20, 1, 100, '', '', '', '', ''),
(22, 'gr_home', 'basic', 'basic', '공지사항', 99, 99, 9, 1, 9, '../mypage/board_head.php', '../mypage/board_tail.php', 0, 0, '', 'Y', 'Y', 'Y', 100, 20, 1, 100, '', '', '', '', ''),
(36, 'gr_home', 'basic', 'basic', '질문과답변', 6, 6, 6, 1, 6, '../mypage/board_head.php', '../mypage/board_tail.php', 0, 0, '', 'Y', '', 'Y', 100, 20, 1, 100, '', '', '', '', ''),
(41, 'gr_mall', 'gallery', 'gallery', '갤러리게시판', 99, 9, 9, 9, 9, './board_head.php', './board_tail.php', 0, 0, '', '', '', '', 100, 30, 2, 40, '', '', '', '', '');


DROP TABLE IF EXISTS `shop_board_group`;
CREATE TABLE IF NOT EXISTS `shop_board_group` (
  `gr_id` varchar(10) NOT NULL default '',
  `gr_subject` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`gr_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `shop_board_group` (`gr_id`, `gr_subject`) VALUES
('gr_home', '가맹점'),
('gr_mall', '쇼핑몰'),
('gr_item', '공급사');


DROP TABLE IF EXISTS `shop_brand`;
CREATE TABLE IF NOT EXISTS `shop_brand` (
  `br_id` int(11) NOT NULL auto_increment,
  `mb_id` varchar(20) NOT NULL,
  `br_name` varchar(255) NOT NULL,
  `br_name_eng` varchar(255) NOT NULL,
  `br_logo` varchar(255) NOT NULL,
  `br_user_yes` tinyint(4) NOT NULL default '0',
  `br_wdate` datetime NOT NULL default '0000-00-00 00:00:00',
  `br_udate` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`br_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=27 ;

INSERT INTO `shop_brand` (`br_id`, `mb_id`, `br_name`, `br_name_eng`, `br_logo`, `br_user_yes`, `br_wdate`, `br_udate`) VALUES
(9, 'admin', '스페리 탑 사이더', 'Sperry Top Sider', '', 0, '2014-05-12 22:44:38', '2017-07-25 10:38:29'),
(8, 'admin', '아르마니 익스체인지', 'Armani Exchange', '', 0, '2014-05-12 22:44:27', '2017-07-25 10:38:40'),
(4, 'admin', '얼반아웃피터', 'Urbanoutfitters', '', 0, '2014-05-12 22:38:08', '0000-00-00 00:00:00'),
(6, 'admin', '인사이트', 'Insight', '', 0, '2014-05-12 22:39:30', '0000-00-00 00:00:00'),
(7, 'admin', '칩 먼데이', 'Cheap Monday', '', 0, '2014-05-12 22:40:00', '0000-00-00 00:00:00'),
(25, 'admin', '얼반아웃피터1', 'Armani Exchange22', '', 0, '2018-04-05 06:12:46', '2018-04-09 02:05:29');


DROP TABLE IF EXISTS `shop_cart`;
CREATE TABLE IF NOT EXISTS `shop_cart` (
  `index_no` int(11) NOT NULL auto_increment,
  `odrkey` varchar(30) NOT NULL,
  `orderno` varchar(30) NOT NULL,
  `mb_yes` tinyint(4) NOT NULL default '0',
  `mb_no` int(11) NOT NULL default '0',
  `gs_id` int(11) NOT NULL default '0',
  `ct_price` int(11) NOT NULL default '0',
  `ct_qty` int(11) NOT NULL default '0',
  `ct_point` int(11) NOT NULL default '0',
  `ca_id` varchar(15) NOT NULL,
  `io_id` varchar(255) NOT NULL,
  `io_type` tinyint(4) NOT NULL default '0',
  `io_price` int(11) NOT NULL default '0',
  `ct_option` varchar(255) NOT NULL,
  `ct_send_cost` tinyint(4) NOT NULL default '0',
  `ct_select` tinyint(4) NOT NULL default '0',
  `ct_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `ct_ip` varchar(255) NOT NULL,
  PRIMARY KEY  (`index_no`),
  KEY `member` (`mb_no`,`gs_id`),
  KEY `ct_select` (`ct_select`),
  KEY `ct_time` (`ct_time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `shop_cate`;
CREATE TABLE IF NOT EXISTS `shop_cate` (
  `index_no` int(11) unsigned NOT NULL auto_increment,
  `catecode` varchar(15) NOT NULL,
  `upcate` varchar(12) NOT NULL,
  `catename` varchar(255) NOT NULL,
  `img_name` varchar(255) NOT NULL,
  `img_name_over` varchar(255) NOT NULL,
  `img_head` varchar(255) NOT NULL,
  `img_head_url` varchar(255) NOT NULL,
  `list_view` int(11) NOT NULL default '0',
  `p_catecode` varchar(15) NOT NULL,
  `p_upcate` varchar(12) NOT NULL,
  `p_oper` enum('n','y') NOT NULL default 'y',
  `p_hide` tinyint(4) NOT NULL default '0',
  `u_hide` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`index_no`),
  KEY `p_oper` (`p_oper`,`p_hide`),
  KEY `catecode` (`catecode`,`upcate`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=163 ;

INSERT INTO `shop_cate` (`index_no`, `catecode`, `upcate`, `catename`, `img_name`, `img_name_over`, `img_head`, `img_head_url`, `list_view`, `p_catecode`, `p_upcate`, `p_oper`, `p_hide`, `u_hide`) VALUES
(99, '003002', '003', '육아용품', '', '', '', '', 2, '', '', 'y', 0, 0),
(100, '003003', '003', '장난감', '', '', '', '', 3, '', '', 'y', 0, 0),
(98, '003001', '003', '기저귀/분유/유아식', '', '', '', '', 1, '', '', 'y', 0, 0),
(95, '002004', '002', '세제/구강', '', '', '', '', 4, '', '', 'y', 0, 0),
(96, '002005', '002', '화장지/물티슈/생리대', '', '', '', '', 5, '', '', 'y', 0, 0),
(84, '001001', '001', '여성의류', '', '', '', '', 2, '', '', 'y', 0, 0),
(85, '001002', '001', '남성의류', '', '', '', '', 1, '', '', 'y', 0, 0),
(86, '001003', '001', '언더웨어', '', '', '', '', 3, '', '', 'y', 0, 0),
(76, '001', '', '패션의류/잡화/뷰티', '', '', '', '', 1, '', '', 'y', 0, 0),
(77, '002', '', '식품/생필품', '', '', '', '', 2, '', '', 'y', 0, 0),
(78, '003', '', '출산/유아동', '', '', '', '', 3, '', '', 'y', 0, 0),
(79, '004', '', '생활/건강', '', '', '', '', 4, '', '', 'y', 0, 0),
(80, '005', '', '가구/인테리어', '', '', '', '', 5, '', '', 'y', 0, 0),
(83, '008', '', '도서/여행/e쿠폰/취미', '', '', '', '', 8, '', '', 'y', 0, 0),
(82, '007', '', '스포츠/레저/자동차/공구', '', '', '', '', 7, '', '', 'y', 0, 0),
(81, '006', '', '가전/디지털/컴퓨터', '', '', '', '', 6, '', '', 'y', 0, 0),
(103, '004001', '004', '건강/의료용품', '', '', '', '', 1, '', '', 'y', 0, 0),
(102, '003005', '003', '유아동신발/잡화', '', '', '', '', 5, '', '', 'y', 0, 0),
(101, '003004', '003', '유아동의류', '', '', '', '', 4, '', '', 'y', 0, 0),
(94, '002003', '002', '커피/음료', '', '', '', '', 3, '', '', 'y', 0, 0),
(87, '001004', '001', '신발', '', '', '', '', 4, '', '', 'y', 0, 0),
(88, '001005', '001', '가방/잡화', '', '', '', '', 5, '', '', 'y', 0, 0),
(89, '001006', '001', '쥬얼리/시계', '', '', '', '', 6, '', '', 'y', 0, 0),
(90, '001007', '001', '화장품/향수', '', '', '', '', 7, '', '', 'y', 0, 0),
(91, '001008', '001', '바디/헤어', '', '', '', '', 8, '', '', 'y', 0, 0),
(92, '002001', '002', '신선식품', '', '', '', '', 1, '', '', 'y', 0, 0),
(93, '002002', '002', '가공식품', '', '', '', '', 2, '', '', 'y', 0, 0),
(104, '004002', '004', '건강식품', '', '', '', '', 2, '', '', 'y', 0, 0),
(105, '004003', '004', '운동용품', '', '', '', '', 3, '', '', 'y', 0, 0),
(106, '005001', '005', '가구/DIY', '', '', '', '', 1, '', '', 'y', 0, 0),
(107, '005002', '005', '침구/커튼', '', '', '', '', 2, '', '', 'y', 0, 0),
(108, '005003', '005', '조명/인테리어', '', '', '', '', 3, '', '', 'y', 0, 0),
(109, '005004', '005', '생활/욕실/수납용품', '', '', '', '', 4, '', '', 'y', 0, 0),
(110, '005005', '005', '주방용품', '', '', '', '', 5, '', '', 'y', 0, 0),
(111, '005006', '005', '꽃/이벤트용품', '', '', '', '', 6, '', '', 'y', 0, 0),
(112, '006001', '006', '대형가전', '', '', '', '', 0, '', '', 'y', 0, 0),
(113, '006002', '006', '계절가전', '', '', '', '', 1, '', '', 'y', 0, 0),
(114, '006003', '006', '주방가전', '', '', '', '', 2, '', '', 'y', 0, 0),
(115, '006004', '006', '생활/미용가전', '', '', '', '', 3, '', '', 'y', 0, 0),
(116, '006005', '006', '카메라', '', '', '', '', 4, '', '', 'y', 0, 0),
(117, '006006', '006', '음향기기', '', '', '', '', 5, '', '', 'y', 0, 0),
(118, '006007', '006', '게임', '', '', '', '', 6, '', '', 'y', 0, 0),
(119, '006008', '006', '휴대폰', '', '', '', '', 7, '', '', 'y', 0, 0),
(120, '006009', '006', '태블릿', '', '', '', '', 8, '', '', 'y', 0, 0),
(121, '006010', '006', '노트북/PC', '', '', '', '', 9, '', '', 'y', 0, 0),
(122, '006011', '006', '모니터/프린터', '', '', '', '', 10, '', '', 'y', 0, 0),
(123, '006012', '006', 'PC주변기기', '', '', '', '', 11, '', '', 'y', 0, 0),
(124, '006013', '006', '저장장치', '', '', '', '', 12, '', '', 'y', 0, 0),
(125, '007001', '007', '휘트니스/수영', '', '', '', '', 1, '', '', 'y', 0, 0),
(126, '007002', '007', '스포츠의류/운동화', '', '', '', '', 2, '', '', 'y', 0, 0),
(127, '007003', '007', '골프클럽/의류/용품', '', '', '', '', 3, '', '', 'y', 0, 0),
(128, '007004', '007', '등산/아웃도어', '', '', '', '', 4, '', '', 'y', 0, 0),
(129, '007005', '007', '캠핑/낚시', '', '', '', '', 5, '', '', 'y', 0, 0),
(130, '007006', '007', '구기/라켓', '', '', '', '', 6, '', '', 'y', 0, 0),
(131, '007007', '007', '자전거/보드', '', '', '', '', 7, '', '', 'y', 0, 0),
(132, '007008', '007', '자동차용품/블랙박스', '', '', '', '', 8, '', '', 'y', 0, 0),
(133, '007009', '007', '타이어/오일/부품', '', '', '', '', 9, '', '', 'y', 0, 0),
(134, '007010', '007', '공구/안전/산업용품', '', '', '', '', 10, '', '', 'y', 0, 0),
(135, '008001', '008', '도서음반/e교육', '', '', '', '', 1, '', '', 'y', 0, 0),
(136, '008002', '008', '여행/항공권', '', '', '', '', 2, '', '', 'y', 0, 0),
(137, '008003', '008', '티켓', '', '', '', '', 3, '', '', 'y', 0, 0),
(138, '008004', '008', 'e쿠폰/상품권', '', '', '', '', 4, '', '', 'y', 0, 0),
(139, '008005', '008', '취미', '', '', '', '', 5, '', '', 'y', 0, 0),
(143, '001001001', '001001', 'test', '', '', '', '', 0, '', '', 'y', 0, 0),
(144, '001001002', '001001', 'test2', '', '', '', '', 1, '', '', 'y', 0, 0),
(145, '001001001001', '001001001', '테스트4', '', '', '', '', 1, '', '', 'y', 0, 0),
(146, '001001001001001', '001001001001', '테스트5', 'kaZB6WGz3Th3FKHYbLERMqtFg14YhP.gif', 'uaQdXezLVv5DMFTPJ2U9WTxMb7CMmv.gif', '', '', 1, '', '', 'y', 0, 0),
(161, '002001001', '002001', '33', '', '', '', '', 1, '', '', 'y', 0, 0);


DROP TABLE IF EXISTS `shop_config`;
CREATE TABLE IF NOT EXISTS `shop_config` (
  `admin_shop_url` varchar(255) NOT NULL,
  `admin_reg_yes` enum('yes','no') NOT NULL default 'yes',
  `admin_reg_msg` text NOT NULL,
  `delivery_method` char(3) NOT NULL,
  `delivery_type` char(1) NOT NULL,
  `delivery_103mon` int(11) NOT NULL default '0',
  `delivery_104mon` int(11) NOT NULL default '0',
  `delivery_104mon_up` int(11) NOT NULL default '0',
  `delivery_method_up` char(3) NOT NULL,
  `delivery_ment` varchar(100) NOT NULL,
  `delivery_upmon` int(11) NOT NULL default '0',
  `delivery_zip` text NOT NULL,
  `delivery_downmon` int(11) NOT NULL default '0',
  `delivery_sorts` text NOT NULL,
  `join_point` int(11) NOT NULL default '0',
  `reco_point` int(11) NOT NULL default '0',
  `login_point` int(11) NOT NULL default '0',
  `usepoint` int(11) NOT NULL default '0',
  `usepoint_yes` tinyint(4) NOT NULL default '0',
  `head_title` varchar(255) NOT NULL,
  `meta_author` varchar(255) NOT NULL,
  `meta_description` varchar(255) NOT NULL,
  `meta_keywords` text NOT NULL,
  `add_meta` text NOT NULL,
  `head_script` text NOT NULL,
  `tail_script` text NOT NULL,
  `company_type` tinyint(4) NOT NULL default '0',
  `shop_reg_yes` tinyint(4) NOT NULL default '0',
  `shop_reg_auto` tinyint(4) NOT NULL default '0',
  `shop_mod_auto` tinyint(4) NOT NULL default '0',
  `shop_card` varchar(11) NOT NULL,
  `shop_bank` varchar(11) NOT NULL,
  `shop_i` tinyint(4) NOT NULL default '1',
  `shop_yesc` varchar(11) NOT NULL,
  `shop_yesc_type` varchar(5) NOT NULL,
  `shop_phone` varchar(11) NOT NULL,
  `shop_phone_type` varchar(5) NOT NULL,
  `shop_reg_agree` longtext NOT NULL,
  `shop_reg_guide` text NOT NULL,
  `shop_name` varchar(100) NOT NULL,
  `shop_name_us` varchar(100) NOT NULL,
  `dan` tinyint(4) NOT NULL default '0',
  `company_name` varchar(50) NOT NULL,
  `company_saupja_no` varchar(30) NOT NULL,
  `tongsin_no` varchar(30) NOT NULL,
  `company_tel` varchar(30) NOT NULL,
  `company_fax` varchar(30) NOT NULL,
  `company_item` varchar(100) NOT NULL,
  `company_service` varchar(100) NOT NULL,
  `company_owner` varchar(50) NOT NULL,
  `company_zip` varchar(5) NOT NULL,
  `company_addr` varchar(255) NOT NULL,
  `company_hours` varchar(255) NOT NULL,
  `company_lunch` varchar(255) NOT NULL,
  `company_close` varchar(255) NOT NULL,
  `info_name` varchar(50) NOT NULL,
  `info_email` varchar(255) NOT NULL,
  `sp_mouse` tinyint(4) NOT NULL default '0',
  `sp_provision` longtext NOT NULL,
  `sp_private` longtext NOT NULL,
  `sp_policy` longtext NOT NULL,
  `sp_send_cost` text NOT NULL,
  `sp_prohibit_id` text NOT NULL,
  `sp_prohibit_email` text NOT NULL,
  `sp_possible_ip` text NOT NULL,
  `sp_intercept_ip` text NOT NULL,
  `sp_use_hp` tinyint(4) NOT NULL default '0',
  `sp_rep_hp` tinyint(4) NOT NULL default '0',
  `sp_use_tel` tinyint(4) NOT NULL default '0',
  `sp_req_tel` tinyint(4) NOT NULL default '0' COMMENT ' ',
  `sp_use_addr` tinyint(4) NOT NULL default '0',
  `sp_req_addr` tinyint(4) NOT NULL default '0',
  `sp_use_email` tinyint(4) NOT NULL default '0',
  `sp_req_email` tinyint(4) NOT NULL default '0',
  `sp_intro` tinyint(4) NOT NULL default '0',
  `sp_app` tinyint(4) NOT NULL default '0',
  `sp_app_super` tinyint(4) NOT NULL default '0',
  `sp_coupon` tinyint(4) NOT NULL default '0',
  `sp_gift` tinyint(4) NOT NULL default '0',
  `mo_shop_yn` tinyint(4) NOT NULL default '0',
  `mo_about_limit` tinyint(4) NOT NULL default '0',
  `mo_se_default` varchar(100) NOT NULL,
  `mo_se_yn` tinyint(4) NOT NULL default '0',
  `mo_noti_yn` tinyint(4) NOT NULL default '0',
  `mo_send_cost` text NOT NULL,
  `accent_one` char(1) NOT NULL,
  `accent_tree` char(1) NOT NULL,
  `accent_max` int(11) NOT NULL default '0',
  `accent_tax` varchar(11) NOT NULL,
  `p_type` varchar(10) NOT NULL,
  `p_month` char(1) NOT NULL,
  `p_member` char(1) NOT NULL,
  `p_login` char(1) NOT NULL,
  `p_shop` char(1) NOT NULL,
  `p_shop_flag` tinyint(4) NOT NULL default '0',
  `p_use_good` char(1) NOT NULL default '1',
  `p_use_cate` char(1) NOT NULL default '1',
  `p_use_pg` char(1) NOT NULL default '1',
  `p_reg_agree` longtext NOT NULL,
  `p_payment_yes` tinyint(4) NOT NULL default '0',
  `partner_reg_yes` tinyint(4) NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `shop_config` (`admin_shop_url`, `admin_reg_yes`, `admin_reg_msg`, `delivery_method`, `delivery_type`, `delivery_103mon`, `delivery_104mon`, `delivery_104mon_up`, `delivery_method_up`, `delivery_ment`, `delivery_upmon`, `delivery_zip`, `delivery_downmon`, `delivery_sorts`, `join_point`, `reco_point`, `login_point`, `usepoint`, `usepoint_yes`, `head_title`, `meta_author`, `meta_description`, `meta_keywords`, `add_meta`, `head_script`, `tail_script`, `company_type`, `shop_reg_yes`, `shop_reg_auto`, `shop_mod_auto`, `shop_card`, `shop_bank`, `shop_i`, `shop_yesc`, `shop_yesc_type`, `shop_phone`, `shop_phone_type`, `shop_reg_agree`, `shop_reg_guide`, `shop_name`, `shop_name_us`, `dan`, `company_name`, `company_saupja_no`, `tongsin_no`, `company_tel`, `company_fax`, `company_item`, `company_service`, `company_owner`, `company_zip`, `company_addr`, `company_hours`, `company_lunch`, `company_close`, `info_name`, `info_email`, `sp_mouse`, `sp_provision`, `sp_private`, `sp_policy`, `sp_send_cost`, `sp_prohibit_id`, `sp_prohibit_email`, `sp_possible_ip`, `sp_intercept_ip`, `sp_use_hp`, `sp_rep_hp`, `sp_use_tel`, `sp_req_tel`, `sp_use_addr`, `sp_req_addr`, `sp_use_email`, `sp_req_email`, `sp_intro`, `sp_app`, `sp_app_super`, `sp_coupon`, `sp_gift`, `mo_shop_yn`, `mo_about_limit`, `mo_se_default`, `mo_se_yn`, `mo_noti_yn`, `mo_send_cost`, `accent_one`, `accent_tree`, `accent_max`, `accent_tax`, `p_type`, `p_month`, `p_member`, `p_login`, `p_shop`, `p_shop_flag`, `p_use_good`, `p_use_cate`, `p_use_pg`, `p_reg_agree`, `p_payment_yes`, `partner_reg_yes`) VALUES
('demofran.tubeweb.co.kr', '', '본사 쇼핑몰에서는 회원가입이 불가능 합니다.\r\n[ 아이디.tubeweb.co.kr]으로 접속하셔야 가입이 가능합니다.', '103', '1', 1800, 1800, 80000, 'Y', '도서,산간지역 추가요금 3,000원', 3000, '799,690', 2500, 'KG로지스|http://www.kglogis.co.kr/contents/waybill.jsp?item_no=,KGB택배|http://www.kgbls.co.kr/sub5/trace.asp?f_slipno=,KG옐로우캡택배|http://www.yellowcap.co.kr/custom/inquiry_result.asp?invoice_no=,CVSnet편의점택배|http://was.cvsnet.co.kr/_ver2/board/ctod_status.jsp?invoice_no=,CJ대한통운|https://www.doortodoor.co.kr/parcel/doortodoor.do?fsp_action=PARC_ACT_002&fsp_cmd=retrieveInvNoACT&invc_no=,롯데택배(구현대택배)|https://www.lotteglogis.com/open/tracking?InvNo=,한진택배|http://www.hanjin.co.kr/Delivery_html/inquiry/result_waybill.jsp?wbl_num=,이노지스택배|http://www.innogis.co.kr/tracking_view.asp?invoice=,우체국|http://service.epost.go.kr/trace.RetrieveRegiPrclDeliv.postal?sid1=,로젠택배|http://www.ilogen.com/iLOGEN.Web.New/TRACE/TraceView.aspx?gubun=slipno&slipno=,동부택배|http://www.dongbups.com/delivery/delivery_search_view.jsp?item_no=,대신택배|http://home.daesinlogistics.co.kr/daesin/jsp/d_freight_chase/d_general_process2.jsp?billno1=,경동택배|http://www.kdexp.com/sub3_shipping.asp?stype=1&p_item=', 3000, 0, 0, 5000, 1, '투비웹 - PHP 웹솔루션 전문 개발업체', '투비웹, TubeWeb', '투비웹 - PHP 웹솔루션 전문 개발업체', 'PHP, 쇼핑몰솔루션, 독립형쇼핑몰, 입점형쇼핑몰, 독립몰, 입점몰, 몰인몰, 분양쇼핑몰, 분양몰, 프랜차이즈몰, 그누보드, 영카트, 홈빌더', '', '', '', 0, 1, 1, 0, '3.0', '3.2', 0, '200', 'won', '800', 'won', '해당 쇼핑몰에 맞는 입점 가입약관을 입력합니다.', '입점 이용안내 내용 또는 이미지를 입력해주세요.', '행복을 주는 쇼핑몰!', 'Happy shopping', 5, '투비웹', '123-45-67890', '2017-서울강남-0000호', '02-123-4567', '02-123-4568', '서비스업,도소매', '전자상거래업', '홍길동', '12345', 'OO도 OO시 OO구 OO동 123-45', '오전10시~오후06시', '오후12시~오후1시', '토요일,공휴일 휴무', '임꺽정', 'help@domain.com', 0, '해당 쇼핑몰에 맞는 회원가입약관을 입력합니다.', '해당 쇼핑몰에 맞는 개인정보 수집 및 이용을 입력합니다.', '해당 쇼핑몰에 맞는 개인정보처리방침을 입력합니다.', '쇼핑몰 배송/교환/반품안내', 'admin,administrator,webmaster,sysop,manager,root,su,guest,www', 'hanmail.net', '', '', 1, 1, 1, 0, 1, 1, 1, 0, 0, 0, 0, 1, 1, 1, 6, '검색어를 입력하세요!', 1, 1, '모바일 배송/교환/반품안내', '', '', 5000, '3.3', 'month', 'y', 'n', 'y', 'y', 0, '2', '2', '3', '해당 쇼핑몰에 맞는 가맹점 이용약관을 입력합니다.', 1, 1);


DROP TABLE IF EXISTS `shop_content`;
CREATE TABLE IF NOT EXISTS `shop_content` (
  `co_id` int(11) unsigned NOT NULL auto_increment,
  `co_subject` varchar(255) NOT NULL default '',
  `co_content` longtext NOT NULL,
  `co_mobile_content` longtext NOT NULL,
  PRIMARY KEY  (`co_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

INSERT INTO `shop_content` (`co_id`, `co_subject`, `co_content`, `co_mobile_content`) VALUES
(1, '회사소개', '회사소개 내용 또는 이미지를 입력해주세요.', '회사소개 내용 또는 이미지를 입력해주세요.'),
(2, '이용안내', '이용안내 내용 또는 이미지를 입력해주세요.', '이용안내 내용 또는 이미지를 입력해주세요.');


DROP TABLE IF EXISTS `shop_coupon`;
CREATE TABLE IF NOT EXISTS `shop_coupon` (
  `cp_id` int(11) NOT NULL auto_increment,
  `cp_type` tinyint(4) NOT NULL default '0',
  `cp_dlimit` varchar(11) NOT NULL,
  `cp_dlevel` tinyint(4) NOT NULL default '10',
  `cp_subject` varchar(255) NOT NULL,
  `cp_explan` varchar(255) NOT NULL,
  `cp_use` tinyint(4) NOT NULL default '0',
  `cp_download` tinyint(4) NOT NULL default '0',
  `cp_overlap` tinyint(4) NOT NULL default '0',
  `cp_sale_type` tinyint(4) NOT NULL default '0',
  `cp_sale_percent` int(11) NOT NULL default '0',
  `cp_sale_amt_max` int(11) NOT NULL default '0',
  `cp_sale_amt` int(11) NOT NULL default '0',
  `cp_dups` tinyint(4) NOT NULL default '0',
  `cp_use_sex` char(1) NOT NULL,
  `cp_use_sage` varchar(4) NOT NULL,
  `cp_use_eage` varchar(4) NOT NULL,
  `cp_week_day` varchar(100) NOT NULL,
  `cp_pub_1_use` tinyint(4) NOT NULL default '0',
  `cp_pub_shour1` char(2) NOT NULL,
  `cp_pub_ehour1` char(2) NOT NULL,
  `cp_pub_1_cnt` int(11) NOT NULL default '0',
  `cp_pub_1_down` int(11) NOT NULL default '0',
  `cp_pub_2_use` tinyint(4) NOT NULL default '0',
  `cp_pub_shour2` char(2) NOT NULL,
  `cp_pub_ehour2` char(2) NOT NULL,
  `cp_pub_2_cnt` int(11) NOT NULL default '0',
  `cp_pub_2_down` int(11) NOT NULL default '0',
  `cp_pub_3_use` tinyint(4) NOT NULL default '0',
  `cp_pub_shour3` char(2) NOT NULL,
  `cp_pub_ehour3` char(2) NOT NULL,
  `cp_pub_3_cnt` int(11) NOT NULL default '0',
  `cp_pub_3_down` int(11) NOT NULL default '0',
  `cp_pub_sdate` varchar(10) NOT NULL,
  `cp_pub_edate` varchar(10) NOT NULL,
  `cp_pub_sday` varchar(11) NOT NULL,
  `cp_pub_eday` varchar(11) NOT NULL,
  `cp_inv_type` tinyint(4) NOT NULL default '0',
  `cp_inv_sdate` varchar(10) NOT NULL,
  `cp_inv_edate` varchar(10) NOT NULL,
  `cp_inv_shour1` char(2) NOT NULL,
  `cp_inv_shour2` char(2) NOT NULL,
  `cp_inv_day` varchar(11) NOT NULL,
  `cp_low_amt` int(11) NOT NULL default '0',
  `cp_use_part` tinyint(4) NOT NULL default '0',
  `cp_use_goods` text NOT NULL,
  `cp_use_category` text NOT NULL,
  `cp_odr_cnt` int(11) NOT NULL default '0',
  `cp_wdate` datetime NOT NULL,
  `cp_udate` datetime NOT NULL,
  PRIMARY KEY  (`cp_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `shop_coupon_log`;
CREATE TABLE IF NOT EXISTS `shop_coupon_log` (
  `lo_id` int(11) NOT NULL auto_increment,
  `mb_id` varchar(50) NOT NULL,
  `mb_name` varchar(50) NOT NULL,
  `mb_use` tinyint(4) NOT NULL default '0',
  `od_id` varchar(20) NOT NULL,
  `cp_id` int(11) NOT NULL default '0',
  `cp_type` tinyint(4) NOT NULL default '0',
  `cp_dlimit` varchar(11) NOT NULL,
  `cp_dlevel` tinyint(4) NOT NULL default '10',
  `cp_subject` varchar(255) NOT NULL,
  `cp_explan` varchar(255) NOT NULL,
  `cp_use` tinyint(4) NOT NULL default '0',
  `cp_download` tinyint(4) NOT NULL default '0',
  `cp_overlap` tinyint(4) NOT NULL default '0',
  `cp_sale_type` tinyint(4) NOT NULL default '0',
  `cp_sale_percent` int(11) NOT NULL default '0',
  `cp_sale_amt_max` int(11) NOT NULL default '0',
  `cp_sale_amt` int(11) NOT NULL default '0',
  `cp_dups` tinyint(4) NOT NULL default '0',
  `cp_use_sex` char(1) NOT NULL,
  `cp_use_sage` varchar(4) NOT NULL,
  `cp_use_eage` varchar(4) NOT NULL,
  `cp_week_day` varchar(100) NOT NULL,
  `cp_pub_1_use` tinyint(4) NOT NULL default '0',
  `cp_pub_shour1` char(2) NOT NULL,
  `cp_pub_ehour1` char(2) NOT NULL,
  `cp_pub_1_cnt` int(11) NOT NULL default '0',
  `cp_pub_1_down` int(11) NOT NULL default '0',
  `cp_pub_2_use` tinyint(4) NOT NULL default '0',
  `cp_pub_shour2` char(2) NOT NULL,
  `cp_pub_ehour2` char(2) NOT NULL,
  `cp_pub_2_cnt` int(11) NOT NULL default '0',
  `cp_pub_2_down` int(11) NOT NULL default '0',
  `cp_pub_3_use` tinyint(4) NOT NULL default '0',
  `cp_pub_shour3` char(2) NOT NULL,
  `cp_pub_ehour3` char(2) NOT NULL,
  `cp_pub_3_cnt` int(11) NOT NULL default '0',
  `cp_pub_3_down` int(11) NOT NULL default '0',
  `cp_pub_sdate` varchar(10) NOT NULL,
  `cp_pub_edate` varchar(10) NOT NULL,
  `cp_pub_sday` varchar(11) NOT NULL,
  `cp_pub_eday` varchar(11) NOT NULL,
  `cp_inv_type` tinyint(4) NOT NULL default '0',
  `cp_inv_sdate` varchar(10) NOT NULL,
  `cp_inv_edate` varchar(10) NOT NULL,
  `cp_inv_shour1` char(2) NOT NULL,
  `cp_inv_shour2` char(2) NOT NULL,
  `cp_inv_day` varchar(11) NOT NULL,
  `cp_low_amt` int(11) NOT NULL default '0',
  `cp_use_part` tinyint(4) NOT NULL default '0',
  `cp_use_goods` text NOT NULL,
  `cp_use_category` text NOT NULL,
  `cp_wdate` datetime NOT NULL,
  `cp_udate` datetime NOT NULL,
  PRIMARY KEY  (`lo_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `shop_default`;
CREATE TABLE IF NOT EXISTS `shop_default` (
  `cf_bank_yn` tinyint(4) NOT NULL default '0',
  `cf_card_yn` tinyint(4) NOT NULL default '0',
  `cf_iche_yn` tinyint(4) NOT NULL default '0',
  `cf_hp_yn` tinyint(4) NOT NULL default '0',
  `cf_vbank_yn` tinyint(4) NOT NULL default '0',
  `cf_card_test_yn` tinyint(4) NOT NULL default '0',
  `cf_tax_flag_use` tinyint(4) NOT NULL default '0',
  `cf_card_pg` varchar(10) NOT NULL,
  `cf_nm_pg` varchar(150) NOT NULL,
  `cf_escrow_yn` tinyint(4) NOT NULL default '0',
  `cf_inicis_id` varchar(100) NOT NULL,
  `cf_inicis_quota` varchar(255) NOT NULL,
  `cf_inicis_tax_yn` varchar(15) NOT NULL,
  `cf_inicis_noint_yn` char(3) NOT NULL default 'no',
  `cf_inicis_noint_mt` varchar(255) NOT NULL,
  `cf_inicis_hp_unit` tinyint(4) NOT NULL default '2',
  `cf_inicis_skin` varchar(15) NOT NULL,
  `cf_inicis_escrow_id` varchar(100) NOT NULL,
  `cf_kcp_id` varchar(100) NOT NULL,
  `cf_kcp_key` varchar(255) NOT NULL,
  `cf_kcp_tax_yn` char(1) NOT NULL default 'N',
  `cf_kcp_noint_yn` char(1) NOT NULL,
  `cf_kcp_noint_mt` varchar(255) NOT NULL,
  `cf_kcp_quota` varchar(255) NOT NULL,
  `cf_ags_id` varchar(100) NOT NULL,
  `cf_ags_tax_yn` tinyint(4) NOT NULL default '0',
  `cf_ags_noint_yn` tinyint(4) NOT NULL default '0',
  `cf_ags_noint_mt` varchar(255) NOT NULL,
  `cf_ags_quota` varchar(255) NOT NULL,
  `cf_ags_hp_id` varchar(100) NOT NULL,
  `cf_ags_hp_pwd` varchar(150) NOT NULL,
  `cf_ags_hp_subid` varchar(100) NOT NULL,
  `cf_ags_hp_code` varchar(100) NOT NULL,
  `cf_ags_hp_unit` tinyint(4) NOT NULL default '2',
  `cf_bank_account` text NOT NULL,
  `cf_banking` text NOT NULL,
  `cf_logo_wpx` int(11) NOT NULL default '0',
  `cf_logo_hpx` int(11) NOT NULL default '0',
  `cf_mobile_logo_wpx` int(11) NOT NULL default '0',
  `cf_mobile_logo_hpx` int(11) NOT NULL default '0',
  `cf_slider_wpx` int(11) NOT NULL default '0',
  `cf_slider_hpx` int(11) NOT NULL default '0',
  `cf_mobile_slider_wpx` int(11) NOT NULL default '0',
  `cf_mobile_slider_hpx` int(11) NOT NULL default '0',
  `cf_item_small_wpx` int(11) NOT NULL default '0',
  `cf_item_small_hpx` int(11) NOT NULL default '0',
  `cf_item_medium_wpx` int(11) NOT NULL default '0',
  `cf_item_medium_hpx` int(11) NOT NULL default '0',
  `de_certify` tinyint(4) NOT NULL default '0',
  `de_certify_nm` varchar(30) NOT NULL,
  `de_wish_day` int(11) NOT NULL default '0',
  `de_cart_day` int(11) NOT NULL default '0',
  `de_checkplus_id` varchar(100) NOT NULL,
  `de_checkplus_pw` varchar(100) NOT NULL,
  `de_ipin_id` varchar(100) NOT NULL,
  `de_ipin_pw` varchar(100) NOT NULL,
  `de_kakaopay_mid` varchar(255) NOT NULL,
  `de_kakaopay_key` varchar(255) NOT NULL,
  `de_kakaopay_enckey` varchar(255) NOT NULL,
  `de_kakaopay_hashkey` varchar(255) NOT NULL,
  `de_kakaopay_cancelpwd` varchar(255) NOT NULL,
  `de_naverpay_mid` varchar(255) NOT NULL default '',
  `de_naverpay_cert_key` varchar(255) NOT NULL default '',
  `de_naverpay_button_key` varchar(255) NOT NULL default '',
  `de_naverpay_test` tinyint(4) NOT NULL default '0',
  `de_naverpay_mb_id` varchar(255) NOT NULL default '',
  `de_naverpay_sendcost` varchar(255) NOT NULL default '',
  `de_order_day` int(11) NOT NULL default '0',
  `de_optimize_date` date NOT NULL default '0000-00-00',
  `de_bank_name` varchar(255) NOT NULL,
  `de_bank_account` varchar(255) NOT NULL,
  `de_bank_holder` varchar(255) NOT NULL,
  `de_sns_login_use` tinyint(4) NOT NULL default '0',
  `de_naver_appid` varchar(255) NOT NULL,
  `de_naver_secret` varchar(255) NOT NULL,
  `de_facebook_appid` varchar(255) NOT NULL,
  `de_facebook_secret` varchar(255) NOT NULL,
  `de_kakao_rest_apikey` varchar(255) NOT NULL,
  `de_googl_shorturl_apikey` varchar(255) NOT NULL,
  `de_review_wr_use` tinyint(4) NOT NULL default '0',
  `de_board_wr_use` tinyint(4) NOT NULL default '0',
  `de_insta_url` varchar(255) NOT NULL,
  `de_insta_client_id` varchar(255) NOT NULL,
  `de_insta_redirect_uri` varchar(255) NOT NULL,
  `de_insta_access_token` varchar(255) NOT NULL,
  `de_sns_facebook` varchar(255) NOT NULL,
  `de_sns_twitter` varchar(255) NOT NULL,
  `de_sns_instagram` varchar(255) NOT NULL,
  `de_sns_pinterest` varchar(255) NOT NULL,
  `de_sns_naverblog` varchar(255) NOT NULL,
  `de_sns_naverband` varchar(255) NOT NULL,
  `de_sns_kakaotalk` varchar(255) NOT NULL,
  `de_sns_kakaostory` varchar(255) NOT NULL,
  `de_maintype_title` varchar(255) NOT NULL,
  `de_maintype_best` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `shop_default` (`cf_bank_yn`, `cf_card_yn`, `cf_iche_yn`, `cf_hp_yn`, `cf_vbank_yn`, `cf_card_test_yn`, `cf_tax_flag_use`, `cf_card_pg`, `cf_nm_pg`, `cf_escrow_yn`, `cf_inicis_id`, `cf_inicis_quota`, `cf_inicis_tax_yn`, `cf_inicis_noint_yn`, `cf_inicis_noint_mt`, `cf_inicis_hp_unit`, `cf_inicis_skin`, `cf_inicis_escrow_id`, `cf_kcp_id`, `cf_kcp_key`, `cf_kcp_tax_yn`, `cf_kcp_noint_yn`, `cf_kcp_noint_mt`, `cf_kcp_quota`, `cf_ags_id`, `cf_ags_tax_yn`, `cf_ags_noint_yn`, `cf_ags_noint_mt`, `cf_ags_quota`, `cf_ags_hp_id`, `cf_ags_hp_pwd`, `cf_ags_hp_subid`, `cf_ags_hp_code`, `cf_ags_hp_unit`, `cf_bank_account`, `cf_banking`, `cf_logo_wpx`, `cf_logo_hpx`, `cf_mobile_logo_wpx`, `cf_mobile_logo_hpx`, `cf_slider_wpx`, `cf_slider_hpx`, `cf_mobile_slider_wpx`, `cf_mobile_slider_hpx`, `cf_item_small_wpx`, `cf_item_small_hpx`, `cf_item_medium_wpx`, `cf_item_medium_hpx`, `de_certify`, `de_certify_nm`, `de_wish_day`, `de_cart_day`, `de_checkplus_id`, `de_checkplus_pw`, `de_ipin_id`, `de_ipin_pw`, `de_kakaopay_mid`, `de_kakaopay_key`, `de_kakaopay_enckey`, `de_kakaopay_hashkey`, `de_kakaopay_cancelpwd`, `de_naverpay_mid`, `de_naverpay_cert_key`, `de_naverpay_button_key`, `de_naverpay_test`, `de_naverpay_mb_id`, `de_naverpay_sendcost`, `de_order_day`, `de_optimize_date`, `de_bank_name`, `de_bank_account`, `de_bank_holder`, `de_sns_login_use`, `de_naver_appid`, `de_naver_secret`, `de_facebook_appid`, `de_facebook_secret`, `de_kakao_rest_apikey`, `de_googl_shorturl_apikey`, `de_review_wr_use`, `de_board_wr_use`, `de_insta_url`, `de_insta_client_id`, `de_insta_redirect_uri`, `de_insta_access_token`, `de_sns_facebook`, `de_sns_twitter`, `de_sns_instagram`, `de_sns_pinterest`, `de_sns_naverblog`, `de_sns_naverband`, `de_sns_kakaotalk`, `de_sns_kakaostory`, `de_maintype_title`, `de_maintype_best`) VALUES
(1, 1, 1, 0, 1, 1, 0, 'ini', '투비웹', 1, 'INIpayTest', 'lumpsum:00:02:03:04:05:06:07:08:09:10:11:12', 'no_receipt', 'no', '11-3:6,12-3', 2, 'GREEN', 'iniescrow0', 'T0000', '3grptw1.zW0GSo4PQdaGvsF__', 'Y', 'Y', 'CCBC-03:06,CCSS-06', '12', 'aegis', 0, 1, '100-2:3:6,200-2:3:6,300-2:3:6,400-2:3:6,500-2:3:6,600-2:3:6,800-2:3:6,900-2:3:6', '0:2:3:4:5:6:7:8:9:10:11:12', '', '', '', '', 2, '농협 356-1180-0548-53 홍길동', '농협 http://www.nonghyup.com', 175, 45, 450, 120, 1000, 400, 960, 720, 0, 0, 400, 400, 1, 'namecheck', 7, 7, '*****', '**********', '****', '********', '**********', '*********************************/*************************/**/***********************==', '****************', '****************', '*********', '*******', '********-****-****-****-************', '********-****-****-****-************', 1, 'naverpay', '제주도 3,000원 추가, 제주도 외 도서·산간 지역 5,000원 추가', 7, '2018-04-16', '농협', '12345-67-89012', '홍길동', 1, '**********************', '**********************', '**********************', '**********************', '**********************', '**********************', 0, 0, '', '', '', '', 'https://www.facebook.com', 'https://twitter.com', 'https://www.instagram.com', 'https://www.pinterest.co.kr', 'https://blog.naver.com', 'https://band.us/ko', 'https://www.kakaocorp.com/service/KakaoTalk?lang=ko', 'https://story.kakao.com', '카테고리별 베스트', 'YTo3OntpOjA7YToyOntzOjQ6InN1YmoiO3M6MTM6Iuy5tO2FjOqzoOumrDEiO3M6NDoiY29kZSI7czo0MzoiMTQ4MzQxMTA5MiwxNDgzNDExMDA3LDE0ODM0MTA2ODAsMTUxNjkzNDQ4NSI7fWk6MTthOjI6e3M6NDoic3ViaiI7czoxMzoi7Lm07YWM6rOg66asMiI7czo0OiJjb2RlIjtzOjQzOiIxNTIyODcwNzgyLDE0ODM0MTA4NjcsMTQ4MzQxMTM0NiwxNDgzNDExNTk3Ijt9aToyO2E6Mjp7czo0OiJzdWJqIjtzOjEzOiLsubTthYzqs6DrpqwzIjtzOjQ6ImNvZGUiO3M6NDM6IjE0ODM0MTEwOTIsMTQ4MzQxMTAwNywxNDgzNDEwNjgwLDE1MTY5MzQ0ODUiO31pOjM7YToyOntzOjQ6InN1YmoiO3M6MTM6Iuy5tO2FjOqzoOumrDQiO3M6NDoiY29kZSI7czo0MzoiMTUyMjg3MDc4MiwxNDgzNDEwODY3LDE0ODM0MTEzNDYsMTQ4MzQxMTU5NyI7fWk6NDthOjI6e3M6NDoic3ViaiI7czoxMzoi7Lm07YWM6rOg66asNSI7czo0OiJjb2RlIjtzOjQzOiIxNDgzNDExMDkyLDE0ODM0MTEwMDcsMTQ4MzQxMDY4MCwxNTE2OTM0NDg1Ijt9aTo1O2E6Mjp7czo0OiJzdWJqIjtzOjEzOiLsubTthYzqs6Drpqw2IjtzOjQ6ImNvZGUiO3M6NDM6IjE1MjI4NzA3ODIsMTQ4MzQxMDg2NywxNDgzNDExMzQ2LDE0ODM0MTE1OTciO31pOjY7YToyOntzOjQ6InN1YmoiO3M6MTM6Iuy5tO2FjOqzoOumrDciO3M6NDoiY29kZSI7czo0MzoiMTQ4MzQxMTA5MiwxNDgzNDExMDA3LDE0ODM0MTA2ODAsMTUxNjkzNDQ4NSI7fX0=');


DROP TABLE IF EXISTS `shop_faq`;
CREATE TABLE IF NOT EXISTS `shop_faq` (
  `index_no` int(11) NOT NULL auto_increment,
  `cate` int(11) NOT NULL default '0',
  `subject` varchar(255) NOT NULL,
  `memo` text NOT NULL,
  `wdate` datetime NOT NULL,
  PRIMARY KEY  (`index_no`),
  KEY `cate` (`cate`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

INSERT INTO `shop_faq` (`index_no`, `cate`, `subject`, `memo`, `wdate`) VALUES
(1, 1, '주문결제는 어떻게 진행되나요??', '<p>주문결제 자주묻는 질문의 테스트 답입니다.</p><p>주문결제 자주묻는 질문의 테스트 답입니다.​</p><p>주문결제 자주묻는 질문의 테스트 답입니다.​</p>', '2017-02-21 10:44:23');


DROP TABLE IF EXISTS `shop_faq_cate`;
CREATE TABLE IF NOT EXISTS `shop_faq_cate` (
  `index_no` int(11) NOT NULL auto_increment,
  `catename` varchar(50) NOT NULL,
  PRIMARY KEY  (`index_no`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

INSERT INTO `shop_faq_cate` (`index_no`, `catename`) VALUES
(1, '주문결제'),
(2, '배송'),
(3, '반품'),
(4, '취소'),
(5, '교환'),
(6, '적립금'),
(7, '회원관련'),
(8, '기타문의');


DROP TABLE IF EXISTS `shop_gift`;
CREATE TABLE IF NOT EXISTS `shop_gift` (
  `no` int(11) NOT NULL auto_increment,
  `gr_id` varchar(20) NOT NULL,
  `gr_subject` varchar(255) NOT NULL,
  `gr_price` int(11) NOT NULL default '0',
  `gr_sdate` varchar(10) NOT NULL,
  `gr_edate` varchar(10) NOT NULL,
  `gi_num` varchar(255) NOT NULL,
  `gi_use` tinyint(4) NOT NULL default '0',
  `mb_id` varchar(50) NOT NULL,
  `mb_name` varchar(50) NOT NULL,
  `mb_wdate` datetime NOT NULL,
  PRIMARY KEY  (`no`),
  KEY `gcate` (`gr_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `shop_gift_group`;
CREATE TABLE IF NOT EXISTS `shop_gift_group` (
  `gr_id` varchar(20) NOT NULL,
  `gr_subject` varchar(255) NOT NULL,
  `gr_explan` varchar(255) NOT NULL,
  `gr_price` int(11) NOT NULL default '0',
  `gr_wdate` date NOT NULL,
  `gr_sdate` varchar(10) NOT NULL,
  `gr_edate` varchar(10) NOT NULL,
  `use_gift` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`gr_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `shop_goods`;
CREATE TABLE IF NOT EXISTS `shop_goods` (
  `index_no` int(11) NOT NULL auto_increment,
  `gcode` varchar(30) NOT NULL,
  `mb_id` varchar(20) NOT NULL,
  `gcate` varchar(30) NOT NULL,
  `gname` varchar(255) NOT NULL,
  `explan` varchar(255) NOT NULL,
  `keywords` text NOT NULL,
  `isopen` tinyint(4) NOT NULL default '1',
  `saccount` int(11) NOT NULL default '0',
  `daccount` int(11) NOT NULL default '0',
  `account` int(11) NOT NULL default '0',
  `gpoint` int(11) NOT NULL default '0',
  `simg1` varchar(255) NOT NULL,
  `simg2` varchar(255) NOT NULL,
  `simg3` varchar(255) NOT NULL,
  `simg4` varchar(255) NOT NULL,
  `simg5` varchar(255) NOT NULL,
  `simg6` varchar(255) NOT NULL,
  `bimg1` varchar(255) NOT NULL,
  `bimg2` varchar(255) NOT NULL,
  `bimg3` varchar(255) NOT NULL,
  `bimg4` varchar(255) NOT NULL,
  `bimg5` varchar(255) NOT NULL,
  `maker` varchar(255) NOT NULL,
  `origin` varchar(255) NOT NULL,
  `memo` text NOT NULL,
  `admin_memo` text NOT NULL,
  `reg_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `update_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `readcount` int(11) NOT NULL default '0',
  `rank` int(11) NOT NULL default '0',
  `icon1` char(1) NOT NULL,
  `icon2` char(1) NOT NULL,
  `icon3` char(1) NOT NULL,
  `icon4` char(1) NOT NULL,
  `sum_qty` int(11) NOT NULL default '0',
  `model` varchar(255) NOT NULL,
  `opt_subject` varchar(255) NOT NULL,
  `spl_subject` varchar(255) NOT NULL,
  `money_type` tinyint(4) NOT NULL default '0',
  `money_yo` char(1) NOT NULL default '%',
  `money_acc` varchar(255) NOT NULL,
  `money_dan` int(11) NOT NULL default '0',
  `stock_qty` int(11) NOT NULL default '0',
  `noti_qty` int(11) NOT NULL default '0',
  `shop_state` tinyint(4) NOT NULL default '0',
  `shop_open` tinyint(4) NOT NULL default '0',
  `m_count` int(11) NOT NULL default '0',
  `repair` varchar(255) NOT NULL,
  `brand_uid` varchar(11) NOT NULL,
  `brand_nm` varchar(255) NOT NULL,
  `ec_mall_pid` varchar(255) NOT NULL,
  `notax` tinyint(4) NOT NULL default '0',
  `zone` varchar(30) NOT NULL,
  `zone_msg` varchar(255) NOT NULL,
  `sc_type` tinyint(4) NOT NULL default '0',
  `sc_method` tinyint(4) NOT NULL default '0',
  `sc_minimum` int(11) NOT NULL default '0',
  `sc_amt` int(11) NOT NULL default '0',
  `sc_each_use` tinyint(4) NOT NULL default '0',
  `price_msg` varchar(100) NOT NULL,
  `stock_mod` tinyint(4) NOT NULL default '0',
  `odr_max` varchar(10) NOT NULL,
  `odr_min` varchar(10) NOT NULL,
  `sb_date` date NOT NULL default '0000-00-00',
  `eb_date` date NOT NULL default '0000-00-00',
  `buy_level` tinyint(4) NOT NULL default '10',
  `buy_only` tinyint(4) NOT NULL default '0',
  `img_mod` tinyint(4) NOT NULL default '0',
  `info_gubun` varchar(50) NOT NULL,
  `info_value` text NOT NULL,
  `info_color` varchar(255) NOT NULL,
  `use_hide` text NOT NULL,
  `use_aff` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`index_no`),
  KEY `member` (`mb_id`),
  KEY `set` (`gcode`,`isopen`,`shop_state`,`shop_open`),
  KEY `use_aff` (`use_aff`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `shop_goods_cate`;
CREATE TABLE IF NOT EXISTS `shop_goods_cate` (
  `index_no` int(11) NOT NULL auto_increment,
  `gcate` varchar(15) NOT NULL,
  `gs_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`index_no`),
  KEY `gcate` (`gcate`),
  KEY `gs_id` (`gs_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `shop_goods_color`;
CREATE TABLE IF NOT EXISTS `shop_goods_color` (
  `index_no` int(11) NOT NULL auto_increment,
  `gd_color` varchar(10) NOT NULL,
  `gd_b_use` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`index_no`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

INSERT INTO `shop_goods_color` (`index_no`, `gd_color`, `gd_b_use`) VALUES
(1, '#8E562E', 0),
(2, '#E91818', 0),
(3, '#F4AA24', 0),
(4, '#F4D324', 0),
(5, '#F2F325', 0),
(6, '#A4DC0C', 0),
(7, '#37B300', 0),
(8, '#6F822E', 0),
(9, '#97D0E8', 0),
(10, '#3030F8', 0),
(11, '#1E2C89', 0),
(12, '#FDC4DA', 0),
(13, '#FFFFFF', 1),
(14, '#C5C5C6', 0);


DROP TABLE IF EXISTS `shop_goods_option`;
CREATE TABLE IF NOT EXISTS `shop_goods_option` (
  `io_no` int(11) NOT NULL auto_increment,
  `io_id` varchar(255) NOT NULL default '0',
  `io_type` tinyint(4) NOT NULL default '0',
  `gs_id` varchar(20) NOT NULL default '',
  `io_price` int(11) NOT NULL default '0',
  `io_stock_qty` int(11) NOT NULL default '0',
  `io_noti_qty` int(11) NOT NULL default '0',
  `io_use` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`io_no`),
  KEY `io_id` (`io_id`),
  KEY `gs_id` (`gs_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `shop_goods_qa`;
CREATE TABLE IF NOT EXISTS `shop_goods_qa` (
  `iq_id` int(11) NOT NULL auto_increment,
  `mb_id` varchar(50) NOT NULL,
  `gs_id` varchar(20) NOT NULL,
  `gs_se_id` varchar(50) NOT NULL,
  `iq_ty` varchar(20) NOT NULL,
  `iq_secret` tinyint(4) NOT NULL default '0',
  `iq_name` varchar(50) NOT NULL,
  `iq_email` varchar(50) NOT NULL,
  `iq_hp` varchar(30) NOT NULL,
  `iq_subject` varchar(255) NOT NULL,
  `iq_question` text NOT NULL,
  `iq_answer` text NOT NULL,
  `iq_reply` tinyint(4) NOT NULL default '0',
  `iq_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `iq_ip` varchar(30) NOT NULL,
  PRIMARY KEY  (`iq_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `shop_goods_relation`;
CREATE TABLE IF NOT EXISTS `shop_goods_relation` (
  `gs_id` varchar(20) NOT NULL default '',
  `gs_id2` varchar(20) NOT NULL default '',
  `ir_no` int(11) NOT NULL default '0',
  PRIMARY KEY  (`gs_id`,`gs_id2`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `shop_goods_review`;
CREATE TABLE IF NOT EXISTS `shop_goods_review` (
  `index_no` int(11) NOT NULL auto_increment,
  `writer` int(11) NOT NULL default '0',
  `writer_s` varchar(50) NOT NULL,
  `memo` text NOT NULL,
  `score` tinyint(4) NOT NULL default '0',
  `wdate` int(11) NOT NULL default '0',
  `gs_id` int(11) NOT NULL default '0',
  `gs_se_id` varchar(50) NOT NULL,
  `pt_id` varchar(20) NOT NULL,
  PRIMARY KEY  (`index_no`),
  KEY `writer` (`writer`),
  KEY `gs_id` (`gs_id`),
  KEY `pid` (`gs_se_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `shop_goods_type`;
CREATE TABLE IF NOT EXISTS `shop_goods_type` (
  `gt_no` int(11) NOT NULL auto_increment,
  `mb_id` varchar(30) NOT NULL default '',
  `gs_id` int(11) NOT NULL default '0',
  `it_type1` tinyint(4) NOT NULL default '0',
  `it_type2` tinyint(4) NOT NULL default '0',
  `it_type3` tinyint(4) NOT NULL default '0',
  `it_type4` tinyint(4) NOT NULL default '0',
  `it_type5` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`gt_no`),
  KEY `mb_id` (`mb_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `shop_island`;
CREATE TABLE IF NOT EXISTS `shop_island` (
  `is_id` int(11) NOT NULL auto_increment,
  `is_name` varchar(255) NOT NULL,
  `is_zip1` varchar(10) NOT NULL,
  `is_zip2` varchar(10) NOT NULL,
  `is_price` int(11) NOT NULL default '0',
  PRIMARY KEY  (`is_id`),
  KEY `is_zip1` (`is_zip1`),
  KEY `is_zip2` (`is_zip2`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `shop_joincheck`;
CREATE TABLE IF NOT EXISTS `shop_joincheck` (
  `j_idx` int(11) NOT NULL auto_increment,
  `j_ciphertime` varchar(255) NOT NULL,
  `j_requestnumber` varchar(255) NOT NULL,
  `j_responsenumber` varchar(255) NOT NULL,
  `j_authtype` varchar(20) NOT NULL,
  `j_name` varchar(60) NOT NULL,
  `j_birthdate` varchar(60) NOT NULL,
  `j_sex` varchar(2) NOT NULL,
  `j_nationalinfo` varchar(30) NOT NULL,
  `DI` text NOT NULL,
  `CI` text NOT NULL,
  `cell` varchar(30) NOT NULL,
  `j_key` varchar(255) NOT NULL,
  `allow` enum('N','Y') NOT NULL default 'N',
  PRIMARY KEY  (`j_idx`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `shop_keyword`;
CREATE TABLE IF NOT EXISTS `shop_keyword` (
  `index_no` int(11) NOT NULL auto_increment,
  `keyword` varchar(100) NOT NULL,
  `scount` int(11) NOT NULL default '0',
  `old_scount` int(11) NOT NULL default '0',
  `pp_date` tinyint(4) NOT NULL default '0',
  `pt_id` varchar(50) NOT NULL,
  PRIMARY KEY  (`index_no`),
  UNIQUE KEY `keyword` (`pp_date`,`keyword`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `shop_leave_log`;
CREATE TABLE IF NOT EXISTS `shop_leave_log` (
  `index_no` int(11) NOT NULL auto_increment,
  `new_id` varchar(50) NOT NULL COMMENT '신규추천인[ID]',
  `old_id` varchar(50) NOT NULL COMMENT '탈퇴추천인[ID]',
  `check_id` varchar(50) NOT NULL COMMENT '변경대상자[ID]',
  `wdate` varchar(50) NOT NULL COMMENT '등록날짜',
  `memo` text NOT NULL COMMENT '로그기록',
  PRIMARY KEY  (`index_no`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `shop_logo`;
CREATE TABLE IF NOT EXISTS `shop_logo` (
  `index_no` int(11) NOT NULL auto_increment,
  `mb_id` varchar(20) NOT NULL,
  `basic_logo` varchar(255) NOT NULL,
  `mobile_logo` varchar(255) NOT NULL,
  `sns_logo` varchar(255) NOT NULL,
  `favicon_ico` varchar(255) NOT NULL,
  PRIMARY KEY  (`index_no`),
  KEY `mb_id` (`mb_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

INSERT INTO `shop_logo` (`index_no`, `mb_id`, `basic_logo`, `mobile_logo`, `sns_logo`, `favicon_ico`) VALUES
(1, 'admin', 'Le6fjQ8MNBY1v3Vx5LuTLqd66lVeus.jpg', 'dMaB8cCEdlc2DtNU5nFpRS8YBrL7XP.jpg', 'vTfZ3PV9m9X8as4Er8sECsJ64NcKhg.jpg', 'Pn3xJukk7WKaj6cu5k5G5BlW5DWTVq.ico');


DROP TABLE IF EXISTS `shop_member`;
CREATE TABLE IF NOT EXISTS `shop_member` (
  `index_no` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `id` varchar(20) NOT NULL,
  `sns_id` varchar(255) NOT NULL,
  `pt_id` varchar(20) NOT NULL,
  `grade` tinyint(4) NOT NULL default '9',
  `passwd` varchar(255) NOT NULL,
  `homepage` varchar(255) NOT NULL,
  `theme` varchar(255) NOT NULL default 'basic',
  `mobile_theme` varchar(255) NOT NULL default 'basic',
  `point` int(11) NOT NULL default '0',
  `birth_year` varchar(4) NOT NULL,
  `birth_month` char(2) NOT NULL,
  `birth_day` char(2) NOT NULL,
  `birth_type` char(1) NOT NULL default 'S',
  `age` char(2) NOT NULL,
  `gender` char(1) NOT NULL default 'M',
  `email` varchar(255) NOT NULL,
  `telephone` varchar(255) NOT NULL,
  `cellphone` varchar(255) NOT NULL,
  `zip` char(5) NOT NULL,
  `addr1` varchar(255) NOT NULL,
  `addr2` varchar(255) NOT NULL,
  `addr3` varchar(255) NOT NULL,
  `addr_jibeon` varchar(255) NOT NULL,
  `mailser` char(1) NOT NULL,
  `smsser` char(1) NOT NULL,
  `reg_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `today_login` datetime NOT NULL default '0000-00-00 00:00:00',
  `term_date` varchar(20) NOT NULL,
  `anew_date` varchar(20) NOT NULL,
  `login_ip` varchar(100) NOT NULL,
  `login_sum` int(11) NOT NULL default '0',
  `pay` int(11) NOT NULL default '0',
  `payment` int(11) NOT NULL default '0',
  `payflag` tinyint(4) NOT NULL default '1',
  `use_good` tinyint(4) NOT NULL default '0',
  `use_pg` tinyint(4) NOT NULL default '0',
  `use_app` tinyint(4) NOT NULL default '0',
  `memo` text NOT NULL,
  `supply` char(1) NOT NULL,
  `lost_certify` varchar(255) NOT NULL,
  `vi_today` int(11) NOT NULL default '0',
  `vi_yesterday` int(11) default '0',
  `vi_max` int(11) NOT NULL default '0',
  `vi_sum` int(11) NOT NULL default '0',
  `vi_history` varchar(255) NOT NULL,
  `auth_1` tinyint(4) NOT NULL default '0',
  `auth_2` tinyint(4) NOT NULL default '0',
  `auth_3` tinyint(4) NOT NULL default '0',
  `auth_4` tinyint(4) NOT NULL default '0',
  `auth_5` tinyint(4) NOT NULL default '0',
  `auth_6` tinyint(4) NOT NULL default '0',
  `auth_7` tinyint(4) NOT NULL default '0',
  `auth_8` tinyint(4) NOT NULL default '0',
  `auth_9` tinyint(4) NOT NULL default '0',
  `auth_10` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`index_no`),
  KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

INSERT INTO `shop_member` (`index_no`, `name`, `id`, `sns_id`, `pt_id`, `grade`, `passwd`, `homepage`, `theme`, `mobile_theme`, `point`, `birth_year`, `birth_month`, `birth_day`, `birth_type`, `age`, `gender`, `email`, `telephone`, `cellphone`, `zip`, `addr1`, `addr2`, `addr3`, `addr_jibeon`, `mailser`, `smsser`, `reg_time`, `today_login`, `term_date`, `anew_date`, `login_ip`, `login_sum`, `pay`, `payment`, `payflag`, `use_good`, `use_pg`, `use_app`, `memo`, `supply`, `lost_certify`, `vi_today`, `vi_yesterday`, `vi_max`, `vi_sum`, `vi_history`, `auth_1`, `auth_2`, `auth_3`, `auth_4`, `auth_5`, `auth_6`, `auth_7`, `auth_8`, `auth_9`, `auth_10`) VALUES
(1, '관리자', 'admin', '', '', 1, '*89C6B530AA78695E257E55D63C00A6EC9AD3E977', '', 'basic', 'basic', 0, '1975', '01', '01', 'L', '', 'M', 'admin@domain.com', '02-0000-0000', '010-0000-0000', '02644', '서울 동대문구 천호대로85길 21', '4층', '(장안동, 동원빌딩)', 'R', 'Y', 'N', '1970-01-01 00:00:00', '2018-04-16 19:57:09', '', '', '118.47.197.208', 780, 0, 0, 1, 0, 0, 0, '', '', '', 1, 0, 1, 1, '오늘:1, 어제:0, 최대:1, 전체:1', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);


DROP TABLE IF EXISTS `shop_member_grade`;
CREATE TABLE IF NOT EXISTS `shop_member_grade` (
  `index_no` int(11) NOT NULL auto_increment,
  `grade_name` varchar(50) NOT NULL,
  `mb_sale` int(11) NOT NULL default '0',
  `mb_cutting` int(11) NOT NULL default '0',
  `mb_per` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`index_no`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

INSERT INTO `shop_member_grade` (`index_no`, `grade_name`, `mb_sale`, `mb_cutting`, `mb_per`) VALUES
(1, '관리자', 0, 0, 0),
(2, '', 0, 0, 0),
(3, '', 0, 0, 0),
(4, '', 0, 0, 0),
(5, '지점', 0, 0, 0),
(6, '가맹점', 0, 0, 0),
(7, '특별회원', 0, 0, 0),
(8, '우수회원', 0, 0, 0),
(9, '일반회원', 0, 0, 0);


DROP TABLE IF EXISTS `shop_member_leave`;
CREATE TABLE IF NOT EXISTS `shop_member_leave` (
  `index_no` int(11) NOT NULL auto_increment,
  `mb_no` int(11) NOT NULL default '0',
  `mb_id` varchar(50) NOT NULL,
  `memo` text NOT NULL,
  `other` text NOT NULL,
  `wdate` int(11) NOT NULL default '0',
  `isover` varchar(50) default '0',
  `name` varchar(30) NOT NULL,
  `dwdate` varchar(50) NOT NULL,
  PRIMARY KEY  (`index_no`),
  KEY `mb_no` (`mb_no`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `shop_nows`;
CREATE TABLE IF NOT EXISTS `shop_nows` (
  `mb_no` int(11) NOT NULL default '0',
  `keys_v` varchar(40) NOT NULL,
  `end_time` int(11) NOT NULL default '0',
  PRIMARY KEY  (`keys_v`),
  KEY `mb_no` (`mb_no`),
  KEY `end_time` (`end_time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `shop_order`;
CREATE TABLE IF NOT EXISTS `shop_order` (
  `index_no` int(11) NOT NULL auto_increment,
  `odrkey` varchar(30) NOT NULL,
  `orderno` varchar(30) NOT NULL,
  `pt_id` varchar(20) NOT NULL,
  `shop_id` varchar(20) NOT NULL,
  `dan` tinyint(4) NOT NULL default '0',
  `buymethod` char(2) NOT NULL,
  `mb_yes` tinyint(4) NOT NULL default '0',
  `mb_no` int(11) NOT NULL default '0',
  `passwd` varchar(255) NOT NULL,
  `name` varchar(30) NOT NULL,
  `cellphone` varchar(20) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `zip` varchar(5) NOT NULL,
  `addr1` varchar(100) NOT NULL,
  `addr2` varchar(100) NOT NULL,
  `addr3` varchar(255) NOT NULL,
  `addr_jibeon` varchar(255) NOT NULL,
  `b_name` varchar(30) NOT NULL,
  `b_cellphone` varchar(20) NOT NULL,
  `b_telephone` varchar(20) NOT NULL,
  `b_zip` varchar(5) NOT NULL,
  `b_addr1` varchar(100) NOT NULL,
  `b_addr2` varchar(100) NOT NULL,
  `b_addr3` varchar(255) NOT NULL,
  `b_addr_jibeon` varchar(255) NOT NULL,
  `gs_id` int(11) NOT NULL default '0',
  `gs_se_id` varchar(20) NOT NULL,
  `gs_notax` tinyint(4) NOT NULL default '0',
  `taxflag` tinyint(4) NOT NULL default '0',
  `account` int(11) NOT NULL default '0',
  `dc_exp_amt` int(11) NOT NULL default '0',
  `dc_exp_lo_id` int(11) NOT NULL default '0',
  `dc_exp_cp_id` int(11) NOT NULL default '0',
  `use_account` int(11) NOT NULL default '0',
  `use_point` int(11) NOT NULL default '0',
  `del_account` int(11) NOT NULL default '0',
  `del_account2` int(11) NOT NULL default '0',
  `cancel_amt` int(11) NOT NULL default '0',
  `path` tinyint(4) NOT NULL default '0',
  `bank` varchar(255) NOT NULL,
  `incomename` varchar(100) NOT NULL,
  `indate` varchar(10) NOT NULL,
  `orderdate` int(11) NOT NULL default '0',
  `orderdate_s` varchar(10) NOT NULL,
  `incomedate` int(11) NOT NULL default '0',
  `incomedate_s` varchar(10) NOT NULL,
  `shipdate` int(11) NOT NULL default '0',
  `memo` text NOT NULL,
  `delivery` varchar(255) NOT NULL,
  `gonumber` varchar(30) NOT NULL,
  `canceldate_s` varchar(10) NOT NULL,
  `returndate_s` varchar(10) NOT NULL,
  `swapdate` varchar(10) NOT NULL,
  `overdate_s` varchar(10) NOT NULL,
  `taxsave_yes` char(1) NOT NULL default 'N',
  `itempay_yes` tinyint(4) NOT NULL default '0',
  `user_ok` tinyint(4) NOT NULL default '0',
  `user_date` varchar(10) NOT NULL,
  `company_saupja_no` varchar(30) NOT NULL,
  `company_name` varchar(100) NOT NULL,
  `company_owner` varchar(30) NOT NULL,
  `company_addr` varchar(255) NOT NULL,
  `company_item` varchar(50) NOT NULL,
  `company_service` varchar(50) NOT NULL,
  `taxbill_yes` char(1) NOT NULL default 'N',
  `tax_hp` varchar(20) NOT NULL,
  `tax_saupja_no` varchar(30) NOT NULL,
  `vact_num` varchar(100) NOT NULL,
  `cash_info` text NOT NULL,
  `cash_ca_log` text NOT NULL,
  `casseqno` varchar(255) NOT NULL,
  `ip` varchar(255) NOT NULL,
  PRIMARY KEY  (`index_no`),
  KEY `member` (`mb_no`),
  KEY `dan` (`dan`),
  KEY `odrkey` (`odrkey`,`orderno`),
  KEY `orderdate` (`orderdate`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `shop_order_cancel`;
CREATE TABLE IF NOT EXISTS `shop_order_cancel` (
  `ca_uid` int(11) NOT NULL auto_increment,
  `ca_key` varchar(10) NOT NULL,
  `ca_type` varchar(4) NOT NULL,
  `ca_od_uid` int(11) NOT NULL default '0',
  `ca_od_dan` tinyint(4) NOT NULL default '1',
  `ca_it_aff` tinyint(4) NOT NULL default '0',
  `ca_it_seller` varchar(50) NOT NULL,
  `ca_ip` varchar(20) NOT NULL,
  `ca_cancel_use` varchar(4) NOT NULL,
  `ca_cancel` varchar(50) NOT NULL,
  `ca_memo` text NOT NULL,
  `ca_bankcd` varchar(20) NOT NULL,
  `ca_banknum` varchar(30) NOT NULL,
  `ca_bankname` varchar(30) NOT NULL,
  `ca_wdate` datetime NOT NULL default '0000-00-00 00:00:00',
  `ca_yn` tinyint(4) NOT NULL default '0',
  `ca_yname` varchar(30) NOT NULL,
  `ca_ydate` datetime NOT NULL default '0000-00-00 00:00:00',
  `ca_logs` varchar(255) NOT NULL,
  PRIMARY KEY  (`ca_uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `shop_order_goods`;
CREATE TABLE IF NOT EXISTS `shop_order_goods` (
  `index_no` int(11) NOT NULL auto_increment,
  `gcode` varchar(30) NOT NULL COMMENT '상품코드',
  `mb_id` varchar(20) NOT NULL COMMENT '회원ID',
  `gcate` varchar(30) NOT NULL COMMENT '미사용',
  `gname` varchar(255) NOT NULL COMMENT '상품명',
  `explan` varchar(255) NOT NULL COMMENT '짧은설명',
  `keywords` text NOT NULL COMMENT '키워드',
  `isopen` tinyint(4) NOT NULL default '1' COMMENT '진열상태',
  `saccount` int(11) NOT NULL default '0' COMMENT '시중가',
  `daccount` int(11) NOT NULL default '0' COMMENT '공급가',
  `account` int(11) NOT NULL default '0' COMMENT '판매가',
  `gpoint` int(11) NOT NULL default '0' COMMENT '적립금',
  `simg1` varchar(255) NOT NULL COMMENT '소 이미지',
  `simg2` varchar(255) NOT NULL COMMENT '중 이미지1',
  `simg3` varchar(255) NOT NULL COMMENT '중 이미지2',
  `simg4` varchar(255) NOT NULL COMMENT '중 이미지3',
  `simg5` varchar(255) NOT NULL COMMENT '중 이미지4',
  `simg6` varchar(255) NOT NULL COMMENT '중 이미지5',
  `bimg1` varchar(255) NOT NULL COMMENT '대 이미지1',
  `bimg2` varchar(255) NOT NULL COMMENT '대 이미지2',
  `bimg3` varchar(255) NOT NULL COMMENT '대 이미지3',
  `bimg4` varchar(255) NOT NULL COMMENT '대 이미지4',
  `bimg5` varchar(255) NOT NULL COMMENT '대 이미지5',
  `maker` varchar(255) NOT NULL COMMENT '제조사',
  `origin` varchar(255) NOT NULL COMMENT '원산지',
  `memo` text NOT NULL COMMENT '상세설명',
  `admin_memo` text NOT NULL COMMENT '관리자메모',
  `reg_time` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT '등록일시',
  `update_time` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT '수정일시',
  `readcount` int(11) NOT NULL default '0' COMMENT '조회수',
  `rank` int(11) NOT NULL default '0' COMMENT '상품정렬',
  `icon1` char(1) NOT NULL COMMENT '아이콘1',
  `icon2` char(1) NOT NULL COMMENT '아이콘2',
  `icon3` char(1) NOT NULL COMMENT '아이콘3',
  `icon4` char(1) NOT NULL COMMENT '아이콘4',
  `sum_qty` int(11) NOT NULL default '0' COMMENT '판매수',
  `model` varchar(255) NOT NULL COMMENT '모델명',
  `opt_subject` varchar(255) NOT NULL COMMENT '상품 선택옵션',
  `spl_subject` varchar(255) NOT NULL COMMENT '상품 추가옵션',
  `money_type` tinyint(4) NOT NULL default '0' COMMENT '수수료 적용타입 ("0":공통,"1":개별)',
  `money_yo` char(1) NOT NULL default '%' COMMENT '수수료 (% or 금액)',
  `money_acc` varchar(255) NOT NULL COMMENT '수수료 적용률',
  `money_dan` int(11) NOT NULL default '0' COMMENT '수수료적립단계',
  `stock_qty` int(11) NOT NULL default '0' COMMENT '재고수',
  `noti_qty` int(11) NOT NULL default '0' COMMENT '재고 통보수량',
  `shop_state` tinyint(4) NOT NULL default '0' COMMENT '공급업체',
  `shop_open` tinyint(4) NOT NULL default '0' COMMENT '공급업체 영업상태 ("0" 이면 영업중)',
  `m_count` int(11) NOT NULL default '0' COMMENT '상품평 수',
  `repair` varchar(255) NOT NULL COMMENT 'A/S',
  `brand_uid` varchar(11) NOT NULL COMMENT '브렌드주키',
  `brand_nm` varchar(255) NOT NULL COMMENT '브랜드명',
  `ec_mall_pid` varchar(255) NOT NULL,
  `notax` tinyint(4) NOT NULL default '0' COMMENT '과세구분',
  `zone` varchar(30) NOT NULL COMMENT '판매가능지역',
  `zone_msg` varchar(255) NOT NULL COMMENT '판매가능지역 추가설명',
  `sc_type` tinyint(4) NOT NULL default '0' COMMENT '배송비 유형',
  `sc_method` tinyint(4) NOT NULL default '0' COMMENT '배송비 결제',
  `sc_minimum` int(11) NOT NULL default '0' COMMENT '조건 배송비',
  `sc_amt` int(11) NOT NULL default '0' COMMENT '기본 배송비',
  `sc_each_use` tinyint(4) NOT NULL default '0' COMMENT '묶음배송불가',
  `price_msg` varchar(100) NOT NULL COMMENT '가격 대체문구',
  `stock_mod` tinyint(4) NOT NULL default '0' COMMENT '수량형식(0 :무제한)',
  `odr_max` varchar(10) NOT NULL COMMENT '최소 주문한도',
  `odr_min` varchar(10) NOT NULL COMMENT '최대 주문한도',
  `sb_date` date NOT NULL default '0000-00-00' COMMENT '판매기간 (시작)',
  `eb_date` date NOT NULL default '0000-00-00' COMMENT '판매기간 (종료)',
  `buy_level` tinyint(4) NOT NULL default '10' COMMENT '구매가능 레벨',
  `buy_only` tinyint(4) NOT NULL default '0' COMMENT '현재 레벨이상 가격공개',
  `img_mod` tinyint(4) NOT NULL default '0' COMMENT '이미지 등록방식',
  `info_gubun` varchar(50) NOT NULL COMMENT '상품정보제공 구분',
  `info_value` text NOT NULL COMMENT '상품정보제공 값',
  `info_color` varchar(255) NOT NULL,
  `use_hide` text NOT NULL COMMENT '가맹점에서 감춤',
  `use_aff` tinyint(4) NOT NULL default '0' COMMENT '가맹점상품일때 1',
  PRIMARY KEY  (`index_no`),
  KEY `member` (`mb_id`),
  KEY `set` (`gcode`,`isopen`,`shop_state`,`shop_open`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `shop_order_memo`;
CREATE TABLE IF NOT EXISTS `shop_order_memo` (
  `index_no` int(11) NOT NULL auto_increment,
  `order_no` int(11) NOT NULL default '0',
  `amemo` text NOT NULL,
  `wdate` int(11) NOT NULL default '0',
  `writer` varchar(50) NOT NULL,
  `gs_se_id` varchar(50) NOT NULL,
  PRIMARY KEY  (`index_no`),
  KEY `wdate` (`wdate`),
  KEY `gs_se_id` (`gs_se_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `shop_partner`;
CREATE TABLE IF NOT EXISTS `shop_partner` (
  `index_no` int(11) NOT NULL auto_increment,
  `mb_id` varchar(20) NOT NULL,
  `state` tinyint(4) NOT NULL default '0',
  `bank_name` varchar(50) NOT NULL,
  `bank_number` varchar(50) NOT NULL,
  `bank_company` varchar(50) NOT NULL,
  `shop_name` varchar(100) NOT NULL,
  `shop_name_us` varchar(100) NOT NULL,
  `company_type` tinyint(4) NOT NULL default '0',
  `company_name` varchar(100) NOT NULL,
  `company_saupja_no` varchar(30) NOT NULL,
  `tongsin_no` varchar(30) NOT NULL,
  `company_tel` varchar(30) NOT NULL,
  `company_fax` varchar(30) NOT NULL,
  `company_item` varchar(100) NOT NULL,
  `company_service` varchar(100) NOT NULL,
  `company_owner` varchar(50) NOT NULL,
  `company_zip` varchar(5) NOT NULL,
  `company_addr` varchar(255) NOT NULL,
  `company_hours` varchar(255) NOT NULL,
  `company_lunch` varchar(255) NOT NULL,
  `company_close` varchar(255) NOT NULL,
  `info_name` varchar(50) NOT NULL,
  `info_email` varchar(255) NOT NULL,
  `memo` text NOT NULL,
  `wdate` varchar(11) NOT NULL,
  `bank_money` int(11) NOT NULL default '0',
  `bank_name2` varchar(50) NOT NULL,
  `bank_type` tinyint(4) NOT NULL default '0',
  `bank_acc` varchar(50) NOT NULL,
  `head_title` varchar(255) NOT NULL,
  `meta_author` varchar(255) NOT NULL,
  `meta_description` varchar(255) NOT NULL,
  `meta_keywords` text NOT NULL,
  `add_meta` text NOT NULL,
  `head_script` text NOT NULL,
  `tail_script` text NOT NULL,
  `delivery_type` tinyint(4) NOT NULL default '1',
  `delivery_method` char(3) NOT NULL default '101',
  `delivery_103mon` int(11) NOT NULL default '0',
  `delivery_104mon` int(11) NOT NULL default '0',
  `delivery_104mon_up` int(11) NOT NULL default '0',
  `delivery_sorts` text NOT NULL,
  `sp_provision` text NOT NULL,
  `sp_private` text NOT NULL,
  `sp_policy` text NOT NULL,
  `sp_send_cost` text NOT NULL,
  `mo_send_cost` text NOT NULL,
  `cf_saupja_use` tinyint(4) NOT NULL default '0',
  `cf_1` tinyint(4) NOT NULL default '0',
  `cf_bank_yn` tinyint(4) NOT NULL default '1',
  `cf_card_yn` tinyint(4) NOT NULL default '1',
  `cf_iche_yn` tinyint(4) NOT NULL default '1',
  `cf_hp_yn` tinyint(4) NOT NULL default '1',
  `cf_vbank_yn` tinyint(4) NOT NULL default '1',
  `cf_card_test_yn` tinyint(4) NOT NULL default '1',
  `cf_tax_flag_use` tinyint(4) NOT NULL default '0',
  `cf_card_pg` varchar(10) NOT NULL default 'kcp',
  `cf_nm_pg` varchar(150) NOT NULL default '행복을주는쇼핑몰',
  `cf_escrow_yn` tinyint(4) NOT NULL default '0',
  `cf_inicis_id` varchar(100) NOT NULL default 'INIpayTest',
  `cf_inicis_quota` varchar(255) NOT NULL default 'lumpsum:00:02:03:04:05:06:07:08:09:10:11:12',
  `cf_inicis_tax_yn` varchar(15) NOT NULL default 'no_receipt',
  `cf_inicis_noint_yn` char(3) NOT NULL default 'no',
  `cf_inicis_noint_mt` varchar(255) NOT NULL default '11-3:6,12-3',
  `cf_inicis_hp_unit` tinyint(4) NOT NULL default '2',
  `cf_inicis_skin` varchar(15) NOT NULL default 'ORIGINAL',
  `cf_inicis_escrow_id` varchar(100) NOT NULL default 'iniescrow0',
  `cf_kcp_id` varchar(100) NOT NULL default 'T0000',
  `cf_kcp_key` varchar(255) NOT NULL default '3grptw1.zW0GSo4PQdaGvsF__',
  `cf_kcp_tax_yn` char(1) NOT NULL default 'N',
  `cf_kcp_noint_yn` char(1) NOT NULL default 'N',
  `cf_kcp_noint_mt` varchar(255) NOT NULL default 'CCBC-03:06,CCSS-06',
  `cf_kcp_quota` varchar(255) NOT NULL default '12',
  `cf_ags_id` varchar(100) NOT NULL default 'aegis',
  `cf_ags_tax_yn` tinyint(4) NOT NULL default '0',
  `cf_ags_noint_yn` tinyint(4) NOT NULL default '0',
  `cf_ags_noint_mt` varchar(255) NOT NULL default 'ALL-02:03:06',
  `cf_ags_quota` varchar(255) NOT NULL default '0:2:3:4:5:6:7:8:9:10:11:12',
  `cf_ags_hp_id` varchar(100) NOT NULL,
  `cf_ags_hp_pwd` varchar(150) NOT NULL,
  `cf_ags_hp_subid` varchar(100) NOT NULL,
  `cf_ags_hp_code` varchar(100) NOT NULL,
  `cf_ags_hp_unit` tinyint(4) NOT NULL default '2',
  `cf_bank_account` text NOT NULL,
  `cf_banking` text NOT NULL,
  `de_bank_name` varchar(255) NOT NULL,
  `de_bank_account` varchar(255) NOT NULL,
  `de_bank_holder` varchar(255) NOT NULL,
  `de_kakaopay_mid` varchar(255) NOT NULL,
  `de_kakaopay_key` varchar(255) NOT NULL,
  `de_kakaopay_enckey` varchar(255) NOT NULL,
  `de_kakaopay_hashkey` varchar(255) NOT NULL,
  `de_kakaopay_cancelpwd` varchar(255) NOT NULL,
  `de_naverpay_mid` varchar(255) NOT NULL default '',
  `de_naverpay_cert_key` varchar(255) NOT NULL default '',
  `de_naverpay_button_key` varchar(255) NOT NULL default '',
  `de_naverpay_test` tinyint(4) NOT NULL default '0',
  `de_naverpay_mb_id` varchar(255) NOT NULL default '',
  `de_naverpay_sendcost` varchar(255) NOT NULL default '',
  `de_sns_login_use` tinyint(4) NOT NULL default '0',
  `de_naver_appid` varchar(255) NOT NULL,
  `de_naver_secret` varchar(255) NOT NULL,
  `de_facebook_appid` varchar(255) NOT NULL,
  `de_facebook_secret` varchar(255) NOT NULL,
  `de_kakao_rest_apikey` varchar(255) NOT NULL,
  `de_googl_shorturl_apikey` varchar(255) NOT NULL,
  `de_insta_url` varchar(255) NOT NULL,
  `de_insta_client_id` varchar(255) NOT NULL,
  `de_insta_redirect_uri` varchar(255) NOT NULL,
  `de_insta_access_token` varchar(255) NOT NULL,
  `de_sns_facebook` varchar(255) NOT NULL,
  `de_sns_twitter` varchar(255) NOT NULL,
  `de_sns_instagram` varchar(255) NOT NULL,
  `de_sns_pinterest` varchar(255) NOT NULL,
  `de_sns_naverblog` varchar(255) NOT NULL,
  `de_sns_naverband` varchar(255) NOT NULL,
  `de_sns_kakaotalk` varchar(255) NOT NULL,
  `de_sns_kakaostory` varchar(255) NOT NULL,
  `de_maintype_title` varchar(255) NOT NULL,
  `de_maintype_best` text NOT NULL,
  PRIMARY KEY  (`index_no`),
  KEY `mb_id` (`mb_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `shop_partner_config`;
CREATE TABLE IF NOT EXISTS `shop_partner_config` (
  `index_no` int(11) NOT NULL auto_increment,
  `etc1` varchar(255) NOT NULL,
  `etc2` int(11) NOT NULL default '0',
  `etc3` int(11) NOT NULL default '0',
  `etc4` varchar(15) NOT NULL,
  `state` char(1) NOT NULL,
  `p_tree` tinyint(4) NOT NULL default '0',
  `mb_grade` tinyint(4) NOT NULL,
  `ch_ty` char(1) NOT NULL,
  `shop_ty` char(1) NOT NULL,
  `ch` int(11) NOT NULL default '0',
  `shop` int(11) NOT NULL default '0',
  PRIMARY KEY  (`index_no`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

INSERT INTO `shop_partner_config` (`index_no`, `etc1`, `etc2`, `etc3`, `etc4`, `state`, `p_tree`, `mb_grade`, `ch_ty`, `shop_ty`, `ch`, `shop`) VALUES
(1, '가맹점', 100000, 10000, 'item1', 'y', 0, 6, '%', '%', 0, 0),
(2, '지점', 200000, 10000, 'item2', 'y', 0, 5, '%', '%', 0, 0),
(3, '', 0, 0, 'item3', '', 0, 4, '', '', 0, 0),
(4, '', 0, 0, 'item4', '', 0, 3, '', '', 0, 0),
(5, '', 0, 0, 'item5', '', 0, 2, '', '', 0, 0),
(6, '', 1, 0, 'item_etc', '%', 1, 0, '', '', 0, 0),
(7, '0', 0, 0, 'item_tree1', '', 0, 0, '', '', 0, 0),
(8, '0', 0, 0, 'item_tree2', '', 0, 0, '', '', 0, 0),
(9, '|', 0, 0, 'item_tree3', '', 0, 0, '', '', 0, 0),
(10, '|', 0, 0, 'item_tree4', '', 0, 0, '', '', 0, 0),
(11, '|', 0, 0, 'item_tree5', '', 0, 0, '', '', 0, 0),
(12, '20', 1, 0, 'shop', '%', 1, 0, '', '', 0, 0),
(13, '0|0|0|0|0', 0, 0, 'item_level1', '', 0, 0, '', '', 0, 0),
(14, '0|0|0|0|0', 0, 0, 'item_level2', '', 0, 0, '', '', 0, 0);


DROP TABLE IF EXISTS `shop_partner_pay`;
CREATE TABLE IF NOT EXISTS `shop_partner_pay` (
  `index_no` int(11) NOT NULL auto_increment,
  `mb_no` int(11) NOT NULL default '0',
  `mb_id` varchar(50) NOT NULL,
  `income` int(11) NOT NULL default '0',
  `outcome` int(11) NOT NULL default '0',
  `total` int(11) NOT NULL default '0',
  `memo` varchar(255) NOT NULL,
  `wdate` int(11) NOT NULL default '0',
  `ju_date` varchar(30) NOT NULL,
  `month_date` varchar(10) NOT NULL,
  `reg_date` varchar(10) NOT NULL,
  `p_member` int(11) NOT NULL default '0',
  `p_login` int(11) NOT NULL default '0',
  `p_shop` int(11) NOT NULL default '0',
  `p_admin` int(11) NOT NULL default '0',
  `p_cancel` int(11) NOT NULL default '0',
  `ragi` char(1) NOT NULL default '0',
  PRIMARY KEY  (`index_no`),
  KEY `income` (`income`,`outcome`,`total`),
  KEY `p_member` (`p_member`,`p_login`,`p_shop`,`p_admin`),
  KEY `mb_no` (`mb_no`,`mb_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `shop_partner_paylog`;
CREATE TABLE IF NOT EXISTS `shop_partner_paylog` (
  `index_no` int(11) NOT NULL auto_increment,
  `mb_id` varchar(20) NOT NULL,
  `pt_id` varchar(20) NOT NULL,
  `in_money` int(11) NOT NULL default '0',
  `ca_money` int(11) NOT NULL default '0',
  `memo` varchar(255) NOT NULL,
  `wdate` varchar(11) NOT NULL,
  `ju_date` varchar(50) NOT NULL,
  `month_date` varchar(7) NOT NULL,
  `etc1` varchar(20) NOT NULL,
  `etc2` varchar(20) NOT NULL,
  `etc3` text NOT NULL,
  `month_date2` varchar(10) NOT NULL,
  `shop_ban` tinyint(4) NOT NULL default '0',
  `ip` varchar(50) NOT NULL,
  PRIMARY KEY  (`index_no`),
  KEY `mb_id` (`mb_id`),
  KEY `in_money` (`in_money`,`ca_money`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `shop_partner_payrun`;
CREATE TABLE IF NOT EXISTS `shop_partner_payrun` (
  `index_no` int(11) NOT NULL auto_increment,
  `mb_id` varchar(20) NOT NULL,
  `state` tinyint(4) NOT NULL default '0',
  `money` int(11) NOT NULL default '0',
  `tax1_money` int(11) NOT NULL default '0',
  `tax2_money` int(11) NOT NULL default '0',
  `membank` varchar(255) NOT NULL,
  `wdate` int(11) NOT NULL,
  PRIMARY KEY  (`index_no`),
  KEY `mb_id` (`mb_id`),
  KEY `money` (`money`,`tax1_money`,`tax2_money`),
  KEY `state` (`state`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `shop_partner_payuse`;
CREATE TABLE IF NOT EXISTS `shop_partner_payuse` (
  `index_no` int(11) NOT NULL auto_increment,
  `mb_id` varchar(50) NOT NULL,
  `out_money` int(11) NOT NULL default '0',
  `tax2_money` int(11) NOT NULL default '0',
  `tax3_money` int(11) NOT NULL default '0',
  `wdate` int(11) NOT NULL default '0',
  `ju_date` varchar(30) NOT NULL,
  `month_date` varchar(10) NOT NULL,
  `bankinfo` varchar(100) NOT NULL,
  `memo` varchar(255) NOT NULL,
  PRIMARY KEY  (`index_no`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `shop_partner_term`;
CREATE TABLE IF NOT EXISTS `shop_partner_term` (
  `index_no` int(11) NOT NULL auto_increment,
  `mb_id` varchar(20) NOT NULL,
  `state` tinyint(4) NOT NULL default '0',
  `go_date` varchar(50) NOT NULL,
  `money` int(11) NOT NULL default '0',
  `bank` varchar(255) NOT NULL,
  `bank_name` varchar(255) NOT NULL,
  `bank_acc` varchar(255) NOT NULL,
  `wdate` varchar(50) NOT NULL,
  PRIMARY KEY  (`index_no`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `shop_plan`;
CREATE TABLE IF NOT EXISTS `shop_plan` (
  `pl_no` int(11) NOT NULL auto_increment,
  `pl_name` varchar(255) NOT NULL,
  `pl_it_code` text NOT NULL,
  `pl_limg` varchar(255) NOT NULL,
  `pl_bimg` varchar(255) NOT NULL,
  `pl_use` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`pl_no`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `shop_point`;
CREATE TABLE IF NOT EXISTS `shop_point` (
  `index_no` int(11) NOT NULL auto_increment,
  `mb_no` int(11) NOT NULL default '0',
  `income` int(11) NOT NULL default '0',
  `outcome` int(11) NOT NULL default '0',
  `total` int(11) NOT NULL default '0',
  `memo` varchar(255) NOT NULL,
  `wdate` int(11) NOT NULL default '0',
  `po_ty` varchar(10) NOT NULL,
  PRIMARY KEY  (`index_no`),
  KEY `mb_no` (`mb_no`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `shop_popup`;
CREATE TABLE IF NOT EXISTS `shop_popup` (
  `index_no` int(11) NOT NULL auto_increment,
  `mb_id` varchar(50) NOT NULL,
  `state` tinyint(4) NOT NULL default '0',
  `width` int(11) NOT NULL default '0',
  `height` int(11) NOT NULL default '0',
  `top` int(11) NOT NULL default '0',
  `lefts` int(11) NOT NULL default '0',
  `title` varchar(255) NOT NULL,
  `begin_date` date NOT NULL default '0000-00-00',
  `end_date` date NOT NULL default '0000-00-00',
  `memo` text NOT NULL,
  PRIMARY KEY  (`index_no`),
  KEY `mb_id` (`mb_id`),
  KEY `width` (`state`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `shop_qa`;
CREATE TABLE IF NOT EXISTS `shop_qa` (
  `index_no` int(11) NOT NULL auto_increment,
  `mb_id` varchar(20) NOT NULL,
  `catename` varchar(100) NOT NULL default '',
  `subject` varchar(255) NOT NULL,
  `memo` text NOT NULL,
  `wdate` datetime NOT NULL default '0000-00-00 00:00:00',
  `result_yes` tinyint(4) NOT NULL default '0',
  `result_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `reply` text NOT NULL,
  `replyer` varchar(30) NOT NULL,
  `email` varchar(100) NOT NULL,
  `cellphone` varchar(30) NOT NULL,
  `email_send_yes` tinyint(4) NOT NULL default '0',
  `sms_send_yes` tinyint(4) NOT NULL default '0',
  `ip` varchar(20) NOT NULL,
  PRIMARY KEY  (`index_no`),
  KEY `mb_id` (`mb_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `shop_qa_cate`;
CREATE TABLE IF NOT EXISTS `shop_qa_cate` (
  `index_no` int(11) NOT NULL auto_increment,
  `catename` varchar(255) NOT NULL,
  `isuse` char(1) NOT NULL default 'Y',
  PRIMARY KEY  (`index_no`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

INSERT INTO `shop_qa_cate` (`index_no`, `catename`, `isuse`) VALUES
(1, '주문결제', 'Y'),
(2, '배송문의', 'Y'),
(3, '반품문의', 'Y'),
(4, '취소문의', 'Y'),
(5, '교환문의', 'Y'),
(6, '적립금', 'Y'),
(7, '회원관련', 'Y'),
(8, '기타문의', 'Y');


DROP TABLE IF EXISTS `shop_seller`;
CREATE TABLE IF NOT EXISTS `shop_seller` (
  `index_no` int(11) NOT NULL auto_increment,
  `mb_id` varchar(20) NOT NULL,
  `sup_code` varchar(50) NOT NULL,
  `in_item` varchar(100) NOT NULL,
  `in_compay` varchar(100) NOT NULL,
  `in_sanumber` varchar(100) NOT NULL,
  `in_up` varchar(100) NOT NULL,
  `in_name` varchar(100) NOT NULL,
  `in_phone` varchar(100) NOT NULL,
  `in_zipcode` char(5) NOT NULL,
  `in_addr1` varchar(255) NOT NULL,
  `in_addr2` varchar(255) NOT NULL,
  `in_addr3` varchar(255) NOT NULL,
  `in_addr_jibeon` varchar(255) NOT NULL,
  `in_fax` varchar(100) NOT NULL,
  `in_home` varchar(100) NOT NULL,
  `in_dam` varchar(100) NOT NULL,
  `in_jik` varchar(100) NOT NULL,
  `memo` text NOT NULL,
  `wdate` int(11) NOT NULL default '0',
  `state` tinyint(4) NOT NULL default '0',
  `shop_open` tinyint(4) NOT NULL default '1',
  `n_name` varchar(100) NOT NULL,
  `n_bank` varchar(100) NOT NULL,
  `n_bank_num` varchar(100) NOT NULL,
  `n_email` varchar(100) NOT NULL,
  `n_phone` varchar(100) NOT NULL,
  `in_upte` varchar(255) NOT NULL,
  `delivery_type` tinyint(4) NOT NULL default '1',
  `delivery_method` char(3) NOT NULL default '101',
  `delivery_103mon` int(11) NOT NULL default '0',
  `delivery_104mon` int(11) NOT NULL default '0',
  `delivery_104mon_up` int(11) NOT NULL default '0',
  `delivery_method_up` char(3) NOT NULL,
  `delivery_ment` varchar(100) NOT NULL,
  `delivery_upmon` int(11) NOT NULL default '0',
  `delivery_downmon` int(11) NOT NULL default '0',
  `delivery_zip` text NOT NULL,
  `sp_send_cost` text NOT NULL,
  `mo_send_cost` text NOT NULL,
  `delivery_sorts` text NOT NULL,
  PRIMARY KEY  (`index_no`),
  KEY `mb_id` (`mb_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `shop_seller_cal`;
CREATE TABLE IF NOT EXISTS `shop_seller_cal` (
  `index_no` int(11) NOT NULL auto_increment,
  `mb_id` varchar(20) NOT NULL,
  `idx` text NOT NULL,
  `money` int(11) NOT NULL default '0',
  `month` varchar(10) NOT NULL,
  `wdate` varchar(15) NOT NULL,
  PRIMARY KEY  (`index_no`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `shop_sessions`;
CREATE TABLE IF NOT EXISTS `shop_sessions` (
  `sesskey` varchar(255) NOT NULL,
  `expiry` int(11) NOT NULL default '0',
  `value` text NOT NULL,
  PRIMARY KEY  (`sesskey`),
  KEY `expiry` (`expiry`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `shop_sms`;
CREATE TABLE IF NOT EXISTS `shop_sms` (
  `cf_sms_use` tinyint(4) NOT NULL default '0',
  `cf_sms_type` varchar(255) NOT NULL,
  `cf_sms_recall` varchar(255) NOT NULL,
  `cf_icode_server_ip` varchar(255) NOT NULL,
  `cf_icode_server_port` varchar(255) NOT NULL,
  `cf_icode_id` varchar(255) NOT NULL,
  `cf_icode_pw` varchar(255) NOT NULL,
  `cf_cont1` text NOT NULL,
  `cf_cont2` text NOT NULL,
  `cf_cont3` text NOT NULL,
  `cf_cont4` text NOT NULL,
  `cf_cont5` text NOT NULL,
  `cf_cont6` text NOT NULL,
  `cf_mb_use1` tinyint(4) NOT NULL default '0',
  `cf_ad_use1` tinyint(4) NOT NULL default '0',
  `cf_re_use1` tinyint(4) NOT NULL default '0',
  `cf_sr_use1` tinyint(4) NOT NULL default '0',
  `cf_mb_use2` tinyint(4) NOT NULL default '0',
  `cf_ad_use2` tinyint(4) NOT NULL default '0',
  `cf_re_use2` tinyint(4) NOT NULL default '0',
  `cf_sr_use2` tinyint(4) NOT NULL default '0',
  `cf_mb_use3` tinyint(4) NOT NULL default '0',
  `cf_ad_use3` tinyint(4) NOT NULL default '0',
  `cf_re_use3` tinyint(4) NOT NULL default '0',
  `cf_sr_use3` tinyint(4) NOT NULL default '0',
  `cf_mb_use4` tinyint(4) NOT NULL default '0',
  `cf_ad_use4` tinyint(4) NOT NULL default '0',
  `cf_re_use4` tinyint(4) NOT NULL default '0',
  `cf_sr_use4` tinyint(4) NOT NULL default '0',
  `cf_mb_use5` tinyint(4) NOT NULL default '0',
  `cf_ad_use5` tinyint(4) NOT NULL default '0',
  `cf_re_use5` tinyint(4) NOT NULL default '0',
  `cf_sr_use5` tinyint(4) NOT NULL default '0',
  `cf_mb_use6` tinyint(4) NOT NULL default '0',
  `cf_ad_use6` tinyint(4) NOT NULL default '0',
  `cf_re_use6` tinyint(4) NOT NULL default '0',
  `cf_sr_use6` tinyint(4) NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `shop_sms` (`cf_sms_use`, `cf_sms_type`, `cf_sms_recall`, `cf_icode_server_ip`, `cf_icode_server_port`, `cf_icode_id`, `cf_icode_pw`, `cf_cont1`, `cf_cont2`, `cf_cont3`, `cf_cont4`, `cf_cont5`, `cf_cont6`, `cf_mb_use1`, `cf_ad_use1`, `cf_re_use1`, `cf_sr_use1`, `cf_mb_use2`, `cf_ad_use2`, `cf_re_use2`, `cf_sr_use2`, `cf_mb_use3`, `cf_ad_use3`, `cf_re_use3`, `cf_sr_use3`, `cf_mb_use4`, `cf_ad_use4`, `cf_re_use4`, `cf_sr_use4`, `cf_mb_use5`, `cf_ad_use5`, `cf_re_use5`, `cf_sr_use5`, `cf_mb_use6`, `cf_ad_use6`, `cf_re_use6`, `cf_sr_use6`) VALUES
(0, 'SMS', '1544-0000', '211.172.232.124', '7295', 'gnd_test', '1111', '{이름}님 회원가입을 축하합니다. 가입하신 아디디는 {아이디}입니다.', '{이름}님 {주문번호} 주문이 정상적으로 주문완료 되었습니다.', '{이름}님 {주문번호} 주문이 입금 확인되었습니다.', '{이름}님 {주문번호} 상품이 발송 되었습니다. 배송업체:{업체}, 송장번호:{송장번호}', '{이름}님 {주문번호} 상품이 취소 되었습니다.', '{이름}님 {주문번호} 상품이 배송완료 되었습니다.', 1, 1, 0, 0, 1, 1, 0, 1, 1, 0, 0, 1, 1, 0, 0, 0, 1, 0, 0, 1, 1, 0, 0, 0);


DROP TABLE IF EXISTS `shop_uniqid`;
CREATE TABLE IF NOT EXISTS `shop_uniqid` (
  `uq_id` bigint(20) unsigned NOT NULL,
  `uq_ip` varchar(255) NOT NULL,
  PRIMARY KEY  (`uq_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `shop_visit`;
CREATE TABLE IF NOT EXISTS `shop_visit` (
  `vi_id` int(11) NOT NULL default '0',
  `mb_id` varchar(30) NOT NULL,
  `vi_ip` varchar(255) NOT NULL default '',
  `vi_date` date NOT NULL default '0000-00-00',
  `vi_time` time NOT NULL default '00:00:00',
  `vi_referer` text NOT NULL,
  `vi_agent` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`vi_id`),
  UNIQUE KEY `index1` (`vi_ip`,`vi_date`),
  KEY `index2` (`vi_date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `shop_visit_sum`;
CREATE TABLE IF NOT EXISTS `shop_visit_sum` (
  `vs_id` int(11) NOT NULL auto_increment,
  `mb_id` varchar(30) NOT NULL default '',
  `vs_date` date NOT NULL default '0000-00-00',
  `vs_count` int(11) NOT NULL default '0',
  PRIMARY KEY  (`vs_id`),
  KEY `index1` (`vs_count`),
  KEY `vs_date` (`vs_date`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `shop_wish`;
CREATE TABLE IF NOT EXISTS `shop_wish` (
  `wi_id` int(11) NOT NULL auto_increment,
  `mb_id` varchar(20) NOT NULL,
  `gs_id` int(11) NOT NULL default '0',
  `wi_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `wi_ip` varchar(25) NOT NULL,
  PRIMARY KEY  (`wi_id`),
  KEY `mb_id` (`mb_id`,`gs_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;