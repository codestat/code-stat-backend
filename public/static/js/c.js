;
function l(_var) {
	if (typeof console !== 'undefined')
		console.log(_var);
}

XD = {};
DT = XD;
XD.tool = {
	hidDialog:function(){
		$('#dialog_element').modal('hide');
	},
	dialog:function(message,title){
		XD.tool.hidDialog();
		var _dialog = $('#dialog_element');
		var _dialog_con = $('#myModalLabel');
		var options = {
//			backdrop: false,
//			keyboard: false,
//			remote: '/brand',
			show:true
		};
//		_dialog.find('.modal-footer').removeClass('hidden');
		_dialog.find('.modal-body').html(message);
		_dialog_con.html(title);
		_dialog.modal(options);
	},
	showError:function(message){
		XD.tool.dialog('<div class="alert alert-danger">'+message+'</div>','糟糕，出错了！');
	},
	showSuccess:function(message){
		XD.tool.dialog('<div class="alert alert-success">'+message+'</div>','成功！');
	},
	showMessage:function(message,title){
		XD.tool.dialog('<div class="">'+message+'</div>',title);
	}
};
XD.action = {
	regDatePicker: function(id, format_info) {
		var format = "yyyy-mm-dd";
		var minView = 2;
		if ( format_info) {
			format = format_info.fomat;
			minView = format_info.level;
		}
		$(id).datetimepicker({
			autoclose: true,
			todayBtn: true,
			minView: 2,
			//viewSelect:'month',
			format: format
		});
		$(id).next('span').click(function() {
			$(id).datetimepicker('show');
		});
		$(id).next('span').next('span').click(function() {
			$(id).val('');
		});
	},
	regUpimg: function(id, editor_id) {
		if(typeof UE === 'undefined'){
			XD.tool.showError('错误，UEditor 未加载');
			//todo show error
			return;
		}
		var ue =  UE.getEditor(editor_id);
		ue.ready(function() {
			//设置编辑器不可用
			ue.setDisabled();
			//隐藏编辑器，因为不会用到这个编辑器实例，所以要隐藏
			ue.hide();
			//侦听图片上传
			ue.addListener('beforeInsertImage', function(t, arg) {
				//将地址赋值给相应的input,只去第一张图片的路径
				$(id).attr("value", arg[0].src);
				//图片预览
				$(id).next('.show_img').attr("src", arg[0].src);
			});
//			//侦听文件上传，取上传文件列表中第一个上传的文件的路径
//			_select_upload.addListener('afterUpfile', function(t, arg) {
//				$("#file").attr("value", _select_upload.options.filePath + arg[0].url);
//			});
		});
		//弹出图片上传的对话框
		$(id).click(function(){
			var myImage = ue.getDialog("insertimage");
			myImage.open();
		});
		$(id).next('span').click(function(){
			$(id).click();
		});
		$(id).next('span').next('span').click(function(){
			$(id).val('');
		});
		
//		//弹出文件上传的对话框
//		function upFiles() {
//			var myFiles = _select_upload.getDialog("attachment");
//			myFiles.open();
//		}
	},
	regUedtior:function(id) {
		if(typeof UE === 'undefined'){
			XD.tool.showError('错误，UEditor 未加载');
			//todo show error
			return;
		}
		var ue =  UE.getEditor(id);
		ue.ready(function() {
			ue.setHeight(300);
			//$('#'+id).height($('#'+id).height() -105);
		});
	}
};