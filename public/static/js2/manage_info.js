//
//
//
//
//const config = {
//  locale: 'zh'
//};
//    Vue.use(VeeValidate,config);
//
//
////var ydjh_form_group= new Vue({
////  el: '#ydjh_form_group',
////  data: {
////    name: 'Vue.js'
////  }
////})
//
//
//var submit = new Vue({
//  el: '#form_submit',
//  data: () => ({
//    ydjh: '',
//    rq:''
//  }),
//  methods: {
//    validateBeforeSubmit(event) {
//      this.$validator.validateAll().then((result) => {
//        if (result) {
//          // eslint-disable-next-line
//          //alert('sssss');
//          //  this.el().submit()
//                      document.querySelector('#form_submit').submit();
//
////            event.submit()
////            console.log(event)
//
//          //  this.submit()
//
//          return true;
//        }
//
//        alert('请检查错误后再次提交');
//          return false;
//      });
//
//        return true
//    }
//  }
//
//});

//var rq = new Vue({
//  el: '#b',
//  data: {
//    name: 'Vue.js'
//  }
//})
//var c = new Vue({
//  el: '#c',
//  data: {
//    name: 'Vue.js'
//  }
//})
//
////此处导入的是上面代码的validation.js
//import vee-validate from 'vee-validate'
//
//export default {
//  name: 'form-example',
//  data: () => ({
//    email: '',
//    name: '',
//    phone: '',
//    url: ''
//  }),
//  methods: {
//    validateBeforeSubmit() {
//      this.$validator.validateAll().then((result) => {
//        if (result) {
//          // eslint-disable-next-line
//          alert('From Submitted!');
//          return;
//        }
//
//        alert('Correct them errors!');
//      });
//    }
//  }
//};
$(function(){

    
    //
    // lable设置为一边宽
    // @Description: 
    // @Author: simonsun
    // @Mail: 4664919@qq.com
    // @Last Modified: 2017-12-26 17:10
    //
    var max = 0;
    $(".content").find('label').each(function(){
        var _this = $(this);
        if(_this.Width() > max){
            max = _this.Width();
        }
    });
    $(".content").find('label').each(function(){
        var _this = $(this);
        _this.Width(max);
    });
});

