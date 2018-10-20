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
								<h2 class="title text-uppercase text-weight-bold m-none"><i class="fa fa-user mr-xs"></i> <?php echo $CFX->title(); ?></h2>
							</div>
							<div class="panel-body">

								<form class="form-moduletest" method="post" id="moduletest_form" name="moduletest_form" target="_top" autocomplete="off">
									<input type="hidden" id="mta_id" name="mta_id" value="<?php echo $view_data['mta_case']['mta_id']; ?>">
									<input type="hidden" id="mta_sent" name="mta_sent" value="N">
									<div class="form-group">
										<div class="panel panel-default">
											<div class="panel-body">
												암으로 진단받은 사람들이 가장 많이 경험하는 우울/불안, 수면, 통증 중 
												현재 내가 가장 어려움을 겪고 있는 영역이 무엇인지 알아보는 간단한 검사입니다.<br>
												각 문항에 대해 지난 일주일을 기준으로 평가해 주십시오.<br><br>
												(<strong>0점</strong> : 전혀 그렇지 않았다. ~ <strong>10점</strong> : 매우 그렇다.)
											</div>
											<div class="panel-footer">
												<div>
													<div><h5><?php echo $view_data['mtq_case']['mtq_case1']; ?></h5></div>
													<br>
													<select id="mta_case1" class="rating" name="mta_case1" autocomplete="off">
														<?php for ($i = 0; $i <= 10; $i++) { ?>
														<option value="<?php echo $i ?>" <?php echo ($view_data['mta_case']['mta_case1'] == "$i") ? ' selected' : ''; ?>><?php echo $i ?>점</option>
														<?php } ?>
													</select>
												</div>
												<div>
													<div><h5><?php echo $view_data['mtq_case']['mtq_case2']; ?></h5></div>
													<br>
													<select id="mta_case2" class="rating" name="mta_case2" autocomplete="off">
														<?php for ($i = 0; $i <= 10; $i++) { ?>
														<option value="<?php echo $i ?>" <?php echo ($view_data['mta_case']['mta_case2'] == "$i") ? ' selected'  : ''; ?>><?php echo $i ?>점</option>
														<?php } ?>
													</select>
												</div>
												<div>
													<div><h5><?php echo $view_data['mtq_case']['mtq_case3']; ?></h5></div>
													<br>
													<select id="mta_case3" class="rating" name="mta_case3" autocomplete="off">
														<?php for ($i = 0; $i <= 10; $i++) { ?>
														<option value="<?php echo $i ?>" <?php echo ($view_data['mta_case']['mta_case3'] == "$i") ? ' selected'  : ''; ?>><?php echo $i ?>점</option>
														<?php } ?>
													</select>
												</div>
												<div>
													<div><h5><?php echo $view_data['mtq_case']['mtq_case4']; ?></h5></div>
													<br>
													<select id="mta_case4" class="rating" name="mta_case4" autocomplete="off">
														<?php for ($i = 0; $i <= 10; $i++) { ?>
														<option value="<?php echo $i ?>" <?php echo ($view_data['mta_case']['mta_case4'] == "$i") ? ' selected'  : ''; ?>><?php echo $i ?>점</option>
														<?php } ?>
													</select>
												</div>
												<div>
													<div><h5><?php echo $view_data['mtq_case']['mtq_case5']; ?></h5></div>
													<br>
													<select id="mta_case5" class="rating" name="mta_case5" autocomplete="off">
														<?php for ($i = 0; $i <= 10; $i++) { ?>
														<option value="<?php echo $i ?>" <?php echo ($view_data['mta_case']['mta_case5'] == "$i") ? ' selected'  : ''; ?>><?php echo $i ?>점</option>
														<?php } ?>
													</select>
												</div>
												<div>
													<div><h5><?php echo $view_data['mtq_case']['mtq_case6']; ?></h5></div>
													<br>
													<select id="mta_case6" class="rating" name="mta_case6" autocomplete="off">
														<?php for ($i = 0; $i <= 10; $i++) { ?>
														<option value="<?php echo $i ?>" <?php echo ($view_data['mta_case']['mta_case6'] == "$i") ? ' selected' : ''; ?>><?php echo $i ?>점</option>
														<?php } ?>
													</select>
												</div>
												<div>
													<div><h4><?php echo $view_data['mtq_case']['mtq_case7']; ?></h4></div>
													<br>
													<select id="mta_case7" class="rating" name="mta_case7" autocomplete="off">
														<?php for ($i = 0; $i <= 10; $i++) { ?>
														<option value="<?php echo $i ?>" <?php echo ($view_data['mta_case']['mta_case7'] == "$i") ? ' selected'  : ''; ?>><?php echo $i ?>점</option>
														<?php } ?>
													</select>
												</div>
												<div>
													<div><h4><?php echo $view_data['mtq_case']['mtq_case8']; ?></h4></div>
													<br>
													<select id="mta_case8" class="rating" name="mta_case8" autocomplete="off">
														<?php for ($i = 0; $i <= 10; $i++) { ?>
														<option value="<?php echo $i ?>" <?php echo ($view_data['mta_case']['mta_case8'] == "$i") ? ' selected'  : ''; ?>><?php echo $i ?>점</option>
														<?php } ?>
													</select>
												</div>
											</div>
										</div>
									</div>


									<div class="form-group">
										<button class="btn btn-lg btn-primary btn-block" type="submit">저장하기</button>
									</div>

									<span class="clearfix"></span>

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
$( document ).ready(function( $ ) {

	$( "#send_btn" ).click(function() {
		var result = confirm('모듈검사 평가를 제출하시겠습니까?');
		if (result) {
			$("#mta_sent").val("Y");
			form.submit();
		}
	});


	$(function() {
	    function ratingEnable() {
	        $('.rating').barrating('show', {
	            theme: 'bars-1to10',
	            showSelectedRating: true,
	            hoverState: false
	        });
	    }
	    ratingEnable();
	});

	$("#moduletest_form").validate({
		errorClass: 'moduletest_error',
		rules: {
	   	},
	    messages: {
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
});
</script>

<?php $CFX->load->common_view('page-home-bottom'); ?>

</div>

