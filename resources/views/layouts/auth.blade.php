<!DOCTYPE html>
<html>
@include('partials.htmlheader')
<body class="hold-transition @stack('body_class')">
	@include('partials.flash')

	@yield('content')

	<script src="{{ asset('/js/all.js') }}" type="text/javascript"></script>
	@stack('scripts')
</body>
</html>