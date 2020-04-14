/**
 * codestat项目 
 * @version V1.0
 * @author: simon.sun <4664919@qq.com>
 * $Id: form_ajax_action.js 2014-6-15 simon.sun $
 */

XD.dialog_name = '#dialog_element';
XD.CLOSE_SECOND = 2;
$(function() {

    $('.form_ajax_action').click(function() {
        var _this = $(this).parents('form');
        var _url = _this.attr('action');
        var _data = {};
        var _callback = _this.attr('ajax_callback');
        _this.find('.ajax_field').each(function() {
            var __this = $(this);
            //var _field = __this.find('.ajax_field');
            _data[__this.attr('name')] = __this.val();
        });
        var _dialog = $('.modal-body');
        var options = {
//				backdrop:false,
//				 keyboard: false,
//				remote:'/brand',
            show: true
        };
        $('#myModalLabel').html('正在提交数据，请稍等。。');
        _dialog.html('正在提交数据！请稍等。。');
        $('#dialog_element').modal(options);


//			var _dialog = $(XD.dialog_name);
//			_dialog.html('正在提交数据，请稍等。。');
//			_dialog.dialog({title:'正在提交数据，请稍等。。'});
//			_dialog.dialog('open');
        $.post(_url, _data, function(data) {
            if (data) {
                if (data.status == 1) {
                    _dialog.html('<div style="color:green;padding: 20px;">执行成功,此对话框将在' + XD.CLOSE_SECOND + '秒后关闭</div>');
                    setTimeout(XD.tool.hidDialog, XD.CLOSE_SECOND * 1000);
                } else {
                    _dialog.html('<div style="color:red;padding: 20px;">错误：' + data.msg + '</div>');
                }
            } else {
                _dialog.html('<div style="color:red;padding: 20px;">糟糕，数据返回格式出错，请联系管理员</div>');
            }

        }, 'json');
        return false;
    });
});

//	XD.runStringCallback = function(func){
//		func_arr = {
//			'XD.ajax.updateGoodsStatus':XD.ajax.updateGoodsStatus
//		};
//		return func_arr[func];
//	};

XD.ajax = {
    updateOrderStatus: function(data) {
        alert('aaaa');
        l(data);
    },
    updateGoodsStatus: function(data) {
        alert('aaaa');
        l(data);
    }
};
//	XD.tool = {
//		dialogClose:function(){
//			$(XD.dialog_name).dialog('close');
//		}
//	};
