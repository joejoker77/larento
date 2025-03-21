@extends('layouts.empty')

@section('content')
    <div class="row login">
        <div class="col d-flex flex-column justify-content-between">
            <div class="h1 text-nowrap">Админ Larento</div>
            <form method="POST" action="{{ route('login') }}" class="d-flex flex-column justify-content-between h-100">
                @csrf
                <div class="form-floating mb-3">
                    <input id="emailLogin" type="text" placeholder="email" class="form-control @error('email', 'login') is-invalid @enderror" name="email" value="{{ old('email') }}" required>
                    <label for="emailLogin">E-Mail адрес или телефон</label>
                    @error('email', 'login')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-floating mb-3">
                    <input id="passwordLogin" type="password" placeholder="Пароль" class="form-control @error('password') is-invalid @enderror" name="password" required>
                    <label for="passwordLogin">Пароль</label>
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="row mb-3 mt-auto">
                    <div class="col">
                        <div class="form-check p-0">
                            <input type="checkbox" checked name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label for="remember" class="form-check-label">Запомнить меня</label>
                        </div>
                    </div>
                    <div class="col text-end">
                        <a class="btn btn-link" href="{{ route('password.request') }}">Забыли пароль?</a>
                    </div>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-lg btn-blue-dark w-100">Войти</button>
                </div>
            </form>
        </div>
    </div>
@endsection
