/**
 * @license Copyright (c) 2003-2017, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	config.language = 'ko';
	// config.uiColor = '#AADC6E';

	config.resize_enabled = true;	//안늘어나게
	config.height = 450;
	config.width = 'auto';
	config.font_style =
	{
		element : 'span',
		styles : { 'font-family' : '#(family)' },
		overrides : [ { element : 'font', attributes : { 'face' : null } } ]
	};
	config.enterMode = 1;			//<p>
	config.shiftEnterMode = 2;		//<br />
	config.toolbar = [
		{ name: 'document', items: [ 'Source' ] },
		{ name: 'clipboard', items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
		{ name: 'links', items: [ 'Link', 'Unlink' ] },
		{ name: 'colors', items: [ 'TextColor', 'BGColor' ] },
		{ name: 'insert', items: [ 'Image', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar' ] },
		{ name: 'editing', items: [ 'Find', 'Replace'] },
		'/',
		{ name: 'styles', items: [ 'Font', 'FontSize' ] },
		{ name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike' ] },
		{ name: 'paragraph', items: [ 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote' ] }
	];
	// 본문 태그 허용여부. 허용할 태그로 할경우 띄워쓰기로 나열하면됨.
	config.allowedContent = true;

	config.font_names="본고딕/Noto Sans KR, sans-serif; 나눔고딕/나눔고딕, NanumGothic, sans-serif; 나눔명조/나눔명조, NanumMyeongjo, sans-serif; 나눔바른고딕/나눔바른고딕, NanumBarunGothic, sans-serif;"

	// IE 링크 삽입시 기본 _blank로
	CKEDITOR.on('instanceReady', function(ev) {
		ev.editor.dataProcessor.htmlFilter.addRules( {
			elements: {
				a: function( element ) {
					element.attributes.target = '_blank';
				}
			}
		});
	});

	CKEDITOR.on('dialogDefinition', function(ev) {
		try {
			var dialogName = ev.data.name;
			var dialogDefinition = ev.data.definition;
			var dialog = dialogDefinition.dialog;
			var editor = ev.editor;

			if (dialogName=='image') {
				dialogDefinition.onLoad = function(){ 
					dialog.getContentElement('info', 'htmlPreview').getElement().hide();     
					dialog.getContentElement('info', 'cmbAlign').getElement().hide();     
					this.hidePage('Link');
					this.hidePage('advanced');
					this.selectPage('Upload'); 
				}; 

			} else if (dialogName=='link') {
				var informationTab = dialogDefinition.getContents('target');
				var targetField = informationTab.get('linkTargetType');
				targetField['default'] = '_blank';

				dialogDefinition.removeContents('advanced');
				dialogDefinition.onShow = function() { 
					dialog.getContentElement('info','anchorOptions').getElement().hide(); 
					dialog.getContentElement('info','emailOptions').getElement().hide();
					dialog.getContentElement('info','linkType').getElement().hide(); 
					dialog.getContentElement('info','protocol').disable();
				}; 
			}

			var infoTab = dialogDefinition.getContents('info');
			if (infoTab) {
				infoTab.remove('txtHSpace');
				infoTab.remove('txtVSpace');
				infoTab.remove('txtBorder');
				infoTab.remove('txtWidth');
				infoTab.remove('txtHeight');
				infoTab.remove('ratioLock');
			}
		}catch(exception){}
	});
};
