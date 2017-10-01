<script>
    var unixdiff = 0;
    $(document).ready(function () {
        costomunixtime = Math.round(new Date().getTime() / 1000);
        serverunix = {{time()}};
        unixdiff = serverunix - costomunixtime + 1;
    });
    function submitcheck() {
        if ($("#letters_code").val() == null || $("#letters_code").val() == "") {
            $("#errorspan").html('<span id="emailAddress-message" class="inline-message">请先输入验证码!</span>');
            return false;
        }
        var finalsalt = '{{config('app.rsa_salt')}}';
        var rsa_n = "{{config('app.rsa_key')}}";
        var unixtime = Math.round(new Date().getTime() / 1000) + unixdiff;
        var inputpass = $("#password").val();
        var saltedpass = hex_md5(inputpass) + finalsalt + unixtime;
        saltedpass = hex_md5(saltedpass) + unixtime;
        setMaxDigits(131);
        var key = new RSAKeyPair("10001", '', rsa_n);
        var password = encryptedString(key, saltedpass);
        $("#password").val(password);
    }
    function removeNoCpatchaInputError() {
        $("#errorspan").html('');
    }
</script>
{{HTML::script('/resources/js/ajaxcheck.js')}}
<style>
    #layout-bottom{
        background: url('/resources/img/toumin.png') no-repeat 50% 70%;
    }
    #checkyanzhenmaajax img {
        position: relative;
        top: 4px;
    }
</style>