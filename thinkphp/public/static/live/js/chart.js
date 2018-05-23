
var chartwsUrl = "ws://123.57.204.35:8812";

var chartwebsocket = new WebSocket(chartwsUrl);

//实例对象的onopen属性
chartwebsocket.onopen = function(evt) {
    console.log("conected-swoole-success");
}

// 实例化 onmessage
chartwebsocket.onmessage = function(evt) {
    chartpush(evt.data);
    console.log("ws-server-return-data:" + evt.data);
}

//onclose
chartwebsocket.onclose = function(evt) {
    console.log("close");
}
//onerror

chartwebsocket.onerror = function(evt, e) {
    console.log("error:" + evt.data);
}



function chartpush(data){
    data = JSON.parse(data);

    html = '<div class="comment">';
    html += '<span>'+data.user+' </span>';
    html += '<span>'+data.content+'</span>';
    html += '</div>';

    $('#comments').append(html);

}