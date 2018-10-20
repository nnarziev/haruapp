<?php
defined('CFX_APPPATH') OR exit('No direct script access allowed');
?>
<?php $CFX->load->common_view('page-home-navibar'); ?>
<div role="main" class="main">
	<section id="overview" class="custom-section custom-background-color-1 m-none">
	</section>

	<div class="container-fluid">
		<div id="content">
		<!-- 내용보기 시작 { -->
		<fieldset>
			<legend><?php echo $CFX->title(); ?></legend>

			<section class="container">
				<div class="container_inner">
					<article>

						<div class="filter">
							<div class="btn-group" role="group">
								<button type="button" class="btn btn-warn">관리자 메뉴 : </button>
								<a href="/adm/member/lists"><button type="button" class="btn btn-info">회원 관리</button></a>
								<a href="/adm/manager/lists"><button type="button" class="btn btn-info">관리자 관리</button></a>
								<a href="/adm/group/lists"><button type="button" class="btn btn-info">프로젝트 관리</button></a>
								<a href="/adm/gift/lists"><button type="button" class="btn btn-primary">기프티콘 관리</button></a>
								<a href="/adm/screening/lists"><button type="button" class="btn btn-info">설문 스크리닝 관리</button></a>
								<a href="/adm/severity/lists"><button type="button" class="btn btn-info">심각도 평가 관리</button></a>
								<a href="/adm/survey/lists"><button type="button" class="btn btn-info">추가 설문 관리</button></a>
								<a href="/adm/setup/lists"><button type="button" class="btn btn-info">설정 관리</button></a>
							</div>
						</div>

						<table summary="기프티콘  보기" class="com_table col_table">
							<colgroup>
								<col width="15%">
								<col width="35%">
								<col width="15%">
								<col width="35%">
							</colgroup>

							<tr>
								<th scope="col">프로그램*</th>
								<td colspan="3">
									<?php echo $view_data['view']['gift_view']['program']; ?>
								</td>
							</tr>

							<tr>
								<th scope="col">아이디*</th>
								<td colspan="3">
									<a href="/adm/member/view/<?php echo $view_data['view']['gift_view']['member_no']; ?>"><?php echo $view_data['view']['gift_view']['member_id']; ?></a>
								</td>
							</tr>

							<tr>
								<th scope="col">이름*</th>
								<td colspan="3">
									<a href="/adm/member/view/<?php echo $view_data['view']['gift_view']['member_no']; ?>"><?php echo $view_data['view']['gift_view']['member_name']; ?></a>
								</td>
							</tr>

							<tr>
								<th scope="col">이메일</th>
								<td colspan="3">
									<?php echo $view_data['view']['gift_view']['member_email']; ?>
								</td>
							</tr>

							<tr>
								<th scope="col">휴대전화</th>
								<td colspan="3">
									<?php echo $view_data['view']['gift_view']['member_hp']; ?>
								</td>
							</tr>

							<tr>
								<th scope="col">신청단계*</th>
								<td colspan="3">
									<?php echo $view_data['view']['gift_view']['gift_count']; ?>단계
								</td>
							</tr>

							<tr>
								<th scope="col">기프티콘 선택번호*</th>
								<td colspan="3">
									<?php echo $view_data['view']['gift_view']['gift_num']; ?>번
								</td>
							</tr>

							<tr>
								<th scope="col">기프티콘 선택이미지*</th>
								<td colspan="3">
									<img src="<?php echo CFX_URL.'/data/images/'.$view_data['view']['gift_image']; ?>" height="250">
								</td>
							</tr>

							<tr>
								<th scope="col">기프티콘 신청일*</th>
								<td colspan="3">
									<?php echo $view_data['view']['gift_view']['gift_created_datetime']; ?>
								</td>
							</tr>

							<tr>
								<th scope="col">관리자 메모*</th>
								<td colspan="3">
									<?php echo $view_data['view']['gift_view']['memo']; ?>
								</td>
							</tr>

							<tr>
								<th scope="col">기프티콘 처리일*</th>
								<td colspan="3">
									<?php echo $view_data['view']['gift_view']['confirm_datetime']; ?>
								</td>
							</tr>

						</table>

						<span class="btn_wrap">
							<a href="<?php echo $CFX->router->link_href('lists', urldecode($CFX->uri->qstr)); ?>" class="list_left" title="목록">목록</a>
							<a href="<?php echo $CFX->router->link_href('update', urldecode($CFX->uri->qstr)); ?>" class="update_right" title="수정">수정</a>
						</span>

					</article>
				</div>
			</section>
		</fieldset>
		<!-- } 내용보기 끝 -->
		</div>
	</div>

<?php $CFX->load->common_view('page-home-bottom'); ?>
</div>