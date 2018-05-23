
$(function(){

    $('#discuss-box').keydown(function(event){
        if(event.keyCode == 13 ){
            var text = $(this).val();
            var url = 'http://123.57.204.35:8811?s=index/chart/index';
            var data = {'content':text,'game_id':1};
            $.post(url,data,function(res){

                $('#discuss-box').val("");

            },'json');


        }
    });


});


