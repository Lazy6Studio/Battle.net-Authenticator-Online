{{HTML::style('/resources/css/register.css')}}
{{HTML::script('/resources/js/class-inheritance.js')}}
{{HTML::script('/resources/js/inputs.js')}}
{{HTML::script('/resources/js/streamlined-creation.js')}}
<!--suppress JSJQueryEfficiency -->
<script type="text/javascript">
    //<![CDATA[
    $(function () {
        var inputs = new Inputs('#creation');
        var creation = new Creation('#creation');
    });
    jqueryCodeChecked = false;
    $(document).ready(function () {
        $("#letters_code").keyup(function () {
            if ($("#letters_code")[0].value.length === 6) {
                checkyanzhenma($("#letters_code")[0].value);
                jqueryCodeChecked = true;
            } else {
                jqueryCodeChecked = false;
                document.getElementById('checkyanzhenmaajax').innerHTML = "";
            }
        });
    });
    //]]>
</script>
{{HTML::script('/resources/js/ajaxcheck.js')}}