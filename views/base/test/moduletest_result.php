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
								<form class="form-moduletestresult" method="post" id="moduletestresult_form" name="moduletestresult_form" target="_top" autocomplete="off">
									<div class="panel panel-default">
										<div class="panel-body text-center">
											<h4>모듈 검사가 끝났습니다.</h4>
										</div>
										<div class="panel-footer text-center">
											<div class="tempGauge1">우울/불안 (<?php echo $view_data['mta_case']['mta_point1']; ?> / 40) 점</div>
											<div class="tempGauge2">수면 (<?php echo $view_data['mta_case']['mta_point2']; ?> / 20) 점</div>
											<div class="tempGauge3">통증 (<?php echo $view_data['mta_case']['mta_point3']; ?> / 20) 점</div>
											<br>
											<h4><label class="label label-info"><?php echo $CFX->auth->get_session_member_name(); ?></label> 님은 <label class="label label-success"><?php echo $view_data['mta_target']; ?></label> 영역에서<br><br>가장 큰 어려움을 경험하고 있는 것으로 보입니다.</h4>
										</div>
									</div>

									<?php if ($view_data['mta_code'] == 1) { ?>

									<a href="/test/severitytest1" class="btn btn-lg btn-primary btn-block">심각도 검사하기</a>

									<?php } ?>
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
	$(".tempGauge1").tempGauge({
		// border color
		borderColor: "#5b6776",
		// border width
		borderWidth: 4,
		// default temperature
		defaultTemp: <?php echo $view_data['mta_case']['mta_point1']; ?>,
		// fill color
		fillColor: "#fecb37",
		// show label
		showLabel: true,
		// label size in pixels
		labelSize: 14,
		// maximum temperature
		maxTemp: 40,
		// minimum temperature
		minTemp: 0,
		// Temperature width
		width: 130
	});
	$(".tempGauge2").tempGauge({
		// border color
		borderColor: "#5b6776",
		// border width
		borderWidth: 4,
		// default temperature
		defaultTemp: <?php echo $view_data['mta_case']['mta_point2']; ?>,
		// fill color
		fillColor: "#28a38e",
		// show label
		showLabel: true,
		// label size in pixels
		labelSize: 14,
		// maximum temperature
		maxTemp: 20,
		// minimum temperature
		minTemp: 0,
		// Temperature width
		width: 130
	});
	$(".tempGauge3").tempGauge({
		// border color
		borderColor: "#5b6776",
		// border width
		borderWidth: 4,
		// default temperature
		defaultTemp: <?php echo $view_data['mta_case']['mta_point3']; ?>,
		// fill color
		fillColor: "#ef5c60",
		// show label
		showLabel: true,
		// label size in pixels
		labelSize: 14,
		// maximum temperature
		maxTemp: 20,
		// minimum temperature
		minTemp: 0,
		// Temperature width
		width: 130
	});
</script>

<?php $CFX->load->common_view('page-home-bottom'); ?>

</div>