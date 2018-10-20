<?php
defined('CFX_APPPATH') OR exit('No direct script access allowed');
?>

<?php $CFX->load->common_view('page-home-navibar'); ?>

<div role="main" class="main">
	<section class="custom-section custom-background-color-1 m-none">
	</section>

	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-12 col-sm-8 col-md-4 col-lg-4 col-sm-offset-2 col-md-offset-4 col-lg-offset-4">
				<section class="body-sign">
					<div class="center-sign">
						<hr>
						<div class="panel panel-sign">
							<div class="panel-title-sign mt-xl text-center">
								<h2 class="title text-uppercase text-weight-bold m-none"><i class="fa fa-user mr-xs"></i> <?php echo $CFX->title(); ?></h2>
							</div>
							<div class="panel-body">
								<form method="post" id="login_form" name="login_form" target="_top" autocomplete="off">
									<input type="hidden" id="token" name="token" value="<?php echo $CFX->uri->token; ?>">
									<input type="hidden" id="url" name="url" value="<?php echo $CFX->uri->URL(); ?>">

									<div class="form-group mb-lg">
										<label><span class="required-input">*</span> 아이디</label>
										<div class="input-group">
											<input type="text" class="form-control input-lg" id="id" name="id" placeholder="아이디" required autofocus tabindex="1" value="<?php echo isset($_COOKIE["id_save"]) ? $_COOKIE["id_save"] : ""; ?>" style="ime-mode:inactive">
											<span class="input-group-addon">
												<span class="icon icon-lg">
													<i class="fa fa-user"></i>
												</span>
											</span>
										</div>
									</div>

									<?php if ($error_message = form_error('id')) { ?>
									<section class="panel">
										<div class="alert alert-danger" id="id-alert">
											<button type="button" class="close" data-dismiss="alert">x</button>
											<label><?php echo $error_message; ?></label>
										</div>
									</section>
									<?php } ?>

									<div class="form-group mb-lg">
										<div class="clearfix">
											<label class="pull-left"><span class="required-input">*</span> 비밀번호</label>
											<!-- <a href="pages-recover-password.html" class="pull-right">Lost Password?</a>-->
										</div>
										<div class="input-group">
											<input type="password" class="form-control input-lg" id="pw" name="pw" placeholder="비밀번호" required tabindex="2" value="">
											<span class="input-group-addon">
												<span class="icon icon-lg">
													<i class="fa fa-lock"></i>
												</span>
											</span>
										</div>
									</div>

									<?php if ($error_message = form_error('pw')) { ?>
									<section class="panel">
										<div class="alert alert-danger" id="pw-alert">
											<button type="button" class="close" data-dismiss="alert">x</button>
											<label><?php echo $error_message; ?></label>
										</div>
									</section>
									<?php } ?>

									<div class="form-group mb-lg">
										<div class="clearfix">
											<label class="pull-left"><span class="required-input">*</span> 아래 보안문자를 보이는 대로 입력해주세요.</label>
										</div>
										<div style="height:55px;">
											<img id="captcha_image" src="<?php echo $CFX->router->link_href('captcha_image', '?t='.CFX_SERVER_TIME); ?>" style="height:46px;">
											<button type="button" id="captcha_reload" class="btn btn-primary btn-lg mt-lg" style="position:relative;top:-20px;vertical-align:top;" tabindex="3"><i class="fa fa-refresh" aria-hidden="true"></i></button>
											<button type="button" id="captcha_audio" class="btn btn-primary btn-lg mt-lg" style="position:relative;top:-20px;vertical-align:top;" tabindex="4"><i class="fa fa-play" aria-hidden="true"></i></button>
										</div>
										<input type="text" class="form-control input-lg" id="captcha_key" name="captcha_key" placeholder="보안문자" required tabindex="5" maxlength="5" value="">
									</div>

									<?php if ($error_message = form_error('captcha_key')) { ?>
									<section class="panel">
										<div class="alert alert-danger" id="captcha_key-alert">
											<button type="button" class="close" data-dismiss="alert">x</button>
											<label><?php echo $error_message; ?></label>
										</div>
									</section>
									<?php } ?>

									<div class="row">
										<div class="col-sm-8">
											<div class="checkbox-custom checkbox-default">
												<input type="checkbox" class="form-check-input" id="id_save" name="id_save" tabindex="7" <?php echo isset($_COOKIE["id_save"]) ? "checked" : ""; ?> value="true">
												<label for="id_save">아이디 저장</label>
											</div>
										</div>
										<div class="col-sm-4 text-right">
											<button type="submit" class="btn btn-primary btn-block btn-lg">로그인</button>
										</div>
									</div>

									<?php if ($error_message = form_error('common')) { ?>
									<section class="panel">
										<div class="alert alert-danger" id="common-alert">
											<button type="button" class="close" data-dismiss="alert">x</button>
											<label><?php echo $error_message; ?></label>
										</div>
									</section>
									<?php } ?>

									<hr>

									<p class="text-center">회원이 아니신가요?</p>

									<div class="form-group mb-lg">
										<a href="/auth/regist" class="btn btn-lg btn-default btn-block" tabindex="8" role="button">회원가입</a>
									</div>

								</form>
							</div>
						</div>
					</div>
				</section>
				<!-- end: page -->
			</div>
		</div>
	</div>

<?php $CFX->load->common_view('page-home-bottom'); ?>
</div>