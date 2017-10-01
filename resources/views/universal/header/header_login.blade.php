<!DOCTYPE html>
<!--suppress ALL -->
<html lang="zh-hans">
<head>
    <script>
        var siteAddressForAllJsFile = "{{config("app.url")}}";
        var ifNotLoginIframeCanChangeThisValue = false;
    </script>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="og:title" content="{{config("app.name")}}">
    <meta name="og:description" content="{{config("app.meta_seo")}}">
    <meta property="og:image" content="/resources/weiboimg/fbshare.png"/>
    <title>@if(!empty(@$topNavValueText)){{@$topNavValueText}} - @endif{{config("app.name")}}</title>
    <link rel="shortcut icon" type="image/x-icon" href="/resources/img/favicon.ico">
    {{HTML::style('/resources/css/footer.css')}}
    {{HTML::style('/resources/css/login.css')}}
    @if(config('app.debug'))
        {{HTML::script('/resources/js/jquery-3.1.0.min.js')}}
    @else
        {{HTML::script('https://lib.sinaapp.com/js/jquery/3.1.0/jquery-3.1.0.min.js')}}
        <script type="text/javascript">
            !window.jQuery && document.write('<script src="/resources/js/jquery-3.1.0.min.js"><\/script>');
        </script>
    @endif
    {{HTML::script('/resources/js/rsa.js')}}
    {{HTML::script('/resources/js/md5.js')}}
    @if(!empty(config('app.baidu_tongji_id')))
        <script>
            var _hmt = _hmt || [];
            (function () {
                var hm = document.createElement("script");
                var Baidu_Js_Server = (("https:" === document.location.protocol) ? "https://" : "http://");
                hm.src = Baidu_Js_Server + "hm.baidu.com/hm.js?{{config('app.baidu_tongji_id')}}";
                var s = document.getElementsByTagName("script")[0];
                s.parentNode.insertBefore(hm, s);
            })();
        </script>
@endif