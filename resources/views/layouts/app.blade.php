<!DOCTYPE html>
<html>
@include('partials.htmlheader')
<!--
SKINS: skin-blue,skin-black,skin-purple,skin-yellow,skin-red,skin-green
LAYOUT OPTIONS: fixed,layout-boxed,layout-top-nav,sidebar-collapse,sidebar-mini
-->
<body class="hold-transition skin-blue sidebar-mini @stack('body_class')">
    <div class="wrapper">
        @include('partials.mainheader')

        @include('partials.sidebar')
        <!-- Content Wrapper -->
        <div class="content-wrapper">
            @include('partials.contentheader')

            @include('partials.flash')
            <!-- Main content -->
            <section class="content">
                @yield('content')
            </section><!-- /.content -->
        </div><!-- /.content-wrapper -->

        @include('partials.footer')
    </div><!-- ./wrapper -->
    <script src="{{ asset('/js/all.js') }}" type="text/javascript"></script>
    @stack('scripts')
    <script>
        $(function() {
            $('input[type=checkbox]').iCheck({
                checkboxClass: 'icheckbox_minimal-blue',
                radioClass: 'iradio_minimal-blue'
            });
        });
    </script>
</body>
</html>
