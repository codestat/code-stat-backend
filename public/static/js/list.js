/**
 * codestat项目 
 * @version V1.0
 * @author: simon.sun <4664919@qq.com>
 * $Id: list.js.js 2014-4-10 simon.sun $
 */
var _form_id = $('#list_form');
var _t_head = $('#list_table thead td');
var _order_asc = '<div class="btn-group dropup"><span class="caret"></span></div>';
var _order_desc = '<div class="btn-group "><span class="caret"></span></div>';
var LS = {};
$(function(){
	LS.initOrder();
            //分页筛选
            $('.search_show .search_but').click(function(){ 
                var _this = $(this);
                var _search_show = $('.search_show');
                if( ! _this.hasClass('_hide')){
                    _this.addClass('_hide');
                    //set show
                    _this.prev().slideDown(300);
                    _this.children('span').attr('class','glyphicon glyphicon-chevron-up');
                    _this.children('span').html(' 收起');
                     _search_show.css({'padding':'30px 0'});
                }else{
                    _this.removeClass('_hide');
                    //set hide
                    _search_show.css({'padding':'0'});
                    _this.children('span').attr('class','glyphicon  glyphicon-chevron-down');
                    
                    _this.children('span').html(' 更多筛选');
                    _this.prev().slideUp(200);
                }
            });
            //search_show_div search_but
            
            
            //分页js跳转
	$('.flickr a').click(function(){
		var _this = $(this);
		_form_id.append('<input type="hidden" name="page" value="'+_this.html()+'">');
		_form_id.submit();
	//	alert('ok');
		return false;
	});
});

LS.initOrder = function(){
	var _s_sc = $('#s_sc');
	var _s_order = $('#s_order');
	var _val = $.trim(_s_order.val());
	var _class_name = _order_desc;
	if(_val){
		_t_head.each(function(){
			var _this = $(this);
			if(_this.attr('data_attr') === _val){
				_this.attr('data_attr_hover',_s_sc.val());
				if(_s_sc.val() === 'asc'){
					_class_name = _order_asc;
				}
				_this.append(_class_name);
			}
		});
	}
	_t_head.click(function(){
		var _this = $(this);
		
		var _data_attr_hover = _this.attr('data_attr_hover');
		if(_data_attr_hover === 'undefined'){//默认desc
			_s_sc.val('desc');
		}else{
			_s_sc.val(_data_attr_hover === 'desc' ? 'asc' : 'desc');
		}
		_s_order.val(_this.attr('data_attr'));
		_form_id.submit();
	});
};