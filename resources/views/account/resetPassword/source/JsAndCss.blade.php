{{HTML::style('/resources/css/resetpsd.css')}}
{{HTML::script('/resources/js/class-inheritance.js')}}
{{HTML::script('/resources/js/inputs.js')}}
{{HTML::script('/resources/js/password.js')}}
<script>
    function submitcheck() {
        var finalsalt = '{{config('app.rsa_salt')}}';
        var rsa_n = "{{config('app.rsa_key')}}";
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