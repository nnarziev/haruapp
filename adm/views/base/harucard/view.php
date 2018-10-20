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
								<button type="button" class="btn btn-warn">하루카드 메뉴 : </button>
								<a href="/adm/harucard/lists"><button type="button" class="btn btn-primary">하루카드 관리</button></a>
							</div>
						</div>

						<table summary="하루카드 보기" class="com_table col_table">
							<colgroup>
								<col width="15%">
								<col width="35%">
								<col width="15%">
								<col width="35%">
							</colgroup>

							<tr>
								<th scope="col">프로젝트명*</th>
								<td colspan="3">
									<select name="group" class="cm_sl ca_sl" disabled>
										<?php echo $view_data['view']['select_group']; ?>
									</select>
								</td>
							</tr>

							<tr>
								<th scope="col">프로젝트 집단명*</th>
								<td colspan="3">
									<select name="group_label" class="cm_sl ca_sl" disabled>
										<?php echo $view_data['view']['select_group_label']; ?>
									</select>
								</td>
							</tr>

							<tr>
								<th scope="col">아이디*</th>
								<td colspan="3">
									<?php echo $view_data['view']['member_view']['id']; ?>
								</td>
							</tr>

							<tr>
								<th scope="col">이름*</th>
								<td colspan="3">
									<?php echo $view_data['view']['member_view']['name']; ?>
								</td>
							</tr>

							<tr>
								<th scope="col">하루카드 구분*</th>
								<td colspan="3">
									<?php echo $view_data['view']['harucard_join_view']['hc_current_part_name']; ?>
								</td>
							</tr>

							<tr>
								<th scope="col">하루카드 사용여부*</th>
								<td>
									<?php echo $view_data['view']['harucard_join_view']['hc_activated_name']; ?>
								</td>
								<th scope="col" class="br_left">하루카드 시작일*</th>
								<td>
									<?php 
										if ($view_data['view']['harucard_join_view']['hc_current_part'] == 1)
											echo $view_data['view']['harucard_join_view']['hc_join1_datetime'];
										else if ($view_data['view']['harucard_join_view']['hc_current_part'] == 2)
											echo $view_data['view']['harucard_join_view']['hc_join2_datetime'];
										else if ($view_data['view']['harucard_join_view']['hc_current_part'] == 3)
											echo $view_data['view']['harucard_join_view']['hc_join3_datetime'];
									?>
								</td>
							</tr>

							<tr>
								<th scope="col">참여 합산점수*</th>
								<td colspan="3">
									<?php echo $view_data['view']['harucard_join_view']['total_sum']; ?> 점
								</td>
							</tr>

							<tr>
								<th scope="col">하루카드 기록지*</th>
								<td colspan="3">
									<?php if (is_array($view_data['view']['harucard_join_view']['hc_part_data'])) { ?>
									<table class="com_table row_table">
										<colgroup>
											<col width="15%">
											<col width="15%">
											<col width="15%">
											<col width="15%">
											<col width="15%">
											<col width="">
										</colgroup>
										<thead>
											<tr>
											 	<th>* 회기</th>
											 	<th>* 카드순번</th>
											 	<th>* 조회</th>
											 	<th>* 출석점수</th>
											 	<th>* 기분평점</th>
											 	<th>* 북마크</th>
											</tr>
										</thead>
										<tbody>
									<?php
										for ($i = 0; $i < 48; $i++) {
									?>
											<tr>
												<td><?php echo $i+1;?>회기</td>
												<td>
												<a href="/haru/harucard?idx=<?php echo $view_data['view']['harucard_join_view']['hc_part_data'][$i]; ?>" target="_blank"><?php echo sprintf("%03d", $view_data['view']['harucard_join_view']['hc_part_data'][$i]); ?></a>
												</td>
												<td>
												<?php echo ($view_data['view']['harucard_join_view']['hc_read_items'][$i+1] == '1') ? '읽음' : '-'; ?>
												</td>
												<td>
												<?php echo $view_data['view']['harucard_join_view']['hc_point_items'][$i+1]; ?>점
												</td>
												<td>
												<?php echo $view_data['view']['harucard_join_view']['hc_rating_items'][$i+1]; ?>점
												</td>
												<td>
												<?php echo ($view_data['view']['harucard_join_view']['hc_bookmark_items'][$i+1] == '1') ? '북마크' : '-'; ?>
												</td>
											</tr>
									<?php
										}
									?>
											<tr>
												<td>합계</td>
												<td></td>
												<td></td>
												<td><?php echo $view_data['view']['harucard_join_view']['total_sum'] ?>점</td>
												<td></td>
												<td></td>
										</tbody>
									</table>
									<?php } ?>
								</td>
							</tr>

							<tr>
								<th scope="col">설문조사*</th>
								<td colspan="3">
									<table class="com_table row_table">
										<colgroup>
											<col width="20%">
											<col width="15%">
											<col width="20%">
											<col width="15%">
											<col width="">
										</colgroup>
										<thead>
											<tr>
											 	<th>* 진단명</th>
												<th>* 병기</th>
												<th>* 진단시기</th>
												<th>* 재발여부</th>
												<th>* 받은치료 종류</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td><span class="title"><?php echo $view_data['view']['research_view']['name']; ?></span></td>
												<td><span class="title"><?php echo $view_data['view']['research_view']['level']; ?>기</span></td>
												<td><span class="title"><?php echo $view_data['view']['research_view']['date']; ?></span></td>
												<td><span class="title"><?php echo $view_data['view']['research_view']['recur']; ?></span></td>
												<td><span class="title"><?php echo $view_data['view']['research_view']['care']; ?></span></td>
											</tr>
											<tr>
												<td colspan="3" class="text_left"><b>* 과거에 정신의학과에서 진단을 받은 적이 있으십니까?</b></td>
												<td colspan="2"><?php echo $view_data['view']['research_view']['test1']; ?></td>
											</tr>
											<tr>
												<td colspan="3" class="text_left"><b>* 과거에 정신의학과에서 처방받아 복용하신 약물이 있습니까?</b></td>
												<td colspan="2"><?php echo $view_data['view']['research_view']['test2']; ?></td>
											</tr>
											<tr>
												<td colspan="3" class="text_left"><b>* 현재 정신의학과에서 처방받아 복용하신 약물이 있습니까?</b></td>
												<td colspan="2"><?php echo $view_data['view']['research_view']['test3']; ?></td>
											</tr>
											<tr>
												<td colspan="3" class="text_left"><b>* 심리사회 서비스(심리치료)를 받아보신 경험이 있으십니까?</b></td>
												<td colspan="2"><?php echo $view_data['view']['research_view']['test4']; ?></td>
											</tr>
											<tr>
												<td colspan="3" class="text_left"><b>* 웹/앱 기반 심리치료를 이용해보신 경험이 있으십니까?</b></td>
												<td colspan="2"><?php echo $view_data['view']['research_view']['test5']; ?></td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>

							<tr>
								<th scope="col">사이트 가입일*</th>
								<td>
									<?php echo $view_data['view']['member_view']['created_datetime']; ?>
								</td>
								<th scope="col" class="br_left">사이트 가입 IP*</th>
								<td>
									<?php echo $view_data['view']['member_view']['ip']; ?>
								</td>
							</tr>

						</table>

						<span class="btn_wrap">
							<a href="<?php echo $CFX->router->link_href('update', urldecode($CFX->uri->qstr)); ?>" class="update_right" title="수정">수정</a>
							<a href="<?php echo $CFX->router->link_href('lists', urldecode($CFX->uri->qstr)); ?>" class="list_left" title="목록">목록</a>
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