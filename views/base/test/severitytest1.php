<?php
defined('CFX_APPPATH') OR exit('No direct script access allowed');
?>

<?php $CFX->load->common_view('page-home-navibar'); ?>

<div role="main" class="main">
	<section id="overview" class="custom-section custom-background-color-1 m-none">
	</section>

	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-4 col-md4 col-md-offset-4">
				<section class="body-sign">
					<div class="center-sign">
						<hr>
						<div class="panel panel-sign">
							<div class="panel-title-sign mt-xl text-center">
								<h2 class="title text-uppercase text-weight-bold m-none"><i class="fa fa-user mr-xs"></i> <?php echo $CFX->title(); ?><br>(우울/불안)</h2>
							</div>
							<div class="panel-body">

								<form class="form-severitytest" method="post" id="severitytest_form" name="severitytest_form" target="_top" autocomplete="off">
									<input type="hidden" id="sta1_id" name="sta1_id" value="<?php echo $view_data['sta1_case']['sta1_id']; ?>">
									<input type="hidden" id="sta1_sent" name="sta1_sent" value="N">
									<div class="form-group">
										<div class="panel panel-default">
											<div class="panel-body">
												여러분이 어려움 경험하고 있는 우울 및 불안 문제가 얼마나 심각한지 알아보는 검사로,
												총 16문항으로 구성되어 있으며, 약 5-10분 소요됩니다.
												지난 2주 동안 다음과 같은 문제를 얼마나 자주 겪었는지 해당되는 난에 선택(O) 클릭해주세요
											</div>
											<div class="panel-footer panel-custom">
											<?php for ($i = 1; $i <= 16; $i++ ) { ?>
												<div class="form-group">
													<label for="sta1_case<?php echo $i ?>"><span class="required_input">*</span> <?php echo $view_data['stq1_case']['stq1_case'.$i]; ?></label>
													<span class="clearfix"></span>
													<label class="radio-inline">
														<input type="radio" name="sta1_case<?php echo $i ?>" required value="0" data-error="#sta1_case<?php echo $i ?>_error"<?php echo (intVal($view_data['sta1_case']['sta1_case'.$i]) == 0) ? ' checked' : ''; ?>> 전혀없음
													</label>
													<label class="radio-inline">
														<input type="radio" name="sta1_case<?php echo $i ?>" required value="1" data-error="#sta1_case<?php echo $i ?>_error"<?php echo (intVal($view_data['sta1_case']['sta1_case'.$i]) == 1) ? ' checked' : ''; ?>> 며칠 동안
													</label>
													<label class="radio-inline">
														<input type="radio" name="sta1_case<?php echo $i ?>" required value="2" data-error="#sta1_case<?php echo $i ?>_error"<?php echo (intVal($view_data['sta1_case']['sta1_case'.$i]) == 2) ? ' checked' : ''; ?>> 일주일 이상
													</label>
													<label class="radio-inline">
														<input type="radio" name="sta1_case<?php echo $i ?>" required value="3" data-error="#sta1_case<?php echo $i ?>_error"<?php echo (intVal($view_data['sta1_case']['sta1_case'.$i]) == 3) ? ' checked' : ''; ?>> 거의 매일
													</label>
													<div id="sta1_case<?php echo $i ?>_error"></div>
												</div>
											<?php } ?>
											</div>
										</div>
									</div>

									<div class="form-group">
										<button class="btn btn-lg btn-primary btn-block" type="submit">저장하기</button>
									</div>

									<div class="form-group">
										<button class="btn btn-lg btn-default btn-block" id="send_btn">제출하기</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<br>

<script>

	$( "#send_btn" ).click(function() {
		var result = confirm('심각도검사 평가를 제출하시겠습니까?');
		if (result) {
			$("#sta1_sent").val("Y");
			form.submit();
		}
	});

	$("#severitytest_form").validate({
		errorClass: 'severitytest_error',
		rules: {
			'sta1_case1' : {required : true},
			'sta1_case2' : {required : true},
			'sta1_case3' : {required : true},
			'sta1_case4' : {required : true},
			'sta1_case5' : {required : true},
			'sta1_case6' : {required : true},
			'sta1_case7' : {required : true},
			'sta1_case8' : {required : true},
			'sta1_case9' : {required : true},
			'sta1_case10' : {required : true},
			'sta1_case11' : {required : true},
			'sta1_case12' : {required : true},
			'sta1_case13' : {required : true},
			'sta1_case14' : {required : true},
			'sta1_case15' : {required : true},
			'sta1_case16' : {required : true}			
	   	},
	    messages: {
			'sta1_case1' : { required : '* 문항 답변을 선택하십시요.' },
			'sta1_case2' : { required : '* 문항 답변을 선택하십시요.' },	
			'sta1_case3' : { required : '* 문항 답변을 선택하십시요.' },
			'sta1_case4' : { required : '* 문항 답변을 선택하십시요.' },
			'sta1_case5' : { required : '* 문항 답변을 선택하십시요.' },
			'sta1_case6' : { required : '* 문항 답변을 선택하십시요.' },
			'sta1_case7' : { required : '* 문항 답변을 선택하십시요.' },
			'sta1_case8' : { required : '* 문항 답변을 선택하십시요.' },
			'sta1_case9' : { required : '* 문항 답변을 선택하십시요.' },
			'sta1_case10' : { required : '* 문항 답변을 선택하십시요.' },
			'sta1_case11' : { required : '* 문항 답변을 선택하십시요.' },
			'sta1_case12' : { required : '* 문항 답변을 선택하십시요.' },
			'sta1_case13' : { required : '* 문항 답변을 선택하십시요.' },
			'sta1_case14' : { required : '* 문항 답변을 선택하십시요.' },
			'sta1_case15' : { required : '* 문항 답변을 선택하십시요.' },
			'sta1_case16' : { required : '* 문항 답변을 선택하십시요.' }
		},
		onfocusout: function(element) {
			this.element(element);
		},		
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
</script>

<?php $CFX->load->common_view('page-home-bottom'); ?>

</div>