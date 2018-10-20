<?php
defined('CFX_APPPATH') OR exit('No direct script access allowed');

/********************
    테이블 상수
********************/
define('CFX_TABLE_PREFIX', 'cfx_');

// 테이블 정보
define('CFX_CONFIG_TABLE', CFX_TABLE_PREFIX.'config'); // 설정 테이블
define('CFX_UNIQID_TABLE', CFX_TABLE_PREFIX.'uniqid'); // 유니크 아이디 테이블
define('CFX_SETUP_TABLE', CFX_TABLE_PREFIX.'setup'); // 설정 테이블
define('CFX_MEMBER_TABLE', CFX_TABLE_PREFIX.'member'); // 회원 테이블
define('CFX_MEMBER_MEMO_TABLE', CFX_TABLE_PREFIX.'member_memo'); // 회원 메모 테이블
define('CFX_GROUP_TABLE', CFX_TABLE_PREFIX.'group'); // 그룹 테이블
define('CFX_GROUP_LABEL_TABLE', CFX_TABLE_PREFIX.'group_label'); // 그룹 라벨 테이블
define('CFX_PROFILE_TABLE', CFX_TABLE_PREFIX.'profile'); // 회원 프로필 테이블
define('CFX_LOGIN_ATTEMPTS_TABLE', CFX_TABLE_PREFIX.'login_attempts'); // 로그인 시도 테이블
define('CFX_BOARD_TABLE', CFX_TABLE_PREFIX.'board'); // 게시판 테이블
define('CFX_BOARD_FILE_TABLE', CFX_TABLE_PREFIX.'board_file'); // 게시판 일반파일 테이블
define('CFX_BOARD_THUMB_TABLE', CFX_TABLE_PREFIX.'board_thumb'); // 게시판 썸네일파일 테이블
define('CFX_PUBLISH_TABLE_PREFIX', CFX_TABLE_PREFIX.'publish_'); // 게시판 테이블명 접두사

define('CFX_GIFT_TABLE', CFX_TABLE_PREFIX.'gift'); // 기프티콘 테이블
define('CFX_RESEARCH_TABLE', CFX_TABLE_PREFIX.'research'); // 설문조사 테이블
define('CFX_SCREENING_TABLE', CFX_TABLE_PREFIX.'screening'); // 설문 스크리닝 테이블
define('CFX_SEVERITY_TABLE', CFX_TABLE_PREFIX.'severity'); // 심각도 평가 테이블
define('CFX_SURVEY_TABLE', CFX_TABLE_PREFIX.'survey'); // 추가 설문 테이블

// 회원 테이블 필드 정보
define('MEMBER_NO', 'member_no');
define('MEMBER_ID', 'member_id');
define('MEMBER_KEY', 'member_key');
define('MEMBER_PW', 'member_pw');
define('MEMBER_NAME', 'member_name');
define('MEMBER_EMAIL', 'member_email');
define('MEMBER_LEVEL', 'member_level');
define('MEMBER_LOGIN_DATETIME', 'member_login_datetime');
define('MEMBER_LOGIN_IP', 'member_login_ip');
define('MEMBER_CREATED_DATETIME', 'member_created_datetime');
define('MEMBER_LEFT_DATE', 'member_left_date');

?>