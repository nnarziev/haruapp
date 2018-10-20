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

						<form name="harucardJoinUpdateForm" id="harucardJoinUpdateForm" method="post" autocomplete="off">
						<input type="hidden" name="wmode" value="<?php echo $CFX->uri->wmode; ?>">
						<input type="hidden" name="token" value="<?php echo $CFX->uri->token; ?>">
						<input type="hidden" name="page_rows" value="<?php echo $CFX->uri->page_rows; ?>">
						<input type="hidden" name="page" value="<?php echo $CFX->uri->page; ?>">
						<input type="hidden" name="stext" value="<?php echo $CFX->uri->stext; ?>">
						<input type="hidden" name="ssort" value="<?php echo $CFX->uri->ssort; ?>">
						<input type="hidden" name="sorder" value="<?php echo $CFX->uri->sorder; ?>">

						<div class="filter">
							<div class="btn-group" role="group">
								<button type="button" class="btn btn-warn">하루카드 메뉴 : </button>
								<a href="/adm/harucard/lists"><button type="button" class="btn btn-primary">하루카드 관리</button></a>
							</div>
						</div>

						<table summary="하루카드 수정" class="com_table col_table">
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
										<?php echo $view_data['update']['select_group']; ?>
									</select>
								</td>
							</tr>

							<tr>
								<th scope="col">프로젝트 집단명*</th>
								<td colspan="3">
									<select name="group_label" class="cm_sl ca_sl" disabled>
										<?php echo $view_data['update']['select_group_label']; ?>
									</select>
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

							<tr>
								<th scope="col">하루카드 구분*</th>
								<td colspan="3">
									<span class="col_ul">
										<em class="radio_group">
											<input type="radio" id="hc_part1" name="hc_current_part" value="1" <?php  echo ($view_data['update']['harucard_join_view']['hc_current_part'] == '1') ? ' checked' : ''; ?>>
											<label for="hc_part1">우울·불안</label>
										</em>
										<label for="hc_part1">우울·불안</label>
									</span>
									<span>
										<em class="radio_group">
											<input type="radio" id="hc_part2" name="hc_current_part" value="2" <?php echo ($view_data['update']['harucard_join_view']['hc_current_part'] == '2') ? ' checked' : ''; ?>>
											<label for="hc_part2">수면</label>
										</em>
										<label for="hc_part2">수면</label>
									</span>
									<span>
										<em class="radio_group">
											<input type="radio" id="hc_part3" name="hc_current_part" value="3" <?php echo ($view_data['update']['harucard_join_view']['hc_current_part'] == '3') ? ' checked' : ''; ?>>
											<label for="hc_part3">통증</label>
										</em>
										<label for="hc_part3">통증</label>
									</span>
									<span>
										<em class="radio_group">
											<input type="radio" id="hc_part4" name="hc_current_part" value="4" <?php echo ($view_data['update']['harucard_join_view']['hc_current_part'] == '4') ? ' checked' : ''; ?>>
											<label for="hc_part4">미사용</label>
										</em>
										<label for="hc_part4">미사용</label>
									</span>
								</td>
							</tr>

							<tr>
								<th scope="col">우울·불안 모듈 시작일*</th>
								<td colspan="3">
									<div class="input-group input-append date" id="join1_datetimepicker">
										<input type="text" class="form-control input_type" id="join1_datetime" name="join1_datetime" placeholder="하루카드 우울·불안 시작일" required value="<?php echo $view_data['update']['harucard_join_view']['hc_join1_datetime']; ?>">
										<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
									</div>
									<input type="hidden" name="join1_datetime_org" value="<?php echo $view_data['update']['harucard_join_view']['hc_join1_datetime']; ?>">
								</td>
							</tr>

							<tr>
								<th scope="col">수면모듈 시작일*</th>
								<td colspan="3">
									<div class="input-group input-append date" id="join2_datetimepicker">
										<input type="text" class="form-control input_type" id="join2_datetime" name="join2_datetime" placeholder="하루카드 수면 시작일" required value="<?php echo $view_data['update']['harucard_join_view']['hc_join2_datetime']; ?>">
										<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
									</div>
									<input type="hidden" name="join2_datetime_org" value="<?php echo $view_data['update']['harucard_join_view']['hc_join2_datetime']; ?>">
								</td>
							</tr>

							<tr>
								<th scope="col">통증모듈 시작일*</th>
								<td colspan="3">
									<div class="input-group input-append date" id="join3_datetimepicker">
										<input type="text" class="form-control input_type" id="join3_datetime" name="join3_datetime" placeholder="하루카드 통증 시작일" required value="<?php echo $view_data['update']['harucard_join_view']['hc_join3_datetime']; ?>">
										<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
									</div>
									<input type="hidden" name="join3_datetime_org" value="<?php echo $view_data['update']['harucard_join_view']['hc_join3_datetime']; ?>">
								</td>
							</tr>

							<tr>
								<th scope="col">하루카드 사용여부*</th>
								<td colspan="3">
									<span class="col_ul">
										<em class="radio_group">
											<input type="radio" id="use" name="hc_activated" value="1" <?php  echo ($view_data['update']['harucard_join_view']['hc_activated'] == '1') ? ' checked' : ''; ?>>
											<label for="use">사용</label>
										</em>
										<label for="use">사용</label>
									</span>
									<span>
										<em class="radio_group">
											<input type="radio" id="nouse" name="hc_activated" value="0" <?php echo ($view_data['update']['harucard_join_view']['hc_activated'] == '0') ? ' checked' : ''; ?>>
											<label for="nouse">미사용</label>
										</em>
										<label for="nouse">미사용</label>
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
$( document ).ready(function( $ ) {

	$('input').keydown(function() {
		if (event.keyCode === 13) {
			event.preventDefault();
		}
	});

	;(function($){
		$.fn.datepicker.dates['kr'] = {
			days: ["일요일", "월요일", "화요일", "수요일", "목요일", "금요일", "토요일", "일요일"],
			daysShort: ["일", "월", "화", "수", "목", "금", "토", "일"],
			daysMin: ["일", "월", "화", "수", "목", "금", "토", "일"],
			months: ["1월", "2월", "3월", "4월", "5월", "6월", "7월", "8월", "9월", "10월", "11월", "12월"],
			monthsShort: ["1월", "2월", "3월", "4월", "5월", "6월", "7월", "8월", "9월", "10월", "11월", "12월"]
		};
	}(jQuery));

	$(function () {
		$('#join1_datetimepicker, #join2_datetimepicker, #join3_datetimepicker').datepicker({
			calendarWeeks: false,
			todayHighlight: true,
			autoclose: true,
			keepEmptyValues: true,
			format: "yyyy-mm-dd",
			language: "kr"
		});
	});

	$("#harucardJoinUpdateForm").submit(function(e) {
    	e.preventDefault();
	}).validate({
		errorPlacement: function(error, element) {
			var placement = $(element).data('error');

			if (placement) {
				$(placement).append(error)
			} else {
				error.insertAfter(element);
			}
		},
		invalidHandler: function(form, validator) {
			var errors = validator.numberOfInvalids();
			if (errors) {
				validator.errorList[0].element.focus();
			}
		},
		showErrors: function(errorMap, errorList) {
			if( errorList != '' )
			{
				$(errorList[0].element).show();
			}
			this.defaultShowErrors();
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