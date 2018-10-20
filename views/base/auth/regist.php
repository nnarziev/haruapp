<?php
defined('CFX_APPPATH') OR exit('No direct script access allowed');
?>
<?php $CFX->load->common_view('page-home-navibar'); ?>

<div role="main" class="main">
	<section id="overview" class="custom-section custom-background-color-1 m-none">
	</section>


	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-12 col-sm-8 col-md-6 col-lg-4 col-sm-offset-2 col-md-offset-3 col-lg-offset-4">
				<section class="body-sign">
					<div class="center-sign">
						<hr>
						<div class="panel panel-sign">
							<div class="panel-title-sign mt-xl text-center">
								<h2 class="title text-uppercase text-weight-bold m-none"><i class="fa fa-user mr-xs"></i> <?php echo $CFX->title(); ?></h2>
							</div>
							<div class="panel-body">
								<form class="form-signup" method="post" id="regist_form" name="regist_form" target="_top" autocomplete="off">
									<input type="hidden" id="token" name="token" value="<?php echo $CFX->uri->token; ?>">
									<input type="hidden" id="url" name="url" value="<?php echo $CFX->uri->URL(); ?>">
									
									<div class="form-group mb-lg">
										<!-- 프로그램 사용 약관 동의 -->
										<section class="panel panel-sign">
											<header class="panel-heading">
												<h2 class="panel-title">프로그램 사용 약관 동의 <span class="required_input">(필수)</span></h2>
											</header>
											<div class="panel-body" style="border:1px solid #ccc;">
												<div class="scrollable visible-slider" data-plugin-scrollable>
													<div class="scrollable-content">
														<h4>프로그램 사용</h4>
														<br>
														<p>
														연세대학교 행동심리연구실의 사전 서면 승인(동의)이 없는 하루(HARU) 콘텐츠 일부 혹은 전체의 상업적·비상업적 무단사용은 엄격히 금지됩니다.</p>
														<p>
														사용자는 본 앱을 둘러보고, 배정된 프로그램을 사용할 수 있습니다. 본 앱은 개인의 훈련을 위한 용도로만 제공됩니다.</p>
														<p>
														모든 사용자는 본 앱을 실행할 때 정확한 최신의 개인정보만을 등록해야 합니다.</p>
														<p>
														연세대학교 행동심리연구실은 의료 기기 또는 서비스 제공에 관여하지 않습니다. 따라서 본 앱의 용도는 전문 의학적 진단 또는 치료를 대체하는 것과 무관합니다.</p>
													</div>
												</div>
											</div>
										</section>	
										<div id="regist_agree1_error" class="pull-right"></div>
										<div class="clearfix"></div>
										<div class="checkbox-custom checkbox-default pull-right">
											<input type="checkbox" class="form-check-input" id="regist_agree1" name="regist_agree1" tabindex="1" value="Y" required data-error="#regist_agree1_error">
											<label for="id_save"> 동의합니다.</label>
										</div>
									</div>

									<div class="form-group mb-lg">
										<!-- 프로그램 사용 약관 동의 -->
										<section class="panel panel-sign">
											<header class="panel-heading">
												<h2 class="panel-title">개인정보 이용 동의 <span class="required_input">(필수)</span></h2>
											</header>
											<div class="panel-body" style="border:1px solid #ccc;">
												<div class="scrollable visible-slider" data-plugin-scrollable>
													<div class="scrollable-content">
														<h4>개인정보 이용</h4>
														<br>
														<p>
														사용자가 본 앱을 사용하는 과정에서 기록된 개인정보(생년월일, 나이, 성별, 진단명), 아이디, 설문 자료, 사용한 핸드폰 기종, 훈련 관련 데이터등에 관한 정보는 
														연세대학교 행동심리연구실에서 수집·관리하며 연구 목적 이외의 용도로는 사용하지 않습니다.</p>
														<p>
														이 정보는 연구를 위해 5년 간 보관되며 수집된 정보는 개인정보보호법에 따라 적절히 관리됩니다.</p>
														<p>
														사용자들의 개인정보는 잠금 장치가 있는 보안컴퓨터에 의해 안전하게 보관되며 오직 연세대학교 행동심리연구실 연구원에 의해서만 접근 가능합니다.</p>
														<p>
														연세대학교 행동심리연구실은 본 앱을 통해 얻은 모든 개인 정보의 비밀보장을 위해 최선을 다할 것입니다.</p>
													</div>
												</div>
											</div>
										</section>	
										<div id="regist_agree2_error" class="pull-right"></div>
										<div class="clearfix"></div>
										<div class="checkbox-custom checkbox-default pull-right">
											<input type="checkbox" class="form-check-input" id="regist_agree2" name="regist_agree2" tabindex="1" value="Y" required data-error="#regist_agree2_error">
											<label for="id_save"> 동의합니다.</label>
										</div>
									</div>

									<div class="form-group mb-lg">
										<!-- 프로그램 사용 약관 동의 -->
										<section class="panel panel-sign">
											<header class="panel-heading">
												<h2 class="panel-title">저작권 약관 동의 <span class="required_input">(필수)</span></h2>
											</header>
											<div class="panel-body" style="border:1px solid #ccc;">
												<div class="scrollable visible-slider" data-plugin-scrollable>
													<div class="scrollable-content">
														<h4>저작권 약관</h4>
														<br>
														<p>
														본 앱 및 그에 포함된 모든 구성요소에 대한 일체의 권리는 연세대학교 행동심리연구실에 있습니다.</p>
														<p>
														귀하는 연세대학교의 지적재산권 등 권리의 보호에 적용되는 법률에 준수할 것을 동의합니다.</p>
														<p>
														컨텐츠 개발: 연세대학교 행동심리연구실</p>
														<p>
														본 사업은 보건복지부의 재원으로 국립암센터의 암정복추진연구 개발사업 지원에 의하여 이루어진 것입니다.</p>
													</div>
												</div>
											</div>
										</section>
										<div id="regist_agree3_error" class="pull-right"></div>
										<div class="clearfix"></div>
										<div class="checkbox-custom checkbox-default pull-right">
											<input type="checkbox" class="form-check-input" id="regist_agree3" name="regist_agree3" tabindex="1" value="Y" required data-error="#regist_agree3_error">
											<label for="id_save"> 동의합니다.</label>
										</div>
									</div>

									<div class="divider">
										<i class="fa fa-chevron-down"></i>
									</div>

									<div class="form-group mb-lg">
										<label for="member_id"><span class="required_input">*</span> 아이디</label>
										<input type="text" class="form-control input-lg" id="member_id" name="member_id" placeholder="아이디" required tabindex="4" value="" style="ime-mode:inactive">
									</div>

									<div class="form-group mb-lg">
										<label for="member_pw"><span class="required_input">*</span> 비밀번호</label>
										<input type="password" class="form-control input-lg" id="member_pw" name="member_pw" placeholder="비밀번호" required tabindex="5" value="">
									</div>

									<div class="form-group mb-lg">
										<label for="member_re_pw"><span class="required_input">*</span> 비밀번호 확인</label>
										<input type="password" class="form-control input-lg" id="member_re_pw" name="member_re_pw" placeholder="비밀번호 확인" required tabindex="6" value="">
									</div>

									<div class="form-group mb-lg">
										<label for="member_name"><span class="required_input">*</span> 이름</label>
										<input type="text" class="form-control input-lg" id="member_name" name="member_name" placeholder="이름" required tabindex="7" value="">
									</div>

									<div class="form-group mb-lg">
										<label for="member_email">이메일</label>
										<input type="text" class="form-control input-lg" id="member_email" name="member_email" placeholder="이메일" tabindex="8" value="">
									</div>

									<div class="form-group mb-lg">
										<label for="member_hp"><span class="required_input">*</span> 휴대전화</label>
										<input type="text" class="form-control input-lg" id="member_hp" name="member_hp" placeholder="휴대전화" required tabindex="9" value="">
									</div>

									<div class="form-group mb-lg">
										<label for="profile_birth"><span class="required_input">*</span> 생년월일</label>

										<div class="input-group input-append date" id="birth_datetimepicker">
											<input type="text" class="form-control input-lg" id="profile_birth" name="profile_birth" placeholder="생년월일" required tabindex="10" value="">
											<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
										</div>
									</div>

									<div class="form-group mb-lg">
										<label for="profile_gender"><span class="required_input">*</span> 성별</label>
										<span class="clearfix"></span>
										<label class="radio-inline">
											<input type="radio" id="profile_male" name="profile_gender" required tabindex="11" value="M" data-error="#profile_gender_error"> 남자
										</label>
										<label class="radio-inline">
											<input type="radio" id="profile_female" name="profile_gender" required tabindex="12" value="F" data-error="#profile_gender_error"> 여자
										</label>
										<div id="profile_gender_error"></div>
									</div>

									<div class="divider">
										<i class="fa fa-chevron-down"></i>
									</div>



									<div class="panel panel-default">
										<header class="panel-heading">
											<h2 class="panel-title panel-primary">연구에 필요한 필수 정보입니다. 정확히 입력해 주세요.</h2>
										</header>
										<div class="panel-body">

											<div class="form-group mb-lg">
												<label for="research_part"><span class="required_input">*</span> 하루(HARU) 프로그램을 선택하세요.</label>
												<span class="clearfix"></span>
												<label class="radio-inline">
													<input type="radio" id="research_part1" name="research_part" required tabindex="13" value="1" data-error="#research_part_error"> 우울/불안
												</label>
												<label class="radio-inline">
													<input type="radio" id="research_part2" name="research_part" required tabindex="14" value="2" data-error="#research_part_error"> 수면
												</label>
												<label class="radio-inline">
													<input type="radio" id="research_part3" name="research_part" required tabindex="15" value="3" data-error="#research_part_error"> 통증
												</label>
												<div id="research_part_error"></div>
											</div>

											<div class="form-group mb-lg">
												<label for="research_name"><span class="required_input">*</span> 진단명</label>
												<input type="text" class="form-control" id="research_name" name="research_name" placeholder="진단명을 입력하세요." required tabindex="16" value="">
											</div>

											<div class="form-group mb-lg">
												<label for="research_level"><span class="required_input">*</span> 병기</label>
												<span class="clearfix"></span>
												<label class="radio-inline">
													<input type="radio" id="research_level1" name="research_level" required tabindex="17" value="1" data-error="#research_level_error"> 1기
												</label>
												<label class="radio-inline">
													<input type="radio" id="research_level2" name="research_level" required tabindex="18" value="2" data-error="#research_level_error"> 2기
												</label>
												<label class="radio-inline">
													<input type="radio" id="research_level3" name="research_level" required tabindex="19" value="3" data-error="#research_level_error"> 3기
												</label>
												<label class="radio-inline">
													<input type="radio" id="research_level4" name="research_level" required tabindex="20" value="4" data-error="#research_level_error"> 4기
												</label>
												<div id="research_level_error"></div>
											</div>

											<div class="form-group mb-lg">
												<label for="research_date"><span class="required_input">*</span> 진단시기</label>

												<div class="input-group input-append date" id="research_datetimepicker">
													<input type="text" class="form-control" id="research_date" name="research_date" placeholder="진단시기" required tabindex="21" value="">
													<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
												</div>
												<div class="research_date_error"></div>
											</div>

											<div class="form-group mb-lg">
												<label for="research_recur"><span class="required_input">*</span> 재발여부</label>
												<span class="clearfix"></span>
												<label class="radio-inline">
													<input type="radio" id="research_recur1" name="research_recur" required tabindex="22" value="1" data-error="#research_recur_error"> 없음
												</label>
												<label class="radio-inline">
													<input type="radio" id="research_recur2" name="research_recur" required tabindex="23" value="2" data-error="#research_recur_error"> 재발
												</label>
												<label class="radio-inline">
													<input type="radio" id="research_recur3" name="research_recur" required tabindex="24" value="3" data-error="#research_recur_error"> 전이
												</label>
												<div id="research_recur_error"></div>
											</div>

											<div class="form-group mb-lg">
												<label for="research_care"><span class="required_input">*</span> 받은치료 종류</label>
												
												<span class="clearfix"></span>


												<div class="checkbox-custom checkbox-default">
													<input type="checkbox" class="form-check-input" id="research_care1" name="research_care[]" tabindex="25" value="1" required data-error="#research_care_error">
													<label>수술</label>
												</div>


												<div class="checkbox-custom checkbox-default">
													<input type="checkbox" class="form-check-input" id="research_care1" name="research_care[]" tabindex="26" value="2" required data-error="#research_care_error">
													<label>방사선치료</label>
												</div>


												<div class="checkbox-custom checkbox-default">
													<input type="checkbox" class="form-check-input" id="research_care1" name="research_care[]" tabindex="27" value="3" required data-error="#research_care_error">
													<label>항암치료</label>
												</div>


												<div class="checkbox-custom checkbox-default">
													<input type="checkbox" class="form-check-input" id="research_care1" name="research_care[]" tabindex="28" value="4" required data-error="#research_care_error">
													<label>호르몬치료</label>
												</div>


												<div class="checkbox-custom checkbox-default">
													<input type="checkbox" class="form-check-input" id="research_care1" name="research_care[]" tabindex="29" value="5" required data-error="#research_care_error">
													<label>기타</label>
												</div>

												<label id="research_care_error"></label>
											</div>

											<div class="form-group mb-lg">
												<label for="research_test1"><span class="required_input">*</span> 과거에 정신의학과에서 진단을 받은 적이 있으십니까?</label>
												<span class="clearfix"></span>
												<label class="radio-inline">
													<input type="radio" id="research_test1_yes" name="research_test1" required tabindex="30" value="Y" data-error="#research_test1_error"> 예
												</label>
												<label class="radio-inline">
													<input type="radio" id="research_test1_no" name="research_test1" required tabindex="31" value="N" data-error="#research_test1_error"> 아니오
												</label>
												<div id="research_test1_error"></div>
											</div>

											<div class="form-group mb-lg">
												<label for="research_test2"><span class="required_input">*</span> 과거에 정신의학과에서 처방받아 복용하신 약물이 있습니까?</label>
												<span class="clearfix"></span>
												<label class="radio-inline">
													<input type="radio" id="research_test2_yes" name="research_test2" required tabindex="32" value="Y" data-error="#research_test2_error"> 예
												</label>
												<label class="radio-inline">
													<input type="radio" id="research_test2_no" name="research_test2" required tabindex="33" value="N" data-error="#research_test2_error"> 아니오
												</label>
												<div id="research_test2_error"></div>
											</div>

											<div class="form-group mb-lg">
												<label for="research_test3"><span class="required_input">*</span> 현재 정신의학과에서 처방받아 복용하신 약물이 있습니까?</label>
												<span class="clearfix"></span>
												<label class="radio-inline">
													<input type="radio" id="research_test3_yes" name="research_test3" required tabindex="34" value="Y" data-error="#research_test3_error"> 예
												</label>
												<label class="radio-inline">
													<input type="radio" id="research_test3_no" name="research_test3" required tabindex="35" value="N" data-error="#research_test3_error"> 아니오
												</label>
												<div id="research_test3_error"></div>
											</div>

											<div class="form-group mb-lg">
												<label for="research_test4"><span class="required_input">*</span> 심리사회 서비스(심리치료)를 받아보신 경험이 있으십니까?</label>
												<span class="clearfix"></span>
												<label class="radio-inline">
													<input type="radio" id="research_test4_yes" name="research_test4" required tabindex="36" value="Y" data-error="#research_test4_error"> 예
												</label>
												<label class="radio-inline">
													<input type="radio" id="research_test4_no" name="research_test4" required tabindex="37" value="N" data-error="#research_test4_error"> 아니오
												</label>
												<div id="research_test4_error"></div>
											</div>

											<div class="form-group mb-lg">
												<label for="research_test5"><span class="required_input">*</span> 웹/앱 기반 심리치료를 이용해보신 경험이 있으십니까?</label>
												<span class="clearfix"></span>
												<label class="radio-inline">
													<input type="radio" id="research_test5_yes" name="research_test5" required tabindex="38" value="Y" data-error="#research_test5_error"> 예
												</label>
												<label class="radio-inline">
													<input type="radio" id="research_test5_no" name="research_test5" required tabindex="39" value="N" data-error="#research_test5_error"> 아니오
												</label>
												<div id="research_test5_error"></div>
											</div>
										</div>
									</div>

									<div class="divider">
										<i class="fa fa-chevron-down"></i>
									</div>

									<div class="form-group mb-lg">
										<div class="clearfix">
											<label class="pull-left"><span class="required-input">*</span> 아래 보안문자를 보이는 대로 입력해주세요.</label>
										</div>
										<div style="height:55px;">
											<img id="captcha_image" src="<?php echo $CFX->router->link_href('captcha_image', '?t='.CFX_SERVER_TIME); ?>" style="height:46px;">
											<button type="button" id="captcha_reload" class="btn btn-primary btn-lg mt-lg" style="position:relative;top:-20px;vertical-align:top;" tabindex="40"><i class="fa fa-refresh" aria-hidden="true"></i></button>
											<button type="button" id="captcha_audio" class="btn btn-primary btn-lg mt-lg" style="position:relative;top:-20px;vertical-align:top;" tabindex="41"><i class="fa fa-play" aria-hidden="true"></i></button>
										</div>
										<input type="text" class="form-control input-lg" id="captcha_key" name="captcha_key" placeholder="보안문자" required tabindex="42" maxlength="5" value="">
									</div>

									<?php if ($error_message = form_error('captcha_key')) { ?>
									<section class="panel">
										<div class="alert alert-danger" id="captcha_key-alert">
											<button type="button" class="close" data-dismiss="alert">x</button>
											<label><?php echo $error_message; ?></label>
										</div>
									</section>
									<?php } ?>

									<?php if ($error_message = form_error('common')) { ?>
									<section class="panel">
										<div class="alert alert-danger" id="common-alert">
											<button type="button" class="close" data-dismiss="alert">x</button>
											<label><?php echo $error_message; ?></label>
										</div>
									</section>
									<?php } ?>

									<div class="form-group mb-lg">
										<button class="btn btn-lg btn-primary btn-block" tabindex="43" type="submit">회원가입</button>
									</div>

									<span class="clearfix"></span>

									<div class="form-group mb-lg">
										<button class="btn btn-lg btn-default btn-block" id="cancel_btn" tabindex="44">가입취소</button>
									</div>

								</form>
							</div>
						</div>
					</div>
				</section>
			</div>
		</div>
	</div>

<?php $CFX->load->common_view('page-home-bottom'); ?>

</div>
