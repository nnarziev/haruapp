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

						<form name="memberUpdateForm" id="memberUpdateForm" method="post" autocomplete="off">
						<input type="hidden" name="wmode" value="<?php echo $CFX->uri->wmode; ?>">
						<input type="hidden" name="token" value="<?php echo $CFX->uri->token; ?>">
						<input type="hidden" name="page_rows" value="<?php echo $CFX->uri->page_rows; ?>">
						<input type="hidden" name="page" value="<?php echo $CFX->uri->page; ?>">
						<input type="hidden" name="stext" value="<?php echo $CFX->uri->stext; ?>">
						<input type="hidden" name="ssort" value="<?php echo $CFX->uri->ssort; ?>">
						<input type="hidden" name="sorder" value="<?php echo $CFX->uri->sorder; ?>">

						<table summary="회원관리 수정" class="com_table col_table">
							<colgroup>
								<col width="15%">
								<col width="35%">
								<col width="15%">
								<col width="35%">
							</colgroup>

							<tr>
								<th scope="col">프로젝트명*</th>
								<td colspan="3">
									<select id="group" name="group" class="cm_sl ca_sl" onChange="show_group_label(this.options[this.selectedIndex].value);">
										<?php echo $view_data['update']['select_group']; ?>
									</select> ( 선택하세요. )
								</td>
							</tr>

							<tr>
								<th scope="col">프로젝트 집단명*</th>
								<td colspan="3">
									<select  id="group_label" name="group_label" class="cm_sl ca_sl">
										<?php echo $view_data['update']['select_group_label']; ?>
									</select> ( 선택하세요. )
								</td>
							</tr>

							<tr>
								<th scope="col">아이디*</th>
								<td colspan="3">
									<?php echo $view_data['update']['member_view']['id']; ?>
								</td>
							</tr>

							<tr>
								<th scope="col">이름*</th>
								<td colspan="3">
									<?php echo $view_data['update']['member_view']['name']; ?>
								</td>
							</tr>

							<!-- 비밀번호 변경 시작 { -->
							<tr>
								<th scope="col">새 비밀번호 변경</th>
								<td colspan="3">
									<input id="pw" name="pw" type="password" value="" placeholder="" maxlength="255" class="cm_ip w300_ip">
									( 새 비밀번호 변경시 최소 6글자 이상 20글자 이하 입니다. )
								</td>
							</tr>
							<!-- } 비밀번호 변경 끝 -->

							<tr>
								<th scope="col">이메일</th>
								<td colspan="3">
									<?php echo $view_data['update']['member_view']['email']; ?>
								</td>
							</tr>

							<tr>
								<th scope="col">휴대전화</th>
								<td colspan="3">
									<?php echo $view_data['update']['member_view']['hp']; ?>
								</td>
							</tr>

							<tr>
								<th scope="col">생년월일*</th>
								<td colspan="3">
									<?php echo $view_data['update']['member_view']['birth']; ?>
								</td>
							</tr>

							<tr>
								<th scope="col">성별*</th>
								<td colspan="3">
									<?php echo $view_data['update']['member_view']['gender_name']; ?>
								</td>
							</tr>

							<tr>
								<th scope="col">회원레벨*</th>
								<td colspan="3">
									<?php echo $view_data['update']['member_view']['level_name']; ?>
								</td>
							</tr>

							<tr>
								<th scope="col">회원상태*</th>
								<td colspan="3">
									<span class="col_ul">
										<em class="radio_group">
											<input type="radio" id="use" name="activated" value="1" <?php  echo ($view_data['update']['member_view']['activated'] == '1') ? ' checked' : ''; ?>>
											<label for="use">등록</label>
										</em>
										<label for="use">등록</label>
									</span>
									<span>
										<em class="radio_group">
											<input type="radio" id="nouse" name="activated" value="0" <?php echo ($view_data['update']['member_view']['activated'] == '0') ? ' checked' : ''; ?>>
											<label for="nouse">탈퇴</label>
										</em>
										<label for="nouse">탈퇴</label>
									</span>
								</td>
							</tr>

							<tr>
								<th scope="col">오늘 로그인*</th>
								<td>
									<?php echo $view_data['update']['member_view']['login_datetime']; ?>
								</td>
								<th scope="col" class="br_left">오늘 로그인 IP*</th>
								<td>
									<?php echo $view_data['update']['member_view']['login_ip']; ?>
								</td>
							</tr>

							<tr>
								<th scope="col">사이트 가입일*</th>
								<td>
									<?php echo $view_data['update']['member_view']['created_datetime']; ?>
								</td>
								<th scope="col" class="br_left">사이트 가입 IP*</th>
								<td>
									<?php echo $view_data['update']['member_view']['ip']; ?>
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

		$.validator.addMethod('passwordchk', function( value, element ) {
			return this.optional(element) || /^.*(?=.*\d)(?=.*[a-zA-Z])(?=.*[!@#$%^&+=]).*$/.test(value);
		});

		$('#memberUpdateForm').validate({
			rules : {
				'pw' : {required : false, rangelength : [6, 20], passwordchk : false}
		    },
		    messages : {
				'pw' : {
					rangelength : $.validator.format('* 새 비밀번호는 최소 {0}글자 이상 {1}글자 이하 입니다.'),
					passwordchk : '* 새 비밀번호는 영문, 숫자, 특수문자(!@#$%^&+=) 포함입니다.',
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

	function show_group_label(obj) {

		$.ajax( {
			type: 'POST',
			url: '<?php echo $CFX->router->link_href('group_label_change'); ?>',
			data: { 'group_no' : obj },
			success: function(data) {
				document.getElementById('group_label').innerHTML = data;
			},
			error: function (error) {
				alert('error ; ' + eval(error));
			}
		} );
	}

//-->
</script>

<?php $CFX->load->common_view('page-home-bottom'); ?>
</div>