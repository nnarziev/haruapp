<?php
defined('CFX_APPPATH') OR exit('No direct script access allowed');
?>
<?php $CFX->load->common_view('page-home-navibar'); ?>

<div role="main" class="main">
	<section id="overview" class="custom-section custom-background-color-1 m-none">
	</section>

	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-12 col-sm-8 col-md-6 col-lg-4 col-sm-offset-2 col-md-offset-3 col-lg-offset-4">
				<br>
				<br>
				<div class="card-group">
					<div class="card">
						<div class="card-header">
							<h3 class="card-title text-center"><span style="font-weight:500;"><?php echo "[".$CFX->title()."] ".$view_data['harucard_view']['hc_category_name']; ?></h3>
							<br>
							<h3 class="card-title text-center"><?php echo $view_data['harucard_view']['hc_view_title']; ?></h3>
						</div>
						<div class="card-img-top">
							<img src="<?php echo $view_data['harucard_view']['hc_image'][0]; ?>" alt="" class="img-responsive <?php echo $view_data['harucard_view']['hc_image_class']; ?>">
						</div>

					<?php if (isset($view_data['harucard_view']['hc_content'][0]) && !empty($view_data['harucard_view']['hc_content'][0])) { ?>
						<div class="card-body">
							<p class="card-text <?php echo $view_data['harucard_view']['hc_content_class'][0]; ?>">
								<?php echo $view_data['harucard_view']['hc_content'][0]; ?>
							</p>
						</div>
					<?php } ?>
					<?php if (isset($view_data['harucard_view']['hc_url_link'][0]) && !empty($view_data['harucard_view']['hc_url_link'][0])) { ?>
							<a class="btn btn-block <?php echo $view_data['harucard_view']['hc_url_class'][0]; ?>"
									href="<?php echo $view_data['harucard_view']['hc_url_link'][0]; ?>" target="_blank">
									<?php echo $view_data['harucard_view']['hc_url_title'][0]; ?></a>
					<?php } ?>
					<?php if (isset($view_data['harucard_view']['hc_url_link'][1]) && !empty($view_data['harucard_view']['hc_url_link'][1])) { ?>
							<a class="btn btn-block <?php echo $view_data['harucard_view']['hc_url_class'][1]; ?>"
									href="<?php echo $view_data['harucard_view']['hc_url_link'][1]; ?>" target="_blank">
									<?php echo $view_data['harucard_view']['hc_url_title'][1]; ?></a>
					<?php } ?>
					<?php if (isset($view_data['harucard_view']['hc_dot_class']) && !empty($view_data['harucard_view']['hc_dot_class'])) { ?>
							<div class="<?php echo $view_data['harucard_view']['hc_dot_class']; ?>">
								<div class="dot"></div>
								<div class="dot"></div>
								<div class="dot"></div>
							</div>
					<?php } ?>
					<?php if (isset($view_data['harucard_view']['hc_content'][1]) && !empty($view_data['harucard_view']['hc_content'][1])) { ?>
							<p class="card-text <?php echo $view_data['harucard_view']['hc_content_class'][1]; ?>">
									<?php echo $view_data['harucard_view']['hc_content'][1]; ?>
							</p>
					<?php } ?>
					<?php if (isset($view_data['harucard_view']['hc_url_link'][2]) && !empty($view_data['harucard_view']['hc_url_link'][2])) { ?>
							<a class="btn btn-block <?php echo $view_data['harucard_view']['hc_url_class'][2]; ?>"
									href="<?php echo $view_data['harucard_view']['hc_url_link'][2]; ?>" target="_blank">
									<?php echo $view_data['harucard_view']['hc_url_title'][2]; ?></a>
					<?php } ?>
					<?php if (isset($view_data['harucard_view']['hc_url_link'][3]) && !empty($view_data['harucard_view']['hc_url_link'][3])) { ?>
							<a class="btn btn-block <?php echo $view_data['harucard_view']['hc_url_class'][3]; ?>"
									href="<?php echo $view_data['harucard_view']['hc_url_link'][3]; ?>" target="_blank">
									<?php echo $view_data['harucard_view']['hc_url_title'][3]; ?></a>
					<?php } ?>
					<?php if (isset($view_data['harucard_view']['hc_content'][2]) && !empty($view_data['harucard_view']['hc_content'][2])) { ?>
							<p class="card-text <?php echo $view_data['harucard_view']['hc_content_class'][2]; ?>">
									<?php echo $view_data['harucard_view']['hc_content'][2]; ?>
							</p>
					<?php } ?>
					<?php if (isset($view_data['harucard_view']['hc_url_link'][4]) && !empty($view_data['harucard_view']['hc_url_link'][4])) { ?>
							<a href="#" class="btn btn-block <?php echo $view_data['harucard_view']['hc_url_class'][4]; ?>"
									href="<?php echo $view_data['harucard_view']['hc_url_link'][4]; ?>" target="_blank">
									<?php echo $view_data['harucard_view']['hc_url_title'][4]; ?></a>
					<?php } ?>
					<?php if (isset($view_data['harucard_view']['hc_url_link'][5]) && !empty($view_data['harucard_view']['hc_url_link'][5])) { ?>
							<a href="#" class="btn btn-block <?php echo $view_data['harucard_view']['hc_url_class'][5]; ?>"
									href="<?php echo $view_data['harucard_view']['hc_url_link'][5]; ?>" target="_blank">
									<?php echo $view_data['harucard_view']['hc_url_title'][5]; ?></a>
					<?php } ?>
							<p>&nbsp;</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

<?php $CFX->load->common_view('page-home-bottom'); ?>

</div>