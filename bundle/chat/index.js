/*var cssId = 'myCss';
if (!document.getElementById(cssId))
{
    var head  = document.getElementsByTagName('head')[0];
    var link  = document.createElement('link');
    link.id   = cssId;
    link.rel  = 'stylesheet';
    link.type = 'text/css';
    link.href = 'bundle/jquery/css/base/jquery-ui.css';
    link.media = 'all';
    head.appendChild(link);
}
var cssId = 'myCss1';
if (!document.getElementById(cssId)){
    var head  = document.getElementsByTagName('head')[0];
    var link  = document.createElement('link');
    link.id   = cssId;
    link.rel  = 'stylesheet';
    link.type = 'text/css';
    link.href = 'css/jquery.ui.chatbox.css';
    link.media = 'all';
    head.appendChild(link);
}
jQuery.cachedScript = function( url, options ) {
    // Allow user to set any option except for dataType, cache, and url
    options = $.extend( options || {}, {
        dataType: "script",
        cache: true,
        url: url
    });
    // Use $.ajax() since it is more flexible than $.getScript
    // Return the jqXHR object so we can chain callbacks
    return jQuery.ajax( options );
};

$.when(
    //$.cachedScript( "bundle/io/build/iolib.min.js" ),
    //$.cachedScript( "build/chat.min.js" )
    $.cachedScript( "bundle/io/src/iolib.js" ),
    $.cachedScript( "src/footer.js" ),
    $.cachedScript( "src/jquery.ui.chatlist.js" ),
    $.cachedScript( "src/jquery.ui.chatbox.js" ),
    $.cachedScript( "src/jquery.ui.chatroom.js" ),
    $.cachedScript( "src/chatboxManager.js" ),
    $.cachedScript( "src/lib.js" ),
    $.cachedScript( "src/lang.en.js" )

 ).done(function(){*/
require.config({
//   baseUrl: "bundle",
  paths: {
    jquery: '//code.jquery.com/jquery-1.12.4',
    jqueryui: '//code.jquery.com/ui/1.12.0/jquery-ui',
    bootstrap :  '//maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.3/js/bootstrap.min'
  },
  shim : {
        "bootstrap" : { "deps" :['jquery'] }
    }
});

/*
define('jquery-private', ['jquery'], function (jq) {
    return jq.noConflict( true );
});
*/

define(['jquery', 'jqueryui', 'bundle/io/src/iolib','src/lang.en', 'src/chatboxManager','src/lib','src/window','src/footer', 'src/uichatlist','src/uichatbox', 'src/uichatroom'], function($, jui, io,lang, chatboxManager, clib, window) {
  
     //$.uiBackCompat = false;
     var userobj = {'userid':id,'name':name,'img':imageurl};
     var room = 'main-c-room';//ToDo:

     dataobject = {
            'userid':id,
            'sid':'212',
            'rid': path,
            'authuser':auth_user,
            'authpass':auth_pass,
            'userobj': userobj,
            'room':room};

     $(document).ready(function(){
         counter = 0;
         idList = new Array();
         var box = null;
         $.htab = [];
         $.htabIndex = [];
         vmstorage = {};

        if (clib.browserSupportsLocalStorage() == false)  { // check browser for local storage
             clib.display_error(lang.sterror);
             return;
        }

        if(localStorage.getItem('init') != 'false') {
            io.init(dataobject);
        }

        $('body').footerbar();

        if(localStorage.getItem('init') == 'false'){ // check footer is close
             $('#stickybar').removeClass('maximize').addClass('minimize');
             $('#hide_bar input').removeClass('close').addClass('expand');
        }
        tabs = $('#tabs').tabs({ cache: true, activeOnAdd: true});

/*
        if (clib.browserSupportsLocalStorage() == false)  { // check browser for local storage
             clib.display_error(lang.sterror);
             return;
        }
*/
         // checking private chat local storage
         // Data stored in session key inside localStorage variable
         // sid is the session id
         if (localStorage.getItem(sid) != null)  {
             clib.displayChatHistory();
             vmstorage = JSON.parse(localStorage.getItem(sid));
         }

         //checking common chat local storage
         //Data stored inside sessionStorage variable
         if(sessionStorage.length > 0){ 
             clib.displaycomChatHistory();
             //console.log(clib.chatroombox);
         }

         /* Remove user tab and chatbox when click on tab close icon */
         $('#tabs').delegate( "span.ui-icon-close", "click", function() {

             // delete box
             var tabid = $( this ).closest( "li" ).attr( "id").substring(5);
             $("#" + tabid).chatbox("option").boxClosed(tabid);
             $('div#cb' + tabid + '.ui-widget').hide();

             //delete tab
             var panelId = $( this ).closest( "li" ).remove().attr( "aria-controls" );
             $( "#" + panelId ).remove();

             delete vmstorage[tabid]; //delete variable storage
         });

         /* Hide box when click on user tab */
         $("#tabs").on("click", "li a", function(){
             var tabid = $( this ).closest( "li" ).attr( "id").substring(5);
             $("#" + tabid).chatbox('toggleContentbox');
             if(localStorage.getItem(tabid) == 'hidden'){
                 localStorage.removeItem(tabid);
             }else{
                 localStorage.setItem(tabid, 'hidden');
             }
         });

         // new message alert
         $('ul.tabs').on("click, focus", "li", function(){
             $("li[aria-controls='" + $(this).attr('id') + "']").removeClass('ui-state-highlight');
         });

         $(document).on("member_added", function(e){
             clib.memberUpdate(e);
         });
         $(document).on("member_removed", function(e){
             clib.memberUpdate(e);
         });
         $(document).on("newmessage", function(e){
             clib.messageUpdate(e);
         });
         $(document).on("Multiple_login", function(e){
            //if same user login multiple times then
            //remove previously logged in detail

            $('.ui-memblist').remove();
            $('.ui-chatbox').remove();
            $('div#chatrm').remove();
            clib.chatroombox = null;

            // delete open chat box
            for(key in io.uniquesids){
                if(key != io.cfg.userid){
                    chatboxManager.delBox(key);
                    $( "li#tabcb" + key ).remove(); //delete tab
                }
            }
            idList = new Array(); // chatbox
            $('#stickybar').removeClass('maximize').addClass('minimize');
            $('#hide_bar input').removeClass('close').addClass('expand');
            $('#hide_bar input').prop('title', 'Open chat');
            tabs.tabs( "refresh" );//tabs
         });

         $(document).on("authentication_failed", function(e){
            //delete cookie
            document.cookie = "auth_user=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/";
            document.cookie = "auth_pass=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/";
            document.cookie = "path=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/";
            clib.display_error(e.message);
         });

         $(document).on("error", function(e){
            if(typeof e.message != 'object'){
                clib.display_error(e.message);
            }
         });

         $(document).on("connectionclose", function(e){
             $("#user_list .inner_bt #usertab_icon").css({'background': 'url(/images/offline.png)no-repeat top left'});
             $("#user_list .inner_bt #usertab_text").text(lang.whos + " (0)");
             $("#chatroom_bt .inner_bt #chatroom_text").text(lang.chatroom + " (0)");
             $('div#memlist').css('display','none');
         });
         
         //TODO: check ios private mode
        
        if(clib.isIosDevices()){ 
            //window.unload(function() {
            //$(window).on('unload', function() {
            //window.onload = function() {
/*
            $(document).on("pageshow",function(){
                console.log("before load");
            });
*/
/*
            window.addEventListener("pageshide", function(evt){
            alert('show');
        }, false);
*/
/*
            if(document.readyState === "complete"){
                console.log('window loaded');
                var data = JSON.stringify(vmstorage);
                localStorage.setItem(sid, data);
            };
*/          $(window).bind('pagehide',function(){
                var data = JSON.stringify(vmstorage);
                localStorage.setItem(sid, data);
            });

        } else {
            $(window).bind('beforeunload',function(){
                var data = JSON.stringify(vmstorage);
                localStorage.setItem(sid, data);
            });
        };
   });
<<<<<<< HEAD
});
=======
/*
   if(clib.isIosDevices()){
       $(window).bind('pagehide',function(){
                var data = JSON.stringify(vmstorage);
                localStorage.setItem(sid, data);
            });
    }
*/
 
//});
})
>>>>>>> 07a09c9292056ee37c67a3af28d8c60f7bef4b77
