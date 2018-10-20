<?php
defined('CFX_APPPATH') OR exit('No direct script access allowed');
?>

<?php $CFX->load->common_view('page-home-navibar'); ?>

<div role="main" class="main">
	<section id="overview" class="custom-section custom-background-color-1 m-none">
	</section>
	
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-6 col-md-6 col-md-offset-3">
				<br>
				<br>
				<div class="custom-bg">
					<h3 class="text-center regist-title"><?php echo $CFX->title(); ?></h3>
					<h3 class="text-center regist-title">(우울/불안)</h3>
					<br>
					<form class="form-severitytestresult" method="post" id="severitytestresult_form" name="severityresult_form" target="_top" autocomplete="off">
						<div class="panel panel-default">
							<div class="panel-body text-center">
								<h4>심각도 검사가 끝났습니다.</h4>
							</div>
							<div class="panel-footer text-center">

								<h4>우울 심각도 검사결과</h4>
								<br>
								<div class="progress" style="height:40px;">
									<div class="progress-bar progress-bar-info" role="progressbar" style="width:<?php echo (5 / 27) * 100; ?>%">
									우울하지 않음<br>0점~5점
									</div>
									<div class="progress-bar progress-bar-default" role="progressbar" style="width:<?php echo (5 / 27) * 100; ?>%">
									가벼운 우울<br>6점~10점
									</div>
									<div class="progress-bar progress-bar-success" role="progressbar" style="width:<?php echo (5 / 27) * 100; ?>%">
									중간 정도 우울<br>11점~15점
									</div>
									<div class="progress-bar progress-bar-warning" role="progressbar" style="width:<?php echo (5 / 27) * 100; ?>%">
									약간 심각한 우울<br>16점~20점
									</div>
									<div class="progress-bar progress-bar-danger" role="progressbar" style="width:<?php echo (7 / 27) * 100; ?>%">
									심각한 우울<br>21점~27점
									</div>
								</div>

								<div class="progress" style="height:25px;">
									<div class="progress-bar progress-bar-striped <?php echo $view_data['sta1_progressbar1']; ?> active" role="progressbar"
									aria-valuenow="<?php echo intVal($view_data['sta1_case']['sta1_point1']); ?>" aria-valuemin="0" aria-valuemax="27" style="width:<?php echo (intVal($view_data['sta1_case']['sta1_point1']) / 27) * 100; ?>%">
									우울점수 ( <?php echo intVal($view_data['sta1_case']['sta1_point1']); ?> 점 )
									</div>
								</div>

								<hr>

								<h4>불안 심각도 검사결과</h4>
								<br>
								<div class="progress" style="height:40px;">
									<div class="progress-bar progress-bar-info" role="progressbar" style="width:<?php echo (5 / 21) * 100; ?>%">
									불안하지 않음<br>0점~5점
									</div>
									<div class="progress-bar progress-bar-default" role="progressbar" style="width:<?php echo (5 / 21) * 100; ?>%">
									가벼운 불안<br>6점~10점
									</div>
									<div class="progress-bar progress-bar-success" role="progressbar" style="width:<?php echo (5 / 21) * 100; ?>%">
									중간 정도 불안<br>11점~15점
									</div>
									<div class="progress-bar progress-bar-danger" role="progressbar" style="width:<?php echo (6 / 21) * 100; ?>%">
									심각한 불안<br>16점~21점
									</div>
								</div>

								<div class="progress" style="height:25px;">
									<div class="progress-bar progress-bar-striped <?php echo $view_data['sta1_progressbar2']; ?> active" role="progressbar"
									aria-valuenow="<?php echo intVal($view_data['sta1_case']['sta1_point2']); ?>" aria-valuemin="0" aria-valuemax="27" style="width:<?php echo (intVal($view_data['sta1_case']['sta1_point2']) / 27) * 100; ?>%">
									불안점수 ( <?php echo intVal($view_data['sta1_case']['sta1_point2']); ?> 점 )
									</div>
								</div>
							</div>

							<div class="text-center">
								<br>
								<h4><label class="label label-info"><?php echo $CFX->auth->get_session_member_name(); ?></label> 님의 우울 정도는 <label class="label <?php echo $view_data['sta1_label1']; ?>"><?php echo intVal($view_data['sta1_case']['sta1_point1']); ?>점</label>으로 <label class="label <?php echo $view_data['sta1_label1']; ?>"><?php echo $view_data['sta1_code1']; ?></label> 정도에 해당하며,</h4>
								<h4>불안 정도는 <label class="label <?php echo $view_data['sta1_label2']; ?>"><?php echo intVal($view_data['sta1_case']['sta1_point2']); ?>점</label>으로 <label class="label <?php echo $view_data['sta1_label2']; ?>"><?php echo $view_data['sta1_code2']; ?></label> 정도에 해당합니다.</h4>
								<br>
								<h4><label class="label label-info"><?php echo $view_data['sta1_target']; ?></label> 구독을 추천합니다.</h4>
								<br>
							</div>
						</div>

						<br>

						<a href="/" class="btn btn-lg btn-default btn-block">홈으로 돌아가기</a>

					</form>
				</div>
			</div>
		</div>
	</div>

<?php $CFX->load->common_view('page-home-bottom'); ?>

</div>