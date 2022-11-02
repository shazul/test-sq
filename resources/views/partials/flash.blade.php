@if(session()->has('flash_messages'))
<section class="content-header">
    <div class="row">
        <div class="col-sm-12">

            @foreach(session('flash_messages') as $flash)
            <div class="alert alert-{{ $flash['type'] }} @if($flash['dismiss']) alert-dismissable @endif">

                @if($flash['dismiss'])
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                @endif

                <div>
                    <i class="icon fa fa-{{ $flash['icon'] }}"></i>
                    {{ $flash['message'] }}
                </div>

            </div>
            @endforeach

        </div>
    </div>
</section>
@endif
