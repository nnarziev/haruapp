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
								<a href="/adm/screening/lists"><button type="button" class="btn btn-info">설문 스크리닝 관리</button></a>
								<a href="/adm/severity/lists"><button type="button" class="btn btn-info">심각도 평가 관리</button></a>
								<a href="/adm/survey/lists"><button type="button" class="btn btn-info">추가 설문 관리</button></a>
								<a href="/adm/setup/lists"><button type="button" class="btn btn-primary">설정 관리</button></a>
							</div>
						</div>

						<form name="setupUpdateForm" id="setupUpdateForm" method="post" enctype="multipart/form-data" autocomplete="off">
						<input type="hidden" name="wmode" value="<?php echo $CFX->uri->wmode; ?>">
						<input type="hidden" name="token" value="<?php echo $CFX->uri->token; ?>">
						<input type="hidden" name="page_rows" value="<?php echo $CFX->uri->page_rows; ?>">
						<input type="hidden" name="page" value="<?php echo $CFX->uri->page; ?>">
						<input type="hidden" name="ssort" value="<?php echo $CFX->uri->ssort; ?>">
						<input type="hidden" name="sorder" value="<?php echo $CFX->uri->sorder; ?>">
						
						<table summary="설정 수정" class="com_table col_table">
							<colgroup>
								<col width="15%">
								<col width="35%">
								<col width="15%">
								<col width="35%">
							</colgroup>

							<tr>
								<th scope="col">설정명*</th>
								<td colspan="3">
									<?php echo $view_data['update']['setup_view']['name']; ?>
								</td>
							</tr>

							<!-- 기프티콘 썸네일 이미지 1 시작 { -->
							<tr>
								<th scope="col">기프티콘 이미지 1번*</th>
								<td colspan="3">
									<div class="data1_show">
										<span class="content">
											<img src="<?php echo CFX_URL.'/data/images/'.$view_data['update']['setup_view']['data1']; ?>" height="250">
										</span>
									</div>

									<div class="file_input">
									    <label>
									        이미지  업로드
									        <input type="file" name="data1" onchange="javascript:document.getElementById('data1').value=this.value" >
									    </label>
									    <input id="data1" type="text" readonly="readonly">
									    <input type="hidden" name="org_data1" value="<?php echo $view_data['update']['setup_view']['data1']; ?>">
									</div>
									<ul class="table_ul">
										<li>* JPG, GIF, PNG 형태의 이미지 파일만 등록 가능합니다.</li>
									</ul>
								</td>
							</tr>
 							<!-- } 기프티콘 썸네일 이미지 1 끝 -->

							<!-- 기프티콘 썸네일 이미지 2 시작 { -->
							<tr>
								<th scope="col">기프티콘 이미지 2번*</th>
								<td colspan="3">
									<div class="data2_show">
										<span class="content">
											<img src="<?php echo CFX_URL.'/data/images/'.$view_data['update']['setup_view']['data2']; ?>" height="250">
										</span>
									</div>

									<div class="file_input">
									    <label>
									        이미지  업로드
									        <input type="file" name="data2" onchange="javascript:document.getElementById('data2').value=this.value" >
									    </label>
									    <input id="data2" type="text" readonly="readonly">
									    <input type="hidden" name="org_data2" value="<?php echo $view_data['update']['setup_view']['data2']; ?>">
									</div>
									<ul class="table_ul">
										<li>* JPG, GIF, PNG 형태의 이미지 파일만 등록 가능합니다.</li>
									</ul>
								</td>
							</tr>
 							<!-- } 기프티콘 썸네일 이미지 2 끝 -->

							<!-- 기프티콘 썸네일 이미지 3 시작 { -->
							<tr>
								<th scope="col">기프티콘 이미지 3번*</th>
								<td colspan="3">
									<div class="data3_show">
										<span class="content">
											<img src="<?php echo CFX_URL.'/data/images/'.$view_data['update']['setup_view']['data3']; ?>" height="250">
										</span>
									</div>

									<div class="file_input">
									    <label>
									        이미지  업로드
									        <input type="file" name="data3" onchange="javascript:document.getElementById('data3').value=this.value" >
									    </label>
									    <input id="data3" type="text" readonly="readonly">
									    <input type="hidden" name="org_data3" value="<?php echo $view_data['update']['setup_view']['data3']; ?>">
									</div>
									<ul class="table_ul">
										<li>* JPG, GIF, PNG 형태의 이미지 파일만 등록 가능합니다.</li>
									</ul>
								</td>
							</tr>
 							<!-- } 기프티콘 썸네일 이미지 3 끝 -->

							<!-- 기프티콘 썸네일 이미지 4 시작 { -->
							<tr>
								<th scope="col">기프티콘 이미지 4번*</th>
								<td colspan="3">
									<div class="data4_show">
										<span class="content">
											<img src="<?php echo CFX_URL.'/data/images/'.$view_data['update']['setup_view']['data4']; ?>" height="250">
										</span>
									</div>

									<div class="file_input">
									    <label>
									        이미지  업로드
									        <input type="file" name="data4" onchange="javascript:document.getElementById('data4').value=this.value" >
									    </label>
									    <input id="data4" type="text" readonly="readonly">
									    <input type="hidden" name="org_data4" value="<?php echo $view_data['update']['setup_view']['data4']; ?>">
									</div>
									<ul class="table_ul">
										<li>* JPG, GIF, PNG 형태의 이미지 파일만 등록 가능합니다.</li>
									</ul>
								</td>
							</tr>
 							<!-- } 기프티콘 썸네일 이미지 4 끝 -->

							<!-- 기프티콘 썸네일 이미지 5 시작 { -->
							<tr>
								<th scope="col">기프티콘 이미지 5번*</th>
								<td colspan="3">
									<div class="data5_show">
										<span class="content">
											<img src="<?php echo CFX_URL.'/data/images/'.$view_data['update']['setup_view']['data5']; ?>" height="250">
										</span>
									</div>

									<div class="file_input">
									    <label>
									        이미지  업로드
									        <input type="file" name="data5" onchange="javascript:document.getElementById('data5').value=this.value" >
									    </label>
									    <input id="data5" type="text" readonly="readonly">
									    <input type="hidden" name="org_data5" value="<?php echo $view_data['update']['setup_view']['data5']; ?>">
									</div>
									<ul class="table_ul">
										<li>* JPG, GIF, PNG 형태의 이미지 파일만 등록 가능합니다.</li>
									</ul>
								</td>
							</tr>
 							<!-- } 기프티콘 썸네일 이미지 5 끝 -->

							<tr>
								<th scope="col">상태*</th>
								<td colspan="3">
									<span class="col_ul">
										<em class="radio_group">
											<input type="radio" id="use" name="activated" value="1" <?php  echo ($view_data['update']['setup_view']['activated'] == '1') ? ' checked' : ''; ?>>
											<label for="use">활성화</label>
										</em>
										<label for="use">활성화</label>
									</span>
									<span>
										<em class="radio_group">
											<input type="radio" id="nouse" name="activated" value="0" <?php echo ($view_data['update']['setup_view']['activated'] == '0') ? ' checked' : ''; ?>>
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
				'data1' : {required : true },
				'data2' : {required : true },
				'data3' : {required : true },
				'data4' : {required : true },
				'data5' : {required : true }
		    },
		    messages : {
				'data1' : {
					required: '기프티콘 이미지를 입력하세요.'
				},
				'data2' : {
					required: '기프티콘 이미지를 입력하세요.'
				},
				'data3' : {
					required: '기프티콘 이미지를 입력하세요.'
				},
				'data4' : {
					required: '기프티콘 이미지를 입력하세요.'
				},
				'data5' : {
					required: '기프티콘 이미지를 입력하세요.'
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