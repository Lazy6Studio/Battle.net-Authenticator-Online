<script>
    function handlePaste3(e) {
        var clipboardData, pastedData;
        e.stopPropagation();
        e.preventDefault();
        clipboardData = e.clipboardData || window.clipboardData;
        pastedData = clipboardData.getData('Text');
        var data = pastedData.toUpperCase();
        var reg17 = /(US|EU|CN)-([0-9]{4})-([0-9]{4})-([0-9]{4})/;
        var reg14 = /(US|EU|CN)([0-9]{4})([0-9]{4})([0-9]{4})/;
        var array = [];
        if (data.length === 17 && reg17.test(data)) {
            array = data.match(reg17);
        }
        if (data.length === 14 && reg14.test(data)) {
            array = data.match(reg14);
        }
        notJumpWhenFocus=false;
        if (array.length > 0) {
            switch (array[1]) {
                case "CN":
                    $("#question3").val("21");
                    break;
                case "US":
                    $("#question3").val("22");
                    break;
                case "EU":
                    $("#question3").val("23");
                    break;
            }
            $('#authcodeA3').val(array[2]);
            $('#authcodeB3').val(array[3]);
            $('#authcodeC3').val(array[4]);
            return;
        }
        var numberReg = /([0-9]+)/
        if (numberReg.test(data)) {
            $('#authcodeA3').val(data.substr(0, data.length > 3 ? 4 : data.length));
            if (data.length > 4) {
                $('#authcodeB3').val(data.substr(4, data.length > 7 ? 4 : data.length - 4));
            }
            if (data.length > 8) {
                $('#authcodeC3').val(data.substr(8, data.length > 11 ? 4 : data.length - 4));
            }
        }
    }
    function handlePaste2(e) {
        var clipboardData, pastedData;
        e.stopPropagation();
        e.preventDefault();
        clipboardData = e.clipboardData || window.clipboardData;
        pastedData = clipboardData.getData('Text');
        var data = pastedData.toUpperCase();
        var reg17 = /(US|EU|CN)-([0-9]{4})-([0-9]{4})-([0-9]{4})/;
        var reg14 = /(US|EU|CN)([0-9]{4})([0-9]{4})([0-9]{4})/;
        var array = [];
        if (data.length === 17 && reg17.test(data)) {
            array = data.match(reg17);
        }
        if (data.length === 14 && reg14.test(data)) {
            array = data.match(reg14);
        }
        notJumpWhenFocus=false;
        if (array.length > 0) {
            switch (array[1]) {
                case "CN":
                    $("#question2").val("21");
                    break;
                case "US":
                    $("#question2").val("22");
                    break;
                case "EU":
                    $("#question2").val("23");
                    break;
            }
            $('#authcodeA2').val(array[2]);
            $('#authcodeB2').val(array[3]);
            $('#authcodeC2').val(array[4]);
            return;
        }
        var numberReg = /([0-9]+)/
        if (numberReg.test(data)) {
            $('#authcodeA2').val(data.substr(0, data.length > 3 ? 4 : data.length));
            if (data.length > 4) {
                $('#authcodeB2').val(data.substr(4, data.length > 7 ? 4 : data.length - 4));
            }
            if (data.length > 8) {
                $('#authcodeC2').val(data.substr(8, data.length > 11 ? 4 : data.length - 4));
            }
        }
    }
    document.getElementById('authcodeA3').addEventListener('paste', handlePaste3);
    document.getElementById('authcodeB3').addEventListener('paste', handlePaste3);
    document.getElementById('authcodeC3').addEventListener('paste', handlePaste3);
    document.getElementById('authcodeA2').addEventListener('paste', handlePaste2);
    document.getElementById('authcodeB2').addEventListener('paste', handlePaste2);
    document.getElementById('authcodeC2').addEventListener('paste', handlePaste2);
</script>