<html>
<head>
    <title>{{ trans('errors.email.title') }}</title>
</head>

<body>
<h3>[{{ $code }}] {{ $error }}</h3>
<pre>{{ $file }}: {{ $line }}
{{ $traces }}
</pre>
<h3>{{ trans('errors.email.server') }}</h3>
<pre>{{ print_r($_SERVER, true) }}</pre>
<h3>{{ trans('errors.email.request') }}</h3>
<pre>{{ print_r($_REQUEST, true) }}</pre>
</body>
</html>
