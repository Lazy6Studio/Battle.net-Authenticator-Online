<script>
    $(document).ready(function () {
        if ($("#page-content").height() < $(".article-column").outerHeight(true)) {
            $("#page-content").height($(".article-column").outerHeight(true));
        }
        if ($("#layout-middle").outerHeight(true) < 360) {
            $("#layout-bottom").css("background", "url('/resources/img/toumin.png') no-repeat 50% 70%");
        }
    });
    $(window).resize(function() {
        $("#page-content").height($(".article-column").outerHeight(true));
    });

    function shareweibo(e) {
        var _t = "{{config('app.meta_seo')}}";
        var _url = "{{config("app.url")}}";
        var _sinakey = "{{config('app.third_share_sina_key')}}";
        var _tencentkey = "{{config('app.third_share_tencent_key')}}";
        var _sohukey = "{{config('app.third_share_sohu_key')}}";
        var _neteasekey = "{{config('app.third_share_netease_key')}}";
        switch (e) {
            case "sina":
                var _usina = 'http://service.weibo.com/share/share.php?type=icon&url=' + _url + '&title=' + _t + '&ralateUid=' + _sinakey;
                window.open(_usina, '分享到新浪微博', 'toolbar=0,status=0,resizable=1,width=620,height=450,left=' + (screen.width - 620) / 2 + ',top=' + (screen.height - 450) / 2);
                break;
            case "tencent":
                var _utencent = 'http://v.t.qq.com/share/share.php?title=' + _t + '&url=' + _url + '&appkey=' + _tencentkey;
                window.open(_utencent, '分享到腾讯微博', 'toolbar=0,status=0,resizable=1,width=700,height=580,left=' + (screen.width - 700) / 2 + ',top=' + (screen.height - 580) / 2);
                break;
            case "sohu":
                var _usohu = 'http://t.sohu.com/third/post.jsp?title=' + _t + '&url=\\n' + _url + '&appkey=' + _sohukey;
                window.open(_usohu, '分享到搜狐微博', 'toolbar=0,status=0,resizable=1,width=660,height=470,left=' + (screen.width - 660) / 2 + ',top=' + (screen.height - 470) / 2);
                break;
            case "netease":
                var _unetease = 'http://t.163.com/article/user/checkLogin.do?info=' + _t + ' ' + _url + '&key=' + _neteasekey;
                window.open(_unetease, '分享到网易微博', 'toolbar=0,status=0,resizable=1,width=660,height=470,left=' + (screen.width - 660) / 2 + ',top=' + (screen.height - 470) / 2);
                break;
            case "facebook":
                var _ufacebook = 'http://www.facebook.com/sharer.php?u={{config("app.url")}}';
                window.open(_ufacebook, '分享到FACEBOOK', 'toolbar=0,status=0,resizable=1,width=660,height=470,left=' + (screen.width - 660) / 2 + ',top=' + (screen.height - 470) / 2);
                break;
            case "twitter":
                var _utwitter = 'https://twitter.com/intent/tweet?source=webclient&text={{config("app.url")}}%20' + _t;
                window.open(_utwitter, '分享到TWITTER', 'toolbar=0,status=0,resizable=1,width=660,height=470,left=' + (screen.width - 660) / 2 + ',top=' + (screen.height - 470) / 2);
                break;
            case "plurk":
                var _uplurk = 'http://www.plurk.com/?qualifier=shares&status={{config("app.url")}}%20' + _t;
                window.open(_uplurk, '分享到PLURK', 'toolbar=0,status=0,resizable=1,width=660,height=470,left=' + (screen.width - 660) / 2 + ',top=' + (screen.height - 470) / 2);
                break;
        }
    }
</script>