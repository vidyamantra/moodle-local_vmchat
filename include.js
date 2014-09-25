if(!window.jQuery) {
    //include jquery
    var script = document.createElement('script');
    script.type = "text/javascript";
    script.src = wwwroot+"local/vmchat/bundle/chat/bundle/jquery/jquery-1.11.0.min.js";
    document.getElementsByTagName('head')[0].appendChild(script);
}

var checkScript = setInterval(
    function (){
        if(typeof jQuery != 'undefined'){
            // Include jquery ui
            var script1 = document.createElement('script');
            script1.type = "text/javascript";
            script1.src = wwwroot+"local/vmchat/bundle/chat/bundle/jquery/jquery-ui.min.js";
            document.getElementsByTagName('head')[0].appendChild(script1);

            var script2 = document.createElement('script');
            script2.type = "text/javascript";
            script2.src = wwwroot+"local/vmchat/index.js";
            document.getElementsByTagName('head')[0].appendChild(script2);
            clearInterval(checkScript);
        }
    },
    100
);
