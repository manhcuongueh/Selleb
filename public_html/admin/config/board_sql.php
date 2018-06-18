<?php
$board_table = 
"CREATE TABLE IF NOT EXISTS `shop_board_{$bo_table}` (
  `index_no` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '주키',
  `btype` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '형식 (1:공지,2:회원글)',
  `fid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '글등록 순서',
  `ca_name` varchar(100) NOT NULL COMMENT '분류',
  `issecret` char(1) NOT NULL COMMENT '비밀글(Y:비밀글,N:공개)',
  `havehtml` char(1) NOT NULL COMMENT 'HTML 사용여부(미사용)',
  `writer` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '회원 테이블 주키',
  `writer_s` varchar(50) NOT NULL COMMENT '회원(작성자)명',
  `subject` varchar(200) NOT NULL COMMENT '글제목',
  `memo` text NOT NULL COMMENT '글내용',
  `fileurl1` varchar(50) NOT NULL COMMENT '파일첨부,1',
  `fileurl2` varchar(50) NOT NULL COMMENT '파일첨부,2',
  `readcount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '글 조회수',
  `tailcount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '댓글 카운터',
  `wdate` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '등록일',
  `wip` varchar(20) NOT NULL COMMENT '작성자 아이피',
  `thread` varchar(255) NOT NULL COMMENT '답글체크',
  `passwd` varchar(20) NOT NULL COMMENT '글작성 패스워드',
  `average` char(1) NOT NULL COMMENT '구매후기 게시판전용 (상품평점)',
  `product` varchar(50) NOT NULL COMMENT '구매후기 게시판전용 (상품번호 및 주문번호)',
  `pt_id` varchar(20) NOT NULL COMMENT '가맹점ID',
  PRIMARY KEY (`index_no`),
  KEY `btype` (`btype`,`fid`,`wdate`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='공지사항' AUTO_INCREMENT=1;";


$board_tail_table = 
"CREATE TABLE IF NOT EXISTS `shop_board_{$bo_table}_tail` (
  `index_no` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '주키',
  `board_index` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '게시판 주키',
  `writer` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '회원 테이블주키',
  `writer_s` varchar(30) NOT NULL COMMENT '회원(작성자)명',
  `memo` text NOT NULL COMMENT '글내용',
  `wdate` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '작성일',
  `wip` varchar(20) NOT NULL COMMENT '작성자 아이피',
  `passwd` varchar(20) NOT NULL COMMENT '작성자 패스워드',
  PRIMARY KEY (`index_no`),
  KEY `board_index` (`board_index`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
?>    