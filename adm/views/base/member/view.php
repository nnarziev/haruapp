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

						<table summary="회원관리 보기" class="com_table col_table">
							<colgroup>
								<col width="15%">
								<col width="35%">
								<col width="15%">
								<col width="35%">
							</colgroup>

							<tr>
								<th scope="col">프로젝트명*</th>
								<td colspan="3">
									<?php echo $view_data['view']['member_view']['group_name']; ?>
								</td>
							</tr>

							<tr>
								<th scope="col">프로젝트 집단명*</th>
								<td colspan="3">
									<?php echo $view_data['view']['member_view']['group_label_name']; ?>
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
								<th scope="col">이메일</th>
								<td colspan="3">
									<?php echo $view_data['view']['member_view']['email']; ?>
								</td>
							</tr>

							<tr>
								<th scope="col">휴대전화</th>
								<td colspan="3">
									<?php echo $view_data['view']['member_view']['hp']; ?>
								</td>
							</tr>

							<tr>
								<th scope="col">생년월일*</th>
								<td colspan="3">
									<?php echo $view_data['view']['member_view']['birth']; ?>
								</td>
							</tr>

							<tr>
								<th scope="col">성별*</th>
								<td colspan="3">
									<?php echo $view_data['view']['member_view']['gender_name']; ?>
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
												<td><span class="title"><?php echo $view_data['view']['member_view']['research_name']; ?></span></td>
												<td><span class="title"><?php echo $view_data['view']['member_view']['research_level']; ?>기</span></td>
												<td><span class="title"><?php echo $view_data['view']['member_view']['research_date']; ?></span></td>
												<td><span class="title"><?php echo $view_data['view']['member_view']['research_recur']; ?></span></td>
												<td><span class="title"><?php echo $view_data['view']['member_view']['research_care']; ?></span></td>
											</tr>
											<tr>
												<td colspan="3" class="text_left"><b>* 과거에 정신의학과에서 진단을 받은 적이 있으십니까?</b></td>
												<td colspan="2"><?php echo $view_data['view']['member_view']['research_test1']; ?></td>
											</tr>
											<tr>
												<td colspan="3" class="text_left"><b>* 과거에 정신의학과에서 처방받아 복용하신 약물이 있습니까?</b></td>
												<td colspan="2"><?php echo $view_data['view']['member_view']['research_test2']; ?></td>
											</tr>
											<tr>
												<td colspan="3" class="text_left"><b>* 현재 정신의학과에서 처방받아 복용하신 약물이 있습니까?</b></td>
												<td colspan="2"><?php echo $view_data['view']['member_view']['research_test3']; ?></td>
											</tr>
											<tr>
												<td colspan="3" class="text_left"><b>* 심리사회 서비스(심리치료)를 받아보신 경험이 있으십니까?</b></td>
												<td colspan="2"><?php echo $view_data['view']['member_view']['research_test4']; ?></td>
											</tr>
											<tr>
												<td colspan="3" class="text_left"><b>* 웹/앱 기반 심리치료를 이용해보신 경험이 있으십니까?</b></td>
												<td colspan="2"><?php echo $view_data['view']['member_view']['research_test5']; ?></td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>

							<tr>
								<th scope="col">회원레벨*</th>
								<td colspan="3">
									<?php echo $view_data['view']['member_view']['level_name']; ?>
								</td>
							</tr>

							<tr>
								<th scope="col">회원상태*</th>
								<td colspan="3">
									<?php echo $view_data['view']['member_view']['status']; ?>
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
							<a href="<?php echo $CFX->router->link_href('lists', urldecode($CFX->uri->qstr)); ?>" class="list_left" title="목록">목록</a>
							<a href="<?php echo $CFX->router->link_href('update', urldecode($CFX->uri->qstr)); ?>" class="update_right" title="수정">수정</a>
						</span>

						<table summary="회원관리 보기" class="com_table col_table">
							<colgroup>
								<col width="15%">
								<col width="35%">
								<col width="15%">
								<col width="35%">
							</colgroup>

							<tr>
								<th scope="col">메모 입력</th>
								<td>
									<form name="memoRegistForm" id="memoRegistForm" method="post" autocomplete="off">
										<input type="input" name="memo_content" value="" placeholder="" required maxlength="800" class="cm_ip w250_ip">
										<input type="submit" class="submit_right" value="메모 등록">
									</form>
								</td>
								<td colspan="2"></td>
							</tr>
							<tr>
								<th scope="col">메모 내용</th>
								<td colspan="3">
									<table class="com_table col_table">
										<colgroup>
											<col width="18%">
											<col width="70%">
											<col width="12%">
											<col width="40">
										</colgroup>
									<?php for ($i=0; $i<count($view_data['view']['memo_list']); $i++) { ?>
										<tr>
											<th scope="col"><?php echo $view_data['view']['memo_list'][$i]['id']; ?>(<?php echo $view_data['view']['memo_list'][$i]['name']; ?>)</th>
											<td><?php echo $view_data['view']['memo_list'][$i]['content']; ?></td>
											<td><?php echo $view_data['view']['memo_list'][$i]['created_datetime']; ?></td>
											<td>
												<a href="javascript:delete_memo('<?php echo  $view_data['view']['memo_list'][$i]['delete_link']; ?>');" title="삭제"><img src="/adm/assets/img/com_delete.png"></a>
											</td>
										</tr>
									<?php } ?>
									</table>
								</td>
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

		$('input').keydown(function() {
			if (event.keyCode === 13) {
				event.preventDefault();
			}
		});

		$('#memoRegistForm').validate({
			rules : {
				'memo_content' : {required : true }
		    },
		    messages : {
				'memo_content' : {
					required: '메모 내용을 입력하세요.'
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

	function delete_memo(url) {
		var result = confirm("해당 메모를 삭제하시겠습니까?");
		if (result == true) {
			 window.location.href = url;
		}
	}

//-->
</script>

<?php $CFX->load->common_view('page-home-bottom'); ?>
</div>