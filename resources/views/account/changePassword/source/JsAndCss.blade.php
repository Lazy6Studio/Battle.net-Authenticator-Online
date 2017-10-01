{{HTML::style('/resources/css/resetpsd.css')}}
{{HTML::script('/resources/js/class-inheritance.js')}}
{{HTML::script('/resources/js/inputs.js')}}
{{HTML::script('/resources/js/password.js')}}
{{HTML::script('/resources/js/forgetpwd_ajaxcheck.js')}}
<script>
    var unixdiff = 0;
    $(document).ready(function () {
        costomunixtime = Math.round(new Date().getTime() / 1000);
        serverunix ={{time()}};
        unixdiff = serverunix - costomunixtime+1;
    });
    function submitcheck() {
        if ($("#letters_code").val() == null || $("#letters_code").val() == "") {
            $("#errorspan").html('<span id="emailAddress-message" class="inline-message" style="color:rgb(255,0,0) !important;margin-bottom: 8px;">请先输入验证码!</span>');
            return false;
        }
        var finalsalt = '{{config('app.rsa_salt')}}';
        var rsa_n = "{{config('app.rsa_key')}}";
        var unixtime = Math.round(new Date().getTime() / 1000)+unixdiff;
        var oldpass = $("#oldPassword").val();
        var saltedoldpass = hex_md5(oldpass) + finalsalt + unixtime;
        saltedoldpass = hex_md5(saltedoldpass) + unixtime;
        setMaxDigits(131);
        var key = new RSAKeyPair("10001", '', rsa_n);
        oldpass = encryptedString(key, saltedoldpass);
        $("#oldPassword").val(oldpass);

        var newpass = $("#newPassword").val();
        setMaxDigits(131);
        var key = new RSAKeyPair("10001", '', rsa_n);
        newpass = encryptedString(key, newpass);
        $("#newPassword").val(newpass);
        var newpasscheck = $("#newPasswordVerify").val();
        setMaxDigits(131);
        var key = new RSAKeyPair("10001", '', rsa_n);
        newpasscheck = encryptedString(key, newpasscheck);
        $("#newPasswordVerify").val(newpasscheck);
    }
</script>