@if($authUtils->getAuthCount()>0)
    <script type="text/javascript">
        var serverauthmorenid = {{$authUtils->getDefaultAuth()->getAuthId()}};
    </script>
@endif
{{HTML::style('/resources/css/myauthall.css')}}
{{HTML::script('/resources/js/auth_union.js')}}

<style>
    .parent-row{
        height:55px;
    }
    .authxuliehao{
        width: 120px;
    }
</style>