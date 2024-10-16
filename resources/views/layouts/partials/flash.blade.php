@if (session('status'))
    <div class="alert alert-success text-center @if(!request()->is('admin/*'))position-fixed @endif">
        {!! session('status') !!}
    </div>
@endif

@if (session('success'))
    <div class="alert alert-success text-center @if(!request()->is('admin/*'))position-fixed @endif">
        {!! session('success') !!}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger text-center @if(!request()->is('admin/*'))position-fixed @endif">
        {!! session('error') !!}
    </div>
@endif

@if (session('warning'))
    <div class="alert alert-warning text-center @if(!request()->is('admin/*'))position-fixed @endif">
        {!! session('warning') !!}
    </div>
@endif

@if (session('info'))
    <div class="alert alert-info text-center @if(!request()->is('admin/*'))position-fixed @endif">
        {!! session('info') !!}
    </div>
@endif
