{{HTML::style('/resources/css/addauth.css')}}
{{HTML::script('/resources/js/bam.js')}}
{{HTML::script('/resources/js/dashboard.js')}}
{{HTML::script('/resources/js/addauth_ajaxcheck.js')}}
{{HTML::script('/resources/js/authadd.js')}}
<script type='text/javascript'>
    jquerycodechecked1 = false;
    jquerycodechecked2 = false;
    jquerycodechecked3 = false;
    //<![CDATA[
    $(document).ready(function () {
        $("#letters_code1").keyup(function () {
            if ($("#letters_code1")[0].value.length == 6) {
                checkyanzhenma($("#letters_code1")[0].value, 1);
                jquerycodechecked1 = true;
            } else {
                jquerycodechecked1 = false;
                document.getElementById('checkyanzhenmaajax1').innerHTML = "";
            }
        });
        $("#letters_code2").keyup(function () {
            if ($("#letters_code2")[0].value.length == 6) {
                checkyanzhenma($("#letters_code2")[0].value, 2);
                jquerycodechecked2 = true;
            } else {
                jquerycodechecked2 = false;
                document.getElementById('checkyanzhenmaajax2').innerHTML = "";
            }
        });
        $("#letters_code3").keyup(function () {
            if ($("#letters_code3")[0].value.length == 6) {
                checkyanzhenma($("#letters_code3")[0].value, 3);
                jquerycodechecked3 = true;
            } else {
                jquerycodechecked3 = false;
                document.getElementById('checkyanzhenmaajax3').innerHTML = "";
            }
        });
    });
    //]]>
    var notJumpWhenFocus;
</script>

