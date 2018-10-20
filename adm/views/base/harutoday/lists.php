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
									<button type="button" class="btn btn-warn">오늘하루 메뉴 : </button>
									<a href="/adm/harutoday/lists"><button type="button" class="btn btn-primary">오늘하루 관리</button></a>
								</div>
							</div>

							<?php if ($CFX->auth->is_admin()) { ?>

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

							<?php } ?>

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
									<col width="8%">
									<col width="8%">
									<col width="7%">
									<col width="6%">
									<col width="4%">
									<col width="5%">
									<col width="">
									<col width="8%">
									<col width="8%">
								</colgroup>
								<thead>
									<tr>
										<th>No</th>
										<th scope="row"><span>프로젝트명</span></th>
										<th scope="row"><span>집단명</span></th>
										<th scope="row"><span>모듈</span></th>
										<th scope="row"><span>아이디</span></th>
										<th scope="row"><span>이름</span></th>
										<th scope="row"><span>성별</span></th>

										<th scope="row"><span>상태</span></th>
										<th scope="row"><span>로그인</span></th>
										<th scope="row"><span>시작일</span></th>
									</tr>
								</thead>
								<tbody>
								<?php for ($i=0; $i<count($view_data['list']['member_list']); $i++) { ?>
									<tr>
										<td><span class="title"><?php echo $view_data['list']['member_list'][$i]['num'] ?></span></td>
										<td><span class="title"><?php echo $view_data['list']['member_list'][$i]['group_name'] ?></span></td>
										<td><span class="title"><?php echo $view_data['list']['member_list'][$i]['group_label_name'] ?></span></td>
										<td><span class="title"><?php echo $view_data['list']['member_list'][$i]['ht_current_part'] ?></span></td>
										<td><span class="title"><a href="<?php echo $view_data['list']['member_list'][$i]['view_href'] ?>"><?php echo $view_data['list']['member_list'][$i]['id'] ?></a></span></td>
										<td><span class="title"><a href="<?php echo $view_data['list']['member_list'][$i]['view_href'] ?>"><?php echo $view_data['list']['member_list'][$i]['name'] ?></a></span></td>
										<td><span class="title"><?php echo $view_data['list']['member_list'][$i]['gender_name'] ?></span></td>
										<td><span class="title"><?php echo $view_data['list']['member_list'][$i]['ht_activated'] ?></span></td>
										<td><span class="title"><?php echo $view_data['list']['member_list'][$i]['login_datetime'] ?></span></td>	
										<td><span class="title"><?php echo $view_data['list']['member_list'][$i]['ht_created_datetime'] ?></span></td>	
									</tr>
								<?php } // end for ?>
								<?php if (count($view_data['list']['member_list']) == 0) { ?>
									<tr>
										<td colspan="10"><span class="title"> 해당 자료가 없습니다.</span></td>
									</tr>
								<?php }  ?>
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