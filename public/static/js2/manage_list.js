$(function(){
    var order = $("#order");
    var sort = $("#sort");
    
    //
    // 对select换行bug做修复
    // @Author: simonsun
    // @Last Modified: 2017-10-10 15:32
    //
    $("#index_search").find("select").each(function(i){

        var _this = $(this);
        var _width = _this.parents('.box-body').width();
        var _this_width = _this.width();

        _this.parent().width(_width-10)
        _this.width(_this_width)
    });

    //$("#list_id_"+order.val()).attr('sort',sort.val());
    //$("#list_id_"+order.val()).attr('class',"list_sort_"+sort.val());
    var sort_class = sort.val() == "asc" ? "up" : "down";
    $("#list_id_"+order.val()).append("<span class='glyphicon glyphicon-menu-"+sort_class+"'></span>");



     $("#pagination_box a").click(function(){
         var _sub =$("#sub") ;
         var _this = $(this);
         _sub.after("<input type='hidden' value='"+_this.attr("page")+"' name='page' />")
         _sub.click();
        return false;
     });

     $("#index_list_tab thead tr th").click(function(){
         var _this = $(this)
         if(order.val() == _this.attr('name')){
             sort.val(sort.val() == 'asc' ? 'desc' : 'asc');
         }else{
             order.val(_this.attr('name'))
             sort.val('desc')
         }
         $("#index_search").submit();


     });

    $("#index_list_tab thead th ").click(function(){
        var _this = $(this);
        var order = $("#order");
        if(order.val() == _this.attr('name')){
        }else{

        }
        var sort = _this.attr("sort")
        if(sort != 'asc'){
            sort='desc'
        }
        //_this.attr("order")
        //alert(1);
        //能点击
        //能排序显示
        //k
    });
    $("#index_search").submit(function(){
        var _this = $(this);
        _this.find('input').each(function(i){
            //p(i)
            var _input = $(this);
            if( _input.val() == ""){
                _input.remove()
            }

        });

        //return false;

    });
});
