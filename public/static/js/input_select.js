/*!
 * 基于jquery、bootstrap的input选择js
 * @author simon.sun
 * 
 * DEMO
 */
$(function() {
    var dt_select = {};
    dt_select.selected = 'glyphicon-ok-sign';
    dt_select.unselect = 'glyphicon-stop';
    dt_select.select_class = 'glyphicon';
    dt_select.tmp = {
        _icon: {},
        _select: {},
        _this: {}
    };
    dt_select.func = {
        
        /**
         * 设置选择状态
         * @returns {undefined}
         */
        setSelect: function() {
            dt_select.tmp._icon.attr('class', dt_select.select_class + " " + dt_select.selected);
            dt_select.tmp._select.attr('checked', 'checked');
            dt_select.tmp._this.addClass('dt_selected');
        },
        
        /**
         * 设置未选择状态
         * @returns {undefined}
         */
        setUnselect: function() {
            dt_select.tmp._icon.attr('class', dt_select.select_class + " " + dt_select.unselect);
            dt_select.tmp._select.removeAttr('checked');
            dt_select.tmp._this.removeClass('dt_selected');
        }
    };
    
    /**
     * 初始化选择筛选器
     */
    $('.dt_select').each(function() {
        dt_select.tmp._this = $(this);
        dt_select.tmp._icon = dt_select.tmp._this.children('.glyphicon');
        dt_select.tmp._select = dt_select.tmp._this.children('input');
        if (dt_select.tmp._select.attr('checked')) {
            dt_select.func.setSelect();
        }
    });
    
    /**
     * 注册点击事件
     */
    $('.dt_select').click(function() {
        dt_select.tmp._this = $(this);
        dt_select.tmp._icon = dt_select.tmp._this.children('.glyphicon');
        dt_select.tmp._select = dt_select.tmp._this.children('input');
        if (dt_select.tmp._icon.hasClass(dt_select.unselect)) {
            /**
             * 当前是未选择状态，需要改为选择状态
             */
            dt_select.func.setSelect();
        } else {
            dt_select.func.setUnselect();
        }
        return false;
    });

});