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
								<a href="/adm/screening/lists"><button type="button" class="btn btn-primary">설문 스크리닝 관리</button></a>
								<a href="/adm/severity/lists"><button type="button" class="btn btn-info">심각도 평가 관리</button></a>
								<a href="/adm/survey/lists"><button type="button" class="btn btn-info">추가 설문 관리</button></a>
								<a href="/adm/setup/lists"><button type="button" class="btn btn-info">설정 관리</button></a>
							</div>
						</div>

						<form name="screeningUpdateForm" id="screeningUpdateForm" method="post">
						<input type="hidden" name="wmode" value="<?php echo $CFX->uri->wmode; ?>">
						<input type="hidden" name="token" value="<?php echo $CFX->uri->token; ?>">
						<input type="hidden" name="page_rows" value="<?php echo $CFX->uri->page_rows; ?>">
						<input type="hidden" name="page" value="<?php echo $CFX->uri->page; ?>">
						<input type="hidden" name="ssort" value="<?php echo $CFX->uri->ssort; ?>">
						<input type="hidden" name="sorder" value="<?php echo $CFX->uri->sorder; ?>">
						
						<table summary="설문 스크리닝 수정" class="com_table col_table">
							<colgroup>
								<col width="15%">
								<col width="35%">
								<col width="15%">
								<col width="35%">
							</colgroup>

							<tr>
								<th scope="col">분류명*</th>
								<td colspan="3">
									<?php echo $view_data['update']['screening_view']['screening_group']; ?>
								</td>
							</tr>

							<tr>
								<th scope="col">문항번호*</th>
								<td colspan="3">
									<?php echo $view_data['update']['screening_view']['screening_order']; ?>
								</td>
							</tr>

							<tr>
								<th scope="col">설문문항*</th>
								<td colspan="3">
									<input name="screening_title" type="text" value="<?php echo $view_data['update']['screening_view']['title']; ?>" placeholder="" maxlength="255" class="cm_ip w100_ip">
								</td>
							</tr>

							<tr>
								<th scope="col">문항보기 1*</th>
								<td colspan="3">
									<input name="screening_text1" type="text" value="<?php echo $view_data['update']['screening_view']['screening_text1']; ?>" placeholder="" maxlength="255" class="cm_ip w300_ip">
								</td>
							</tr>

							<tr>
								<th scope="col">문항보기 2*</th>
								<td colspan="3">
									<input name="screening_text2" type="text" value="<?php echo $view_data['update']['screening_view']['screening_text2']; ?>" placeholder="" maxlength="255" class="cm_ip w300_ip">
								</td>
							</tr>

							<tr>
								<th scope="col">설문유형*</th>
								<td colspan="3">
									<?php echo $view_data['update']['screening_view']['screening_type']; ?>
								</td>
							</tr>

							<tr>
								<th scope="col">수정일*</th>
								<td colspan="3">
									<?php echo $view_data['update']['screening_view']['modified_datetime']; ?>
								</td>
							</tr>

						</table>

						<span class="btn_wrap">
							<input type="submit" class="submit_right" value="완료">
							<input type="reset" class="cancel_right" value="취소">

							<a href="<?php echo $CFX->router->link_href('lists', urldecode($CFX->uri->qstr)); ?>" class="list_left" title="목록">목록</a>
						</span>

						</form>
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

		$('#screeningUpdateForm').validate({
			rules : {
				'screening_title' : {required : true},
				'screening_text1' : {required : true},
				'screening_text2' : {required : true}
		    },
		    messages : {
				'screening_title' : {
					required : '* 설문문항을 입력하세요.',
				},
				'screening_text1' : {
					required : '* 최소치를 입력하세요.',
				},
				'screening_text2' : {
					required : '* 최대치를 입력하세요.',
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

//-->
</script>

<?php $CFX->load->common_view('page-home-bottom'); ?>
</div>