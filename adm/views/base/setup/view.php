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
								<a href="/adm/gift/lists"><button type="button" class="btn btn-info">기프티콘 관리</button></a>
								<a href="/adm/screening/lists"><button type="button" class="btn btn-info">설문 스크리닝 관리</button></a>
								<a href="/adm/severity/lists"><button type="button" class="btn btn-info">심각도 평가 관리</button></a>
								<a href="/adm/survey/lists"><button type="button" class="btn btn-info">추가 설문 관리</button></a>
								<a href="/adm/setup/lists"><button type="button" class="btn btn-primary">설정 관리</button></a>
							</div>
						</div>

						<table summary="설정 보기" class="com_table col_table">
							<colgroup>
								<col width="15%">
								<col width="35%">
								<col width="15%">
								<col width="35%">
							</colgroup>

							<tr>
								<th scope="col">설정명*</th>
								<td colspan="3">
									<?php echo $view_data['view']['setup_view']['name']; ?>
								</td>
							</tr>

							<tr>
								<th scope="col">기프티콘 이미지 1번*</th>
								<td colspan="3" style="height:75px;">
									<img src="<?php echo CFX_URL.'/data/images/'.$view_data['view']['setup_view']['data1']; ?>" height="250">
								</td>
							</tr>

							<tr>
								<th scope="col">기프티콘 이미지 2번*</th>
								<td colspan="3" style="height:75px;">
									<img src="<?php echo CFX_URL.'/data/images/'.$view_data['view']['setup_view']['data2']; ?>" height="250">
								</td>
							</tr>

							<tr>
								<th scope="col">기프티콘 이미지 3번*</th>
								<td colspan="3" style="height:75px;">
									<img src="<?php echo CFX_URL.'/data/images/'.$view_data['view']['setup_view']['data3']; ?>" height="250">
								</td>
							</tr>

							<tr>
								<th scope="col">기프티콘 이미지 4번*</th>
								<td colspan="3" style="height:75px;">
									<img src="<?php echo CFX_URL.'/data/images/'.$view_data['view']['setup_view']['data4']; ?>" height="250">
								</td>
							</tr>

							<tr>
								<th scope="col">기프티콘 이미지 5번*</th>
								<td colspan="3" style="height:75px;">
									<img src="<?php echo CFX_URL.'/data/images/'.$view_data['view']['setup_view']['data5']; ?>" height="250">
								</td>
							</tr>

							<tr>
								<th scope="col">상태*</th>
								<td colspan="3">
									<?php echo $view_data['view']['setup_view']['status']; ?>
								</td>
							</tr>

							<tr>
								<th scope="col">수정일*</th>
								<td colspan="3">
									<?php echo $view_data['view']['setup_view']['modified_datetime']; ?>
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