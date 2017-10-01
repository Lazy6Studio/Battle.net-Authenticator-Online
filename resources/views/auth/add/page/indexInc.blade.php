<div id="layout-middle">
    <div id="homewrapper">
        <div id="content">
            <div id="page-header">
                <h2 class="subcategory">
                    {{config('app.blizzard_auth_name')}}
                </h2>
                <h3 class="headline"> 添加一枚新的安全令 </h3>
            </div>
            <div class="columns-2-1 landing-introduction">
                <div class="column column-left">
                    <p>
                        在这里，您可以通过三种方式快速地向您的账号中添加一枚新的{{config('app.blizzard_auth_name')}}，请注意，您最多只能添加{{$maxAuthCount}}枚安全令至您的账号。<br>
                        ①您可以通过{{config('app.name')}}的服务器直接向暴雪官方请求一枚新的{{config('app.blizzard_auth_name')}}，如同您在手机安全令APP上生成的一样<br>
                        ②提交您已有安全令的序列号及密钥(40位)，即可快速恢复一枚{{config('app.blizzard_auth_name')}}。<br>
                        ③提交您已有安全令的序列号及还原码(10位)，即可快速恢复一枚{{config('app.blizzard_auth_name')}}。
                    </p>
                    <p>
                        如果想管理已有的安全令，请访问<a href="/myAuthList" tabindex="1">我的安全令</a>页面。如有任何疑问，请参访<a href="/faq"
                                                                                                   tabindex="1">FAQ</a>。
                    </p>
                    <p style="color: red;">
                        特别说明：如果点击创建安全令后进入的页面未显示任何提示，请返回本页重试，这个问题是由于相关操作需要与暴雪服务器交互，存在失败可能
                    </p>
                </div>
                <div class="column column-right">
                    <div class="landing-promotion"></div>
                </div>
            </div>
            <div class="dashboard cn">
                <div class="secondary">
                    <div class="service-selection additional-services" style="margin-bottom:30px;">
                        <ul class="wow-services">
                            <li class="category">
                                <a class="additional-services" href="#additional-services">
                                    通过服务器请求一枚新安全令
                                </a>
                            </li>
                            <li class="category">
                                <a class="character-services" href="#character-services">
                                    通过密钥(40位)恢复一枚安全令
                                </a>
                            </li>
                            <li class="category">
                                <a class="game-time-subscriptions" href="#game-time-subscriptions">
                                    通过还原码(10位)恢复一枚安全令
                                </a>
                            </li>
                        </ul>
                        <div class="service-links" style="margin-right:1px;">
                            <div class="position"></div>
                            <div id="additional-services" class="content additional-services">

                                <form action="/addAuthByServer" method="post" id="creation" novalidate="novalidate">
                                    {{csrf_field()}}
                                    <div id="page-header" class="pageheadernav">
                                        <p class="privacy-message">
                                            点击通过服务器生成安全令，{{config('app.name')}}
                                            将按照选择的地区向暴雪服务器请求一枚新的安全令，地区为：中国(CN)，美国(US)，欧洲(EU)。<br>
                                            注意：选择美国(US)或欧洲(EU)有一定几率获得EU或US开头的安全令，该安全令在欧服美服等地区通用，但不能用于中国大陆国服账号。<br>
                                            若不选择将默认向暴雪中国服务器发出请求。
                                        </p>
                                    </div>
                                    <div class="input-row input-row-text">
                                        <span class="input-left">
                                            <label for="firstname">
                                                <span class="label-text">
                                                    安全令名称：
                                                </span>
                                                <span class="input-required">*</span>
                                            </label>
                                        </span><!--
                                        --><span class="input-right">
                                            <span class="input-text input-text-small">
                                                <input type="text" name="authname" value="" id="firstname"
                                                       onfocus="if(authnameerrorid==1){this.value='';authnameerrorid==0;document.getElementById('creation-submit1').disabled='';}"
                                                       onblur="checkname(this,1);" class="small border-5 glow-shadow-2"
                                                       autocomplete="off" maxlength="12" tabindex="1"
                                                       required="required" placeholder="不能超过12个字符">
                                                <span id="checkusernameajax1" class="checkusernameajax"></span>
                                                <span class="inline-message " id="firstname-message"> </span>
                                            </span>
                                        </span>
                                    </div>

                                    <div class="input-row input-row-select">
                                        <span class="input-left">
                                            <label for="question1">
                                                <span class="label-text">
                                                    请选择暴雪服务器地区：
                                                </span>
                                                <span class="input-required">*</span>
                                            </label>
                                        </span><!--
                                        --><span class="input-right">

                                            <span class="input-select input-select-small">
                                                <select name="region" id="question1"
                                                        class="selectwitthnav1 small border-5 glow-shadow-2"
                                                        tabindex="1" required="required">
                                                    <option value="21" selected="selected">选择一个地区发出令牌生成申请,默认为中国</option>
                                                    <option value="21">CN&nbsp;&nbsp;&nbsp;&nbsp;(暴雪中国服务器,cn.battle.net)</option>
                                                    <option value="22">US&nbsp;&nbsp;&nbsp;&nbsp;(暴雪美国服务器,us.battle.net)</option>
                                                    <option value="23">EU&nbsp;&nbsp;&nbsp;&nbsp;(暴雪欧洲服务器,eu.battle.net)</option>
                                                </select>
                                            </span>
                                        </span>
                                    </div>
                                    <div class="input-row input-row-note question-info" id="question-info"
                                         style="display: none;">
                                        <span id="question1-message" class="inline-message no-text-clear">您将需要使用该信息进行身份验证，以便在将来找回密码时使用。该内容确定后无法修改。</span>
                                    </div>


                                    <div class="input-row input-row-radiopic">
                                        <span class="input-left">
                                            <label for="radiobutton">
                                                <span class="label-text">
                                                    安全令图片：
                                                </span>
                                                <span class="input-required">*</span>
                                            </label>
                                        </span><!--
                                        --><span class="input-right input-picradio">
                                            <span class="radioandpic">
                                                <img class="spanradioimg" src="resources/img/wow-32.png">
                                                <input id="radiobutton" name="selectpic" type="radio" value="1"
                                                       checked="true"/>
                                            </span>
                                            <span class="radioandpic">
                                                <img class="spanradioimg" src="resources/img/s2-32.png">
                                                <input id="radiobutton" name="selectpic" type="radio" value="2"/>
                                            </span>
                                            <span class="radioandpic">
                                                <img class="spanradioimg" src="resources/img/d3-32.png">
                                                <input id="radiobutton" name="selectpic" type="radio" value="3"/>
                                            </span>
                                            <span class="radioandpic">
                                                <img class="spanradioimg" src="resources/img/pegasus-32.png">
                                                <input id="radiobutton" name="selectpic" type="radio" value="4"/>
                                            </span>
                                            <span class="radioandpic">
                                                <img class="spanradioimg" src="resources/img/heroes-32.png">
                                                <input id="radiobutton" name="selectpic" type="radio" value="5"/>
                                            </span>
                                            <span class="radioandpic">
                                                <img class="spanradioimg" src="resources/img/overwatch-32.png">
                                                <input id="radiobutton" name="selectpic" type="radio" value="6"/>
                                            </span>
                                            <span class="inline-message " id="firstname-message"> </span>
                                        </span>
                                    </div>


                                    <div class="input-row input-row-text">
                                        <span class="input-left">
                                            <label for="letters_code1">
                                                <span class="label-text">
                                                    验证码：
                                                </span>
                                                <span class="input-required">*</span>
                                            </label>
                                        </span>
                                        <span class="input-right">
                                            <div class="imgandreloader">
                                                <div id="captcha-image"><img id="sec-string1" width="200" height="70"
                                                                             onclick="refreshCaptcha(1);document.getElementById('letters_code1').focus();"
                                                                             src="/api/captchaCode?rand=?rand=8&unix={{$captchaCodeUnix}}"
                                                                             alt="换一个" title="换一个"
                                                                             class="border-5"/></div>
                                                <div id="captcha-reloader">
                                                    看不清楚？<br/>
                                                    <a href="javascript:void(0);"
                                                       onclick="refreshCaptcha(1);document.getElementById('letters_code1').focus();">换一个</a>
                                                </div><span class="input-static input-static-extra-large"><span
                                                            class="static"><p><label class="label" for="letters_code1">
                                                                出于安全性考虑，请输入上方图示中的字符。（这并不是您的密码）</label></p></span></span>
                                            </div>
                                            <span class="input-text input-text-small">
                                                <input type="text" name="letters_code" value=""
                                                       onfocus="document.getElementById('creation-submit1').disabled='disabled';"
                                                       onblur="if(!jquerycodechecked1){checkyanzhenma(this.value,1);}"
                                                       id="letters_code1" class="small border-5 glow-shadow-2"
                                                       autocomplete="off" onpaste="return false;" maxlength="6"
                                                       tabindex="1" required="required" placeholder="输入验证码"/>
                                                <span id="checkyanzhenmaajax1" class="checkyanzhenmaajax"></span>
                                                <span class="inline-message " id="emailAddress-message"> </span>
                                            </span>
                                            <span id="remember-me"><label for="persistLogin">
                                                    <input id="persistLogin" type="checkbox" name="morenauthset">设置为默认安全令
                                                </label> </span>
                                        </span>
                                    </div>


                                    <div class="submit-row">
                                        <div class="input-left"></div>
                                        <div class="input-right">
                                            <button class="ui-button button1" type="submit" id="creation-submit1"
                                                    tabindex="1"><span class="button-left"><span class="button-right">通过服务器生成安全令</span></span>
                                            </button>
                                            <a class="ui-cancel " href="/"
                                               tabindex="1">
                                                <span>
                                                    取消 </span>
                                            </a>
                                        </div>
                                    </div>
                                    <input type="hidden" name="agreedToToU" value="true" id="agreedToToU">
                                </form>
                            </div>

                            <div id="character-services" class="content character-services">

                                <form action="/addAuthBySecret" method="post" id="creation" novalidate="novalidate">
                                    {{csrf_field()}}
                                    <div id="page-header" class="pageheadernav">
                                        <p class="privacy-message">请在第二行中输入安全令序列号(类似于US-1234-5678-9012)，横线不必输入。<br>请在第三行中输入40位密钥(类似于F192F7441D4E4FDA1E21E837C9DCAD4510E726BA)，查找方式请参见<a
                                                    href="/faq">FAQ</a></p>
                                    </div>
                                    <div class="input-row input-row-text">
                                        <span class="input-left">
                                            <label for="firstname2">
                                                <span class="label-text">
                                                    安全令名称：
                                                </span>
                                                <span class="input-required">*</span>
                                            </label>
                                        </span><!--
                                        --><span class="input-right">
                                            <span class="input-text input-text-small">
                                                <input type="text" name="authname" value="" id="firstname2"
                                                       onfocus="if(authnameerrorid==1){this.value='';authnameerrorid==0;document.getElementById('creation-submit2').disabled='';}"
                                                       onblur="checkname(this,2);" class="small border-5 glow-shadow-2"
                                                       autocomplete="off" maxlength="12" tabindex="1"
                                                       required="required" placeholder="不能超过12个字符">
                                                <span id="checkusernameajax2" class="checkusernameajax"></span>
                                                <span class="inline-message " id="firstname-message"> </span>
                                            </span>
                                        </span>
                                    </div>

                                    <div class="input-row input-row-select">
                                        <span class="input-left">
                                            <label for="question2">
                                                <span class="label-text">
                                                    请输入安全令序列号：
                                                </span>
                                                <span class="input-required">*</span>
                                            </label>
                                        </span><!--
                                        --><span class="input-right">

                                            <span class="input-select input-select-keysmall">
                                                <select name="region" id="question2"
                                                        class="selectwitthnav2 small border-5 glow-shadow-2"
                                                        tabindex="1" required="required">
                                                    <option value="21" selected="selected">CN</option>
                                                    <option value="22">US</option>
                                                    <option value="23">EU</option>
                                                </select>
                                                <span class="label-text1">-</span>
                                                <span class="input-text input-text-litsmall input-littext">
                                                    <input type="text" id="authcodeA2" contenteditable='true'
                                                           onkeyup="if(this.value.length===4){notJumpWhenFocus=true;document.getElementById('authcodeB2').focus();return true}else return false;"
                                                           name="authcodeA2" class="small border-5 glow-shadow-2"
                                                           autocomplete="off" maxlength="4" tabindex="1"
                                                           onfocus="this.select();"
                                                           required="required" placeholder="1-4"></span>
                                                <span class="label-text1">-</span>
                                                <span class="input-text input-text-litsmall input-littext">
                                                    <input type="text" id="authcodeB2" contenteditable='true'
                                                           onkeydown="notJumpWhenFocus=false;if(this.value.length===0 && event.keyCode===8){document.getElementById('authcodeA2').focus();return true}"
                                                           onkeyup="if(this.value.length===4){if(notJumpWhenFocus){notJumpWhenFocus=false;return;}document.getElementById('authcodeC2').focus();return true}else return false;"
                                                           name="authcodeB2" class="small border-5 glow-shadow-2"
                                                           autocomplete="off" maxlength="4" tabindex="1"
                                                           onfocus="this.select()"
                                                           required="required" placeholder="5-8"></span>
                                                <span class="label-text1">-</span>
                                                <span class="input-text input-text-litsmall input-littext">
                                                    <input type="text" id="authcodeC2" contenteditable='true'
                                                           onfocus="this.select();"
                                                           onkeydown="notJumpWhenFocus=false;if(this.value.length===0 && event.keyCode===8){document.getElementById('authcodeB2').focus();return true}"
                                                           name="authcodeC2" class="small border-5 glow-shadow-2"
                                                           autocomplete="off" maxlength="4" tabindex="1"
                                                           required="required" placeholder="9-12"></span>
                                                <span id="checkkey2" class="checkseries"></span>
                                            </span>
                                        </span>
                                    </div>
                                    <div class="input-row input-row-note question-info" id="question-info"
                                         style="display: none;">
                                        <span id="question1-message" class="inline-message no-text-clear">这是您安全令的序列号，请核对输入，横线不用填。</span>
                                    </div>


                                    <div class="input-row input-row-text">
                                        <span class="input-left">
                                            <label for="key2">
                                                <span class="label-text">
                                                    密钥：
                                                </span>
                                                <span class="input-required">*</span>
                                            </label>
                                        </span><!--
                                        --><span class="input-right">
                                            <span class="input-text input-text-keysmall">
                                                <input type="text" name="authkey" value=""
                                                       onfocus="if(authkeyerrorid==1){this.value='';authkeyerrorid==0;document.getElementById('creation-submit2').disabled='';}"
                                                       onblur="checkkey(this,2);" id="key2"
                                                       class="small border-5 glow-shadow-2 keysmall" autocomplete="off"
                                                       maxlength="40" tabindex="1" required="required"
                                                       placeholder="40个字符长度的密钥，大小写请随意">
                                                <span id="checkuserkey2" class="checkauthkey"></span>
                                                <span class="inline-message " id="firstname-message"> </span>
                                            </span>
                                        </span>
                                    </div>

                                    <div class="input-row input-row-radiopic">
                                        <span class="input-left">
                                            <label for="radiobutton2">
                                                <span class="label-text">
                                                    安全令图片：
                                                </span>
                                                <span class="input-required">*</span>
                                            </label>
                                        </span><!--
                                        --><span class="input-right input-picradio">
                                            <span class="radioandpic">
                                                <img class="spanradioimg" src="resources/img/wow-32.png">
                                                <input id="radiobutton2" name="selectpic" type="radio" value="1"
                                                       checked="true"/>
                                            </span>
                                            <span class="radioandpic">
                                                <img class="spanradioimg" src="resources/img/s2-32.png">
                                                <input id="radiobutton2" name="selectpic" type="radio" value="2"/>
                                            </span>
                                            <span class="radioandpic">
                                                <img class="spanradioimg" src="resources/img/d3-32.png">
                                                <input id="radiobutton2" name="selectpic" type="radio" value="3"/>
                                            </span>
                                            <span class="radioandpic">
                                                <img class="spanradioimg" src="resources/img/pegasus-32.png">
                                                <input id="radiobutton" name="selectpic" type="radio" value="4"/>
                                            </span>
                                            <span class="radioandpic">
                                                <img class="spanradioimg" src="resources/img/heroes-32.png">
                                                <input id="radiobutton" name="selectpic" type="radio" value="5"/>
                                            </span>
                                            <span class="radioandpic">
                                                <img class="spanradioimg" src="resources/img/overwatch-32.png">
                                                <input id="radiobutton" name="selectpic" type="radio" value="6"/>
                                            </span>
                                            <span class="inline-message " id="firstname-message"> </span>
                                        </span>
                                    </div>


                                    <div class="input-row input-row-text">
                                        <span class="input-left">
                                            <label for="letters_code2">
                                                <span class="label-text">
                                                    验证码：
                                                </span>
                                                <span class="input-required">*</span>
                                            </label>
                                        </span>
                                        <span class="input-right">
                                            <div class="imgandreloader">
                                                <div id="captcha-image"><img id="sec-string2" width="200" height="70"
                                                                             onclick="refreshCaptcha(2);document.getElementById('letters_code2').focus();"
                                                                             src="/api/captchaCode?rand=?rand=8&unix={{$captchaCodeUnix}}"
                                                                             alt="换一个" title="换一个"
                                                                             class="border-5"/></div>
                                                <div id="captcha-reloader">
                                                    看不清楚？<br/>
                                                    <a href="javascript:void(0);"
                                                       onclick="refreshCaptcha(2);document.getElementById('letters_code2').focus();">换一个</a>
                                                </div><span class="input-static input-static-extra-large"><span
                                                            class="static"><p><label class="label" for="letters_code2">
                                                                出于安全性考虑，请输入上方图示中的字符。（这并不是您的密码）</label></p></span></span>
                                            </div>
                                            <span class="input-text input-text-small">
                                                <input type="text" name="letters_code" value=""
                                                       onfocus="document.getElementById('creation-submit2').disabled='disabled';"
                                                       onblur="if(!jquerycodechecked2){checkyanzhenma(this.value,2);}"
                                                       id="letters_code2" class="small border-5 glow-shadow-2"
                                                       autocomplete="off" onpaste="return false;" maxlength="6"
                                                       tabindex="1" required="required" placeholder="输入验证码"/>
                                                <span id="checkyanzhenmaajax2" class="checkyanzhenmaajax"></span>
                                                <span class="inline-message " id="emailAddress-message"> </span>
                                            </span>
                                            <span id="remember-me"><label for="persistLogin2">
                                                    <input id="persistLogin2" type="checkbox" name="morenauthset">设置为默认安全令
                                                </label></span>
                                        </span>
                                    </div>


                                    <div class="submit-row">
                                        <div class="input-left"></div>
                                        <div class="input-right">
                                            <button class="ui-button button1"
                                                    onclick="if(checkseries(2))return true;else return false;"
                                                    type="submit" id="creation-submit2" tabindex="1"><span
                                                        class="button-left"><span
                                                            class="button-right">恢复安全令</span></span></button>
                                            <a class="ui-cancel " href="/" tabindex="1">
                                                <span>
                                                    取消
                                                </span>
                                            </a>
                                        </div>
                                    </div>
                                    <input type="hidden" name="agreedToToU" value="true" id="agreedToToU">
                                </form>
                            </div>


                            <div id="game-time-subscriptions" class="content game-time-subscriptions">
                                <form action="/addAuthByRestoreCode" method="post" id="creation"
                                      novalidate="novalidate">
                                    {{csrf_field()}}
                                    <div id="page-header" class="pageheadernav">
                                        <p class="privacy-message">请在第二行中输入安全令序列号(类似于US-1234-5678-9012)，横线不必输入。<br>
                                            请在第三行中输入10位还原码(类似于5S4GVQ2R6B)，还原码请参见<a href="/faq">FAQ</a>
                                        </p>
                                    </div>
                                    <div class="input-row input-row-text">
                                        <span class="input-left">
                                            <label for="firstname3">
                                                <span class="label-text">
                                                    安全令名称：
                                                </span>
                                                <span class="input-required">*</span>
                                            </label>
                                        </span><!--
                                        --><span class="input-right">
                                            <span class="input-text input-text-small">
                                                <input type="text" name="authname" value="" id="firstname3"
                                                       onfocus="if(authnameerrorid==1){this.value='';authnameerrorid==0;document.getElementById('creation-submit3').disabled='';}"
                                                       onblur="checkname(this,3);" class="small border-5 glow-shadow-2"
                                                       autocomplete="off" maxlength="12" tabindex="1"
                                                       required="required" placeholder="小于等于12个字符">
                                                <span id="checkusernameajax3" class="checkusernameajax"></span>
                                                <span class="inline-message " id="firstname-message"> </span>
                                            </span>
                                        </span>
                                    </div>

                                    <div class="input-row input-row-select">
                                        <span class="input-left">
                                            <label for="question3">
                                                <span class="label-text">
                                                    请选择暴雪服务器地区：
                                                </span>
                                                <span class="input-required">*</span>
                                            </label>
                                        </span><!--
                                        --><span class="input-right">

                                            <span class="input-select input-select-keysmall">
                                                <select name="region" id="question3"
                                                        class="selectwitthnav2 small border-5 glow-shadow-2"
                                                        tabindex="1" required="required">
                                                    <option value="21" selected="selected">CN</option>
                                                    <option value="22">US</option>
                                                    <option value="23">EU</option>
                                                </select>
                                                <span class="label-text1">-</span>
                                                <span class="input-text input-text-litsmall input-littext"><input
                                                            contenteditable='true'
                                                            type="text" id="authcodeA3"
                                                            onkeyup="if(this.value.length==4){notJumpWhenFocus=true;document.getElementById('authcodeB3').focus();return true}else return false;"
                                                            name="authcodeA3" class="small border-5 glow-shadow-2"
                                                            autocomplete="off" maxlength="4" tabindex="1"
                                                            onfocus="this.select();"
                                                            required="required" placeholder="1-4"></span>
                                                <span class="label-text1">-</span>
                                                <span class="input-text input-text-litsmall input-littext"><input
                                                            contenteditable='true'
                                                            type="text" id="authcodeB3"
                                                            onkeydown="notJumpWhenFocus=false;if(this.value.length==0 && event.keyCode==8){document.getElementById('authcodeA3').focus();return true}"
                                                            onkeyup="if(this.value.length==4){if(notJumpWhenFocus){notJumpWhenFocus=false;return;}document.getElementById('authcodeC3').focus();return true}else return false;"
                                                            name="authcodeB3" class="small border-5 glow-shadow-2"
                                                            autocomplete="off" maxlength="4" tabindex="1"
                                                            onfocus="this.select()"
                                                            required="required" placeholder="5-8"></span>
                                                <span class="label-text1">-</span>
                                                <span class="input-text input-text-litsmall input-littext"> <input
                                                            contenteditable='true'
                                                            type="text" id="authcodeC3"
                                                            onkeydown="notJumpWhenFocus=false;if(this.value.length==0 && event.keyCode==8){document.getElementById('authcodeB3').focus();return true}"
                                                            name="authcodeC3" class="small border-5 glow-shadow-2"
                                                            autocomplete="off" maxlength="4" tabindex="1"
                                                            onfocus="this.select();"
                                                            required="required" placeholder="9-12"></span>
                                                <span id="checkkey3" class="checkseries"></span>
                                            </span>
                                        </span>
                                    </div>
                                    <div class="input-row input-row-note question-info" id="question-info"
                                         style="display: none;">
                                        <span id="question1-message" class="inline-message no-text-clear">这是您安全令的序列号，请核对输入，横线不用填。</span>
                                    </div>


                                    <div class="input-row input-row-text">
                                        <span class="input-left">
                                            <label for="key3">
                                                <span class="label-text">
                                                    还原码：
                                                </span>
                                                <span class="input-required">*</span>
                                            </label>
                                        </span><!--
                                        --><span class="input-right">
                                            <span class="input-text input-text-small">
                                                <input type="text" name="authrestore" value=""
                                                       onfocus="if(authrestoreerrorid==1){this.value='';authrestoreerrorid==0;document.getElementById('creation-submit3').disabled='';}"
                                                       onblur="checkrestore(this,3);" id="key3"
                                                       class="small border-5 glow-shadow-2" autocomplete="off"
                                                       maxlength="10" tabindex="1" required="required"
                                                       placeholder="10个字符长度的密钥，大小写请随意">
                                                <span id="checkrestore3" class="checkyanzhenmaajax"></span>
                                                <span class="inline-message " id="firstname-message"> </span>
                                            </span>
                                        </span>
                                    </div>

                                    <div class="input-row input-row-radiopic">
                                        <span class="input-left">
                                            <label for="radiobutton3">
                                                <span class="label-text">
                                                    安全令图片：
                                                </span>
                                                <span class="input-required">*</span>
                                            </label>
                                        </span><!--
                                        --><span class="input-right input-picradio">
                                            <span class="radioandpic">
                                                <img class="spanradioimg" src="resources/img/wow-32.png">
                                                <input id="radiobutton3" name="selectpic" type="radio" value="1"
                                                       checked="true"/>
                                            </span>
                                            <span class="radioandpic">
                                                <img class="spanradioimg" src="resources/img/s2-32.png">
                                                <input id="radiobutton3" name="selectpic" type="radio" value="2"/>
                                            </span>
                                            <span class="radioandpic">
                                                <img class="spanradioimg" src="resources/img/d3-32.png">
                                                <input id="radiobutton3" name="selectpic" type="radio" value="3"/>
                                            </span>
                                            <span class="radioandpic">
                                                <img class="spanradioimg" src="resources/img/pegasus-32.png">
                                                <input id="radiobutton" name="selectpic" type="radio" value="4"/>
                                            </span>
                                            <span class="radioandpic">
                                                <img class="spanradioimg" src="resources/img/heroes-32.png">
                                                <input id="radiobutton" name="selectpic" type="radio" value="5"/>
                                            </span>
                                            <span class="radioandpic">
                                                <img class="spanradioimg" src="resources/img/overwatch-32.png">
                                                <input id="radiobutton" name="selectpic" type="radio" value="6"/>
                                            </span>
                                            <span class="inline-message " id="firstname-message"> </span>
                                        </span>
                                    </div>


                                    <div class="input-row input-row-text">
                                        <span class="input-left">
                                            <label for="letters_code3">
                                                <span class="label-text">
                                                    验证码：
                                                </span>
                                                <span class="input-required">*</span>
                                            </label>
                                        </span>
                                        <span class="input-right">
                                            <div class="imgandreloader">
                                                <div id="captcha-image"><img id="sec-string3" width="200" height="70"
                                                                             onclick="refreshCaptcha(3);document.getElementById('letters_code3').focus();"
                                                                             src="/api/captchaCode?rand=?rand=8&unix={{$captchaCodeUnix}}"
                                                                             alt="换一个" title="换一个"
                                                                             class="border-5"/></div>
                                                <div id="captcha-reloader">
                                                    看不清楚？<br/>
                                                    <a href="javascript:void(0);"
                                                       onclick="refreshCaptcha(3);document.getElementById('letters_code3').focus();">换一个</a>
                                                </div><span class="input-static input-static-extra-large"><span
                                                            class="static"><p><label class="label" for="letters_code3">
                                                                出于安全性考虑，请输入上方图示中的字符。（这并不是您的密码）</label></p></span></span>
                                            </div>
                                            <span class="input-text input-text-small">
                                                <input type="text" name="letters_code" value=""
                                                       onfocus="document.getElementById('creation-submit3').disabled='disabled';"
                                                       onblur="if(!jquerycodechecked3){checkyanzhenma(this.value,3);}"
                                                       id="letters_code3" class="small border-5 glow-shadow-2"
                                                       autocomplete="off" onpaste="return false;" maxlength="6"
                                                       tabindex="1" required="required" placeholder="输入验证码"/>
                                                <span id="checkyanzhenmaajax3" class="checkyanzhenmaajax"></span>
                                                <span class="inline-message " id="emailAddress-message"> </span>
                                            </span>
                                            <span id="remember-me"><label for="persistLogin3">
                                                    <input id="persistLogin3" type="checkbox" name="morenauthset">设置为默认安全令
                                                </label></span>
                                        </span>
                                    </div>


                                    <div class="submit-row">
                                        <div class="input-left"></div>
                                        <div class="input-right">
                                            <button class="ui-button button1" onclick="return checkseries(3);"
                                                    type="submit" id="creation-submit3" tabindex="1"><span
                                                        class="button-left"><span
                                                            class="button-right">恢复安全令</span></span></button>
                                            <a class="ui-cancel" href="/" tabindex="1">
                                                <span>
                                                    取消 </span>
                                            </a>
                                        </div>
                                    </div>
                                    <input type="hidden" name="agreedToToU" value="true" id="agreedToToU">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
