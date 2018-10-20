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

						<form name="groupUpdateForm" id="groupUpdateForm" method="post" autocomplete="off">
						<input type="hidden" name="wmode" value="<?php echo $CFX->uri->wmode; ?>">
						<input type="hidden" name="token" value="<?php echo $CFX->uri->token; ?>">
						<input type="hidden" name="page_rows" value="<?php echo $CFX->uri->page_rows; ?>">
						<input type="hidden" name="page" value="<?php echo $CFX->uri->page; ?>">
						<input type="hidden" name="ssort" value="<?php echo $CFX->uri->ssort; ?>">
						<input type="hidden" name="sorder" value="<?php echo $CFX->uri->sorder; ?>">

						<table summary="프로젝트 수정" class="com_table col_table">
							<colgroup>
								<col width="15%">
								<col width="35%">
								<col width="15%">
								<col width="35%">
							</colgroup>

							<tr>
								<th scope="col">프로젝트명*</th>
								<td colspan="3">
									<input id="name" name="name" type="text" value="<?php echo $view_data['update']['group_view']['name']; ?>" placeholder="" required maxlength="255" class="cm_ip w300_ip">
								</td>
							</tr>

							<tr>
								<th scope="col">프로젝트 담당자명*</th>
								<td colspan="3">
									<?php echo $view_data['update']['group_view']['manager_name']; ?>
								</td>
							</tr>

							<tr>
								<th scope="col">상태*</th>
								<td colspan="3">
									<span class="col_ul">
										<em class="radio_group">
											<input type="radio" id="use" name="activated" value="1" <?php  echo ($view_data['update']['group_view']['activated'] == '1') ? ' checked' : ''; ?>>
											<label for="use">활성화</label>
										</em>
										<label for="use">활성화</label>
									</span>
									<span>
										<em class="radio_group">
											<input type="radio" id="nouse" name="activated" value="0" <?php echo ($view_data['update']['group_view']['activated'] == '0') ? ' checked' : ''; ?>>
											<label for="nouse">비활성화</label>
										</em>
										<label for="nouse">비활성화</label>
									</span>
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

		$('#groupUpdateForm').validate({
			rules : {
				'name' : {required : true }
		    },
		    messages : {
				'name' : {
					required: '프로젝트명을 입력하세요.'
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