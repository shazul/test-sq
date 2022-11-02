<!DOCTYPE html><html><head><meta charset="utf-8"><title>Soprema's API</title><link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css"><style>@import url('https://fonts.googleapis.com/css?family=Roboto:400,700|Inconsolata|Raleway:200');@import url('https://fonts.googleapis.com/css?family=Roboto:100,400,700|Source+Code+Pro');.hljs-comment,.hljs-title{color:#8e908c}.hljs-variable,.hljs-attribute,.hljs-tag,.hljs-regexp,.ruby .hljs-constant,.xml .hljs-tag .hljs-title,.xml .hljs-pi,.xml .hljs-doctype,.html .hljs-doctype,.css .hljs-id,.css .hljs-class,.css .hljs-pseudo{color:#c82829}.hljs-number,.hljs-preprocessor,.hljs-pragma,.hljs-built_in,.hljs-literal,.hljs-params,.hljs-constant{color:#f5871f}.ruby .hljs-class .hljs-title,.css .hljs-rules .hljs-attribute{color:#eab700}.hljs-string,.hljs-value,.hljs-inheritance,.hljs-header,.ruby .hljs-symbol,.xml .hljs-cdata{color:#718c00}.css .hljs-hexcolor{color:#3e999f}.hljs-function,.python .hljs-decorator,.python .hljs-title,.ruby .hljs-function .hljs-title,.ruby .hljs-title .hljs-keyword,.perl .hljs-sub,.javascript .hljs-title,.coffeescript .hljs-title{color:#4271ae}.hljs-keyword,.javascript .hljs-function{color:#8959a8}.hljs{display:block;background:white;color:#4d4d4c;padding:.5em}.coffeescript .javascript,.javascript .xml,.tex .hljs-formula,.xml .javascript,.xml .vbscript,.xml .css,.xml .hljs-cdata{opacity:.5}.right .hljs-comment{color:#969896}.right .css .hljs-class,.right .css .hljs-id,.right .css .hljs-pseudo,.right .hljs-attribute,.right .hljs-regexp,.right .hljs-tag,.right .hljs-variable,.right .html .hljs-doctype,.right .ruby .hljs-constant,.right .xml .hljs-doctype,.right .xml .hljs-pi,.right .xml .hljs-tag .hljs-title{color:#c66}.right .hljs-built_in,.right .hljs-constant,.right .hljs-literal,.right .hljs-number,.right .hljs-params,.right .hljs-pragma,.right .hljs-preprocessor{color:#de935f}.right .css .hljs-rule .hljs-attribute,.right .ruby .hljs-class .hljs-title{color:#f0c674}.right .hljs-header,.right .hljs-inheritance,.right .hljs-name,.right .hljs-string,.right .hljs-value,.right .ruby .hljs-symbol,.right .xml .hljs-cdata{color:#b5bd68}.right .css .hljs-hexcolor,.right .hljs-title{color:#8abeb7}.right .coffeescript .hljs-title,.right .hljs-function,.right .javascript .hljs-title,.right .perl .hljs-sub,.right .python .hljs-decorator,.right .python .hljs-title,.right .ruby .hljs-function .hljs-title,.right .ruby .hljs-title .hljs-keyword{color:#81a2be}.right .hljs-keyword,.right .javascript .hljs-function{color:#b294bb}.right .hljs{display:block;overflow-x:auto;background:#1d1f21;color:#c5c8c6;padding:.5em;-webkit-text-size-adjust:none}.right .coffeescript .javascript,.right .javascript .xml,.right .tex .hljs-formula,.right .xml .css,.right .xml .hljs-cdata,.right .xml .javascript,.right .xml .vbscript{opacity:.5}.hljs-comment{color:#969896}.css .hljs-class,.css .hljs-id,.css .hljs-pseudo,.hljs-attribute,.hljs-regexp,.hljs-tag,.hljs-variable,.html .hljs-doctype,.ruby .hljs-constant,.xml .hljs-doctype,.xml .hljs-pi,.xml .hljs-tag .hljs-title{color:#77A619}.hljs-literal{color:#A69819}.hljs-built_in,.hljs-constant,.hljs-number,.hljs-params,.hljs-pragma,.hljs-preprocessor{color:#1B88B3}.css .hljs-rule .hljs-attribute,.ruby .hljs-class .hljs-title{color:#A37518}.hljs-header,.hljs-inheritance,.hljs-name,.hljs-string,.hljs-value,.ruby .hljs-symbol,.xml .hljs-cdata{color:inherit}.coffeescript .hljs-title,.css .hljs-hexcolor,.hljs-function,.hljs-title,.javascript .hljs-title,.perl .hljs-sub,.python .hljs-decorator,.python .hljs-title,.ruby .hljs-function .hljs-title,.ruby .hljs-title .hljs-keyword{color:#A63A4A}.hljs-keyword,.javascript .hljs-function{color:#A69819}.hljs{display:block;overflow-x:auto;background:#1d1f21;color:#c5c8c6;padding:.5em;-webkit-text-size-adjust:none}.coffeescript .javascript,.javascript .xml,.tex .hljs-formula,.xml .css,.xml .hljs-cdata,.xml .javascript,.xml .vbscript{opacity:.5}.right .hljs-comment{color:#969896}.right .css .hljs-class,.right .css .hljs-id,.right .css .hljs-pseudo,.right .hljs-attribute,.right .hljs-regexp,.right .hljs-tag,.right .hljs-variable,.right .html .hljs-doctype,.right .ruby .hljs-constant,.right .xml .hljs-doctype,.right .xml .hljs-pi,.right .xml .hljs-tag .hljs-title{color:#C1EF65}.right .hljs-literal{color:#EBDE68}.right .hljs-built_in,.right .hljs-constant,.right .hljs-number,.right .hljs-params,.right .hljs-pragma,.right .hljs-preprocessor{color:#77BCD7}.right .css .hljs-rule .hljs-attribute,.right .ruby .hljs-class .hljs-title{color:#f0c674}.right .hljs-header,.right .hljs-inheritance,.right .hljs-name,.right .hljs-string,.right .hljs-value,.right .ruby .hljs-symbol,.right .xml .hljs-cdata{color:inherit}.right .coffeescript .hljs-title,.right .css .hljs-hexcolor,.right .hljs-function,.right .hljs-title,.right .javascript .hljs-title,.right .perl .hljs-sub,.right .python .hljs-decorator,.right .python .hljs-title,.right .ruby .hljs-function .hljs-title,.right .ruby .hljs-title .hljs-keyword{color:#f099a6}.right .hljs-keyword,.right .javascript .hljs-function{color:#EBDE68}.right .hljs{display:block;overflow-x:auto;background:#1d1f21;color:#c5c8c6;padding:.5em;-webkit-text-size-adjust:none}.right .coffeescript .javascript,.right .javascript .xml,.right .tex .hljs-formula,.right .xml .css,.right .xml .hljs-cdata,.right .xml .javascript,.right .xml .vbscript{opacity:.5}body{color:#4c555a;background:white;font:400 14px / 1.42 'Roboto',Helvetica,sans-serif}header{border-bottom:1px solid transparent;margin-bottom:12px}h1,h2,h3,h4,h5{color:#292e31;margin:12px 0}h1 .permalink,h2 .permalink,h3 .permalink,h4 .permalink,h5 .permalink{margin-left:0;opacity:0;transition:opacity .25s ease}h1:hover .permalink,h2:hover .permalink,h3:hover .permalink,h4:hover .permalink,h5:hover .permalink{opacity:1}.triple h1 .permalink,.triple h2 .permalink,.triple h3 .permalink,.triple h4 .permalink,.triple h5 .permalink{opacity:.15}.triple h1:hover .permalink,.triple h2:hover .permalink,.triple h3:hover .permalink,.triple h4:hover .permalink,.triple h5:hover .permalink{opacity:.15}h1{font:100 36px 'Roboto',Helvetica,sans-serif;font-size:36px}h2{font:100 36px 'Roboto',Helvetica,sans-serif;font-size:30px}h3{font-size:100%;text-transform:uppercase}h5{font-size:100%;font-weight:normal}p{margin:0 0 10px}p.choices{line-height:1.6}a{color:#0099e5;text-decoration:none}li p{margin:0}hr.split{border:0;height:1px;width:100%;padding-left:6px;margin:12px auto;background-image:linear-gradient(to right, rgba(76,85,90,0) 20%, rgba(76,85,90,0.2) 48%, rgba(221,228,232,0.2) 48%, rgba(221,228,232,0) 80%)}dl dt{float:left;width:130px;clear:left;text-align:right;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-weight:700}dl dd{margin-left:150px}blockquote{color:rgba(76,85,90,0.5);font-size:15.5px;padding:10px 20px;margin:12px 0;border-left:5px solid #e8e8e8}blockquote p:last-child{margin-bottom:0}pre{background-color:#f5f5f5;padding:12px;border:1px solid #cfcfcf;border-radius:3px;overflow:auto}pre code{color:#4c555a;background-color:transparent;padding:0;border:none}code{color:#b93d6a;background-color:#f5f5f5;font:13px / 19.5px 'Source Code Pro',Menlo,monospace;padding:1px 4px;border:1px solid #cfcfcf;border-radius:3px}ul,ol{padding-left:2em}table{border-collapse:collapse;border-spacing:0;margin-bottom:12px}table tr:nth-child(2n){background-color:#fafafa}table th,table td{padding:6px 12px;border:1px solid #e6e6e6}.text-muted{opacity:.5}.note,.warning{padding:.3em 1em;margin:1em 0;border-radius:2px;font-size:90%}.note h1,.warning h1,.note h2,.warning h2,.note h3,.warning h3,.note h4,.warning h4,.note h5,.warning h5,.note h6,.warning h6{font-family:100 36px 'Roboto',Helvetica,sans-serif;font-size:135%;font-weight:500}.note p,.warning p{margin:.5em 0}.note{color:#4c555a;background-color:#ebf7fd;border-left:4px solid #0099e5}.note h1,.note h2,.note h3,.note h4,.note h5,.note h6{color:#0099e5}.warning{color:#4c555a;background-color:#faf0f4;border-left:4px solid #B82E5F}.warning h1,.warning h2,.warning h3,.warning h4,.warning h5,.warning h6{color:#B82E5F}header{margin-top:24px}nav{position:fixed;top:24px;bottom:0;overflow-y:auto}nav .resource-group{padding:0}nav .resource-group .heading{position:relative}nav .resource-group .heading .chevron{position:absolute;top:12px;right:12px;opacity:.5}nav .resource-group .heading a{display:block;color:#4c555a;opacity:.7;border-left:2px solid transparent;margin:0}nav .resource-group .heading a:hover{text-decoration:underline;background-color:transparent;border-left:2px solid transparent}nav ul{list-style-type:none;padding-left:0}nav ul a{display:block;font-size:13px;color:rgba(76,85,90,0.7);padding:8px 12px;border-top:1px solid transparent;border-left:2px solid transparent;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}nav ul a:hover{text-decoration:underline;background-color:transparent;border-left:2px solid transparent}nav ul>li{margin:0}nav ul>li:first-child{margin-top:-12px}nav ul>li:last-child{margin-bottom:-12px}nav ul ul a{padding-left:24px}nav ul ul li{margin:0}nav ul ul li:first-child{margin-top:0}nav ul ul li:last-child{margin-bottom:0}nav>div>div>ul>li:first-child>a{border-top:none}.preload *{transition:none !important}.pull-left{float:left}.pull-right{float:right}.badge{display:inline-block;float:right;min-width:10px;min-height:14px;padding:3px 7px;font-size:12px;color:black;background-color:transparent;border-radius:10px;margin:-2px -8px -2px 0}.badge.get{color:#fff;background-color:#ddf1fc}.badge.head{color:#fff;background-color:#ddf1fc}.badge.options{color:#fff;background-color:#ddf1fc}.badge.put{color:#fff;background-color:#f7f2c3}.badge.patch{color:#fff;background-color:#f7f2c3}.badge.post{color:#fff;background-color:#e4f2c8}.badge.delete{color:#fff;background-color:#f2d8e1}.collapse-button{float:right}.collapse-button .close{display:none;color:#0099e5;cursor:pointer}.collapse-button .open{color:#0099e5;cursor:pointer}.collapse-button.show .close{display:inline}.collapse-button.show .open{display:none}.collapse-content{max-height:0;overflow:hidden;transition:max-height .3s ease-in-out}nav{width:220px}.container{max-width:940px;margin-left:auto;margin-right:auto}.container .row .content{margin-left:244px;width:696px}.container .row:after{content:'';display:block;clear:both}.container-fluid nav{width:22%}.container-fluid .row .content{margin-left:24%}.container-fluid.triple nav{width:15%;padding-right:1px}.container-fluid.triple .row .content{position:relative;margin-left:15%;padding-left:24px}.middle:before,.middle:after{content:'';display:table}.middle:after{clear:both}.middle{box-sizing:border-box;width:48%;padding-right:12px}.right{box-sizing:border-box;float:right;width:52%;padding-left:12px}.right a{color:#0099e5}.right h1,.right h2,.right h3,.right h4,.right h5,.right p,.right div{color:#dde4e8}.right pre{background-color:#272B2D;border:1px solid #272B2D}.right pre code{color:#D0D0D0}.right .description{margin-top:12px}.triple .resource-heading{font-size:125%}.definition{margin-top:12px;margin-bottom:12px}.definition .method{font-weight:bold}.definition .method.get{color:#2e8ab8}.definition .method.head{color:#2e8ab8}.definition .method.options{color:#2e8ab8}.definition .method.post{color:#8ab82e}.definition .method.put{color:#b8aa2e}.definition .method.patch{color:#b8aa2e}.definition .method.delete{color:#b82e5f}.definition .uri{word-break:break-all;word-wrap:break-word}.definition .hostname{opacity:.5}.example-names{background-color:#eee;padding:12px;border-radius:3px}.example-names .tab-button{cursor:pointer;color:#4c555a;border:1px solid #ddd;padding:6px;margin-left:12px}.example-names .tab-button.active{background-color:#d5d5d5}.right .example-names{background-color:#424648}.right .example-names .tab-button{color:#dde4e8;border:1px solid #6C6F71;border-radius:3px}.right .example-names .tab-button.active{background-color:#5a6063}#nav-background{position:fixed;left:0;top:0;bottom:0;width:15%;padding-right:14.4px;background-color:#fafcfc;border-right:1px solid #f0f4f7;z-index:-1}#right-panel-background{position:absolute;right:-12px;top:-12px;bottom:-12px;width:52%;background-color:#2d3134;z-index:-1}@media (max-width:1200px){nav{width:198px}.container{max-width:840px}.container .row .content{margin-left:224px;width:606px}}@media (max-width:992px){nav{width:169.4px}.container{max-width:720px}.container .row .content{margin-left:194px;width:526px}}@media (max-width:768px){nav{display:none}.container{width:95%;max-width:none}.container .row .content,.container-fluid .row .content,.container-fluid.triple .row .content{margin-left:auto;margin-right:auto;width:95%}#nav-background{display:none}#right-panel-background{width:52%}}.back-to-top{position:fixed;z-index:1;bottom:0;right:24px;padding:4px 8px;color:rgba(76,85,90,0.5);background-color:transparent;text-decoration:none !important;border-top:1px solid transparent;border-left:1px solid transparent;border-right:1px solid transparent;border-top-left-radius:3px;border-top-right-radius:3px}.resource-group{padding:12px 12px 12px 0;margin-bottom:12px;background-color:transparent;border:1px solid transparent;border-radius:3px}.resource-group h2.group-heading,.resource-group .heading a{padding:12px 12px 12px 0;margin:0 0 12px 0;background-color:transparent;border-bottom:1px solid transparent;border-top-left-radius:3px;border-top-right-radius:3px;white-space:nowrap;text-overflow:ellipsis;overflow:hidden}.triple .content .resource-group{padding:0;border:none}.triple .content .resource-group h2.group-heading,.triple .content .resource-group .heading a{margin:0 0 12px 0;border:1px solid transparent}nav .resource-group .heading a{padding:12px;margin-bottom:0}nav .resource-group .collapse-content{padding:0}.action{margin-bottom:12px;padding:12px 12px 0 12px;overflow:hidden;border:1px solid transparent;border-radius:3px}.action h4.action-heading{padding:12px;margin:-12px -12px 12px -12px;border-bottom:1px solid transparent;border-top-left-radius:3px;border-top-right-radius:3px;white-space:nowrap;text-overflow:ellipsis;overflow:hidden}.action h4.action-heading .name{float:right;font-weight:normal}.action h4.action-heading .method{padding:6px 12px;margin-right:12px;border-radius:2px}.action h4.action-heading .method.get{color:#fff;background-color:#0099e5}.action h4.action-heading .method.head{color:#fff;background-color:#0099e5}.action h4.action-heading .method.options{color:#fff;background-color:#0099e5}.action h4.action-heading .method.put{color:#fff;background-color:#b1a74e}.action h4.action-heading .method.patch{color:#fff;background-color:#b1a74e}.action h4.action-heading .method.post{color:#fff;background-color:#85a546}.action h4.action-heading .method.delete{color:#fff;background-color:#c14a74}.action h4.action-heading code{color:#444;background-color:#f5f5f5;border-color:#cfcfcf;font-weight:normal}.action dl.inner{padding-bottom:2px}.action .title{border-bottom:1px solid transparent;margin:0 -12px -1px -12px;padding:12px}.action.get{border-color:#ddf1fc}.action.get h4.action-heading{color:#0099e5;background:#ddf1fc;border-bottom-color:#ddf1fc}.action.head{border-color:#ddf1fc}.action.head h4.action-heading{color:#0099e5;background:#ddf1fc;border-bottom-color:#ddf1fc}.action.options{border-color:#ddf1fc}.action.options h4.action-heading{color:#0099e5;background:#ddf1fc;border-bottom-color:#ddf1fc}.action.post{border-color:#e4f2c8}.action.post h4.action-heading{color:#85a546;background:#e4f2c8;border-bottom-color:#e4f2c8}.action.put{border-color:#f7f2c3}.action.put h4.action-heading{color:#b1a74e;background:#f7f2c3;border-bottom-color:#f7f2c3}.action.patch{border-color:#f7f2c3}.action.patch h4.action-heading{color:#b1a74e;background:#f7f2c3;border-bottom-color:#f7f2c3}.action.delete{border-color:#f2d8e1}.action.delete h4.action-heading{color:#c14a74;background:#f2d8e1;border-bottom-color:#f2d8e1}</style></head><body class="preload"><div id="nav-background"></div><div class="container-fluid triple"><div class="row"><nav><div class="resource-group"><div class="heading"><div class="chevron"><i class="open fa fa-angle-down"></i></div><a href="#top">Overview</a></div><div class="collapse-content"><ul><li><a href="#header-request-requirements">Request requirements</a></li><li><a href="#header-rate-limit">Rate Limit</a></li></ul></div></div><div class="resource-group"><div class="heading"><div class="chevron"><i class="open fa fa-angle-down"></i></div><a href="#companies">Companies</a></div><div class="collapse-content"><ul><li><a href="#companies-companies">Companies</a><ul><li><a href="#companies-companies-get"><span class="badge get"><i class="fa fa-arrow-down"></i></span>Get companies</a></li><li><a href="#companies-companies-get-1"><span class="badge get"><i class="fa fa-arrow-down"></i></span>Get company</a></li><li><a href="#companies-companies-get-2"><span class="badge get"><i class="fa fa-arrow-down"></i></span>Get company's products</a></li><li><a href="#companies-companies-get-3"><span class="badge get"><i class="fa fa-arrow-down"></i></span>Get company's systems</a></li><li><a href="#companies-companies-get-4"><span class="badge get"><i class="fa fa-arrow-down"></i></span>Get company's details</a></li><li><a href="#companies-companies-get-5"><span class="badge get"><i class="fa fa-arrow-down"></i></span>Get company's specifications</a></li><li><a href="#companies-companies-get-6"><span class="badge get"><i class="fa fa-arrow-down"></i></span>Get company's technical bulletins</a></li><li><a href="#companies-companies-get-7"><span class="badge get"><i class="fa fa-arrow-down"></i></span>Get company's languages</a></li><li><a href="#companies-companies-get-8"><span class="badge get"><i class="fa fa-arrow-down"></i></span>Get company's medias</a></li><li><a href="#companies-companies-get-9"><span class="badge get"><i class="fa fa-arrow-down"></i></span>Get media's products</a></li></ul></li></ul></div></div><div class="resource-group"><div class="heading"><div class="chevron"><i class="open fa fa-angle-down"></i></div><a href="#products">Products</a></div><div class="collapse-content"><ul><li><a href="#products-products">Products</a><ul><li><a href="#products-products-get"><span class="badge get"><i class="fa fa-arrow-down"></i></span>Get products</a></li><li><a href="#products-products-get-1"><span class="badge get"><i class="fa fa-arrow-down"></i></span>Get product</a></li><li><a href="#products-products-get-2"><span class="badge get"><i class="fa fa-arrow-down"></i></span>Get product's children</a></li></ul></li></ul></div></div><div class="resource-group"><div class="heading"><div class="chevron"><i class="open fa fa-angle-down"></i></div><a href="#systems">Systems</a></div><div class="collapse-content"><ul><li><a href="#systems-systems">Systems</a><ul><li><a href="#systems-systems-get"><span class="badge get"><i class="fa fa-arrow-down"></i></span>Get systems</a></li><li><a href="#systems-systems-get-1"><span class="badge get"><i class="fa fa-arrow-down"></i></span>Get system</a></li></ul></li></ul></div></div><div class="resource-group"><div class="heading"><div class="chevron"><i class="open fa fa-angle-down"></i></div><a href="#details">Details</a></div><div class="collapse-content"><ul><li><a href="#details-details">Details</a><ul><li><a href="#details-details-get"><span class="badge get"><i class="fa fa-arrow-down"></i></span>Get details</a></li><li><a href="#details-details-get-1"><span class="badge get"><i class="fa fa-arrow-down"></i></span>Get detail</a></li></ul></li></ul></div></div><div class="resource-group"><div class="heading"><div class="chevron"><i class="open fa fa-angle-down"></i></div><a href="#specifications">Specifications</a></div><div class="collapse-content"><ul><li><a href="#specifications-specifications">Specifications</a><ul><li><a href="#specifications-specifications-get"><span class="badge get"><i class="fa fa-arrow-down"></i></span>Get specifications</a></li><li><a href="#specifications-specifications-get-1"><span class="badge get"><i class="fa fa-arrow-down"></i></span>Get a specification</a></li></ul></li></ul></div></div><div class="resource-group"><div class="heading"><div class="chevron"><i class="open fa fa-angle-down"></i></div><a href="#technical-bulletins">Technical Bulletins</a></div><div class="collapse-content"><ul><li><a href="#technical-bulletins-technical-bulletins">Technical Bulletins</a><ul><li><a href="#technical-bulletins-technical-bulletins-get"><span class="badge get"><i class="fa fa-arrow-down"></i></span>Get technical-bulletins</a></li><li><a href="#technical-bulletins-technical-bulletins-get-1"><span class="badge get"><i class="fa fa-arrow-down"></i></span>Get a technical bulletin</a></li></ul></li></ul></div></div><div class="resource-group"><div class="heading"><div class="chevron"><i class="open fa fa-angle-down"></i></div><a href="#languages">Languages</a></div><div class="collapse-content"><ul><li><a href="#languages-languages-get"><span class="badge get"><i class="fa fa-arrow-down"></i></span>Get languages</a></li></ul></div></div><p style="text-align: center; word-wrap: break-word;"><a href="{{ route('api.blueprint') }}">{{ route('api.blueprint') }}</a></p></nav><div class="content"><div id="right-panel-background"></div><div class="middle"><header><h1 id="top">Soprema's API</h1></header></div><div class="right"><h5>API Endpoint</h5><a href="{{ route('api.blueprint') }}">{{ route('api.blueprint') }}</a></div><div class="middle"><p>This API offer an access in read-only to the companies, products and all related information.</p><p>The login route is {{ route('api.auth') }} and takes 2 param in the body: email and password.<br>This route will return the json web token to be used in all requests.</p>
<div class="note">
<h2 id="header-request-requirements">Request requirements <a class="permalink" href="#header-request-requirements" aria-hidden="true">¶</a></h2>
<p>Every request must contains the following headers:</p>
<pre><code class="language-http"><span class="hljs-attribute">Accept</span>: <span class="hljs-string">application/vnd.soprema.v1+json</span>
<span class="hljs-attribute">Authorization</span>: <span class="hljs-string">Bearer JSON_WEB_TOKEN</span></code></pre>
    To obtain the JSON_WEB_TOKEN send a POST request to <a href="{{ route('api.auth') }}">{{ route('api.auth') }}</a> with a body containing:
    <pre><code class="language-http"><span class="hljs-attribute">email</span>: <span class="hljs-string">your-soprema-email@soprema.ca</span>
<span class="hljs-attribute">password</span>: <span class="hljs-string">your-password-here</span></code></pre>
    Upon authentication you will receive your JSON web token.
</div>
<div class="warning">
<h2 id="header-rate-limit">Rate Limit <a class="permalink" href="#header-rate-limit" aria-hidden="true">¶</a></h2>
<p>The API has a rate limit of <strong>60</strong> requests per minute.</p>
</div>
</div><div class="middle"><section id="companies" class="resource-group"><h2 class="group-heading">Companies <a href="#companies" class="permalink">&para;</a></h2></section></div><div class="middle"><div id="companies-companies" class="resource"><h3 class="resource-heading">Companies <a href="#companies-companies" class="permalink">&para;</a></h3></div></div><div class="right"><div class="definition"><span class="method get">GET</span>&nbsp;<span class="uri"><span class="hostname">{{ route('api.blueprint') }}</span>/companies?<span class="hljs-attribute">page=</span><span class="hljs-literal">4</span>&<span class="hljs-attribute">per_page=</span><span class="hljs-literal">2</span>&<span class="hljs-attribute">lang=</span><span class="hljs-literal">fr,en</span></span></div><div class="tabs"><div class="example-names"><span>Requests</span><span class="tab-button">example 1</span><span class="tab-button">example 2</span><span class="tab-button">example 3</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Accept</span>: <span class="hljs-string">application/vnd.soprema.v1+json</span><br><span class="hljs-attribute">Authorization</span>: <span class="hljs-string">Bearer JSON_WEB_TOKEN</span></code></pre><div style="height: 1px;"></div></div></div><div class="tabs"><div class="example-names"><span>Responses</span><span class="tab-button">200</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span><br><span class="hljs-attribute">Link</span>: <span class="hljs-string">&lt;{{ route('api.companies') }}?page=1&gt;; rel="first", &lt;{{ route('api.companies') }}?page=3&gt;; rel="prev", &lt;{{ route('api.companies') }}?page=5&gt;; rel="next", &lt;{{ route('api.companies') }}?page=501&gt;; rel="last"</span><br><span class="hljs-attribute">X-RateLimit-Limit</span>: <span class="hljs-string">60</span><br><span class="hljs-attribute">X-RateLimit-Remaining</span>: <span class="hljs-string">59</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">data</span>": <span class="hljs-value">[
    {
      "<span class="hljs-attribute">id</span>": <span class="hljs-value"><span class="hljs-number">7</span></span>,
      "<span class="hljs-attribute">name</span>": <span class="hljs-value"><span class="hljs-string">"Torp-Denesik"</span></span>,
      "<span class="hljs-attribute">language</span>": <span class="hljs-value">{
        "<span class="hljs-attribute">name</span>": <span class="hljs-value"><span class="hljs-string">"English (Canada)"</span></span>,
        "<span class="hljs-attribute">code</span>": <span class="hljs-value"><span class="hljs-string">"en"</span>
      </span>}
    </span>},
    {
      "<span class="hljs-attribute">id</span>": <span class="hljs-value"><span class="hljs-number">8</span></span>,
      "<span class="hljs-attribute">name</span>": <span class="hljs-value"><span class="hljs-string">"Bogan-Pollich"</span></span>,
      "<span class="hljs-attribute">language</span>": <span class="hljs-value">{
        "<span class="hljs-attribute">name</span>": <span class="hljs-value"><span class="hljs-string">"Français (Canada)"</span></span>,
        "<span class="hljs-attribute">code</span>": <span class="hljs-value"><span class="hljs-string">"fr"</span>
      </span>}
    </span>}
  ]</span>,
  "<span class="hljs-attribute">meta</span>": <span class="hljs-value">{
    "<span class="hljs-attribute">pagination</span>": <span class="hljs-value">{
      "<span class="hljs-attribute">first</span>": <span class="hljs-value"><span class="hljs-string">"{{ route('api.companies') }}?page=1"</span></span>,
      "<span class="hljs-attribute">prev</span>": <span class="hljs-value"><span class="hljs-string">"{{ route('api.companies') }}?page=3"</span></span>,
      "<span class="hljs-attribute">next</span>": <span class="hljs-value"><span class="hljs-string">"{{ route('api.companies') }}?page=5"</span></span>,
      "<span class="hljs-attribute">last</span>": <span class="hljs-value"><span class="hljs-string">"{{ route('api.companies') }}?page=501"</span>
    </span>}
  </span>}
</span>}</code></pre><div style="height: 1px;"></div></div></div></div></div></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Accept</span>: <span class="hljs-string">application/vnd.soprema.v1+json</span><br><span class="hljs-attribute">Authorization</span>: <span class="hljs-string">Bearer BAD_JSON_WEB_TOKEN</span></code></pre><div style="height: 1px;"></div></div></div><div class="tabs"><div class="example-names"><span>Responses</span><span class="tab-button">403</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">status</span>": <span class="hljs-value"><span class="hljs-string">"forbidden"</span></span>,
  "<span class="hljs-attribute">message</span>": <span class="hljs-value"><span class="hljs-string">"You do not have access for the resource."</span>
</span>}</code></pre><div style="height: 1px;"></div></div></div></div></div></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Authorization</span>: <span class="hljs-string">Bearer JSON_WEB_TOKEN</span></code></pre><div style="height: 1px;"></div></div></div><div class="tabs"><div class="example-names"><span>Responses</span><span class="tab-button">400</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">status</span>": <span class="hljs-value"><span class="hljs-string">"bad-request"</span></span>,
  "<span class="hljs-attribute">message</span>": <span class="hljs-value"><span class="hljs-string">"Missing version header."</span>
</span>}</code></pre><div style="height: 1px;"></div></div></div></div></div></div></div></div><div class="middle"><div id="companies-companies-get" class="action get"><h4 class="action-heading"><div class="name">Get companies</div><a href="#companies-companies-get" class="method get">GET</a><code class="uri">/companies{?page,per_page,lang}</code></h4><p>Get a list of companies</p>
<div class="title"><strong>URI Parameters</strong><div class="collapse-button show"><span class="close">Hide</span><span class="open">Show</span></div></div><div class="collapse-content"><dl class="inner"><dt>page</dt><dd><code>integer</code>&nbsp;<span>(optional)</span>&nbsp;<span class="text-muted example"><strong>Example:&nbsp;</strong><span>4</span></span><p>Page to display</p>
</dd><dt>per_page</dt><dd><code>integer</code>&nbsp;<span>(optional)</span>&nbsp;<span class="text-info default"><strong>Default:&nbsp;</strong><span>15</span></span>&nbsp;<span class="text-muted example"><strong>Example:&nbsp;</strong><span>2</span></span><p>Elements to display per page</p>
</dd><dt>lang</dt><dd><code>array</code>&nbsp;<span>(optional)</span>&nbsp;<span class="text-muted example"><strong>Example:&nbsp;</strong><span>fr,en</span></span><p>One or more languages of the elements to fetch</p>
</dd></dl></div></div></div><hr class="split"><div class="right"><div class="definition"><span class="method get">GET</span>&nbsp;<span class="uri"><span class="hostname">{{ route('api.blueprint') }}</span>/companies/<span class="hljs-attribute" title="company">7</span>?<span class="hljs-attribute">lang=</span><span class="hljs-literal">fr,en</span></span></div><div class="tabs"><div class="example-names"><span>Requests</span><span class="tab-button">example 1</span><span class="tab-button">example 2</span><span class="tab-button">example 3</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Accept</span>: <span class="hljs-string">application/vnd.soprema.v1+json</span><br><span class="hljs-attribute">Authorization</span>: <span class="hljs-string">Bearer JSON_WEB_TOKEN</span></code></pre><div style="height: 1px;"></div></div></div><div class="tabs"><div class="example-names"><span>Responses</span><span class="tab-button">200</span><span class="tab-button">404</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span><br><span class="hljs-attribute">Link</span>: <span class="hljs-string">&lt;{{ route('api.companies') }}&gt;; rel="companies"</span><br><span class="hljs-attribute">X-RateLimit-Limit</span>: <span class="hljs-string">60</span><br><span class="hljs-attribute">X-RateLimit-Remaining</span>: <span class="hljs-string">59</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">data</span>": <span class="hljs-value">{
    "<span class="hljs-attribute">id</span>": <span class="hljs-value"><span class="hljs-number">7</span></span>,
    "<span class="hljs-attribute">name</span>": <span class="hljs-value"><span class="hljs-string">"Torp-Denesik"</span></span>,
    "<span class="hljs-attribute">language</span>": <span class="hljs-value">{
      "<span class="hljs-attribute">name</span>": <span class="hljs-value"><span class="hljs-string">"English (Canada)"</span></span>,
      "<span class="hljs-attribute">code</span>": <span class="hljs-value"><span class="hljs-string">"en"</span>
    </span>}
  </span>}</span>,
  "<span class="hljs-attribute">meta</span>": <span class="hljs-value">{
    "<span class="hljs-attribute">links</span>": <span class="hljs-value">{
      "<span class="hljs-attribute">companies</span>": <span class="hljs-value"><span class="hljs-string">"{{ route('api.companies') }}"</span>
    </span>}
  </span>}
</span>}</code></pre><div style="height: 1px;"></div></div></div></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">status</span>": <span class="hljs-value"><span class="hljs-string">"not-found"</span></span>,
  "<span class="hljs-attribute">message</span>": <span class="hljs-value"><span class="hljs-string">"The resource could not found."</span>
</span>}</code></pre><div style="height: 1px;"></div></div></div></div></div></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Accept</span>: <span class="hljs-string">application/vnd.soprema.v1+json</span><br><span class="hljs-attribute">Authorization</span>: <span class="hljs-string">Bearer BAD_JSON_WEB_TOKEN</span></code></pre><div style="height: 1px;"></div></div></div><div class="tabs"><div class="example-names"><span>Responses</span><span class="tab-button">403</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">status</span>": <span class="hljs-value"><span class="hljs-string">"forbidden"</span></span>,
  "<span class="hljs-attribute">message</span>": <span class="hljs-value"><span class="hljs-string">"You do not have access for the resource."</span>
</span>}</code></pre><div style="height: 1px;"></div></div></div></div></div></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Authorization</span>: <span class="hljs-string">Bearer JSON_WEB_TOKEN</span></code></pre><div style="height: 1px;"></div></div></div><div class="tabs"><div class="example-names"><span>Responses</span><span class="tab-button">400</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">status</span>": <span class="hljs-value"><span class="hljs-string">"bad-request"</span></span>,
  "<span class="hljs-attribute">message</span>": <span class="hljs-value"><span class="hljs-string">"Missing version header."</span>
</span>}</code></pre><div style="height: 1px;"></div></div></div></div></div></div></div></div><div class="middle"><div id="companies-companies-get-1" class="action get"><h4 class="action-heading"><div class="name">Get company</div><a href="#companies-companies-get-1" class="method get">GET</a><code class="uri">/companies/{company}{?lang}</code></h4><p>Get a company</p>
<div class="title"><strong>URI Parameters</strong><div class="collapse-button show"><span class="close">Hide</span><span class="open">Show</span></div></div><div class="collapse-content"><dl class="inner"><dt>company</dt><dd><code>integer</code>&nbsp;<span class="required">(required)</span>&nbsp;<span class="text-muted example"><strong>Example:&nbsp;</strong><span>7</span></span><p>Id of the company to fetch.</p>
</dd><dt>lang</dt><dd><code>array</code>&nbsp;<span>(optional)</span>&nbsp;<span class="text-muted example"><strong>Example:&nbsp;</strong><span>fr,en</span></span><p>One or more languages of the elements to fetch</p>
</dd></dl></div></div></div><hr class="split"><div class="right"><div class="definition"><span class="method get">GET</span>&nbsp;<span class="uri"><span class="hostname">{{ route('api.blueprint') }}</span>/companies/<span class="hljs-attribute" title="company">1</span>/products?<span class="hljs-attribute">page=</span><span class="hljs-literal">4</span>&<span class="hljs-attribute">per_page=</span><span class="hljs-literal">2</span>&<span class="hljs-attribute">lang=</span><span class="hljs-literal">fr,en</span></span></div><div class="tabs"><div class="example-names"><span>Requests</span><span class="tab-button">example 1</span><span class="tab-button">example 2</span><span class="tab-button">example 3</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Accept</span>: <span class="hljs-string">application/vnd.soprema.v1+json</span><br><span class="hljs-attribute">Authorization</span>: <span class="hljs-string">Bearer JSON_WEB_TOKEN</span></code></pre><div style="height: 1px;"></div></div></div><div class="tabs"><div class="example-names"><span>Responses</span><span class="tab-button">200</span><span class="tab-button">404</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span><br><span class="hljs-attribute">Link</span>: <span class="hljs-string">&lt;{{ route('api.company.products', 1) }}&gt;; rel="company", &lt;{{ route('api.company.products', 1) }}?page=1&gt;; rel="first", &lt;{{ route('api.company.products', 1) }}?page=3&gt;; rel="prev", &lt;{{ route('api.company.products', 1) }}?page=5&gt;; rel="next", &lt;{{ route('api.company.products', 1) }}?page=500&gt;; rel="last"</span><br><span class="hljs-attribute">X-RateLimit-Limit</span>: <span class="hljs-string">60</span><br><span class="hljs-attribute">X-RateLimit-Remaining</span>: <span class="hljs-string">59</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">data</span>": <span class="hljs-value">[
    {
      "<span class="hljs-attribute">id</span>": <span class="hljs-value"><span class="hljs-number">7</span></span>,
      "<span class="hljs-attribute">company_id</span>": <span class="hljs-value"><span class="hljs-number">1</span></span>,
      "<span class="hljs-attribute">product_name</span>": <span class="hljs-value">[
        {
          "<span class="hljs-attribute">fr</span>": <span class="hljs-value"><span class="hljs-string">"tempore dolores veniam"</span></span>,
          "<span class="hljs-attribute">en</span>": <span class="hljs-value"><span class="hljs-string">"vel possimus nulla"</span>
        </span>}
      ]
    </span>},
    {
      "<span class="hljs-attribute">id</span>": <span class="hljs-value"><span class="hljs-number">8</span></span>,
      "<span class="hljs-attribute">company_id</span>": <span class="hljs-value"><span class="hljs-number">1</span></span>,
      "<span class="hljs-attribute">product_name</span>": <span class="hljs-value">[
        {
          "<span class="hljs-attribute">fr</span>": <span class="hljs-value"><span class="hljs-string">"laborum saepe earum"</span></span>,
          "<span class="hljs-attribute">en</span>": <span class="hljs-value"><span class="hljs-string">"distinctio sed atque"</span>
        </span>}
      ]
    </span>}
  ]</span>,
  "<span class="hljs-attribute">meta</span>": <span class="hljs-value">{
    "<span class="hljs-attribute">links</span>": <span class="hljs-value">{
      "<span class="hljs-attribute">company</span>": <span class="hljs-value"><span class="hljs-string">"{{ route('api.company.products', 1) }}"</span>
    </span>}</span>,
    "<span class="hljs-attribute">pagination</span>": <span class="hljs-value">{
      "<span class="hljs-attribute">first</span>": <span class="hljs-value"><span class="hljs-string">"{{ route('api.company.products', 1) }}?page=1"</span></span>,
      "<span class="hljs-attribute">prev</span>": <span class="hljs-value"><span class="hljs-string">"{{ route('api.company.products', 1) }}?page=3"</span></span>,
      "<span class="hljs-attribute">next</span>": <span class="hljs-value"><span class="hljs-string">"{{ route('api.company.products', 1) }}?page=5"</span></span>,
      "<span class="hljs-attribute">last</span>": <span class="hljs-value"><span class="hljs-string">"{{ route('api.company.products', 1) }}?page=500"</span>
    </span>}
  </span>}
</span>}</code></pre><div style="height: 1px;"></div></div></div></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">status</span>": <span class="hljs-value"><span class="hljs-string">"not-found"</span></span>,
  "<span class="hljs-attribute">message</span>": <span class="hljs-value"><span class="hljs-string">"The resource could not found."</span>
</span>}</code></pre><div style="height: 1px;"></div></div></div></div></div></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Accept</span>: <span class="hljs-string">application/vnd.soprema.v1+json</span><br><span class="hljs-attribute">Authorization</span>: <span class="hljs-string">Bearer BAD_JSON_WEB_TOKEN</span></code></pre><div style="height: 1px;"></div></div></div><div class="tabs"><div class="example-names"><span>Responses</span><span class="tab-button">403</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">status</span>": <span class="hljs-value"><span class="hljs-string">"forbidden"</span></span>,
  "<span class="hljs-attribute">message</span>": <span class="hljs-value"><span class="hljs-string">"You do not have access for the resource."</span>
</span>}</code></pre><div style="height: 1px;"></div></div></div></div></div></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Authorization</span>: <span class="hljs-string">Bearer JSON_WEB_TOKEN</span></code></pre><div style="height: 1px;"></div></div></div><div class="tabs"><div class="example-names"><span>Responses</span><span class="tab-button">400</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">status</span>": <span class="hljs-value"><span class="hljs-string">"bad-request"</span></span>,
  "<span class="hljs-attribute">message</span>": <span class="hljs-value"><span class="hljs-string">"Missing version header."</span>
</span>}</code></pre><div style="height: 1px;"></div></div></div></div></div></div></div></div><div class="middle"><div id="companies-companies-get-2" class="action get"><h4 class="action-heading"><div class="name">Get company's products</div><a href="#companies-companies-get-2" class="method get">GET</a><code class="uri">/companies/{company}/products{?page,per_page,lang}</code></h4><p>Get all company’s products</p>
<div class="title"><strong>URI Parameters</strong><div class="collapse-button show"><span class="close">Hide</span><span class="open">Show</span></div></div><div class="collapse-content"><dl class="inner"><dt>company</dt><dd><code>integer</code>&nbsp;<span class="required">(required)</span>&nbsp;<span class="text-muted example"><strong>Example:&nbsp;</strong><span>1</span></span><p>Id of the company</p>
</dd><dt>page</dt><dd><code>integer</code>&nbsp;<span>(optional)</span>&nbsp;<span class="text-muted example"><strong>Example:&nbsp;</strong><span>4</span></span><p>Page to display</p>
</dd><dt>per_page</dt><dd><code>integer</code>&nbsp;<span>(optional)</span>&nbsp;<span class="text-info default"><strong>Default:&nbsp;</strong><span>15</span></span>&nbsp;<span class="text-muted example"><strong>Example:&nbsp;</strong><span>2</span></span><p>Elements to display per page</p>
</dd><dt>lang</dt><dd><code>array</code>&nbsp;<span>(optional)</span>&nbsp;<span class="text-muted example"><strong>Example:&nbsp;</strong><span>fr,en</span></span><p>One or more languages of the elements to fetch</p>
</dd></dl></div></div></div><hr class="split"><div class="right"><div class="definition"><span class="method get">GET</span>&nbsp;<span class="uri"><span class="hostname">{{ route('api.blueprint') }}</span>/companies/<span class="hljs-attribute" title="company">1</span>/systems?<span class="hljs-attribute">page=</span><span class="hljs-literal">4</span>&<span class="hljs-attribute">per_page=</span><span class="hljs-literal">2</span>&<span class="hljs-attribute">lang=</span><span class="hljs-literal">fr,en</span></span></div><div class="tabs"><div class="example-names"><span>Requests</span><span class="tab-button">example 1</span><span class="tab-button">example 2</span><span class="tab-button">example 3</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Accept</span>: <span class="hljs-string">application/vnd.soprema.v1+json</span><br><span class="hljs-attribute">Authorization</span>: <span class="hljs-string">Bearer JSON_WEB_TOKEN</span></code></pre><div style="height: 1px;"></div></div></div><div class="tabs"><div class="example-names"><span>Responses</span><span class="tab-button">200</span><span class="tab-button">404</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span><br><span class="hljs-attribute">Link</span>: <span class="hljs-string">&lt;{{ route('api.companies', 1) }}&gt;; rel="company", &lt;{{ route('api.company.systems', 1) }}?page=1&gt;; rel="first", &lt;{{ route('api.company.systems', 1) }}?page=3&gt;; rel="prev", &lt;{{ route('api.company.systems', 1) }}?page=5&gt;; rel="next", &lt;{{ route('api.company.systems', 1) }}?page=500&gt;; rel="last"</span><br><span class="hljs-attribute">X-RateLimit-Limit</span>: <span class="hljs-string">60</span><br><span class="hljs-attribute">X-RateLimit-Remaining</span>: <span class="hljs-string">59</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">data</span>": <span class="hljs-value">[
    {
      "<span class="hljs-attribute">id</span>": <span class="hljs-value"><span class="hljs-number">7</span></span>,
      "<span class="hljs-attribute">company_id</span>": <span class="hljs-value"><span class="hljs-number">1</span></span>,
      "<span class="hljs-attribute">system_name</span>": <span class="hljs-value">[
        {
          "<span class="hljs-attribute">fr</span>": <span class="hljs-value"><span class="hljs-string">"tempore deleniti corrupti"</span></span>,
          "<span class="hljs-attribute">en</span>": <span class="hljs-value"><span class="hljs-string">"repellat non perspiciatis"</span>
        </span>}
      ]
    </span>},
    {
      "<span class="hljs-attribute">id</span>": <span class="hljs-value"><span class="hljs-number">8</span></span>,
      "<span class="hljs-attribute">company_id</span>": <span class="hljs-value"><span class="hljs-number">1</span></span>,
      "<span class="hljs-attribute">system_name</span>": <span class="hljs-value">[
        {
          "<span class="hljs-attribute">fr</span>": <span class="hljs-value"><span class="hljs-string">"facere est autem"</span></span>,
          "<span class="hljs-attribute">en</span>": <span class="hljs-value"><span class="hljs-string">"quis error deserunt"</span>
        </span>}
      ]
    </span>}
  ]</span>,
  "<span class="hljs-attribute">meta</span>": <span class="hljs-value">{
    "<span class="hljs-attribute">links</span>": <span class="hljs-value">{
      "<span class="hljs-attribute">company</span>": <span class="hljs-value"><span class="hljs-string">"{{ route('api.companies', 1) }}"</span>
    </span>}</span>,
    "<span class="hljs-attribute">pagination</span>": <span class="hljs-value">{
      "<span class="hljs-attribute">first</span>": <span class="hljs-value"><span class="hljs-string">"{{ route('api.company.systems', 1) }}?page=1"</span></span>,
      "<span class="hljs-attribute">prev</span>": <span class="hljs-value"><span class="hljs-string">"{{ route('api.company.systems', 1) }}?page=3"</span></span>,
      "<span class="hljs-attribute">next</span>": <span class="hljs-value"><span class="hljs-string">"{{ route('api.company.systems', 1) }}?page=5"</span></span>,
      "<span class="hljs-attribute">last</span>": <span class="hljs-value"><span class="hljs-string">"{{ route('api.company.systems', 1) }}?page=500"</span>
    </span>}
  </span>}
</span>}</code></pre><div style="height: 1px;"></div></div></div></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">status</span>": <span class="hljs-value"><span class="hljs-string">"not-found"</span></span>,
  "<span class="hljs-attribute">message</span>": <span class="hljs-value"><span class="hljs-string">"The resource could not found."</span>
</span>}</code></pre><div style="height: 1px;"></div></div></div></div></div></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Accept</span>: <span class="hljs-string">application/vnd.soprema.v1+json</span><br><span class="hljs-attribute">Authorization</span>: <span class="hljs-string">Bearer BAD_JSON_WEB_TOKEN</span></code></pre><div style="height: 1px;"></div></div></div><div class="tabs"><div class="example-names"><span>Responses</span><span class="tab-button">403</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">status</span>": <span class="hljs-value"><span class="hljs-string">"forbidden"</span></span>,
  "<span class="hljs-attribute">message</span>": <span class="hljs-value"><span class="hljs-string">"You do not have access for the resource."</span>
</span>}</code></pre><div style="height: 1px;"></div></div></div></div></div></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Authorization</span>: <span class="hljs-string">Bearer JSON_WEB_TOKEN</span></code></pre><div style="height: 1px;"></div></div></div><div class="tabs"><div class="example-names"><span>Responses</span><span class="tab-button">400</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">status</span>": <span class="hljs-value"><span class="hljs-string">"bad-request"</span></span>,
  "<span class="hljs-attribute">message</span>": <span class="hljs-value"><span class="hljs-string">"Missing version header."</span>
</span>}</code></pre><div style="height: 1px;"></div></div></div></div></div></div></div></div><div class="middle"><div id="companies-companies-get-3" class="action get"><h4 class="action-heading"><div class="name">Get company's systems</div><a href="#companies-companies-get-3" class="method get">GET</a><code class="uri">/companies/{company}/systems{?page,per_page,lang}</code></h4><p>Get all comapny’s systems</p>
<div class="title"><strong>URI Parameters</strong><div class="collapse-button show"><span class="close">Hide</span><span class="open">Show</span></div></div><div class="collapse-content"><dl class="inner"><dt>company</dt><dd><code>integer</code>&nbsp;<span class="required">(required)</span>&nbsp;<span class="text-muted example"><strong>Example:&nbsp;</strong><span>1</span></span><p>Id of the company</p>
</dd><dt>page</dt><dd><code>integer</code>&nbsp;<span>(optional)</span>&nbsp;<span class="text-muted example"><strong>Example:&nbsp;</strong><span>4</span></span><p>Page to display</p>
</dd><dt>per_page</dt><dd><code>integer</code>&nbsp;<span>(optional)</span>&nbsp;<span class="text-info default"><strong>Default:&nbsp;</strong><span>15</span></span>&nbsp;<span class="text-muted example"><strong>Example:&nbsp;</strong><span>2</span></span><p>Elements to display per page</p>
</dd><dt>lang</dt><dd><code>array</code>&nbsp;<span>(optional)</span>&nbsp;<span class="text-muted example"><strong>Example:&nbsp;</strong><span>fr,en</span></span><p>One or more languages of the elements to fetch</p>
</dd></dl></div></div></div><hr class="split"><div class="right"><div class="definition"><span class="method get">GET</span>&nbsp;<span class="uri"><span class="hostname">{{ route('api.blueprint') }}</span>/companies/<span class="hljs-attribute" title="company">1</span>/details?<span class="hljs-attribute">page=</span><span class="hljs-literal">4</span>&<span class="hljs-attribute">per_page=</span><span class="hljs-literal">2</span>&<span class="hljs-attribute">lang=</span><span class="hljs-literal">fr,en</span></span></div><div class="tabs"><div class="example-names"><span>Requests</span><span class="tab-button">example 1</span><span class="tab-button">example 2</span><span class="tab-button">example 3</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Accept</span>: <span class="hljs-string">application/vnd.soprema.v1+json</span><br><span class="hljs-attribute">Authorization</span>: <span class="hljs-string">Bearer JSON_WEB_TOKEN</span></code></pre><div style="height: 1px;"></div></div></div><div class="tabs"><div class="example-names"><span>Responses</span><span class="tab-button">200</span><span class="tab-button">404</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span><br><span class="hljs-attribute">Link</span>: <span class="hljs-string">&lt;{{ route('api.companies', 1) }}&gt;; rel="company", &lt;{{ route('api.company.details', 1) }}?page=1&gt;; rel="first", &lt;{{ route('api.company.details', 1) }}?page=3&gt;; rel="prev", &lt;{{ route('api.company.details', 1) }}?page=5&gt;; rel="next", &lt;{{ route('api.company.details', 1) }}?page=501&gt;; rel="last"</span><br><span class="hljs-attribute">X-RateLimit-Limit</span>: <span class="hljs-string">60</span><br><span class="hljs-attribute">X-RateLimit-Remaining</span>: <span class="hljs-string">59</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">data</span>": <span class="hljs-value">[
    {
      "<span class="hljs-attribute">id</span>": <span class="hljs-value"><span class="hljs-number">7</span></span>,
      "<span class="hljs-attribute">company_id</span>": <span class="hljs-value"><span class="hljs-number">1</span></span>,
      "<span class="hljs-attribute">detail_name</span>": <span class="hljs-value">[
        {
          "<span class="hljs-attribute">fr</span>": <span class="hljs-value"><span class="hljs-string">"voluptate libero vel"</span></span>,
          "<span class="hljs-attribute">en</span>": <span class="hljs-value"><span class="hljs-string">"tenetur alias nam"</span>
        </span>}
      ]
    </span>},
    {
      "<span class="hljs-attribute">id</span>": <span class="hljs-value"><span class="hljs-number">8</span></span>,
      "<span class="hljs-attribute">company_id</span>": <span class="hljs-value"><span class="hljs-number">1</span></span>,
      "<span class="hljs-attribute">detail_name</span>": <span class="hljs-value">[
        {
          "<span class="hljs-attribute">fr</span>": <span class="hljs-value"><span class="hljs-string">"officiis rerum voluptatem"</span></span>,
          "<span class="hljs-attribute">en</span>": <span class="hljs-value"><span class="hljs-string">"perferendis nobis in"</span>
        </span>}
      ]
    </span>}
  ]</span>,
  "<span class="hljs-attribute">meta</span>": <span class="hljs-value">{
    "<span class="hljs-attribute">links</span>": <span class="hljs-value">{
      "<span class="hljs-attribute">company</span>": <span class="hljs-value"><span class="hljs-string">"{{ route('api.companies', 1) }}"</span>
    </span>}</span>,
    "<span class="hljs-attribute">pagination</span>": <span class="hljs-value">{
      "<span class="hljs-attribute">first</span>": <span class="hljs-value"><span class="hljs-string">"{{ route('api.company.details', 1) }}?page=1"</span></span>,
      "<span class="hljs-attribute">prev</span>": <span class="hljs-value"><span class="hljs-string">"{{ route('api.company.details', 1) }}?page=3"</span></span>,
      "<span class="hljs-attribute">next</span>": <span class="hljs-value"><span class="hljs-string">"{{ route('api.company.details', 1) }}?page=5"</span></span>,
      "<span class="hljs-attribute">last</span>": <span class="hljs-value"><span class="hljs-string">"{{ route('api.company.details', 1) }}?page=501"</span>
    </span>}
  </span>}
</span>}</code></pre><div style="height: 1px;"></div></div></div></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">status</span>": <span class="hljs-value"><span class="hljs-string">"not-found"</span></span>,
  "<span class="hljs-attribute">message</span>": <span class="hljs-value"><span class="hljs-string">"The resource could not found."</span>
</span>}</code></pre><div style="height: 1px;"></div></div></div></div></div></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Accept</span>: <span class="hljs-string">application/vnd.soprema.v1+json</span><br><span class="hljs-attribute">Authorization</span>: <span class="hljs-string">Bearer BAD_JSON_WEB_TOKEN</span></code></pre><div style="height: 1px;"></div></div></div><div class="tabs"><div class="example-names"><span>Responses</span><span class="tab-button">403</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">status</span>": <span class="hljs-value"><span class="hljs-string">"forbidden"</span></span>,
  "<span class="hljs-attribute">message</span>": <span class="hljs-value"><span class="hljs-string">"You do not have access for the resource."</span>
</span>}</code></pre><div style="height: 1px;"></div></div></div></div></div></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Authorization</span>: <span class="hljs-string">Bearer JSON_WEB_TOKEN</span></code></pre><div style="height: 1px;"></div></div></div><div class="tabs"><div class="example-names"><span>Responses</span><span class="tab-button">400</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">status</span>": <span class="hljs-value"><span class="hljs-string">"bad-request"</span></span>,
  "<span class="hljs-attribute">message</span>": <span class="hljs-value"><span class="hljs-string">"Missing version header."</span>
</span>}</code></pre><div style="height: 1px;"></div></div></div></div></div></div></div></div><div class="middle"><div id="companies-companies-get-4" class="action get"><h4 class="action-heading"><div class="name">Get company's details</div><a href="#companies-companies-get-4" class="method get">GET</a><code class="uri">/companies/{company}/details{?page,per_page,lang}</code></h4><p>Get all company’s details</p>
<div class="title"><strong>URI Parameters</strong><div class="collapse-button show"><span class="close">Hide</span><span class="open">Show</span></div></div><div class="collapse-content"><dl class="inner"><dt>company</dt><dd><code>integer</code>&nbsp;<span class="required">(required)</span>&nbsp;<span class="text-muted example"><strong>Example:&nbsp;</strong><span>1</span></span><p>Id of the company</p>
</dd><dt>page</dt><dd><code>integer</code>&nbsp;<span>(optional)</span>&nbsp;<span class="text-muted example"><strong>Example:&nbsp;</strong><span>4</span></span><p>Page to display</p>
</dd><dt>per_page</dt><dd><code>integer</code>&nbsp;<span>(optional)</span>&nbsp;<span class="text-info default"><strong>Default:&nbsp;</strong><span>15</span></span>&nbsp;<span class="text-muted example"><strong>Example:&nbsp;</strong><span>2</span></span><p>Elements to display per page</p>
</dd><dt>lang</dt><dd><code>array</code>&nbsp;<span>(optional)</span>&nbsp;<span class="text-muted example"><strong>Example:&nbsp;</strong><span>fr,en</span></span><p>One or more languages of the elements to fetch</p>
</dd></dl></div></div></div><hr class="split"><div class="right"><div class="definition"><span class="method get">GET</span>&nbsp;<span class="uri"><span class="hostname">{{ route('api.blueprint') }}</span>/companies/<span class="hljs-attribute" title="company">1</span>/specifications?<span class="hljs-attribute">page=</span><span class="hljs-literal">4</span>&<span class="hljs-attribute">per_page=</span><span class="hljs-literal">2</span>&<span class="hljs-attribute">lang=</span><span class="hljs-literal">fr,en</span></span></div><div class="tabs"><div class="example-names"><span>Requests</span><span class="tab-button">example 1</span><span class="tab-button">example 2</span><span class="tab-button">example 3</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Accept</span>: <span class="hljs-string">application/vnd.soprema.v1+json</span><br><span class="hljs-attribute">Authorization</span>: <span class="hljs-string">Bearer JSON_WEB_TOKEN</span></code></pre><div style="height: 1px;"></div></div></div><div class="tabs"><div class="example-names"><span>Responses</span><span class="tab-button">200</span><span class="tab-button">404</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span><br><span class="hljs-attribute">Link</span>: <span class="hljs-string">&lt;{{ route('api.companies', 1) }}&gt;; rel="company", &lt;{{ route('api.company.specifications', 1) }}?page=1&gt;; rel="first", &lt;{{ route('api.company.specifications', 1) }}?page=3&gt;; rel="prev", &lt;{{ route('api.company.specifications', 1) }}?page=5&gt;; rel="next", &lt;{{ route('api.company.specifications', 1) }}?page=500&gt;; rel="last"</span><br><span class="hljs-attribute">X-RateLimit-Limit</span>: <span class="hljs-string">60</span><br><span class="hljs-attribute">X-RateLimit-Remaining</span>: <span class="hljs-string">59</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">data</span>": <span class="hljs-value">[
    {
      "<span class="hljs-attribute">id</span>": <span class="hljs-value"><span class="hljs-number">1008</span></span>,
      "<span class="hljs-attribute">company_id</span>": <span class="hljs-value"><span class="hljs-number">1</span></span>,
      "<span class="hljs-attribute">specification_name</span>": <span class="hljs-value">[
        {
          "<span class="hljs-attribute">fr</span>": <span class="hljs-value"><span class="hljs-string">"aut et culpa"</span></span>,
          "<span class="hljs-attribute">en</span>": <span class="hljs-value"><span class="hljs-string">"omnis doloremque est"</span>
        </span>}
      ]
    </span>},
    {
      "<span class="hljs-attribute">id</span>": <span class="hljs-value"><span class="hljs-number">1009</span></span>,
      "<span class="hljs-attribute">company_id</span>": <span class="hljs-value"><span class="hljs-number">1</span></span>,
      "<span class="hljs-attribute">specification_name</span>": <span class="hljs-value">[
        {
          "<span class="hljs-attribute">fr</span>": <span class="hljs-value"><span class="hljs-string">"deserunt quaerat officiis"</span></span>,
          "<span class="hljs-attribute">en</span>": <span class="hljs-value"><span class="hljs-string">"soluta minus quod"</span>
        </span>}
      ]
    </span>}
  ]</span>,
  "<span class="hljs-attribute">meta</span>": <span class="hljs-value">{
    "<span class="hljs-attribute">links</span>": <span class="hljs-value">{
      "<span class="hljs-attribute">company</span>": <span class="hljs-value"><span class="hljs-string">"{{ route('api.companies', 1) }}"</span>
    </span>}</span>,
    "<span class="hljs-attribute">pagination</span>": <span class="hljs-value">{
      "<span class="hljs-attribute">first</span>": <span class="hljs-value"><span class="hljs-string">"{{ route('api.company.specifications', 1) }}?page=1"</span></span>,
      "<span class="hljs-attribute">prev</span>": <span class="hljs-value"><span class="hljs-string">"{{ route('api.company.specifications', 1) }}?page=3"</span></span>,
      "<span class="hljs-attribute">next</span>": <span class="hljs-value"><span class="hljs-string">"{{ route('api.company.specifications', 1) }}?page=5"</span></span>,
      "<span class="hljs-attribute">last</span>": <span class="hljs-value"><span class="hljs-string">"{{ route('api.company.specifications', 1) }}?page=500"</span>
    </span>}
  </span>}
</span>}</code></pre><div style="height: 1px;"></div></div></div></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">status</span>": <span class="hljs-value"><span class="hljs-string">"not-found"</span></span>,
  "<span class="hljs-attribute">message</span>": <span class="hljs-value"><span class="hljs-string">"The resource could not found."</span>
</span>}</code></pre><div style="height: 1px;"></div></div></div></div></div></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Accept</span>: <span class="hljs-string">application/vnd.soprema.v1+json</span><br><span class="hljs-attribute">Authorization</span>: <span class="hljs-string">Bearer BAD_JSON_WEB_TOKEN</span></code></pre><div style="height: 1px;"></div></div></div><div class="tabs"><div class="example-names"><span>Responses</span><span class="tab-button">403</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">status</span>": <span class="hljs-value"><span class="hljs-string">"forbidden"</span></span>,
  "<span class="hljs-attribute">message</span>": <span class="hljs-value"><span class="hljs-string">"You do not have access for the resource."</span>
</span>}</code></pre><div style="height: 1px;"></div></div></div></div></div></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Authorization</span>: <span class="hljs-string">Bearer JSON_WEB_TOKEN</span></code></pre><div style="height: 1px;"></div></div></div><div class="tabs"><div class="example-names"><span>Responses</span><span class="tab-button">400</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">status</span>": <span class="hljs-value"><span class="hljs-string">"bad-request"</span></span>,
  "<span class="hljs-attribute">message</span>": <span class="hljs-value"><span class="hljs-string">"Missing version header."</span>
</span>}</code></pre><div style="height: 1px;"></div></div></div></div></div></div></div></div><div class="middle"><div id="companies-companies-get-5" class="action get"><h4 class="action-heading"><div class="name">Get company's specifications</div><a href="#companies-companies-get-5" class="method get">GET</a><code class="uri">/companies/{company}/specifications{?page,per_page,lang}</code></h4><p>Get all company’s specifications</p>
<div class="title"><strong>URI Parameters</strong><div class="collapse-button show"><span class="close">Hide</span><span class="open">Show</span></div></div><div class="collapse-content"><dl class="inner"><dt>company</dt><dd><code>integer</code>&nbsp;<span class="required">(required)</span>&nbsp;<span class="text-muted example"><strong>Example:&nbsp;</strong><span>1</span></span><p>Id of the company</p>
</dd><dt>page</dt><dd><code>integer</code>&nbsp;<span>(optional)</span>&nbsp;<span class="text-muted example"><strong>Example:&nbsp;</strong><span>4</span></span><p>Page to display</p>
</dd><dt>per_page</dt><dd><code>integer</code>&nbsp;<span>(optional)</span>&nbsp;<span class="text-info default"><strong>Default:&nbsp;</strong><span>15</span></span>&nbsp;<span class="text-muted example"><strong>Example:&nbsp;</strong><span>2</span></span><p>Elements to display per page</p>
</dd><dt>lang</dt><dd><code>array</code>&nbsp;<span>(optional)</span>&nbsp;<span class="text-muted example"><strong>Example:&nbsp;</strong><span>fr,en</span></span><p>One or more languages of the elements to fetch</p>
</dd></dl></div></div></div><hr class="split"><div class="right"><div class="definition"><span class="method get">GET</span>&nbsp;<span class="uri"><span class="hostname">{{ route('api.blueprint') }}</span>/companies/<span class="hljs-attribute" title="company">1</span>/technical-bulletins?<span class="hljs-attribute">page=</span><span class="hljs-literal">4</span>&<span class="hljs-attribute">per_page=</span><span class="hljs-literal">2</span>&<span class="hljs-attribute">lang=</span><span class="hljs-literal">fr,en</span></span></div><div class="tabs"><div class="example-names"><span>Requests</span><span class="tab-button">example 1</span><span class="tab-button">example 2</span><span class="tab-button">example 3</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Accept</span>: <span class="hljs-string">application/vnd.soprema.v1+json</span><br><span class="hljs-attribute">Authorization</span>: <span class="hljs-string">Bearer JSON_WEB_TOKEN</span></code></pre><div style="height: 1px;"></div></div></div><div class="tabs"><div class="example-names"><span>Responses</span><span class="tab-button">200</span><span class="tab-button">404</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span><br><span class="hljs-attribute">Link</span>: <span class="hljs-string">&lt;{{ route('api.companies', 1) }}&gt;; rel="company", &lt;{{ route('api.company.technical-bulletins', 1) }}?page=1&gt;; rel="first", &lt;{{ route('api.company.technical-bulletins', 1) }}?page=3&gt;; rel="prev", &lt;{{ route('api.company.technical-bulletins', 1) }}?page=5&gt;; rel="next", &lt;{{ route('api.company.technical-bulletins', 1) }}?page=500&gt;; rel="last"</span><br><span class="hljs-attribute">X-RateLimit-Limit</span>: <span class="hljs-string">60</span><br><span class="hljs-attribute">X-RateLimit-Remaining</span>: <span class="hljs-string">59</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">data</span>": <span class="hljs-value">[
    {
      "<span class="hljs-attribute">id</span>": <span class="hljs-value"><span class="hljs-number">1008</span></span>,
      "<span class="hljs-attribute">company_id</span>": <span class="hljs-value"><span class="hljs-number">1</span></span>,
      "<span class="hljs-attribute">technical_bulletin_name</span>": <span class="hljs-value">[
        {
          "<span class="hljs-attribute">fr</span>": <span class="hljs-value"><span class="hljs-string">"aut et culpa"</span></span>,
          "<span class="hljs-attribute">en</span>": <span class="hljs-value"><span class="hljs-string">"omnis doloremque est"</span>
        </span>}
      ]
    </span>},
    {
      "<span class="hljs-attribute">id</span>": <span class="hljs-value"><span class="hljs-number">1009</span></span>,
      "<span class="hljs-attribute">company_id</span>": <span class="hljs-value"><span class="hljs-number">1</span></span>,
      "<span class="hljs-attribute">technical_bulletin_name</span>": <span class="hljs-value">[
        {
          "<span class="hljs-attribute">fr</span>": <span class="hljs-value"><span class="hljs-string">"deserunt quaerat officiis"</span></span>,
          "<span class="hljs-attribute">en</span>": <span class="hljs-value"><span class="hljs-string">"soluta minus quod"</span>
        </span>}
      ]
    </span>}
  ]</span>,
  "<span class="hljs-attribute">meta</span>": <span class="hljs-value">{
    "<span class="hljs-attribute">links</span>": <span class="hljs-value">{
      "<span class="hljs-attribute">company</span>": <span class="hljs-value"><span class="hljs-string">"{{ route('api.companies', 1) }}"</span>
    </span>}</span>,
    "<span class="hljs-attribute">pagination</span>": <span class="hljs-value">{
      "<span class="hljs-attribute">first</span>": <span class="hljs-value"><span class="hljs-string">"{{ route('api.company.technical-bulletins', 1) }}?page=1"</span></span>,
      "<span class="hljs-attribute">prev</span>": <span class="hljs-value"><span class="hljs-string">"{{ route('api.company.technical-bulletins', 1) }}?page=3"</span></span>,
      "<span class="hljs-attribute">next</span>": <span class="hljs-value"><span class="hljs-string">"{{ route('api.company.technical-bulletins', 1) }}?page=5"</span></span>,
      "<span class="hljs-attribute">last</span>": <span class="hljs-value"><span class="hljs-string">"{{ route('api.company.technical-bulletins', 1) }}?page=500"</span>
    </span>}
  </span>}
</span>}</code></pre><div style="height: 1px;"></div></div></div></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">status</span>": <span class="hljs-value"><span class="hljs-string">"not-found"</span></span>,
  "<span class="hljs-attribute">message</span>": <span class="hljs-value"><span class="hljs-string">"The resource could not found."</span>
</span>}</code></pre><div style="height: 1px;"></div></div></div></div></div></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Accept</span>: <span class="hljs-string">application/vnd.soprema.v1+json</span><br><span class="hljs-attribute">Authorization</span>: <span class="hljs-string">Bearer BAD_JSON_WEB_TOKEN</span></code></pre><div style="height: 1px;"></div></div></div><div class="tabs"><div class="example-names"><span>Responses</span><span class="tab-button">403</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">status</span>": <span class="hljs-value"><span class="hljs-string">"forbidden"</span></span>,
  "<span class="hljs-attribute">message</span>": <span class="hljs-value"><span class="hljs-string">"You do not have access for the resource."</span>
</span>}</code></pre><div style="height: 1px;"></div></div></div></div></div></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Authorization</span>: <span class="hljs-string">Bearer JSON_WEB_TOKEN</span></code></pre><div style="height: 1px;"></div></div></div><div class="tabs"><div class="example-names"><span>Responses</span><span class="tab-button">400</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">status</span>": <span class="hljs-value"><span class="hljs-string">"bad-request"</span></span>,
  "<span class="hljs-attribute">message</span>": <span class="hljs-value"><span class="hljs-string">"Missing version header."</span>
</span>}</code></pre><div style="height: 1px;"></div></div></div></div></div></div></div></div><div class="middle"><div id="companies-companies-get-6" class="action get"><h4 class="action-heading"><div class="name">Get company's technical bulletins</div><a href="#companies-companies-get-6" class="method get">GET</a><code class="uri">/companies/{company}/technical-bulletins{?page,per_page,lang}</code></h4><p>Get all company’s technical bulletins</p>
<div class="title"><strong>URI Parameters</strong><div class="collapse-button show"><span class="close">Hide</span><span class="open">Show</span></div></div><div class="collapse-content"><dl class="inner"><dt>company</dt><dd><code>integer</code>&nbsp;<span class="required">(required)</span>&nbsp;<span class="text-muted example"><strong>Example:&nbsp;</strong><span>1</span></span><p>Id of the company</p>
</dd><dt>page</dt><dd><code>integer</code>&nbsp;<span>(optional)</span>&nbsp;<span class="text-muted example"><strong>Example:&nbsp;</strong><span>4</span></span><p>Page to display</p>
</dd><dt>per_page</dt><dd><code>integer</code>&nbsp;<span>(optional)</span>&nbsp;<span class="text-info default"><strong>Default:&nbsp;</strong><span>15</span></span>&nbsp;<span class="text-muted example"><strong>Example:&nbsp;</strong><span>2</span></span><p>Elements to display per page</p>
</dd><dt>lang</dt><dd><code>array</code>&nbsp;<span>(optional)</span>&nbsp;<span class="text-muted example"><strong>Example:&nbsp;</strong><span>fr,en</span></span><p>One or more languages of the elements to fetch</p>
</dd></dl></div></div></div><hr class="split"><div class="right"><div class="definition"><span class="method get">GET</span>&nbsp;<span class="uri"><span class="hostname">{{ route('api.blueprint') }}</span>/companies/<span class="hljs-attribute" title="company">1</span>/languages?<span class="hljs-attribute">lang=</span><span class="hljs-literal">fr,en</span></span></div><div class="tabs"><div class="example-names"><span>Requests</span><span class="tab-button">example 1</span><span class="tab-button">example 2</span><span class="tab-button">example 3</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Accept</span>: <span class="hljs-string">application/vnd.soprema.v1+json</span><br><span class="hljs-attribute">Authorization</span>: <span class="hljs-string">Bearer JSON_WEB_TOKEN</span></code></pre><div style="height: 1px;"></div></div></div><div class="tabs"><div class="example-names"><span>Responses</span><span class="tab-button">200</span><span class="tab-button">404</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span><br><span class="hljs-attribute">Link</span>: <span class="hljs-string">&lt;http://pim.soprema.local/api/companies/1&gt;; rel="company"</span><br><span class="hljs-attribute">X-RateLimit-Limit</span>: <span class="hljs-string">60</span><br><span class="hljs-attribute">X-RateLimit-Remaining</span>: <span class="hljs-string">59</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">data</span>": <span class="hljs-value">[
    {
      "<span class="hljs-attribute">name</span>": <span class="hljs-value"><span class="hljs-string">"Français (Canada)"</span></span>,
      "<span class="hljs-attribute">code</span>": <span class="hljs-value"><span class="hljs-string">"fr"</span>
    </span>},
    {
      "<span class="hljs-attribute">name</span>": <span class="hljs-value"><span class="hljs-string">"English (Canada)"</span></span>,
      "<span class="hljs-attribute">code</span>": <span class="hljs-value"><span class="hljs-string">"en"</span>
    </span>}
  ]</span>,
  "<span class="hljs-attribute">meta</span>": <span class="hljs-value">{
    "<span class="hljs-attribute">links</span>": <span class="hljs-value">{
      "<span class="hljs-attribute">company</span>": <span class="hljs-value"><span class="hljs-string">"http://pim.soprema.local/api/companies/1"</span>
    </span>}
  </span>}
</span>}</code></pre><div style="height: 1px;"></div></div></div></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">status</span>": <span class="hljs-value"><span class="hljs-string">"not-found"</span></span>,
  "<span class="hljs-attribute">message</span>": <span class="hljs-value"><span class="hljs-string">"The resource could not found."</span>
</span>}</code></pre><div style="height: 1px;"></div></div></div></div></div></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Accept</span>: <span class="hljs-string">application/vnd.soprema.v1+json</span><br><span class="hljs-attribute">Authorization</span>: <span class="hljs-string">Bearer BAD_JSON_WEB_TOKEN</span></code></pre><div style="height: 1px;"></div></div></div><div class="tabs"><div class="example-names"><span>Responses</span><span class="tab-button">403</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">status</span>": <span class="hljs-value"><span class="hljs-string">"forbidden"</span></span>,
  "<span class="hljs-attribute">message</span>": <span class="hljs-value"><span class="hljs-string">"You do not have access for the resource."</span>
</span>}</code></pre><div style="height: 1px;"></div></div></div></div></div></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Authorization</span>: <span class="hljs-string">Bearer JSON_WEB_TOKEN</span></code></pre><div style="height: 1px;"></div></div></div><div class="tabs"><div class="example-names"><span>Responses</span><span class="tab-button">400</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">status</span>": <span class="hljs-value"><span class="hljs-string">"bad-request"</span></span>,
  "<span class="hljs-attribute">message</span>": <span class="hljs-value"><span class="hljs-string">"Missing version header."</span>
</span>}</code></pre><div style="height: 1px;"></div></div></div></div></div></div></div></div><div class="middle"><div id="companies-companies-get-7" class="action get"><h4 class="action-heading"><div class="name">Get company's languages</div><a href="#companies-companies-get-7" class="method get">GET</a><code class="uri">/companies/{company}/languages{?lang}</code></h4><p>Get all company’s languages</p>
<div class="title"><strong>URI Parameters</strong><div class="collapse-button show"><span class="close">Hide</span><span class="open">Show</span></div></div><div class="collapse-content"><dl class="inner"><dt>company</dt><dd><code>integer</code>&nbsp;<span class="required">(required)</span>&nbsp;<span class="text-muted example"><strong>Example:&nbsp;</strong><span>1</span></span><p>Id of the company</p>
</dd><dt>lang</dt><dd><code>array</code>&nbsp;<span>(optional)</span>&nbsp;<span class="text-muted example"><strong>Example:&nbsp;</strong><span>fr,en</span></span><p>One or more languages of the elements to fetch</p>
</dd></dl></div></div></div><hr class="split"><div class="right"><div class="definition"><span class="method get">GET</span>&nbsp;<span class="uri"><span class="hostname">{{ route('api.blueprint') }}</span>/companies/<span class="hljs-attribute" title="company">1</span>/medias?<span class="hljs-attribute">page=</span><span class="hljs-literal">4</span>&<span class="hljs-attribute">per_page=</span><span class="hljs-literal">2</span>&<span class="hljs-attribute">lang=</span><span class="hljs-literal">fr,en</span></span></div><div class="tabs"><div class="example-names"><span>Requests</span><span class="tab-button">example 1</span><span class="tab-button">example 2</span><span class="tab-button">example 3</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Accept</span>: <span class="hljs-string">application/vnd.soprema.v1+json</span><br><span class="hljs-attribute">Authorization</span>: <span class="hljs-string">Bearer JSON_WEB_TOKEN</span></code></pre><div style="height: 1px;"></div></div></div><div class="tabs"><div class="example-names"><span>Responses</span><span class="tab-button">200</span><span class="tab-button">404</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span><br><span class="hljs-attribute">Link</span>: <span class="hljs-string">&lt;http://pim.soprema.local/api/companies/1&gt;; rel="company"</span><br><span class="hljs-attribute">X-RateLimit-Limit</span>: <span class="hljs-string">60</span><br><span class="hljs-attribute">X-RateLimit-Remaining</span>: <span class="hljs-string">59</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">data</span>": <span class="hljs-value">[
    {
      "<span class="hljs-attribute">id</span>": <span class="hljs-value"><span class="hljs-number">1</span></span>,
      "<span class="hljs-attribute">code</span>": <span class="hljs-value"><span class="hljs-string">"website"</span></span>,
      "<span class="hljs-attribute">name</span>": <span class="hljs-value"><span class="hljs-string">"website"</span>
    </span>}
  ]</span>,
  "<span class="hljs-attribute">meta</span>": <span class="hljs-value">{
    "<span class="hljs-attribute">links</span>": <span class="hljs-value">{
      "<span class="hljs-attribute">company</span>": <span class="hljs-value"><span class="hljs-string">"http://pim.soprema.local/api/companies/1"</span>
    </span>}
  </span>}
</span>}</code></pre><div style="height: 1px;"></div></div></div></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">status</span>": <span class="hljs-value"><span class="hljs-string">"not-found"</span></span>,
  "<span class="hljs-attribute">message</span>": <span class="hljs-value"><span class="hljs-string">"The resource could not found."</span>
</span>}</code></pre><div style="height: 1px;"></div></div></div></div></div></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Accept</span>: <span class="hljs-string">application/vnd.soprema.v1+json</span><br><span class="hljs-attribute">Authorization</span>: <span class="hljs-string">Bearer BAD_JSON_WEB_TOKEN</span></code></pre><div style="height: 1px;"></div></div></div><div class="tabs"><div class="example-names"><span>Responses</span><span class="tab-button">403</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">status</span>": <span class="hljs-value"><span class="hljs-string">"forbidden"</span></span>,
  "<span class="hljs-attribute">message</span>": <span class="hljs-value"><span class="hljs-string">"You do not have access for the resource."</span>
</span>}</code></pre><div style="height: 1px;"></div></div></div></div></div></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Authorization</span>: <span class="hljs-string">Bearer JSON_WEB_TOKEN</span></code></pre><div style="height: 1px;"></div></div></div><div class="tabs"><div class="example-names"><span>Responses</span><span class="tab-button">400</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">status</span>": <span class="hljs-value"><span class="hljs-string">"bad-request"</span></span>,
  "<span class="hljs-attribute">message</span>": <span class="hljs-value"><span class="hljs-string">"Missing version header."</span>
</span>}</code></pre><div style="height: 1px;"></div></div></div></div></div></div></div></div><div class="middle"><div id="companies-companies-get-8" class="action get"><h4 class="action-heading"><div class="name">Get company's medias</div><a href="#companies-companies-get-8" class="method get">GET</a><code class="uri">/companies/{company}/medias{?page,per_page,lang}</code></h4><p>Get all company’s medias</p>
<div class="title"><strong>URI Parameters</strong><div class="collapse-button show"><span class="close">Hide</span><span class="open">Show</span></div></div><div class="collapse-content"><dl class="inner"><dt>company</dt><dd><code>integer</code>&nbsp;<span class="required">(required)</span>&nbsp;<span class="text-muted example"><strong>Example:&nbsp;</strong><span>1</span></span><p>Id of the company</p>
</dd><dt>page</dt><dd><code>integer</code>&nbsp;<span>(optional)</span>&nbsp;<span class="text-muted example"><strong>Example:&nbsp;</strong><span>4</span></span><p>Page to display</p>
</dd><dt>per_page</dt><dd><code>integer</code>&nbsp;<span>(optional)</span>&nbsp;<span class="text-info default"><strong>Default:&nbsp;</strong><span>15</span></span>&nbsp;<span class="text-muted example"><strong>Example:&nbsp;</strong><span>2</span></span><p>Elements to display per page</p>
</dd><dt>lang</dt><dd><code>array</code>&nbsp;<span>(optional)</span>&nbsp;<span class="text-muted example"><strong>Example:&nbsp;</strong><span>fr,en</span></span><p>One or more languages of the elements to fetch</p>
</dd></dl></div></div></div><hr class="split"><div class="right"><div class="definition"><span class="method get">GET</span>&nbsp;<span class="uri"><span class="hostname">{{ route('api.blueprint') }}</span>/companies/<span class="hljs-attribute" title="company">1</span>/medias/<span class="hljs-attribute" title="media">1</span>/products?<span class="hljs-attribute">page=</span><span class="hljs-literal">4</span>&<span class="hljs-attribute">per_page=</span><span class="hljs-literal">2</span>&<span class="hljs-attribute">lang=</span><span class="hljs-literal">fr,en</span></span></div><div class="tabs"><div class="example-names"><span>Requests</span><span class="tab-button">example 1</span><span class="tab-button">example 2</span><span class="tab-button">example 3</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Accept</span>: <span class="hljs-string">application/vnd.soprema.v1+json</span><br><span class="hljs-attribute">Authorization</span>: <span class="hljs-string">Bearer JSON_WEB_TOKEN</span></code></pre><div style="height: 1px;"></div></div></div><div class="tabs"><div class="example-names"><span>Responses</span><span class="tab-button">200</span><span class="tab-button">404</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span><br><span class="hljs-attribute">Link</span>: <span class="hljs-string">&lt;{{ route('api.company.medias', 1) }}&gt;; rel="medias", &lt;{{ route('api.company.media.products', [1, 1]) }}?page=1&gt;; rel="first", &lt;{{ route('api.company.media.products', [1, 1]) }}?page=3&gt;; rel="prev", &lt;{{ route('api.company.media.products', [1, 1]) }}?page=5&gt;; rel="next", &lt;{{ route('api.company.media.products', [1, 1]) }}?page=1000&gt;; rel="last"</span><br><span class="hljs-attribute">X-RateLimit-Limit</span>: <span class="hljs-string">60</span><br><span class="hljs-attribute">X-RateLimit-Remaining</span>: <span class="hljs-string">59</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">data</span>": <span class="hljs-value">[
    {
      "<span class="hljs-attribute">id</span>": <span class="hljs-value"><span class="hljs-number">7</span></span>,
      "<span class="hljs-attribute">company_id</span>": <span class="hljs-value"><span class="hljs-number">1</span></span>,
      "<span class="hljs-attribute">parent_product_id</span>": <span class="hljs-value"><span class="hljs-number">4</span></span>,
      "<span class="hljs-attribute">company_catalog_product_id</span>": <span class="hljs-value"><span class="hljs-number">1</span></span>,
      "<span class="hljs-attribute">product_name</span>": <span class="hljs-value">[
        {
          "<span class="hljs-attribute">fr</span>": <span class="hljs-value"><span class="hljs-string">"voluptas repudiandae laborum"</span></span>,
          "<span class="hljs-attribute">en</span>": <span class="hljs-value"><span class="hljs-string">"animi dolorem explicabo"</span>
        </span>}
      ]</span>,
      "<span class="hljs-attribute">medias</span>": <span class="hljs-value">[
        {
          "<span class="hljs-attribute">id</span>": <span class="hljs-value"><span class="hljs-number">1</span></span>,
          "<span class="hljs-attribute">name</span>": <span class="hljs-value"><span class="hljs-string">"website"</span></span>,
          "<span class="hljs-attribute">code</span>": <span class="hljs-value"><span class="hljs-string">"website"</span>
        </span>}
      ]
    </span>},
    {
      "<span class="hljs-attribute">id</span>": <span class="hljs-value"><span class="hljs-number">8</span></span>,
      "<span class="hljs-attribute">company_id</span>": <span class="hljs-value"><span class="hljs-number">1</span></span>,
      "<span class="hljs-attribute">parent_product_id</span>": <span class="hljs-value"><span class="hljs-number">4</span></span>,
      "<span class="hljs-attribute">company_catalog_product_id</span>": <span class="hljs-value"><span class="hljs-number">1</span></span>,
      "<span class="hljs-attribute">product_name</span>": <span class="hljs-value">[
        {
          "<span class="hljs-attribute">fr</span>": <span class="hljs-value"><span class="hljs-string">"est assumenda omnis"</span></span>,
          "<span class="hljs-attribute">en</span>": <span class="hljs-value"><span class="hljs-string">"laborum ratione dolorem"</span>
        </span>}
      ]</span>,
      "<span class="hljs-attribute">medias</span>": <span class="hljs-value">[
        {
          "<span class="hljs-attribute">id</span>": <span class="hljs-value"><span class="hljs-number">1</span></span>,
          "<span class="hljs-attribute">name</span>": <span class="hljs-value"><span class="hljs-string">"website"</span></span>,
          "<span class="hljs-attribute">code</span>": <span class="hljs-value"><span class="hljs-string">"website"</span>
        </span>}
      ]
    </span>}
  ]</span>,
  "<span class="hljs-attribute">meta</span>": <span class="hljs-value">{
    "<span class="hljs-attribute">links</span>": <span class="hljs-value">{
      "<span class="hljs-attribute">medias</span>": <span class="hljs-value"><span class="hljs-string">"{{ route('api.company.medias', 1) }}"</span>
    </span>}</span>,
    "<span class="hljs-attribute">pagination</span>": <span class="hljs-value">{
      "<span class="hljs-attribute">first</span>": <span class="hljs-value"><span class="hljs-string">"{{ route('api.company.media.products', [1, 1]) }}?page=1"</span></span>,
      "<span class="hljs-attribute">prev</span>": <span class="hljs-value"><span class="hljs-string">"{{ route('api.company.media.products', [1, 1]) }}?page=3"</span></span>,
      "<span class="hljs-attribute">next</span>": <span class="hljs-value"><span class="hljs-string">"{{ route('api.company.media.products', [1, 1]) }}?page=5"</span></span>,
      "<span class="hljs-attribute">last</span>": <span class="hljs-value"><span class="hljs-string">"{{ route('api.company.media.products', [1, 1]) }}?page=1000"</span>
    </span>}
  </span>}
</span>}</code></pre><div style="height: 1px;"></div></div></div></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">status</span>": <span class="hljs-value"><span class="hljs-string">"not-found"</span></span>,
  "<span class="hljs-attribute">message</span>": <span class="hljs-value"><span class="hljs-string">"The resource could not found."</span>
</span>}</code></pre><div style="height: 1px;"></div></div></div></div></div></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Accept</span>: <span class="hljs-string">application/vnd.soprema.v1+json</span><br><span class="hljs-attribute">Authorization</span>: <span class="hljs-string">Bearer BAD_JSON_WEB_TOKEN</span></code></pre><div style="height: 1px;"></div></div></div><div class="tabs"><div class="example-names"><span>Responses</span><span class="tab-button">403</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">status</span>": <span class="hljs-value"><span class="hljs-string">"forbidden"</span></span>,
  "<span class="hljs-attribute">message</span>": <span class="hljs-value"><span class="hljs-string">"You do not have access for the resource."</span>
</span>}</code></pre><div style="height: 1px;"></div></div></div></div></div></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Authorization</span>: <span class="hljs-string">Bearer JSON_WEB_TOKEN</span></code></pre><div style="height: 1px;"></div></div></div><div class="tabs"><div class="example-names"><span>Responses</span><span class="tab-button">400</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">status</span>": <span class="hljs-value"><span class="hljs-string">"bad-request"</span></span>,
  "<span class="hljs-attribute">message</span>": <span class="hljs-value"><span class="hljs-string">"Missing version header."</span>
</span>}</code></pre><div style="height: 1px;"></div></div></div></div></div></div></div></div><div class="middle"><div id="companies-companies-get-9" class="action get"><h4 class="action-heading"><div class="name">Get media's products</div><a href="#companies-companies-get-9" class="method get">GET</a><code class="uri">/companies/{company}/medias/{media}/products{?page,per_page,lang}</code></h4><p>Get all products related to the media in a company</p>
<div class="title"><strong>URI Parameters</strong><div class="collapse-button show"><span class="close">Hide</span><span class="open">Show</span></div></div><div class="collapse-content"><dl class="inner"><dt>company</dt><dd><code>integer</code>&nbsp;<span class="required">(required)</span>&nbsp;<span class="text-muted example"><strong>Example:&nbsp;</strong><span>1</span></span><p>Id of the company</p>
</dd><dt>media</dt><dd><code>integer</code>&nbsp;<span class="required">(required)</span>&nbsp;<span class="text-muted example"><strong>Example:&nbsp;</strong><span>1</span></span><p>Id of the media</p>
</dd><dt>page</dt><dd><code>integer</code>&nbsp;<span>(optional)</span>&nbsp;<span class="text-muted example"><strong>Example:&nbsp;</strong><span>4</span></span><p>Page to display</p>
</dd><dt>per_page</dt><dd><code>integer</code>&nbsp;<span>(optional)</span>&nbsp;<span class="text-info default"><strong>Default:&nbsp;</strong><span>15</span></span>&nbsp;<span class="text-muted example"><strong>Example:&nbsp;</strong><span>2</span></span><p>Elements to display per page</p>
</dd><dt>lang</dt><dd><code>array</code>&nbsp;<span>(optional)</span>&nbsp;<span class="text-muted example"><strong>Example:&nbsp;</strong><span>fr,en</span></span><p>One or more languages of the elements to fetch</p>
</dd></dl></div></div></div><hr class="split"><div class="middle"><section id="products" class="resource-group"><h2 class="group-heading">Products <a href="#products" class="permalink">&para;</a></h2></section></div><div class="middle"><div id="products-products" class="resource"><h3 class="resource-heading">Products <a href="#products-products" class="permalink">&para;</a></h3></div></div><div class="right"><div class="definition"><span class="method get">GET</span>&nbsp;<span class="uri"><span class="hostname">{{ route('api.blueprint') }}</span>/products?<span class="hljs-attribute">page=</span><span class="hljs-literal">4</span>&<span class="hljs-attribute">per_page=</span><span class="hljs-literal">2</span>&<span class="hljs-attribute">lang=</span><span class="hljs-literal">fr,en</span></span></div><div class="tabs"><div class="example-names"><span>Requests</span><span class="tab-button">example 1</span><span class="tab-button">example 2</span><span class="tab-button">example 3</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Accept</span>: <span class="hljs-string">application/vnd.soprema.v1+json</span><br><span class="hljs-attribute">Authorization</span>: <span class="hljs-string">Bearer JSON_WEB_TOKEN</span></code></pre><div style="height: 1px;"></div></div></div><div class="tabs"><div class="example-names"><span>Responses</span><span class="tab-button">200</span><span class="tab-button">404</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span><br><span class="hljs-attribute">Link</span>: <span class="hljs-string">&lt;{{ route('api.products') }}?page=1&gt;; rel="first", &lt;{{ route('api.products') }}?page=3&gt;; rel="prev", &lt;{{ route('api.products') }}?page=5&gt;; rel="next", &lt;{{ route('api.products') }}?page=501&gt;; rel="last"</span><br><span class="hljs-attribute">X-RateLimit-Limit</span>: <span class="hljs-string">60</span><br><span class="hljs-attribute">X-RateLimit-Remaining</span>: <span class="hljs-string">59</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">data</span>": <span class="hljs-value">[
    {
      "<span class="hljs-attribute">id</span>": <span class="hljs-value"><span class="hljs-number">7</span></span>,
      "<span class="hljs-attribute">company_id</span>": <span class="hljs-value"><span class="hljs-number">1</span></span>,
      "<span class="hljs-attribute">product_name</span>": <span class="hljs-value">[
        {
          "<span class="hljs-attribute">fr</span>": <span class="hljs-value"><span class="hljs-string">"qui incidunt consequuntur"</span></span>,
          "<span class="hljs-attribute">en</span>": <span class="hljs-value"><span class="hljs-string">"dolores id nihil"</span>
        </span>}
      ]
    </span>},
    {
      "<span class="hljs-attribute">id</span>": <span class="hljs-value"><span class="hljs-number">8</span></span>,
      "<span class="hljs-attribute">company_id</span>": <span class="hljs-value"><span class="hljs-number">1</span></span>,
      "<span class="hljs-attribute">product_name</span>": <span class="hljs-value">[
        {
          "<span class="hljs-attribute">fr</span>": <span class="hljs-value"><span class="hljs-string">"nihil et et"</span></span>,
          "<span class="hljs-attribute">en</span>": <span class="hljs-value"><span class="hljs-string">"nostrum totam ut"</span>
        </span>}
      ]
    </span>}
  ]</span>,
  "<span class="hljs-attribute">meta</span>": <span class="hljs-value">{
    "<span class="hljs-attribute">pagination</span>": <span class="hljs-value">{
      "<span class="hljs-attribute">first</span>": <span class="hljs-value"><span class="hljs-string">"{{ route('api.products') }}?page=1"</span></span>,
      "<span class="hljs-attribute">prev</span>": <span class="hljs-value"><span class="hljs-string">"{{ route('api.products') }}?page=3"</span></span>,
      "<span class="hljs-attribute">next</span>": <span class="hljs-value"><span class="hljs-string">"{{ route('api.products') }}?page=5"</span></span>,
      "<span class="hljs-attribute">last</span>": <span class="hljs-value"><span class="hljs-string">"{{ route('api.products') }}?page=500"</span>
    </span>}
  </span>}
</span>}</code></pre><div style="height: 1px;"></div></div></div></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">status</span>": <span class="hljs-value"><span class="hljs-string">"not-found"</span></span>,
  "<span class="hljs-attribute">message</span>": <span class="hljs-value"><span class="hljs-string">"The resource could not found."</span>
</span>}</code></pre><div style="height: 1px;"></div></div></div></div></div></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Accept</span>: <span class="hljs-string">application/vnd.soprema.v1+json</span><br><span class="hljs-attribute">Authorization</span>: <span class="hljs-string">Bearer BAD_JSON_WEB_TOKEN</span></code></pre><div style="height: 1px;"></div></div></div><div class="tabs"><div class="example-names"><span>Responses</span><span class="tab-button">403</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">status</span>": <span class="hljs-value"><span class="hljs-string">"forbidden"</span></span>,
  "<span class="hljs-attribute">message</span>": <span class="hljs-value"><span class="hljs-string">"You do not have access for the resource."</span>
</span>}</code></pre><div style="height: 1px;"></div></div></div></div></div></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Authorization</span>: <span class="hljs-string">Bearer JSON_WEB_TOKEN</span></code></pre><div style="height: 1px;"></div></div></div><div class="tabs"><div class="example-names"><span>Responses</span><span class="tab-button">400</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">status</span>": <span class="hljs-value"><span class="hljs-string">"bad-request"</span></span>,
  "<span class="hljs-attribute">message</span>": <span class="hljs-value"><span class="hljs-string">"Missing version header."</span>
</span>}</code></pre><div style="height: 1px;"></div></div></div></div></div></div></div></div><div class="middle"><div id="products-products-get" class="action get"><h4 class="action-heading"><div class="name">Get products</div><a href="#products-products-get" class="method get">GET</a><code class="uri">/products{?page,per_page,lang}</code></h4><p>Get all products</p>
<div class="title"><strong>URI Parameters</strong><div class="collapse-button show"><span class="close">Hide</span><span class="open">Show</span></div></div><div class="collapse-content"><dl class="inner"><dt>page</dt><dd><code>integer</code>&nbsp;<span>(optional)</span>&nbsp;<span class="text-muted example"><strong>Example:&nbsp;</strong><span>4</span></span><p>Page to display</p>
</dd><dt>per_page</dt><dd><code>integer</code>&nbsp;<span>(optional)</span>&nbsp;<span class="text-info default"><strong>Default:&nbsp;</strong><span>15</span></span>&nbsp;<span class="text-muted example"><strong>Example:&nbsp;</strong><span>2</span></span><p>Elements to display per page</p>
</dd><dt>lang</dt><dd><code>array</code>&nbsp;<span>(optional)</span>&nbsp;<span class="text-muted example"><strong>Example:&nbsp;</strong><span>fr,en</span></span><p>One or more languages of the elements to fetch</p>
</dd></dl></div></div></div><hr class="split"><div class="right"><div class="definition"><span class="method get">GET</span>&nbsp;<span class="uri"><span class="hostname">{{ route('api.blueprint') }}</span>/products/<span class="hljs-attribute" title="product">7</span>?<span class="hljs-attribute">lang=</span><span class="hljs-literal">fr,en</span></span></div><div class="tabs"><div class="example-names"><span>Requests</span><span class="tab-button">example 1</span><span class="tab-button">example 2</span><span class="tab-button">example 3</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Accept</span>: <span class="hljs-string">application/vnd.soprema.v1+json</span><br><span class="hljs-attribute">Authorization</span>: <span class="hljs-string">Bearer JSON_WEB_TOKEN</span></code></pre><div style="height: 1px;"></div></div></div><div class="tabs"><div class="example-names"><span>Responses</span><span class="tab-button">200</span><span class="tab-button">404</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span><br><span class="hljs-attribute">Link</span>: <span class="hljs-string">&lt;{{ route('api.products') }}&gt;; rel="products", &lt;{{ route('api.products') }}/1?with_children=true&gt;; rel="children"</span><br><span class="hljs-attribute">X-RateLimit-Limit</span>: <span class="hljs-string">60</span><br><span class="hljs-attribute">X-RateLimit-Remaining</span>: <span class="hljs-string">59</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">data</span>": <span class="hljs-value">{
    "<span class="hljs-attribute">id</span>": <span class="hljs-value"><span class="hljs-number">7</span></span>,
    "<span class="hljs-attribute">company_id</span>": <span class="hljs-value"><span class="hljs-number">1</span></span>,
    "<span class="hljs-attribute">product_name</span>": <span class="hljs-value">[
      {
        "<span class="hljs-attribute">fr</span>": <span class="hljs-value"><span class="hljs-string">"qui incidunt consequuntur"</span></span>,
        "<span class="hljs-attribute">en</span>": <span class="hljs-value"><span class="hljs-string">"dolores id nihil"</span>
      </span>}
    ]
  </span>}</span>,
  "<span class="hljs-attribute">meta</span>": <span class="hljs-value">{
    "<span class="hljs-attribute">links</span>": <span class="hljs-value">{
      "<span class="hljs-attribute">products</span>": <span class="hljs-value"><span class="hljs-string">"{{ route('api.products') }}"</span>
    </span>}
  </span>}
</span>}</code></pre><div style="height: 1px;"></div></div></div></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">status</span>": <span class="hljs-value"><span class="hljs-string">"not-found"</span></span>,
  "<span class="hljs-attribute">message</span>": <span class="hljs-value"><span class="hljs-string">"The resource could not found."</span>
</span>}</code></pre><div style="height: 1px;"></div></div></div></div></div></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Accept</span>: <span class="hljs-string">application/vnd.soprema.v1+json</span><br><span class="hljs-attribute">Authorization</span>: <span class="hljs-string">Bearer BAD_JSON_WEB_TOKEN</span></code></pre><div style="height: 1px;"></div></div></div><div class="tabs"><div class="example-names"><span>Responses</span><span class="tab-button">403</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">status</span>": <span class="hljs-value"><span class="hljs-string">"forbidden"</span></span>,
  "<span class="hljs-attribute">message</span>": <span class="hljs-value"><span class="hljs-string">"You do not have access for the resource."</span>
</span>}</code></pre><div style="height: 1px;"></div></div></div></div></div></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Authorization</span>: <span class="hljs-string">Bearer JSON_WEB_TOKEN</span></code></pre><div style="height: 1px;"></div></div></div><div class="tabs"><div class="example-names"><span>Responses</span><span class="tab-button">400</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">status</span>": <span class="hljs-value"><span class="hljs-string">"bad-request"</span></span>,
  "<span class="hljs-attribute">message</span>": <span class="hljs-value"><span class="hljs-string">"Missing version header."</span>
</span>}</code></pre><div style="height: 1px;"></div></div></div></div></div></div></div></div><div class="middle"><div id="products-products-get-1" class="action get"><h4 class="action-heading"><div class="name">Get product</div><a href="#products-products-get-1" class="method get">GET</a><code class="uri">/products/{product}{?lang}</code></h4><p>Get a product</p>
<div class="title"><strong>URI Parameters</strong><div class="collapse-button show"><span class="close">Hide</span><span class="open">Show</span></div></div><div class="collapse-content"><dl class="inner"><dt>product</dt><dd><code>integer</code>&nbsp;<span class="required">(required)</span>&nbsp;<span class="text-muted example"><strong>Example:&nbsp;</strong><span>7</span></span><p>Id of the product</p>
</dd><dt>lang</dt><dd><code>array</code>&nbsp;<span>(optional)</span>&nbsp;<span class="text-muted example"><strong>Example:&nbsp;</strong><span>fr,en</span></span><p>One or more languages of the elements to fetch</p>
</dd></dl></div></div></div><hr class="split"><div class="right"><div class="definition"><span class="method get">GET</span>&nbsp;<span class="uri"><span class="hostname">{{ route('api.blueprint') }}</span>/products/<span class="hljs-attribute" title="product">7</span>?<span class="hljs-attribute">with_children=</span><span class="hljs-literal">true</span>&<span class="hljs-attribute">lang=</span><span class="hljs-literal">fr,en</span></span></div><div class="tabs"><div class="example-names"><span>Requests</span><span class="tab-button">example 1</span><span class="tab-button">example 2</span><span class="tab-button">example 3</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Accept</span>: <span class="hljs-string">application/vnd.soprema.v1+json</span><br><span class="hljs-attribute">Authorization</span>: <span class="hljs-string">Bearer JSON_WEB_TOKEN</span></code></pre><div style="height: 1px;"></div></div></div><div class="tabs"><div class="example-names"><span>Responses</span><span class="tab-button">200</span><span class="tab-button">404</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span><br><span class="hljs-attribute">Link</span>: <span class="hljs-string">&lt;{{ route('api.products') }}/1&gt;; rel="product"</span><br><span class="hljs-attribute">X-RateLimit-Limit</span>: <span class="hljs-string">60</span><br><span class="hljs-attribute">X-RateLimit-Remaining</span>: <span class="hljs-string">59</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">data</span>": <span class="hljs-value">{
    "<span class="hljs-attribute">id</span>": <span class="hljs-value"><span class="hljs-number">7</span></span>,
    "<span class="hljs-attribute">company_id</span>": <span class="hljs-value"><span class="hljs-number">1</span></span>,
    "<span class="hljs-attribute">product_name</span>": <span class="hljs-value">[
      {
        "<span class="hljs-attribute">fr</span>": <span class="hljs-value"><span class="hljs-string">"qui incidunt consequuntur"</span></span>,
        "<span class="hljs-attribute">en</span>": <span class="hljs-value"><span class="hljs-string">"dolores id nihil"</span>
      </span>}
    ]</span>,
    "<span class="hljs-attribute">children</span>": <span class="hljs-value">[
      {
        "<span class="hljs-attribute">id</span>": <span class="hljs-value"><span class="hljs-number">14</span></span>,
        "<span class="hljs-attribute">company_id</span>": <span class="hljs-value"><span class="hljs-number">1</span></span>,
        "<span class="hljs-attribute">parent_product_id</span>": <span class="hljs-value"><span class="hljs-number">7</span></span>,
        "<span class="hljs-attribute">product_name</span>": <span class="hljs-value">[
          {
            "<span class="hljs-attribute">fr</span>": <span class="hljs-value"><span class="hljs-string">"omnis est assumenda"</span></span>,
            "<span class="hljs-attribute">en</span>": <span class="hljs-value"><span class="hljs-string">"modi cum vero"</span>
          </span>}
        ]
      </span>},
      {
        "<span class="hljs-attribute">id</span>": <span class="hljs-value"><span class="hljs-number">13</span></span>,
        "<span class="hljs-attribute">company_id</span>": <span class="hljs-value"><span class="hljs-number">1</span></span>,
        "<span class="hljs-attribute">parent_product_id</span>": <span class="hljs-value"><span class="hljs-number">7</span></span>,
        "<span class="hljs-attribute">product_name</span>": <span class="hljs-value">[
          {
            "<span class="hljs-attribute">fr</span>": <span class="hljs-value"><span class="hljs-string">"est quibusdam explicabo"</span></span>,
            "<span class="hljs-attribute">en</span>": <span class="hljs-value"><span class="hljs-string">"quasi nihil rerum"</span>
          </span>}
        ]
      </span>}
    ]
  </span>}</span>,
  "<span class="hljs-attribute">meta</span>": <span class="hljs-value">{
    "<span class="hljs-attribute">links</span>": <span class="hljs-value">{
      "<span class="hljs-attribute">products</span>": <span class="hljs-value"><span class="hljs-string">"{{ route('api.products') }}"</span>
    </span>}
  </span>}
</span>}</code></pre><div style="height: 1px;"></div></div></div></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">status</span>": <span class="hljs-value"><span class="hljs-string">"not-found"</span></span>,
  "<span class="hljs-attribute">message</span>": <span class="hljs-value"><span class="hljs-string">"The resource could not found."</span>
</span>}</code></pre><div style="height: 1px;"></div></div></div></div></div></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Accept</span>: <span class="hljs-string">application/vnd.soprema.v1+json</span><br><span class="hljs-attribute">Authorization</span>: <span class="hljs-string">Bearer BAD_JSON_WEB_TOKEN</span></code></pre><div style="height: 1px;"></div></div></div><div class="tabs"><div class="example-names"><span>Responses</span><span class="tab-button">403</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">status</span>": <span class="hljs-value"><span class="hljs-string">"forbidden"</span></span>,
  "<span class="hljs-attribute">message</span>": <span class="hljs-value"><span class="hljs-string">"You do not have access for the resource."</span>
</span>}</code></pre><div style="height: 1px;"></div></div></div></div></div></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Authorization</span>: <span class="hljs-string">Bearer JSON_WEB_TOKEN</span></code></pre><div style="height: 1px;"></div></div></div><div class="tabs"><div class="example-names"><span>Responses</span><span class="tab-button">400</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">status</span>": <span class="hljs-value"><span class="hljs-string">"bad-request"</span></span>,
  "<span class="hljs-attribute">message</span>": <span class="hljs-value"><span class="hljs-string">"Missing version header."</span>
</span>}</code></pre><div style="height: 1px;"></div></div></div></div></div></div></div></div><div class="middle"><div id="products-products-get-2" class="action get"><h4 class="action-heading"><div class="name">Get product's children</div><a href="#products-products-get-2" class="method get">GET</a><code class="uri">/products/{product}{?with_children,lang}</code></h4><p>Get all children of a product</p>
<div class="title"><strong>URI Parameters</strong><div class="collapse-button show"><span class="close">Hide</span><span class="open">Show</span></div></div><div class="collapse-content"><dl class="inner"><dt>product</dt><dd><code>integer</code>&nbsp;<span class="required">(required)</span>&nbsp;<span class="text-muted example"><strong>Example:&nbsp;</strong><span>7</span></span><p>If of the product</p>
</dd><dt>with_children</dt><dd><code>boolean</code>&nbsp;<span>(optional)</span>&nbsp;<span class="text-info default"><strong>Default:&nbsp;</strong><span>false</span></span>&nbsp;<span class="text-muted example"><strong>Example:&nbsp;</strong><span>true</span></span><p>Include product’s children</p>
</dd><dt>lang</dt><dd><code>array</code>&nbsp;<span>(optional)</span>&nbsp;<span class="text-muted example"><strong>Example:&nbsp;</strong><span>fr,en</span></span><p>One or more languages of the elements to fetch</p>
</dd></dl></div></div></div><hr class="split"><div class="middle"><section id="systems" class="resource-group"><h2 class="group-heading">Systems <a href="#systems" class="permalink">&para;</a></h2></section></div><div class="middle"><div id="systems-systems" class="resource"><h3 class="resource-heading">Systems <a href="#systems-systems" class="permalink">&para;</a></h3></div></div><div class="right"><div class="definition"><span class="method get">GET</span>&nbsp;<span class="uri"><span class="hostname">{{ route('api.blueprint') }}</span>/systems?<span class="hljs-attribute">page=</span><span class="hljs-literal">4</span>&<span class="hljs-attribute">per_page=</span><span class="hljs-literal">2</span>&<span class="hljs-attribute">lang=</span><span class="hljs-literal">fr,en</span></span></div><div class="tabs"><div class="example-names"><span>Requests</span><span class="tab-button">example 1</span><span class="tab-button">example 2</span><span class="tab-button">example 3</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Accept</span>: <span class="hljs-string">application/vnd.soprema.v1+json</span><br><span class="hljs-attribute">Authorization</span>: <span class="hljs-string">Bearer JSON_WEB_TOKEN</span></code></pre><div style="height: 1px;"></div></div></div><div class="tabs"><div class="example-names"><span>Responses</span><span class="tab-button">200</span><span class="tab-button">404</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span><br><span class="hljs-attribute">Link</span>: <span class="hljs-string">&lt;{{ route('api.systems') }}?page=1&gt;; rel="first", &lt;{{ route('api.systems') }}?page=3&gt;; rel="prev", &lt;{{ route('api.systems') }}?page=5&gt;; rel="next", &lt;{{ route('api.systems') }}?page=500&gt;; rel="last"</span><br><span class="hljs-attribute">X-RateLimit-Limit</span>: <span class="hljs-string">60</span><br><span class="hljs-attribute">X-RateLimit-Remaining</span>: <span class="hljs-string">59</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">data</span>": <span class="hljs-value">[
    {
      "<span class="hljs-attribute">id</span>": <span class="hljs-value"><span class="hljs-number">7</span></span>,
      "<span class="hljs-attribute">company_id</span>": <span class="hljs-value"><span class="hljs-number">1</span></span>,
      "<span class="hljs-attribute">system_name</span>": <span class="hljs-value">[
        {
          "<span class="hljs-attribute">fr</span>": <span class="hljs-value"><span class="hljs-string">"tempore deleniti corrupti"</span></span>,
          "<span class="hljs-attribute">en</span>": <span class="hljs-value"><span class="hljs-string">"repellat non perspiciatis"</span>
        </span>}
      ]
    </span>},
    {
      "<span class="hljs-attribute">id</span>": <span class="hljs-value"><span class="hljs-number">8</span></span>,
      "<span class="hljs-attribute">company_id</span>": <span class="hljs-value"><span class="hljs-number">1</span></span>,
      "<span class="hljs-attribute">system_name</span>": <span class="hljs-value">[
        {
          "<span class="hljs-attribute">fr</span>": <span class="hljs-value"><span class="hljs-string">"facere est autem"</span></span>,
          "<span class="hljs-attribute">en</span>": <span class="hljs-value"><span class="hljs-string">"quis error deserunt"</span>
        </span>}
      ]
    </span>}
  ]</span>,
  "<span class="hljs-attribute">meta</span>": <span class="hljs-value">{
    "<span class="hljs-attribute">pagination</span>": <span class="hljs-value">{
      "<span class="hljs-attribute">first</span>": <span class="hljs-value"><span class="hljs-string">"{{ route('api.systems') }}?page=1"</span></span>,
      "<span class="hljs-attribute">prev</span>": <span class="hljs-value"><span class="hljs-string">"{{ route('api.systems') }}?page=3"</span></span>,
      "<span class="hljs-attribute">next</span>": <span class="hljs-value"><span class="hljs-string">"{{ route('api.systems') }}?page=5"</span></span>,
      "<span class="hljs-attribute">last</span>": <span class="hljs-value"><span class="hljs-string">"{{ route('api.systems') }}?page=500"</span>
    </span>}
  </span>}
</span>}</code></pre><div style="height: 1px;"></div></div></div></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">status</span>": <span class="hljs-value"><span class="hljs-string">"not-found"</span></span>,
  "<span class="hljs-attribute">message</span>": <span class="hljs-value"><span class="hljs-string">"The resource could not found."</span>
</span>}</code></pre><div style="height: 1px;"></div></div></div></div></div></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Accept</span>: <span class="hljs-string">application/vnd.soprema.v1+json</span><br><span class="hljs-attribute">Authorization</span>: <span class="hljs-string">Bearer BAD_JSON_WEB_TOKEN</span></code></pre><div style="height: 1px;"></div></div></div><div class="tabs"><div class="example-names"><span>Responses</span><span class="tab-button">403</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">status</span>": <span class="hljs-value"><span class="hljs-string">"forbidden"</span></span>,
  "<span class="hljs-attribute">message</span>": <span class="hljs-value"><span class="hljs-string">"You do not have access for the resource."</span>
</span>}</code></pre><div style="height: 1px;"></div></div></div></div></div></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Authorization</span>: <span class="hljs-string">Bearer JSON_WEB_TOKEN</span></code></pre><div style="height: 1px;"></div></div></div><div class="tabs"><div class="example-names"><span>Responses</span><span class="tab-button">400</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">status</span>": <span class="hljs-value"><span class="hljs-string">"bad-request"</span></span>,
  "<span class="hljs-attribute">message</span>": <span class="hljs-value"><span class="hljs-string">"Missing version header."</span>
</span>}</code></pre><div style="height: 1px;"></div></div></div></div></div></div></div></div><div class="middle"><div id="systems-systems-get" class="action get"><h4 class="action-heading"><div class="name">Get systems</div><a href="#systems-systems-get" class="method get">GET</a><code class="uri">/systems{?page,per_page,lang}</code></h4><p>Get all systems</p>
<div class="title"><strong>URI Parameters</strong><div class="collapse-button show"><span class="close">Hide</span><span class="open">Show</span></div></div><div class="collapse-content"><dl class="inner"><dt>page</dt><dd><code>integer</code>&nbsp;<span>(optional)</span>&nbsp;<span class="text-muted example"><strong>Example:&nbsp;</strong><span>4</span></span><p>Page to display</p>
</dd><dt>per_page</dt><dd><code>integer</code>&nbsp;<span>(optional)</span>&nbsp;<span class="text-info default"><strong>Default:&nbsp;</strong><span>15</span></span>&nbsp;<span class="text-muted example"><strong>Example:&nbsp;</strong><span>2</span></span><p>Elements to display per page</p>
</dd><dt>lang</dt><dd><code>array</code>&nbsp;<span>(optional)</span>&nbsp;<span class="text-muted example"><strong>Example:&nbsp;</strong><span>fr,en</span></span><p>One or more languages of the elements to fetch</p>
</dd></dl></div></div></div><hr class="split"><div class="right"><div class="definition"><span class="method get">GET</span>&nbsp;<span class="uri"><span class="hostname">{{ route('api.blueprint') }}</span>/systems/<span class="hljs-attribute" title="system">7</span>?<span class="hljs-attribute">lang=</span><span class="hljs-literal">fr,en</span></span></div><div class="tabs"><div class="example-names"><span>Requests</span><span class="tab-button">example 1</span><span class="tab-button">example 2</span><span class="tab-button">example 3</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Accept</span>: <span class="hljs-string">application/vnd.soprema.v1+json</span><br><span class="hljs-attribute">Authorization</span>: <span class="hljs-string">Bearer JSON_WEB_TOKEN</span></code></pre><div style="height: 1px;"></div></div></div><div class="tabs"><div class="example-names"><span>Responses</span><span class="tab-button">200</span><span class="tab-button">404</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span><br><span class="hljs-attribute">Link</span>: <span class="hljs-string">&lt;{{ route('api.systems') }}&gt;; rel="systems"</span><br><span class="hljs-attribute">X-RateLimit-Limit</span>: <span class="hljs-string">60</span><br><span class="hljs-attribute">X-RateLimit-Remaining</span>: <span class="hljs-string">59</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">data</span>": <span class="hljs-value">{
    "<span class="hljs-attribute">id</span>": <span class="hljs-value"><span class="hljs-number">7</span></span>,
    "<span class="hljs-attribute">company_id</span>": <span class="hljs-value"><span class="hljs-number">1</span></span>,
    "<span class="hljs-attribute">system_name</span>": <span class="hljs-value">[
      {
        "<span class="hljs-attribute">fr</span>": <span class="hljs-value"><span class="hljs-string">"tempore deleniti corrupti"</span></span>,
        "<span class="hljs-attribute">en</span>": <span class="hljs-value"><span class="hljs-string">"repellat non perspiciatis"</span>
      </span>}
    ]
  </span>}</span>,
  "<span class="hljs-attribute">meta</span>": <span class="hljs-value">{
    "<span class="hljs-attribute">links</span>": <span class="hljs-value">{
      "<span class="hljs-attribute">systems</span>": <span class="hljs-value"><span class="hljs-string">"{{ route('api.systems') }}"</span>
    </span>}
  </span>}
</span>}</code></pre><div style="height: 1px;"></div></div></div></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">status</span>": <span class="hljs-value"><span class="hljs-string">"not-found"</span></span>,
  "<span class="hljs-attribute">message</span>": <span class="hljs-value"><span class="hljs-string">"The resource could not found."</span>
</span>}</code></pre><div style="height: 1px;"></div></div></div></div></div></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Accept</span>: <span class="hljs-string">application/vnd.soprema.v1+json</span><br><span class="hljs-attribute">Authorization</span>: <span class="hljs-string">Bearer BAD_JSON_WEB_TOKEN</span></code></pre><div style="height: 1px;"></div></div></div><div class="tabs"><div class="example-names"><span>Responses</span><span class="tab-button">403</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">status</span>": <span class="hljs-value"><span class="hljs-string">"forbidden"</span></span>,
  "<span class="hljs-attribute">message</span>": <span class="hljs-value"><span class="hljs-string">"You do not have access for the resource."</span>
</span>}</code></pre><div style="height: 1px;"></div></div></div></div></div></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Authorization</span>: <span class="hljs-string">Bearer JSON_WEB_TOKEN</span></code></pre><div style="height: 1px;"></div></div></div><div class="tabs"><div class="example-names"><span>Responses</span><span class="tab-button">400</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">status</span>": <span class="hljs-value"><span class="hljs-string">"bad-request"</span></span>,
  "<span class="hljs-attribute">message</span>": <span class="hljs-value"><span class="hljs-string">"Missing version header."</span>
</span>}</code></pre><div style="height: 1px;"></div></div></div></div></div></div></div></div><div class="middle"><div id="systems-systems-get-1" class="action get"><h4 class="action-heading"><div class="name">Get system</div><a href="#systems-systems-get-1" class="method get">GET</a><code class="uri">/systems/{system}{?lang}</code></h4><p>Get a system</p>
<div class="title"><strong>URI Parameters</strong><div class="collapse-button show"><span class="close">Hide</span><span class="open">Show</span></div></div><div class="collapse-content"><dl class="inner"><dt>system</dt><dd><code>integer</code>&nbsp;<span class="required">(required)</span>&nbsp;<span class="text-muted example"><strong>Example:&nbsp;</strong><span>7</span></span><p>Id of the system</p>
</dd><dt>lang</dt><dd><code>array</code>&nbsp;<span>(optional)</span>&nbsp;<span class="text-muted example"><strong>Example:&nbsp;</strong><span>fr,en</span></span><p>One or more languages of the elements to fetch</p>
</dd></dl></div></div></div><hr class="split"><div class="middle"><section id="details" class="resource-group"><h2 class="group-heading">Details <a href="#details" class="permalink">&para;</a></h2></section></div><div class="middle"><div id="details-details" class="resource"><h3 class="resource-heading">Details <a href="#details-details" class="permalink">&para;</a></h3></div></div><div class="right"><div class="definition"><span class="method get">GET</span>&nbsp;<span class="uri"><span class="hostname">{{ route('api.blueprint') }}</span>/details?<span class="hljs-attribute">page=</span><span class="hljs-literal">4</span>&<span class="hljs-attribute">per_page=</span><span class="hljs-literal">2</span>&<span class="hljs-attribute">lang=</span><span class="hljs-literal">fr,en</span></span></div><div class="tabs"><div class="example-names"><span>Requests</span><span class="tab-button">example 1</span><span class="tab-button">example 2</span><span class="tab-button">example 3</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Accept</span>: <span class="hljs-string">application/vnd.soprema.v1+json</span><br><span class="hljs-attribute">Authorization</span>: <span class="hljs-string">Bearer JSON_WEB_TOKEN</span></code></pre><div style="height: 1px;"></div></div></div><div class="tabs"><div class="example-names"><span>Responses</span><span class="tab-button">200</span><span class="tab-button">404</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span><br><span class="hljs-attribute">Link</span>: <span class="hljs-string">&lt;{{ route('api.details') }}?page=1&gt;; rel="first", &lt;{{ route('api.details') }}?page=3&gt;; rel="prev", &lt;{{ route('api.details') }}?page=5&gt;; rel="next", &lt;{{ route('api.details') }}?page=501&gt;; rel="last"</span><br><span class="hljs-attribute">X-RateLimit-Limit</span>: <span class="hljs-string">60</span><br><span class="hljs-attribute">X-RateLimit-Remaining</span>: <span class="hljs-string">59</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">data</span>": <span class="hljs-value">[
    {
      "<span class="hljs-attribute">id</span>": <span class="hljs-value"><span class="hljs-number">7</span></span>,
      "<span class="hljs-attribute">company_id</span>": <span class="hljs-value"><span class="hljs-number">1</span></span>,
      "<span class="hljs-attribute">detail_name</span>": <span class="hljs-value">[
        {
          "<span class="hljs-attribute">fr</span>": <span class="hljs-value"><span class="hljs-string">"voluptate libero vel"</span></span>,
          "<span class="hljs-attribute">en</span>": <span class="hljs-value"><span class="hljs-string">"tenetur alias nam"</span>
        </span>}
      ]
    </span>},
    {
      "<span class="hljs-attribute">id</span>": <span class="hljs-value"><span class="hljs-number">8</span></span>,
      "<span class="hljs-attribute">company_id</span>": <span class="hljs-value"><span class="hljs-number">1</span></span>,
      "<span class="hljs-attribute">detail_name</span>": <span class="hljs-value">[
        {
          "<span class="hljs-attribute">fr</span>": <span class="hljs-value"><span class="hljs-string">"officiis rerum voluptatem"</span></span>,
          "<span class="hljs-attribute">en</span>": <span class="hljs-value"><span class="hljs-string">"perferendis nobis in"</span>
        </span>}
      ]
    </span>}
  ]</span>,
  "<span class="hljs-attribute">meta</span>": <span class="hljs-value">{
    "<span class="hljs-attribute">pagination</span>": <span class="hljs-value">{
      "<span class="hljs-attribute">first</span>": <span class="hljs-value"><span class="hljs-string">"{{ route('api.details') }}?page=1"</span></span>,
      "<span class="hljs-attribute">prev</span>": <span class="hljs-value"><span class="hljs-string">"{{ route('api.details') }}?page=3"</span></span>,
      "<span class="hljs-attribute">next</span>": <span class="hljs-value"><span class="hljs-string">"{{ route('api.details') }}?page=5"</span></span>,
      "<span class="hljs-attribute">last</span>": <span class="hljs-value"><span class="hljs-string">"{{ route('api.details') }}?page=501"</span>
    </span>}
  </span>}
</span>}</code></pre><div style="height: 1px;"></div></div></div></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">status</span>": <span class="hljs-value"><span class="hljs-string">"not-found"</span></span>,
  "<span class="hljs-attribute">message</span>": <span class="hljs-value"><span class="hljs-string">"The resource could not found."</span>
</span>}</code></pre><div style="height: 1px;"></div></div></div></div></div></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Accept</span>: <span class="hljs-string">application/vnd.soprema.v1+json</span><br><span class="hljs-attribute">Authorization</span>: <span class="hljs-string">Bearer BAD_JSON_WEB_TOKEN</span></code></pre><div style="height: 1px;"></div></div></div><div class="tabs"><div class="example-names"><span>Responses</span><span class="tab-button">403</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">status</span>": <span class="hljs-value"><span class="hljs-string">"forbidden"</span></span>,
  "<span class="hljs-attribute">message</span>": <span class="hljs-value"><span class="hljs-string">"You do not have access for the resource."</span>
</span>}</code></pre><div style="height: 1px;"></div></div></div></div></div></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Authorization</span>: <span class="hljs-string">Bearer JSON_WEB_TOKEN</span></code></pre><div style="height: 1px;"></div></div></div><div class="tabs"><div class="example-names"><span>Responses</span><span class="tab-button">400</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">status</span>": <span class="hljs-value"><span class="hljs-string">"bad-request"</span></span>,
  "<span class="hljs-attribute">message</span>": <span class="hljs-value"><span class="hljs-string">"Missing version header."</span>
</span>}</code></pre><div style="height: 1px;"></div></div></div></div></div></div></div></div><div class="middle"><div id="details-details-get" class="action get"><h4 class="action-heading"><div class="name">Get details</div><a href="#details-details-get" class="method get">GET</a><code class="uri">/details{?page,per_page,lang}</code></h4><p>Get all details</p>
<div class="title"><strong>URI Parameters</strong><div class="collapse-button show"><span class="close">Hide</span><span class="open">Show</span></div></div><div class="collapse-content"><dl class="inner"><dt>page</dt><dd><code>integer</code>&nbsp;<span>(optional)</span>&nbsp;<span class="text-muted example"><strong>Example:&nbsp;</strong><span>4</span></span><p>Page to display</p>
</dd><dt>per_page</dt><dd><code>integer</code>&nbsp;<span>(optional)</span>&nbsp;<span class="text-info default"><strong>Default:&nbsp;</strong><span>15</span></span>&nbsp;<span class="text-muted example"><strong>Example:&nbsp;</strong><span>2</span></span><p>Elements to display per page</p>
</dd><dt>lang</dt><dd><code>array</code>&nbsp;<span>(optional)</span>&nbsp;<span class="text-muted example"><strong>Example:&nbsp;</strong><span>fr,en</span></span><p>One or more languages of the elements to fetch</p>
</dd></dl></div></div></div><hr class="split"><div class="right"><div class="definition"><span class="method get">GET</span>&nbsp;<span class="uri"><span class="hostname">{{ route('api.blueprint') }}</span>/details/<span class="hljs-attribute" title="detail">7</span>?<span class="hljs-attribute">lang=</span><span class="hljs-literal">fr,en</span></span></div><div class="tabs"><div class="example-names"><span>Requests</span><span class="tab-button">example 1</span><span class="tab-button">example 2</span><span class="tab-button">example 3</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Accept</span>: <span class="hljs-string">application/vnd.soprema.v1+json</span><br><span class="hljs-attribute">Authorization</span>: <span class="hljs-string">Bearer JSON_WEB_TOKEN</span></code></pre><div style="height: 1px;"></div></div></div><div class="tabs"><div class="example-names"><span>Responses</span><span class="tab-button">200</span><span class="tab-button">404</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span><br><span class="hljs-attribute">Link</span>: <span class="hljs-string">&lt;{{ route('api.details') }}&gt;; rel="details"</span><br><span class="hljs-attribute">X-RateLimit-Limit</span>: <span class="hljs-string">60</span><br><span class="hljs-attribute">X-RateLimit-Remaining</span>: <span class="hljs-string">59</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">data</span>": <span class="hljs-value">{
    "<span class="hljs-attribute">id</span>": <span class="hljs-value"><span class="hljs-number">7</span></span>,
    "<span class="hljs-attribute">company_id</span>": <span class="hljs-value"><span class="hljs-number">1</span></span>,
    "<span class="hljs-attribute">detail_name</span>": <span class="hljs-value">[
      {
        "<span class="hljs-attribute">fr</span>": <span class="hljs-value"><span class="hljs-string">"voluptate libero vel"</span></span>,
        "<span class="hljs-attribute">en</span>": <span class="hljs-value"><span class="hljs-string">"tenetur alias nam"</span>
      </span>}
    ]
  </span>}</span>,
  "<span class="hljs-attribute">meta</span>": <span class="hljs-value">{
    "<span class="hljs-attribute">links</span>": <span class="hljs-value">{
      "<span class="hljs-attribute">details</span>": <span class="hljs-value"><span class="hljs-string">"{{ route('api.details') }}"</span>
    </span>}
  </span>}
</span>}</code></pre><div style="height: 1px;"></div></div></div></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">status</span>": <span class="hljs-value"><span class="hljs-string">"not-found"</span></span>,
  "<span class="hljs-attribute">message</span>": <span class="hljs-value"><span class="hljs-string">"The resource could not found."</span>
</span>}</code></pre><div style="height: 1px;"></div></div></div></div></div></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Accept</span>: <span class="hljs-string">application/vnd.soprema.v1+json</span><br><span class="hljs-attribute">Authorization</span>: <span class="hljs-string">Bearer BAD_JSON_WEB_TOKEN</span></code></pre><div style="height: 1px;"></div></div></div><div class="tabs"><div class="example-names"><span>Responses</span><span class="tab-button">403</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">status</span>": <span class="hljs-value"><span class="hljs-string">"forbidden"</span></span>,
  "<span class="hljs-attribute">message</span>": <span class="hljs-value"><span class="hljs-string">"You do not have access for the resource."</span>
</span>}</code></pre><div style="height: 1px;"></div></div></div></div></div></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Authorization</span>: <span class="hljs-string">Bearer JSON_WEB_TOKEN</span></code></pre><div style="height: 1px;"></div></div></div><div class="tabs"><div class="example-names"><span>Responses</span><span class="tab-button">400</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">status</span>": <span class="hljs-value"><span class="hljs-string">"bad-request"</span></span>,
  "<span class="hljs-attribute">message</span>": <span class="hljs-value"><span class="hljs-string">"Missing version header."</span>
</span>}</code></pre><div style="height: 1px;"></div></div></div></div></div></div></div></div><div class="middle"><div id="details-details-get-1" class="action get"><h4 class="action-heading"><div class="name">Get detail</div><a href="#details-details-get-1" class="method get">GET</a><code class="uri">/details/{detail}{?lang}</code></h4><p>Get a detail</p>
<div class="title"><strong>URI Parameters</strong><div class="collapse-button show"><span class="close">Hide</span><span class="open">Show</span></div></div><div class="collapse-content"><dl class="inner"><dt>detail</dt><dd><code>integer</code>&nbsp;<span class="required">(required)</span>&nbsp;<span class="text-muted example"><strong>Example:&nbsp;</strong><span>7</span></span><p>Id of the detail</p>
</dd><dt>lang</dt><dd><code>array</code>&nbsp;<span>(optional)</span>&nbsp;<span class="text-muted example"><strong>Example:&nbsp;</strong><span>fr,en</span></span><p>One or more languages of the elements to fetch</p>
</dd></dl></div></div></div><hr class="split"><div class="middle"><section id="specifications" class="resource-group"><h2 class="group-heading">Specifications <a href="#specifications" class="permalink">&para;</a></h2></section></div><div class="middle"><div id="specifications-specifications" class="resource"><h3 class="resource-heading">Specifications <a href="#specifications-specifications" class="permalink">&para;</a></h3></div></div><div class="right"><div class="definition"><span class="method get">GET</span>&nbsp;<span class="uri"><span class="hostname">{{ route('api.blueprint') }}</span>/specifications?<span class="hljs-attribute">page=</span><span class="hljs-literal">4</span>&<span class="hljs-attribute">per_page=</span><span class="hljs-literal">2</span>&<span class="hljs-attribute">lang=</span><span class="hljs-literal">fr,en</span></span></div><div class="tabs"><div class="example-names"><span>Requests</span><span class="tab-button">example 1</span><span class="tab-button">example 2</span><span class="tab-button">example 3</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Accept</span>: <span class="hljs-string">application/vnd.soprema.v1+json</span><br><span class="hljs-attribute">Authorization</span>: <span class="hljs-string">Bearer JSON_WEB_TOKEN</span></code></pre><div style="height: 1px;"></div></div></div><div class="tabs"><div class="example-names"><span>Responses</span><span class="tab-button">200</span><span class="tab-button">404</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span><br><span class="hljs-attribute">Link</span>: <span class="hljs-string">&lt;{{ route('api.specifications') }}?page=1&gt;; rel="first", &lt;{{ route('api.specifications') }}?page=3&gt;; rel="prev", &lt;{{ route('api.specifications') }}?page=5&gt;; rel="next", &lt;{{ route('api.specifications') }}?page=500&gt;; rel="last"</span><br><span class="hljs-attribute">X-RateLimit-Limit</span>: <span class="hljs-string">60</span><br><span class="hljs-attribute">X-RateLimit-Remaining</span>: <span class="hljs-string">59</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">data</span>": <span class="hljs-value">[
    {
      "<span class="hljs-attribute">id</span>": <span class="hljs-value"><span class="hljs-number">7</span></span>,
      "<span class="hljs-attribute">company_id</span>": <span class="hljs-value"><span class="hljs-number">1</span></span>,
      "<span class="hljs-attribute">specification_name</span>": <span class="hljs-value">[
        {
          "<span class="hljs-attribute">fr</span>": <span class="hljs-value"><span class="hljs-string">"aut et culpa"</span></span>,
          "<span class="hljs-attribute">en</span>": <span class="hljs-value"><span class="hljs-string">"omnis doloremque est"</span>
        </span>}
      ]
    </span>},
    {
      "<span class="hljs-attribute">id</span>": <span class="hljs-value"><span class="hljs-number">8</span></span>,
      "<span class="hljs-attribute">company_id</span>": <span class="hljs-value"><span class="hljs-number">1</span></span>,
      "<span class="hljs-attribute">specification_name</span>": <span class="hljs-value">[
        {
          "<span class="hljs-attribute">fr</span>": <span class="hljs-value"><span class="hljs-string">"deserunt quaerat officiis"</span></span>,
          "<span class="hljs-attribute">en</span>": <span class="hljs-value"><span class="hljs-string">"soluta minus quod"</span>
        </span>}
      ]
    </span>}
  ]</span>,
  "<span class="hljs-attribute">meta</span>": <span class="hljs-value">{
    "<span class="hljs-attribute">pagination</span>": <span class="hljs-value">{
      "<span class="hljs-attribute">first</span>": <span class="hljs-value"><span class="hljs-string">"{{ route('api.specifications') }}?page=1"</span></span>,
      "<span class="hljs-attribute">prev</span>": <span class="hljs-value"><span class="hljs-string">"{{ route('api.specifications') }}?page=3"</span></span>,
      "<span class="hljs-attribute">next</span>": <span class="hljs-value"><span class="hljs-string">"{{ route('api.specifications') }}?page=5"</span></span>,
      "<span class="hljs-attribute">last</span>": <span class="hljs-value"><span class="hljs-string">"{{ route('api.specifications') }}?page=500"</span>
    </span>}
  </span>}
</span>}</code></pre><div style="height: 1px;"></div></div></div></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">status</span>": <span class="hljs-value"><span class="hljs-string">"not-found"</span></span>,
  "<span class="hljs-attribute">message</span>": <span class="hljs-value"><span class="hljs-string">"The resource could not found."</span>
</span>}</code></pre><div style="height: 1px;"></div></div></div></div></div></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Accept</span>: <span class="hljs-string">application/vnd.soprema.v1+json</span><br><span class="hljs-attribute">Authorization</span>: <span class="hljs-string">Bearer BAD_JSON_WEB_TOKEN</span></code></pre><div style="height: 1px;"></div></div></div><div class="tabs"><div class="example-names"><span>Responses</span><span class="tab-button">403</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">status</span>": <span class="hljs-value"><span class="hljs-string">"forbidden"</span></span>,
  "<span class="hljs-attribute">message</span>": <span class="hljs-value"><span class="hljs-string">"You do not have access for the resource."</span>
</span>}</code></pre><div style="height: 1px;"></div></div></div></div></div></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Authorization</span>: <span class="hljs-string">Bearer JSON_WEB_TOKEN</span></code></pre><div style="height: 1px;"></div></div></div><div class="tabs"><div class="example-names"><span>Responses</span><span class="tab-button">400</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">status</span>": <span class="hljs-value"><span class="hljs-string">"bad-request"</span></span>,
  "<span class="hljs-attribute">message</span>": <span class="hljs-value"><span class="hljs-string">"Missing version header."</span>
</span>}</code></pre><div style="height: 1px;"></div></div></div></div></div></div></div></div><div class="middle"><div id="specifications-specifications-get" class="action get"><h4 class="action-heading"><div class="name">Get specifications</div><a href="#specifications-specifications-get" class="method get">GET</a><code class="uri">/specifications{?page,per_page,lang}</code></h4><p>Get all specifications</p>
<div class="title"><strong>URI Parameters</strong><div class="collapse-button show"><span class="close">Hide</span><span class="open">Show</span></div></div><div class="collapse-content"><dl class="inner"><dt>page</dt><dd><code>integer</code>&nbsp;<span>(optional)</span>&nbsp;<span class="text-muted example"><strong>Example:&nbsp;</strong><span>4</span></span><p>Page to display</p>
</dd><dt>per_page</dt><dd><code>integer</code>&nbsp;<span>(optional)</span>&nbsp;<span class="text-info default"><strong>Default:&nbsp;</strong><span>15</span></span>&nbsp;<span class="text-muted example"><strong>Example:&nbsp;</strong><span>2</span></span><p>Elements to display per page</p>
</dd><dt>lang</dt><dd><code>array</code>&nbsp;<span>(optional)</span>&nbsp;<span class="text-muted example"><strong>Example:&nbsp;</strong><span>fr,en</span></span><p>One or more languages of the elements to fetch</p>
</dd></dl></div></div></div><hr class="split"><div class="right"><div class="definition"><span class="method get">GET</span>&nbsp;<span class="uri"><span class="hostname">{{ route('api.blueprint') }}</span>/specifications/<span class="hljs-attribute" title="specification">7</span>?<span class="hljs-attribute">lang=</span><span class="hljs-literal">fr,en</span></span></div><div class="tabs"><div class="example-names"><span>Requests</span><span class="tab-button">example 1</span><span class="tab-button">example 2</span><span class="tab-button">example 3</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Accept</span>: <span class="hljs-string">application/vnd.soprema.v1+json</span><br><span class="hljs-attribute">Authorization</span>: <span class="hljs-string">Bearer JSON_WEB_TOKEN</span></code></pre><div style="height: 1px;"></div></div></div><div class="tabs"><div class="example-names"><span>Responses</span><span class="tab-button">200</span><span class="tab-button">404</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span><br><span class="hljs-attribute">Link</span>: <span class="hljs-string">&lt;{{ route('api.specifications') }}&gt;; rel="specifications"</span><br><span class="hljs-attribute">X-RateLimit-Limit</span>: <span class="hljs-string">60</span><br><span class="hljs-attribute">X-RateLimit-Remaining</span>: <span class="hljs-string">59</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">data</span>": <span class="hljs-value">{
    "<span class="hljs-attribute">id</span>": <span class="hljs-value"><span class="hljs-number">7</span></span>,
    "<span class="hljs-attribute">company_id</span>": <span class="hljs-value"><span class="hljs-number">1</span></span>,
    "<span class="hljs-attribute">specification_name</span>": <span class="hljs-value">[
      {
        "<span class="hljs-attribute">fr</span>": <span class="hljs-value"><span class="hljs-string">"aut et culpa"</span></span>,
        "<span class="hljs-attribute">en</span>": <span class="hljs-value"><span class="hljs-string">"omnis doloremque est"</span>
      </span>}
    ]
  </span>}</span>,
  "<span class="hljs-attribute">meta</span>": <span class="hljs-value">{
    "<span class="hljs-attribute">links</span>": <span class="hljs-value">{
      "<span class="hljs-attribute">specifications</span>": <span class="hljs-value"><span class="hljs-string">"{{ route('api.specifications') }}"</span>
    </span>}
  </span>}
</span>}</code></pre><div style="height: 1px;"></div></div></div></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">status</span>": <span class="hljs-value"><span class="hljs-string">"not-found"</span></span>,
  "<span class="hljs-attribute">message</span>": <span class="hljs-value"><span class="hljs-string">"The resource could not found."</span>
</span>}</code></pre><div style="height: 1px;"></div></div></div></div></div></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Accept</span>: <span class="hljs-string">application/vnd.soprema.v1+json</span><br><span class="hljs-attribute">Authorization</span>: <span class="hljs-string">Bearer BAD_JSON_WEB_TOKEN</span></code></pre><div style="height: 1px;"></div></div></div><div class="tabs"><div class="example-names"><span>Responses</span><span class="tab-button">403</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">status</span>": <span class="hljs-value"><span class="hljs-string">"forbidden"</span></span>,
  "<span class="hljs-attribute">message</span>": <span class="hljs-value"><span class="hljs-string">"You do not have access for the resource."</span>
</span>}</code></pre><div style="height: 1px;"></div></div></div></div></div></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Authorization</span>: <span class="hljs-string">Bearer JSON_WEB_TOKEN</span></code></pre><div style="height: 1px;"></div></div></div><div class="tabs"><div class="example-names"><span>Responses</span><span class="tab-button">400</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">status</span>": <span class="hljs-value"><span class="hljs-string">"bad-request"</span></span>,
  "<span class="hljs-attribute">message</span>": <span class="hljs-value"><span class="hljs-string">"Missing version header."</span>
</span>}</code></pre><div style="height: 1px;"></div></div></div></div></div></div></div></div><div class="middle"><div id="specifications-specifications-get-1" class="action get"><h4 class="action-heading"><div class="name">Get a specification</div><a href="#specifications-specifications-get-1" class="method get">GET</a><code class="uri">/specifications/{specification}{?lang}</code></h4><p>Get a specification object</p>
<div class="title"><strong>URI Parameters</strong><div class="collapse-button show"><span class="close">Hide</span><span class="open">Show</span></div></div><div class="collapse-content"><dl class="inner"><dt>specification</dt><dd><code>integer</code>&nbsp;<span class="required">(required)</span>&nbsp;<span class="text-muted example"><strong>Example:&nbsp;</strong><span>7</span></span><p>Id of the specification to fetch</p>
</dd><dt>lang</dt><dd><code>array</code>&nbsp;<span>(optional)</span>&nbsp;<span class="text-muted example"><strong>Example:&nbsp;</strong><span>fr,en</span></span><p>One or more languages of the elements to fetch</p>
</dd></dl></div></div></div><hr class="split"><div class="middle"><section id="technical-bulletins" class="resource-group"><h2 class="group-heading">Technical Bulletins <a href="#technical-bulletins" class="permalink">&para;</a></h2></section></div><div class="middle"><div id="technical-bulletins-technical-bulletins" class="resource"><h3 class="resource-heading">Technical Bulletins <a href="#technical-bulletins-technical-bulletins" class="permalink">&para;</a></h3></div></div><div class="right"><div class="definition"><span class="method get">GET</span>&nbsp;<span class="uri"><span class="hostname">{{ route('api.blueprint') }}</span>/technical-bulletins?<span class="hljs-attribute">page=</span><span class="hljs-literal">4</span>&<span class="hljs-attribute">per_page=</span><span class="hljs-literal">2</span>&<span class="hljs-attribute">lang=</span><span class="hljs-literal">fr,en</span></span></div><div class="tabs"><div class="example-names"><span>Requests</span><span class="tab-button">example 1</span><span class="tab-button">example 2</span><span class="tab-button">example 3</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Accept</span>: <span class="hljs-string">application/vnd.soprema.v1+json</span><br><span class="hljs-attribute">Authorization</span>: <span class="hljs-string">Bearer JSON_WEB_TOKEN</span></code></pre><div style="height: 1px;"></div></div></div><div class="tabs"><div class="example-names"><span>Responses</span><span class="tab-button">200</span><span class="tab-button">404</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span><br><span class="hljs-attribute">Link</span>: <span class="hljs-string">&lt;{{ route('api.technical-bulletins') }}?page=1&gt;; rel="first", &lt;{{ route('api.technical-bulletins') }}?page=3&gt;; rel="prev", &lt;{{ route('api.technical-bulletins') }}?page=5&gt;; rel="next", &lt;{{ route('api.technical-bulletins') }}?page=500&gt;; rel="last"</span><br><span class="hljs-attribute">X-RateLimit-Limit</span>: <span class="hljs-string">60</span><br><span class="hljs-attribute">X-RateLimit-Remaining</span>: <span class="hljs-string">59</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">data</span>": <span class="hljs-value">[
    {
      "<span class="hljs-attribute">id</span>": <span class="hljs-value"><span class="hljs-number">7</span></span>,
      "<span class="hljs-attribute">company_id</span>": <span class="hljs-value"><span class="hljs-number">1</span></span>,
      "<span class="hljs-attribute">technical_bulletin_name</span>": <span class="hljs-value">[
        {
          "<span class="hljs-attribute">fr</span>": <span class="hljs-value"><span class="hljs-string">"aut et culpa"</span></span>,
          "<span class="hljs-attribute">en</span>": <span class="hljs-value"><span class="hljs-string">"omnis doloremque est"</span>
        </span>}
      ]
    </span>},
    {
      "<span class="hljs-attribute">id</span>": <span class="hljs-value"><span class="hljs-number">8</span></span>,
      "<span class="hljs-attribute">company_id</span>": <span class="hljs-value"><span class="hljs-number">1</span></span>,
      "<span class="hljs-attribute">technical_bulletin_name</span>": <span class="hljs-value">[
        {
          "<span class="hljs-attribute">fr</span>": <span class="hljs-value"><span class="hljs-string">"deserunt quaerat officiis"</span></span>,
          "<span class="hljs-attribute">en</span>": <span class="hljs-value"><span class="hljs-string">"soluta minus quod"</span>
        </span>}
      ]
    </span>}
  ]</span>,
  "<span class="hljs-attribute">meta</span>": <span class="hljs-value">{
    "<span class="hljs-attribute">pagination</span>": <span class="hljs-value">{
      "<span class="hljs-attribute">first</span>": <span class="hljs-value"><span class="hljs-string">"{{ route('api.technical-bulletins') }}?page=1"</span></span>,
      "<span class="hljs-attribute">prev</span>": <span class="hljs-value"><span class="hljs-string">"{{ route('api.technical-bulletins') }}?page=3"</span></span>,
      "<span class="hljs-attribute">next</span>": <span class="hljs-value"><span class="hljs-string">"{{ route('api.technical-bulletins') }}?page=5"</span></span>,
      "<span class="hljs-attribute">last</span>": <span class="hljs-value"><span class="hljs-string">"{{ route('api.technical-bulletins') }}?page=500"</span>
    </span>}
  </span>}
</span>}</code></pre><div style="height: 1px;"></div></div></div></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">status</span>": <span class="hljs-value"><span class="hljs-string">"not-found"</span></span>,
  "<span class="hljs-attribute">message</span>": <span class="hljs-value"><span class="hljs-string">"The resource could not found."</span>
</span>}</code></pre><div style="height: 1px;"></div></div></div></div></div></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Accept</span>: <span class="hljs-string">application/vnd.soprema.v1+json</span><br><span class="hljs-attribute">Authorization</span>: <span class="hljs-string">Bearer BAD_JSON_WEB_TOKEN</span></code></pre><div style="height: 1px;"></div></div></div><div class="tabs"><div class="example-names"><span>Responses</span><span class="tab-button">403</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">status</span>": <span class="hljs-value"><span class="hljs-string">"forbidden"</span></span>,
  "<span class="hljs-attribute">message</span>": <span class="hljs-value"><span class="hljs-string">"You do not have access for the resource."</span>
</span>}</code></pre><div style="height: 1px;"></div></div></div></div></div></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Authorization</span>: <span class="hljs-string">Bearer JSON_WEB_TOKEN</span></code></pre><div style="height: 1px;"></div></div></div><div class="tabs"><div class="example-names"><span>Responses</span><span class="tab-button">400</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">status</span>": <span class="hljs-value"><span class="hljs-string">"bad-request"</span></span>,
  "<span class="hljs-attribute">message</span>": <span class="hljs-value"><span class="hljs-string">"Missing version header."</span>
</span>}</code></pre><div style="height: 1px;"></div></div></div></div></div></div></div></div><div class="middle"><div id="technical-bulletins-technical-bulletins-get" class="action get"><h4 class="action-heading"><div class="name">Get technical-bulletins</div><a href="#technical-bulletins-technical-bulletins-get" class="method get">GET</a><code class="uri">/technical-bulletins{?page,per_page,lang}</code></h4><p>Get all technical bulletins</p>
<div class="title"><strong>URI Parameters</strong><div class="collapse-button show"><span class="close">Hide</span><span class="open">Show</span></div></div><div class="collapse-content"><dl class="inner"><dt>page</dt><dd><code>integer</code>&nbsp;<span>(optional)</span>&nbsp;<span class="text-muted example"><strong>Example:&nbsp;</strong><span>4</span></span><p>Page to display</p>
</dd><dt>per_page</dt><dd><code>integer</code>&nbsp;<span>(optional)</span>&nbsp;<span class="text-info default"><strong>Default:&nbsp;</strong><span>15</span></span>&nbsp;<span class="text-muted example"><strong>Example:&nbsp;</strong><span>2</span></span><p>Elements to display per page</p>
</dd><dt>lang</dt><dd><code>array</code>&nbsp;<span>(optional)</span>&nbsp;<span class="text-muted example"><strong>Example:&nbsp;</strong><span>fr,en</span></span><p>One or more languages of the elements to fetch</p>
</dd></dl></div></div></div><hr class="split"><div class="right"><div class="definition"><span class="method get">GET</span>&nbsp;<span class="uri"><span class="hostname">{{ route('api.blueprint') }}</span>/technical-bulletins/<span class="hljs-attribute" title="technical_bulletin">7</span>?<span class="hljs-attribute">lang=</span><span class="hljs-literal">fr,en</span></span></div><div class="tabs"><div class="example-names"><span>Requests</span><span class="tab-button">example 1</span><span class="tab-button">example 2</span><span class="tab-button">example 3</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Accept</span>: <span class="hljs-string">application/vnd.soprema.v1+json</span><br><span class="hljs-attribute">Authorization</span>: <span class="hljs-string">Bearer JSON_WEB_TOKEN</span></code></pre><div style="height: 1px;"></div></div></div><div class="tabs"><div class="example-names"><span>Responses</span><span class="tab-button">200</span><span class="tab-button">404</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span><br><span class="hljs-attribute">Link</span>: <span class="hljs-string">&lt;{{ route('api.technical-bulletins') }}&gt;; rel="technical-bulletins"</span><br><span class="hljs-attribute">X-RateLimit-Limit</span>: <span class="hljs-string">60</span><br><span class="hljs-attribute">X-RateLimit-Remaining</span>: <span class="hljs-string">59</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">data</span>": <span class="hljs-value">{
    "<span class="hljs-attribute">id</span>": <span class="hljs-value"><span class="hljs-number">7</span></span>,
    "<span class="hljs-attribute">company_id</span>": <span class="hljs-value"><span class="hljs-number">1</span></span>,
    "<span class="hljs-attribute">technical_bulletin_name</span>": <span class="hljs-value">[
      {
        "<span class="hljs-attribute">fr</span>": <span class="hljs-value"><span class="hljs-string">"aut et culpa"</span></span>,
        "<span class="hljs-attribute">en</span>": <span class="hljs-value"><span class="hljs-string">"omnis doloremque est"</span>
      </span>}
    ]
  </span>}</span>,
  "<span class="hljs-attribute">meta</span>": <span class="hljs-value">{
    "<span class="hljs-attribute">links</span>": <span class="hljs-value">{
      "<span class="hljs-attribute">technical-bulletins</span>": <span class="hljs-value"><span class="hljs-string">"{{ route('api.technical-bulletins') }}"</span>
    </span>}
  </span>}
</span>}</code></pre><div style="height: 1px;"></div></div></div></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">status</span>": <span class="hljs-value"><span class="hljs-string">"not-found"</span></span>,
  "<span class="hljs-attribute">message</span>": <span class="hljs-value"><span class="hljs-string">"The resource could not found."</span>
</span>}</code></pre><div style="height: 1px;"></div></div></div></div></div></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Accept</span>: <span class="hljs-string">application/vnd.soprema.v1+json</span><br><span class="hljs-attribute">Authorization</span>: <span class="hljs-string">Bearer BAD_JSON_WEB_TOKEN</span></code></pre><div style="height: 1px;"></div></div></div><div class="tabs"><div class="example-names"><span>Responses</span><span class="tab-button">403</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">status</span>": <span class="hljs-value"><span class="hljs-string">"forbidden"</span></span>,
  "<span class="hljs-attribute">message</span>": <span class="hljs-value"><span class="hljs-string">"You do not have access for the resource."</span>
</span>}</code></pre><div style="height: 1px;"></div></div></div></div></div></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Authorization</span>: <span class="hljs-string">Bearer JSON_WEB_TOKEN</span></code></pre><div style="height: 1px;"></div></div></div><div class="tabs"><div class="example-names"><span>Responses</span><span class="tab-button">400</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">status</span>": <span class="hljs-value"><span class="hljs-string">"bad-request"</span></span>,
  "<span class="hljs-attribute">message</span>": <span class="hljs-value"><span class="hljs-string">"Missing version header."</span>
</span>}</code></pre><div style="height: 1px;"></div></div></div></div></div></div></div></div><div class="middle"><div id="technical-bulletins-technical-bulletins-get-1" class="action get"><h4 class="action-heading"><div class="name">Get a technical bulletin</div><a href="#technical-bulletins-technical-bulletins-get-1" class="method get">GET</a><code class="uri">/technical-bulletins/{technical_bulletin}{?lang}</code></h4><p>Get a technical bulletin object</p>
<div class="title"><strong>URI Parameters</strong><div class="collapse-button show"><span class="close">Hide</span><span class="open">Show</span></div></div><div class="collapse-content"><dl class="inner"><dt>technical_bulletin</dt><dd><code>integer</code>&nbsp;<span class="required">(required)</span>&nbsp;<span class="text-muted example"><strong>Example:&nbsp;</strong><span>7</span></span><p>Id of the technical bulletin to fetch</p>
</dd><dt>lang</dt><dd><code>array</code>&nbsp;<span>(optional)</span>&nbsp;<span class="text-muted example"><strong>Example:&nbsp;</strong><span>fr,en</span></span><p>One or more languages of the elements to fetch</p>
</dd></dl></div></div></div><hr class="split"><div class="middle"><section id="languages" class="resource-group"><h2 class="group-heading">Languages <a href="#languages" class="permalink">&para;</a></h2></section></div><div class="middle"><div id="languages-languages" class="resource"><h3 class="resource-heading">Languages <a href="#languages-languages" class="permalink">&para;</a></h3></div></div><div class="right"><div class="definition"><span class="method get">GET</span>&nbsp;<span class="uri"><span class="hostname">{{ route('api.blueprint') }}</span>/languages</span></div><div class="tabs"><div class="example-names"><span>Requests</span><span class="tab-button">example 1</span><span class="tab-button">example 2</span><span class="tab-button">example 3</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Accept</span>: <span class="hljs-string">application/vnd.soprema.v1+json</span><br><span class="hljs-attribute">Authorization</span>: <span class="hljs-string">Bearer JSON_WEB_TOKEN</span></code></pre><div style="height: 1px;"></div></div></div><div class="tabs"><div class="example-names"><span>Responses</span><span class="tab-button">200</span><span class="tab-button">404</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span><br><span class="hljs-attribute">X-RateLimit-Limit</span>: <span class="hljs-string">60</span><br><span class="hljs-attribute">X-RateLimit-Remaining</span>: <span class="hljs-string">59</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">data</span>": <span class="hljs-value">[
    {
      "<span class="hljs-attribute">name</span>": <span class="hljs-value"><span class="hljs-string">"Français (Canada)"</span></span>,
      "<span class="hljs-attribute">code</span>": <span class="hljs-value"><span class="hljs-string">"fr"</span>
    </span>},
    {
      "<span class="hljs-attribute">name</span>": <span class="hljs-value"><span class="hljs-string">"English (Canada)"</span></span>,
      "<span class="hljs-attribute">code</span>": <span class="hljs-value"><span class="hljs-string">"en"</span>
    </span>}
  ]
</span>}</code></pre><div style="height: 1px;"></div></div></div></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">status</span>": <span class="hljs-value"><span class="hljs-string">"not-found"</span></span>,
  "<span class="hljs-attribute">message</span>": <span class="hljs-value"><span class="hljs-string">"The resource could not found."</span>
</span>}</code></pre><div style="height: 1px;"></div></div></div></div></div></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Accept</span>: <span class="hljs-string">application/vnd.soprema.v1+json</span><br><span class="hljs-attribute">Authorization</span>: <span class="hljs-string">Bearer BAD_JSON_WEB_TOKEN</span></code></pre><div style="height: 1px;"></div></div></div><div class="tabs"><div class="example-names"><span>Responses</span><span class="tab-button">403</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">status</span>": <span class="hljs-value"><span class="hljs-string">"forbidden"</span></span>,
  "<span class="hljs-attribute">message</span>": <span class="hljs-value"><span class="hljs-string">"You do not have access for the resource."</span>
</span>}</code></pre><div style="height: 1px;"></div></div></div></div></div></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Authorization</span>: <span class="hljs-string">Bearer JSON_WEB_TOKEN</span></code></pre><div style="height: 1px;"></div></div></div><div class="tabs"><div class="example-names"><span>Responses</span><span class="tab-button">400</span></div><div class="tab"><div><div class="inner"><h5>Headers</h5><pre><code><span class="hljs-attribute">Content-Type</span>: <span class="hljs-string">application/json; charset=utf-8</span></code></pre><div style="height: 1px;"></div><h5>Body</h5><pre><code>{
  "<span class="hljs-attribute">status</span>": <span class="hljs-value"><span class="hljs-string">"bad-request"</span></span>,
  "<span class="hljs-attribute">message</span>": <span class="hljs-value"><span class="hljs-string">"Missing version header."</span>
</span>}</code></pre><div style="height: 1px;"></div></div></div></div></div></div></div></div><div class="middle"><div id="languages-languages-get" class="action get"><h4 class="action-heading"><div class="name">Get languages</div><a href="#languages-languages-get" class="method get">GET</a><code class="uri">/languages</code></h4><p>Get all languages</p>
</div></div><hr class="split"><div class="middle"><p style="text-align: center;" class="text-muted">Generated by&nbsp;<a href="https://github.com/danielgtaylor/aglio" class="aglio">aglio</a>&nbsp;on 13 Apr 2016</p></div></div></div></div><script>/* eslint-env browser */
/* eslint quotes: [2, "single"] */
'use strict';

/*
  Determine if a string ends with another string.
*/
function endsWith(str, suffix) {
    return str.indexOf(suffix, str.length - suffix.length) !== -1;
}

/*
  Get a list of direct child elements by class name.
*/
function childrenByClass(element, name) {
  var filtered = [];

  for (var i = 0; i < element.children.length; i++) {
    var child = element.children[i];
    var classNames = child.className.split(' ');
    if (classNames.indexOf(name) !== -1) {
      filtered.push(child);
    }
  }

  return filtered;
}

/*
  Get an array [width, height] of the window.
*/
function getWindowDimensions() {
  var w = window,
      d = document,
      e = d.documentElement,
      g = d.body,
      x = w.innerWidth || e.clientWidth || g.clientWidth,
      y = w.innerHeight || e.clientHeight || g.clientHeight;

  return [x, y];
}

/*
  Collapse or show a request/response example.
*/
function toggleCollapseButton(event) {
    var button = event.target.parentNode;
    var content = button.parentNode.nextSibling;
    var inner = content.children[0];

    if (button.className.indexOf('collapse-button') === -1) {
      // Clicked without hitting the right element?
      return;
    }

    if (content.style.maxHeight && content.style.maxHeight !== '0px') {
        // Currently showing, so let's hide it
        button.className = 'collapse-button';
        content.style.maxHeight = '0px';
    } else {
        // Currently hidden, so let's show it
        button.className = 'collapse-button show';
        content.style.maxHeight = inner.offsetHeight + 12 + 'px';
    }
}

function toggleTabButton(event) {
    var i, index;
    var button = event.target;

    // Get index of the current button.
    var buttons = childrenByClass(button.parentNode, 'tab-button');
    for (i = 0; i < buttons.length; i++) {
        if (buttons[i] === button) {
            index = i;
            button.className = 'tab-button active';
        } else {
            buttons[i].className = 'tab-button';
        }
    }

    // Hide other tabs and show this one.
    var tabs = childrenByClass(button.parentNode.parentNode, 'tab');
    for (i = 0; i < tabs.length; i++) {
        if (i === index) {
            tabs[i].style.display = 'block';
        } else {
            tabs[i].style.display = 'none';
        }
    }
}

/*
  Collapse or show a navigation menu. It will not be hidden unless it
  is currently selected or `force` has been passed.
*/
function toggleCollapseNav(event, force) {
    var heading = event.target.parentNode;
    var content = heading.nextSibling;
    var inner = content.children[0];

    if (heading.className.indexOf('heading') === -1) {
      // Clicked without hitting the right element?
      return;
    }

    if (content.style.maxHeight && content.style.maxHeight !== '0px') {
      // Currently showing, so let's hide it, but only if this nav item
      // is already selected. This prevents newly selected items from
      // collapsing in an annoying fashion.
      if (force || window.location.hash && endsWith(event.target.href, window.location.hash)) {
        content.style.maxHeight = '0px';
      }
    } else {
      // Currently hidden, so let's show it
      content.style.maxHeight = inner.offsetHeight + 12 + 'px';
    }
}

/*
  Refresh the page after a live update from the server. This only
  works in live preview mode (using the `--server` parameter).
*/
function refresh(body) {
    document.querySelector('body').className = 'preload';
    document.body.innerHTML = body;

    // Re-initialize the page
    init();
    autoCollapse();

    document.querySelector('body').className = '';
}

/*
  Determine which navigation items should be auto-collapsed to show as many
  as possible on the screen, based on the current window height. This also
  collapses them.
*/
function autoCollapse() {
  var windowHeight = getWindowDimensions()[1];
  var itemsHeight = 64; /* Account for some padding */
  var itemsArray = Array.prototype.slice.call(
    document.querySelectorAll('nav .resource-group .heading'));

  // Get the total height of the navigation items
  itemsArray.forEach(function (item) {
    itemsHeight += item.parentNode.offsetHeight;
  });

  // Should we auto-collapse any nav items? Try to find the smallest item
  // that can be collapsed to show all items on the screen. If not possible,
  // then collapse the largest item and do it again. First, sort the items
  // by height from smallest to largest.
  var sortedItems = itemsArray.sort(function (a, b) {
    return a.parentNode.offsetHeight - b.parentNode.offsetHeight;
  });

  while (sortedItems.length && itemsHeight > windowHeight) {
    for (var i = 0; i < sortedItems.length; i++) {
      // Will collapsing this item help?
      var itemHeight = sortedItems[i].nextSibling.offsetHeight;
      if ((itemsHeight - itemHeight <= windowHeight) || i === sortedItems.length - 1) {
        // It will, so let's collapse it, remove its content height from
        // our total and then remove it from our list of candidates
        // that can be collapsed.
        itemsHeight -= itemHeight;
        toggleCollapseNav({target: sortedItems[i].children[0]}, true);
        sortedItems.splice(i, 1);
        break;
      }
    }
  }
}

/*
  Initialize the interactive functionality of the page.
*/
function init() {
    var i, j;

    // Make collapse buttons clickable
    var buttons = document.querySelectorAll('.collapse-button');
    for (i = 0; i < buttons.length; i++) {
        buttons[i].onclick = toggleCollapseButton;

        // Show by default? Then toggle now.
        if (buttons[i].className.indexOf('show') !== -1) {
            toggleCollapseButton({target: buttons[i].children[0]});
        }
    }

    var responseCodes = document.querySelectorAll('.example-names');
    for (i = 0; i < responseCodes.length; i++) {
        var tabButtons = childrenByClass(responseCodes[i], 'tab-button');
        for (j = 0; j < tabButtons.length; j++) {
            tabButtons[j].onclick = toggleTabButton;

            // Show by default?
            if (j === 0) {
                toggleTabButton({target: tabButtons[j]});
            }
        }
    }

    // Make nav items clickable to collapse/expand their content.
    var navItems = document.querySelectorAll('nav .resource-group .heading');
    for (i = 0; i < navItems.length; i++) {
        navItems[i].onclick = toggleCollapseNav;

        // Show all by default
        toggleCollapseNav({target: navItems[i].children[0]});
    }
}

// Initial call to set up buttons
init();

window.onload = function () {
    autoCollapse();
    // Remove the `preload` class to enable animations
    document.querySelector('body').className = '';
};
</script></body></html>
