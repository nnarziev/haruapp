<?php
defined('CFX_APPPATH') OR exit('No direct script access allowed');
?>

    <header id="header" class="header-narrow header-semi-transparent header-transparent-sticky-deactive custom-header-style" data-plugin-options="{'stickyEnabled': true, 'stickyEnableOnBoxed': true, 'stickyEnableOnMobile': true, 'stickyStartAt': 1, 'stickySetTop': '1'}">
        <div class="header-body">
          <div class="header-container container">
            <div class="header-row">
              <div class="header-column">
                <div class="header-logo">
                  <a href="/">
                    <img alt="하루" width="174" height="32" src="/assets/images/logo.png">
                  </a>
                </div>
              </div>

              <div class="header-column">
                <div class="header-row">
                  <div class="header-nav">
                    <button class="btn header-btn-collapse-nav" data-toggle="collapse" data-target=".header-nav-main">
                      <i class="fa fa-bars"></i>
                    </button>
                    <div class="header-nav-main header-nav-main-square custom-header-nav-main-effect-1 collapse">
                      <nav>
                        <ul class="nav nav-pills" id="mainNav">
                          <?php if ($CFX->auth->is_admin() === FALSE) { ?>
                          <li class="active">
                            <a href="/">
                              하루 (HARU)
                            </a>
                          </li>
                          <?php } else { ?>
                          <li class="active">
                            <a href="/adm">
                              하루 (HARU)
                            </a>
                          </li>
                          <?php }  ?>
                          <li>
                            <a href="/#intro">
                              하루 소개
                            </a>
                          </li>
                          <li>
                            <a href="/#downloads">
                              다운로드
                            </a>
                          </li>
                          <li>
                            <a href="/#faq">
                              FAQ's
                            </a>
                          </li>
                          <li>
                            <a href="/test/moduletest">
                              하루 시작하기
                            </a>
                          </li>
                          <?php if ($CFX->auth->is_member() === FALSE) { ?>
                          <li>
                            <a href="/auth/login">로그인</a>
                          </li>
                          <li>
                            <a href="/auth/regist">회원가입</a>
                          </li>
                          <?php } else { ?>
                          <li>
                            <a href="/auth/logout">로그아웃</a>
                          </li>
                          <?php } ?>
                          </li>
                        </ul>
                      </nav>
                    </div>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>
      </header>