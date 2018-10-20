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
									<a href="/adm/member/lists"><button type="button" class="btn btn-primary">회원 관리</button></a>
									<a href="/adm/manager/lists"><button type="button" class="btn btn-info">관리자 관리</button></a>
									<a href="/adm/group/lists"><button type="button" class="btn btn-info">프로젝트 관리</button></a>
									<a href="/adm/gift/lists"><button type="button" class="btn btn-info">기프티콘 관리</button></a>
									<a href="/adm/screening/lists"><button type="button" class="btn btn-info">설문 스크리닝 관리</button></a>
									<a href="/adm/severity/lists"><button type="button" class="btn btn-info">심각도 평가 관리</button></a>
									<a href="/adm/survey/lists"><button type="button" class="btn btn-info">추가 설문 관리</button></a>
									<a href="/adm/setup/lists"><button type="button" class="btn btn-info">설정 관리</button></a>
								</div>
							</div>

							<div class="filter">

								<!-- 프로젝트 시작 { -->
								<span class="group_sl_wrap">
									<label>프로젝트명</label>
									<select name="group" class="cm_sl ca_sl"  onChange="location.href=this.value">
										<?php echo $view_data['list']['select_group']; ?>
									</select>

								</span>
								<!-- } 프로젝트 끝 -->

								<div style="clear:both;"></div>

							</div>

							<div class="about_list">
								<span class="left">
									<label>
										<strong>Total : </strong>
										<strong><?php echo number_format($view_data['list']['total_count']) ?></strong> 건 중, 현재 페이지 
										<strong><?php echo number_format($view_data['list']['current_page']) ?></strong> / 
										<strong><?php echo number_format($view_data['list']['total_page']) ?></strong>
									</label>
								</span>

								<span class="right">
									<label><strong>페이지 목록노출 : </strong></label>
									<select class="cm_sl" onchange="location.href=this.value">
										<?php echo $view_data['list']['select_page_rows_option']; ?>
									</select>
								</span>
							</div>

							<table summary="<?php echo $CFX->title(); ?>" class="com_table row_table">
								<caption><?php echo $CFX->title(); ?></caption>
								<colgroup>
									<col width="4%">
									<col width="6%">
									<col width="6%">
									<col width="6%">
									<col width="4%">
									<col width="8%">
									<col width="4%">
									<col width="7%">
									<col width="4%">
									<col width="8%">
									<col width="7%">
									<col width="7%">
									<col width="">
									<col width="">
									<col width="">
									<col width="">
									<col width="">
									<col width="">
									<col width="8%">
								</colgroup>
								<thead>
									<tr>
										<th>No</th>
										<th scope="row"><span>프로젝트명</span></th>
										<th scope="row"><span>집단명</span></th>
										<th scope="row"><span>아이디</span></th>
										<th scope="row"><span>이름</span></th>
										<th scope="row"><span>생년월일</span></th>
										<th scope="row"><span>성별</span></th>
										<th scope="row"><span>진단명</span></th>
										<th scope="row"><span>병기</span></th>
										<th scope="row"><span>진단시기</span></th>
										<th scope="row"><span>재발여부</span></th>
										<th scope="row"><span>받은치료</span></th>
										<th scope="row"><span title="과거 정신의학진단" style="cursor: pointer;">①</span></th>
										<th scope="row"><span title="과거 정신의학약물복용" style="cursor: pointer;">② </span></th>
										<th scope="row"><span title="현재 정신의학약물복용" style="cursor: pointer;">③</span></th>
										<th scope="row"><span title="심리치료경험" style="cursor: pointer;">④</span></th>
										<th scope="row"><span title="웹/앱 기반 심리치료경험" style="cursor: pointer;">⑤</span></th>
										<th scope="row"><span>상태</span></th>
										<th scope="row"><span>등록일</span></th>
									</tr>
								</thead>
								<tbody>
								<?php for ($i=0; $i<count($view_data['list']['member_list']); $i++) { ?>
									<tr>
										<td><span class="title"><?php echo $view_data['list']['member_list'][$i]['num'] ?></span></td>
										<td><span class="title"><?php echo $view_data['list']['member_list'][$i]['group_name'] ?></span></td>
										<td><span class="title"><?php echo $view_data['list']['member_list'][$i]['group_label_name'] ?></span></td>
										<td><span class="title"><a href="<?php echo $view_data['list']['member_list'][$i]['view_href'] ?>"><?php echo $view_data['list']['member_list'][$i]['id'] ?></a></span></td>
										<td><span class="title"><a href="<?php echo $view_data['list']['member_list'][$i]['view_href'] ?>"><?php echo $view_data['list']['member_list'][$i]['name'] ?></a></span></td>
										<td><span class="title"><?php echo $view_data['list']['member_list'][$i]['birth'] ?></span></td>
										<td><span class="title"><?php echo $view_data['list']['member_list'][$i]['gender_name'] ?></span></td>
										<td><span class="title"><?php echo $view_data['list']['member_list'][$i]['research_name'] ?></span></td>
										<td><span class="title"><?php echo $view_data['list']['member_list'][$i]['research_level'] ?>기</span></td>
										<td><span class="title"><?php echo $view_data['list']['member_list'][$i]['research_date'] ?></span></td>
										<td><span class="title"><?php echo $view_data['list']['member_list'][$i]['research_recur'] ?></span></td>
										<td><span class="title"><?php echo $view_data['list']['member_list'][$i]['research_care'] ?></span></td>
										<td><span class="title"><?php echo $view_data['list']['member_list'][$i]['research_test1'] ?></span></td>
										<td><span class="title"><?php echo $view_data['list']['member_list'][$i]['research_test2'] ?></span></td>
										<td><span class="title"><?php echo $view_data['list']['member_list'][$i]['research_test3'] ?></span></td>
										<td><span class="title"><?php echo $view_data['list']['member_list'][$i]['research_test4'] ?></span></td>
										<td><span class="title"><?php echo $view_data['list']['member_list'][$i]['research_test5'] ?></span></td>
										<td><span class="title"><?php echo $view_data['list']['member_list'][$i]['status'] ?></span></td>
										<td><span class="title"><?php echo $view_data['list']['member_list'][$i]['created_datetime'] ?></span></td>	
									</tr>
								<?php } // end for ?>
								<?php if (count($view_data['list']['member_list']) == 0) { ?>
									<tr>
										<td colspan="19"><span class="title"> 해당 자료가 없습니다.</span></td>
									</tr>
								<?php }  ?>
									<tr>
										<th colspan="19"><span>① 과거 정신의학진단</span> <span>② 과거 정신의학약물복용</span> <span>③ 현재 정신의학약물복용</span> <span>④ 심리치료경험</span> <span>⑤ 웹/앱 기반 심리치료경험</span></th>
									</tr>
								</tbody>
							</table>

							<div class="filter">
								<div class="search">
									<form name="search_form" method="get">
									<label>검색</label>
									<span>
									    <label for="sfield" class="sound_only">검색대상</label>
										<select name="sfield" class="cm_sl ca_sl">
											<?php echo $view_data['list']['select_search_field_option']; ?>
										</select>
										<input type="hidden" name="page_rows" value="<?php echo $CFX->uri->page_rows ?>">
										<label for="stext" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
										<input type="search" name="stext" value="<?php echo stripslashes($CFX->uri->stext) ?>" required id="stext" class="cm_ip" maxlength="20">
										<input type="submit" value="검색">
									</span>
									</form>
								</div>
							</div>

							<span class="btn_wrap">
								<!-- <a href="<?php echo $CFX->router->link_href('write'); ?>" class="write_right" title="등록" >등록</a> -->
								<a href="<?php echo $CFX->router->link_href('lists'); ?>" class="list_left">목록</a>
							</span>
							<?php echo $view_data['list']['member_pages']; ?>

						</article>
					</div>
				</section>
			</fieldset>
			<!-- } 목록 끝 -->
		</div>
	</div>

<?php $CFX->load->common_view('page-home-bottom'); ?>
</div>