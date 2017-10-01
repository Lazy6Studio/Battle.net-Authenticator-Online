<script>
    $(window.parent.document).find("iframe").load(function () {
        var main = $(window.parent.document).find("iframe");
        var thisheight = $(document).height();
        $(window.parent.document).find("#login-embedded").height(thisheight);
        main.height(thisheight);
    });
    $(function () {
        $('#accountName').focus();
    });
    function closeiframe() {
        $(window.parent.document).find("#login-embedded").fadeOut("slow", function () {
            $(window.parent.document).find("#blackout").css('display', 'none');
            $(this).remove();
        });
    }

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

<script>
    $(window.parent.document).find("iframe").load(function(){
        var main = $(window.parent.document).find("iframe");
        var thisheight = $(document).height();
        $(window.parent.document).find("#login-embedded").height(thisheight);
        main.height(thisheight);
        parent.ifnotloginiframecanchangethisvalue=true;
    });

    function closeiframe() {
        window.parent.OneKeyLogin._close(true);
    }
</script>