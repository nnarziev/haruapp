<?php
defined('CFX_APPPATH') OR exit('No direct script access allowed');
?>
<?php $CFX->load->common_view('page-home-navibar'); ?>
<div role="main" class="main">
	<section id="overview" class="custom-section custom-background-color-1 m-none">
	</section>

	<div class="container-fluid">
		<div id="content">
			<!-- 목록 시작 { -->
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

							<table summary="<?php echo $CFX->title(); ?>" class="com_table row_table">
								<caption><?php echo $CFX->title(); ?></caption>
								<colgroup>
									<col width="5%">
									<col width="8%">
									<col width="8%">
									<col width="8%">
									<col width="8%">
									<col width="12%">
									<col width="12%">
									<col width="12%">
									<col width="">
								</colgroup>
								<thead>
									<tr>
										<th scope="row"><span>NO</span></th>
										<th scope="row"><span>프로그램</span></th>
										<th scope="row"><span>아이디</span></th>
										<th scope="row"><span>이름</span></th>
										<th scope="row"><span>신청단계</span></th>
										<th scope="row"><span>기프티콘 선택번호</span></th>
										<th scope="row"><span>기프티콘 신청일</span></th>
										<th scope="row"><span>기프티콘 처리일</span></th>
										<th scope="row"><span>관리자 메모</span></th>
									</tr>
								</thead>
								<tbody>
								<?php for ($i=0; $i<count($view_data['list']['gift_list']); $i++) { ?>
									<tr>
										<td><span class="title"><?php echo $view_data['list']['gift_list'][$i]['num'] ?></span></td>
										<td><span class="title"><?php echo $view_data['list']['gift_list'][$i]['program'] ?></span></td>
										<td><span class="title"><a href="<?php echo $view_data['list']['gift_list'][$i]['view_href']; ?>"><?php echo $view_data['list']['gift_list'][$i]['member_id']; ?></a></span></td>
										<td ><span class="title"><a href="<?php echo $view_data['list']['gift_list'][$i]['view_href']; ?>"><?php echo $view_data['list']['gift_list'][$i]['member_name']; ?></a></span></td>
										<td><span class="title"><?php echo $view_data['list']['gift_list'][$i]['gift_count'] ?>단계</span></td>
										<td><span class="title"><?php echo $view_data['list']['gift_list'][$i]['gift_num'] ?>번</span></td>
										<td><span class="title"><?php echo $view_data['list']['gift_list'][$i]['created_datetime'] ?></span></td>
										<td><span class="title"><?php echo $view_data['list']['gift_list'][$i]['confirm_datetime'] ?></span></td>
										<td><span class="title"><?php echo $view_data['list']['gift_list'][$i]['memo'] ?></span></td>
									</tr>
								<?php } // end for ?>
								<?php if (count($view_data['list']['gift_list']) == 0) { ?>
									<tr>
										<td colspan="6"><span class="title"> 해당 자료가 없습니다.</span></td>
									</tr>
								<?php }  ?>
								</tbody>
							</table>

							<span class="btn_wrap">
								<!-- <a href="<?php echo $CFX->router->link_href('write'); ?>" class="write_right" title="등록" >등록</a> -->
								<a href="<?php echo $CFX->router->link_href('lists'); ?>" class="list_left">목록</a>
							</span>
							<?php echo $view_data['list']['gift_pages']; ?>

						</article>
					</div>
				</section>
			</fieldset>
			<!-- } 목록 끝 -->
		</div>
	</div>

<?php $CFX->load->common_view('page-home-bottom'); ?>
</div>