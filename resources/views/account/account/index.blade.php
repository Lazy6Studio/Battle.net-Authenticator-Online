@include('universal.header.header_normal')
@include('account.account.source.JsAndCss')
@include('universal.top.layoutTop')
<div id="layout-middle">
    <div id="homewrapper">
        <div id="content">
            <div id="lobby">
                <div id="page-content" class="page-content">

                    @include('account.account.page.LeftUser')
                    @include('account.account.page.CenterAndRright')

                </div>
            </div>
        </div>
    </div>
</div>
@include('universal.footer.bottom')