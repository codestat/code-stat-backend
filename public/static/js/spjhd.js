/**
 * 进货单列表页
 */
$('.ys').on('click', function (event) {
	// event.preventDefault();
    var $btn = $(this).button('loading')
    // business logic...
    var val = $(this).attr('data');
    $.ajax({
	  method: "POST",
	  url: "/Spjhd/ys/"+val,
	  dataType: "json"
	})
	  .done(function( msg ) {
	  	if (msg.status==1) {
	  		document.location.reload(true);
	  	} else {
		    $btn.button('reset');
		    alert( msg.msg );
	  	}
	  });
});

$("[data-toggle='confirmation']").popConfirm({
    title: "删除确认",
    content: "你确定要删除吗?",
    placement: "top", // (top, right, bottom, left)
    yesBtn: "确认",
    noBtn: "取消"
});