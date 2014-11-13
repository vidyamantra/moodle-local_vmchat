if (!window.opener) { // Check popup

    var cssId = 'myCss';
    if (!document.getElementById(cssId))
    {
        var head  = document.getElementsByTagName('head')[0];
        var link  = document.createElement('link');
        link.id   = cssId;
        link.rel  = 'stylesheet';
        link.type = 'text/css';
        link.href = wwwroot + 'local/vmchat/bundle/chat/bundle/jquery/css/base/jquery-ui.css';
        link.media = 'all';
        head.appendChild(link);
    }
    var cssId = 'myCss1';
    if (!document.getElementById(cssId))
    {
        var head  = document.getElementsByTagName('head')[0];
        var link  = document.createElement('link');
        link.id   = cssId;
        link.rel  = 'stylesheet';
        link.type = 'text/css';
        link.href = wwwroot + 'local/vmchat/bundle/chat/css/jquery.ui.chatbox.css';
        link.media = 'all';
        head.appendChild(link);
    }

         $.uiBackCompat = false;
         var userobj = {'userid':id,'name':fname,'lname':lname,'img':imageurl};
         var room = tk + '-main-c-room';//ToDo:
         userdata = {
                'userid':id,
                'sid':sid,
                'rid': path,
                'authuser':auth_user,
                'authpass':auth_pass,
                'userobj': userobj,
                'fastchat_lasttime':'0',
                'fastchatroom_title':'fastchat',
                'fastchatroom_name':room};

         $(document).ready(function() {
             counter = 0;
             idList = new Array();
             var box = null;
             $.htab = [];
             $.htabIndex = [];
             vmstorage = {};

             if(localStorage.getItem('init') != 'false') {
                io.init(userdata);
             }

             $('body').footerbar();// footer bar initialization

             if(localStorage.getItem('init') == 'false'){ // check footer is close
                $('#stickybar').removeClass('maximize').addClass('minimize');
                $('#hide_bar input').removeClass('close').addClass('expand');
             }

             tabs = $('#tabs').tabs({ cache: true, activeOnAdd: true});

             if (browserSupportsLocalStorage() == false)  {  // check browser for local storage
                    display_error(lang.sterror);
                    return;
             }
             // checking private chat local storage
             // Data stored in session key inside localStorage variable
             // sid is the session id
             if (localStorage.getItem(sid) != null) {
                 displayChatHistory();
                 vmstorage = JSON.parse(localStorage.getItem(sid));
             }

           //checking common chat local storage
           //Data stored inside sessionStorage variable
           if(sessionStorage.length > 0){
               displaycomChatHistory();
           }

           /* Remove user tab and chatbox when click on tab close icon */
           $('#tabs').delegate( "span.ui-icon-close", "click", function() {
                //delete box
                var tabid = $( this ).closest( "li" ).attr( "id").substring(5);
                $("#" + tabid).chatbox("option").boxClosed(tabid);
                $('div#cb' + tabid + '.ui-widget').hide();

                //delete tab
                var panelId = $( this ).closest( "li" ).remove().attr( "aria-controls" );
                $( "#" + panelId ).remove();

                delete vmstorage[tabid]; //delete variable storage
           });

           /* Hide box when click on user tab */
           $("#tabs").on("click", "li a", function() {
               var tabid = $( this ).closest( "li" ).attr( "id").substring(5);
               $("#" + tabid).chatbox('toggleContentbox');
               if(localStorage.getItem(tabid) == 'hidden'){
                   localStorage.removeItem(tabid);
               }else{
                   localStorage.setItem(tabid, 'hidden');
               }
           });

           // new message alert
           $('ul.tabs').on("click", "li", function() {
               $("li[aria-controls = '" + $(this).attr('id') + "']").removeClass('ui-state-highlight');
           });

           $(document).on("member_added", function(e) {
                memberUpdate(e);
           });
           $(document).on("member_removed", function(e) {
                memberUpdate(e);
           });
           $(document).on("newmessage", function(e) {
                messageUpdate(e);
           });

           $(document).on("Multiple_login", function(e){
                //if same user login multiple times then
                //remove previously logged in detail
                $('.ui-memblist').remove();
                $('.ui-chatbox').remove();
                $('div#chatrm').remove();
                chatroombox = null;

                // delete open chat box
                for(key in io.uniquesids) {
                    if(key != io.cfg.userid){
                        chatboxManager.delBox(key);
                        $( "li#tabcb" + key).remove();//delete tab
                    }
                }
                idList = new Array(); // chatbox
                $('#stickybar').removeClass('maximize').addClass('minimize');
                $('#hide_bar input').removeClass('close').addClass('expand');
                $('#hide_bar input').prop('title', 'Open chat');
                tabs.tabs( "refresh" );//tabs
            });

            $(document).on("authentication_failed", function(e) {

                document.cookie = "auth_user=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/";
                document.cookie = "auth_pass=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/";
                document.cookie = "path=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/";
                display_error(e.message);
            });

            $(document).on("error", function(e) {
                if(typeof e.message != 'object') {
                    display_error(e.message);
                }
            });
            $(document).on("connectionclose", function(e) {
                $("#user_list .inner_bt #usertab_icon").css({'background': 'url(' + wwwroot + 'local/vmchat/bundle/chat/images/offline.png)no-repeat top left'});
                $("#user_list .inner_bt #usertab_text").text(lang.whos + " (0)");
                $("#chatroom_bt .inner_bt #chatroom_text").text(lang.chatroom + " (0)");
                $('div#memlist').css('display','none');
            });
            $(window).bind('beforeunload',function(){
                    var data = JSON.stringify(vmstorage);
                    localStorage.setItem(sid, data);
            });
       });
};