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
								<a href="/adm/group/lists"><button type="button" class="btn btn-primary">프로젝트 관리</button></a>
								<a href="/adm/gift/lists"><button type="button" class="btn btn-info">기프티콘 관리</button></a>
								<a href="/adm/screening/lists"><button type="button" class="btn btn-info">설문 스크리닝 관리</button></a>
								<a href="/adm/severity/lists"><button type="button" class="btn btn-info">심각도 평가 관리</button></a>
								<a href="/adm/survey/lists"><button type="button" class="btn btn-info">추가 설문 관리</button></a>
								<a href="/adm/setup/lists"><button type="button" class="btn btn-info">설정 관리</button></a>
							</div>
						</div>

						<table summary="프로젝트 보기" class="com_table col_table">
							<colgroup>
								<col width="15%">
								<col width="35%">
								<col width="15%">
								<col width="35%">
							</colgroup>

							<tr>
								<th scope="col">프로젝트명*</th>
								<td colspan="3">
									<?php echo $view_data['view']['group_view']['name']; ?>
								</td>
							</tr>

							<tr>
								<th scope="col">프로젝트 담당자명*</th>
								<td colspan="3">
									<?php echo $view_data['view']['group_view']['manager_name']; ?>
								</td>
							</tr>

							<tr>
								<th scope="col">프로젝트 참여자 목록*</th>
								<td colspan="3">
									<table summary="프로젝트 참여자 보기" class="com_table row_table">
										<colgroup>
											<col width="10%">
											<col width="10%">
											<col width="20%">
											<col width="25%">
											<col width="25%">
											<col width="10%">
										</colgroup>
										<thead>
											<tr>
												<th scope="col"><span>아이디</span></th>
												<th scope="col"><span>이름</span></th>
												<th scope="col"><span>프로젝트 집단명</span></th>
												<th scope="col"><span>오늘 로그인</span></th>
												<th scope="col"><span>사이트 가입일</span></th>
												<th scope="col"><span>상태</span></th>
											</tr>
										</thead>
									<?php for ($i=0; $i<count($view_data['view']['member_list']); $i++) { ?>
										<tr>
											<td><a href="/adm/member/view/<?php echo $view_data['view']['member_list'][$i]['member_no']; ?>"><?php echo $view_data['view']['member_list'][$i]['member_id']; ?></a></td>
											<td><a href="/adm/member/view/<?php echo $view_data['view']['member_list'][$i]['member_no']; ?>"><?php echo $view_data['view']['member_list'][$i]['member_name']; ?></a></td>
											<td><?php echo $view_data['view']['member_list'][$i]['member_label_name']; ?></td>
											<td><?php echo $view_data['view']['member_list'][$i]['member_login_datetime']; ?></td>
											<td><?php echo $view_data['view']['member_list'][$i]['member_created_datetime']; ?></td>
											<td><?php echo $view_data['view']['member_list'][$i]['member_label_status']; ?></td>
										</tr>
									<?php } ?>
									<?php if (count($view_data['view']['member_list']) == 0) { ?>
										<tr>
											<td colspan="6"><span class="title"> 해당 자료가 없습니다.</span></td>
										</tr>
									<?php }  ?>
									</table>
									<br>
									* 프로젝트 참여자 등록 및 변경은 <a href="/adm/member/lists"><b>회원 메뉴</b></a>에서 설정 가능합니다.
								</td>
							</tr>

							<tr>
								<th scope="col">참여자수 (활성화)*</th>
								<td>
									<?php echo $view_data['view']['group_view']['member_activated_count']; ?>명
								</td>
								<th scope="col" class="br_left">참여자수 (비활성화)*</th>
								<td>
									<?php echo $view_data['view']['group_view']['member_inactivated_count']; ?>명
								</td>
							</tr>

							<tr>
								<th scope="col">프로젝트 상태*</th>
								<td colspan="3">
									<?php echo $view_data['view']['group_view']['status']; ?>
								</td>
							</tr>

						</table>

						<span class="btn_wrap">
							<a href="<?php echo $CFX->router->link_href('lists', urldecode($CFX->uri->qstr)); ?>" class="list_left" title="목록">목록</a>
							<a href="<?php echo $CFX->router->link_href('update', urldecode($CFX->uri->qstr)); ?>" class="update_right" title="수정">수정</a>
						</span>

						<table summary="프로젝트 보기" class="com_table col_table">
							<colgroup>
								<col width="15%">
								<col width="35%">
								<col width="15%">
								<col width="35%">
							</colgroup>

							<tr>
								<th scope="col">프로젝트 집단명 입력</th>
								<td>
									<form name="labelRegistForm" id="labelRegistForm" method="post" autocomplete="off">
										<input type="input" name="label_name" value="" placeholder="" required maxlength="255" class="cm_ip w250_ip">
										<input type="submit" class="submit_right" value="집단명 등록">
									</form>
								</td>
								<td colspan="2"></td>
							</tr>
							<tr>
								<th scope="col">프로젝트 집단명 목록</th>
								<td>
									<table class="com_table col_table">
										<colgroup>
											<col width="40">
											<col width="">
											<col width="40">
										</colgroup>
									<?php for ($i=0; $i<count($view_data['view']['label_list']); $i++) { ?>
										<tr>
											<td scope="col">
												<div class="col-auto">
				 									<span class="checkbox_group">
														<input type="checkbox"  id="group_label<?php echo $view_data['view']['label_list'][$i]['no']; ?>" name="group_label<?php echo $view_data['view']['label_list'][$i]['no']; ?>" value="<?php echo $view_data['view']['label_list'][$i]['no']; ?>" <?php echo ( $view_data['view']['label_list'][$i]['activated'] == 1) ? " checked" : ""; ?>>
														<label for="group_label<?php echo $view_data['view']['label_list'][$i]['no']; ?>">check_icon</label>
													</span>
												</div>
											</td>
											<td><?php echo $view_data['view']['label_list'][$i]['name']; ?></td>
											<td>
												<a href="javascript:delete_label('<?php echo  $view_data['view']['label_list'][$i]['delete_link']; ?>');" title="삭제"><img src="/adm/assets/img/com_delete.png"></a>
											</td>
										</tr>
									<?php } // end for ?>
									</table>
									<br>
									* 체크 박스를 해제시 프로젝트 집단은 비활성화 됩니다.
								</td>
								<td colspan="2"></td>
							</tr>

						</table>

					</article>
				</div>
			</section>
		</fieldset>
		<!-- } 내용보기 끝 -->
		</div>
	</div>
<script type="text/javascript">
//<![CDATA[
	$(document).ready(function() {

		$('input[type=checkbox]').click(function(){
			var activated = 0;
			if ($(this).is(":checked"))
				activated = 1;

			$.ajax( {
				type: 'POST',
				url: '<?php echo $CFX->router->link_href('label_update'); ?>',
				data: { 'no' : $(this).val(), 'activated' : activated },
				success: function(data) {
					if (activated == 0)
						alert('집단명이 비활성화 되었습니다.');
					else
						alert('집단명이 활성화 되었습니다.');
				},
				error: function (error) {
					alert('error ; ' + eval(error));
				}
			} );
		});

		$('input').keydown(function() {
			if (event.keyCode === 13) {
				event.preventDefault();
			}
		});

		$('#labelRegistForm').validate({
			rules : {
				'label_name' : {required : true }
		    },
		    messages : {
				'label_name' : {
					required: '프로젝트 집단명을 입력하세요.'
				}
		    },
		    onkeyup : false,
		    onclick : false,
		    onfocusout : false,
		    showErrors: function(errorMap, errorList) {
		    	if( errorList != '' )
			    {    
		    		$(errorList[0].element).focus();
		    		alert( errorList[0].message);
		    		return false;
			    }
		    },   
		    submitHandler : function(form) {
				form.submit();
			}
		});
	});

	function delete_label(url) {
		var result = confirm("해당 프로젝트 집단명을 삭제하시겠습니까?");
		if (result == true) {
			 window.location.href = url;
		}
	}
//-->
</script>

<?php $CFX->load->common_view('page-home-bottom'); ?>
</div>