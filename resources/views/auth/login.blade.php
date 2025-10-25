@extends('home.base')

@section('title', 'login')

@section('content')

@if (session('status'))
    <div class="alert alert-success text-center">
        {{ session('status') }}
    </div>
@endif


<div class="container">
    <div class="row">
       <div class="col-11 col-sm-8 col-md-6 col-lg-4 mx-auto">
        <h1 class="text-center text-muted mb-3 mt-3 mt-md-5" style="font-size: clamp(1.5rem, 4vw, 2rem);">Veuillez-vous connecter</h1>
        <!--<p class="text-center text-muted mb-5">Your articles are waiting for you.</p>-->


       <form method="post" action="{{ route('login')}}">
         @csrf
         @error('email')
         <div class="alert alert-danger text-center" role="alert">

            {{ $message }}

         </div>
          @enderror

          @error('password')
         <div class="alert alert-danger text-center" role="alert">

            {{ $message }}

         </div>
         @enderror

         <label for="email">Email</label>
         <input type="email" name="email" id="email" class="form-control mb-3 @error('email') is-invalid @enderror" value="{{ old('email')}}" required autocomplete="email" autofocus>

         <label for="password">Mot de pass</label>
         <input type="password" name="password" id="password" class="form-control mb-3 @error('password') is-invalid @enderror" required autocomplete="current-password">
         <div class="row mb-3">
            <div class="col-md-6">
                <div class="form-check form-switch">
  <input class="form-check-input" type="checkbox" role="switch" id="remember" name="remember" {{ old('remember') ? 'checked': ''}}>
  <label class="form-check-label" for="remember">Se souvenir de moi</label>
</div>
            </div>

            <div class="col-12 col-md-6 text-center text-md-end mt-2 mt-md-0">
                <a href="#" class="text-decoration-none">Mot de pass oublié?</a>
            </div>
         </div>

<div class="d-grid gap-2 mt-4">
     <button class="btn btn-primary btn-lg" type="submit" style="padding: 12px; font-size: 1.1rem;">Se connecter</button>
</div>

<p class="text-center text-muted mt-4 mt-md-5" style="font-size: clamp(0.9rem, 2.5vw, 1rem);">
    Vous n'êtes pas encore enregistré? 
    <a href="{{ route('register')}}" class="text-decoration-none fw-bold">Créer un compte</a>
</p>
        </form>
        </div>
    </div>
</div>

@endsection
