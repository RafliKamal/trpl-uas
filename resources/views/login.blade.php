@extends('layouts.user_type.guest')

@section('content')

  <main class="main-content  mt-0">
    <section>
    <div class="page-header min-vh-75">
      <div class="container">
      <div class="row">
        <div class="col-xl-4 col-lg-5 col-md-6 d-flex flex-column mx-auto">
        <div class="card card-plain mt-8">
          <div class="card-header pb-0 text-left bg-transparent">
          <h3 class="font-weight-bolder text-info text-gradient">Welcome back</h3>

          <div class="card-body">
            <form role="form" method="POST" action="/login">
            @csrf
            <label>NIM/NIDN</label>
            <div class="mb-3">
              <input type="text" class="form-control" name="userId" id="userId" placeholder="NIM/NIDN" value=""
              aria-label="userId" aria-describedby="id-addon">
              @error('userId')
          <p class="text-danger text-xs mt-2">{{ $message }}</p>
        @enderror

            </div>
            <label>Password</label>
            <div class="mb-3">
              <input type="password" class="form-control" name="password" id="password" placeholder="Password"
              value="" aria-label="Password" aria-describedby="password-addon">
              @error('password')
          <p class="text-danger text-xs mt-2">{{ $message }}</p>
        @enderror
            </div>

            <div class="text-center">
              <button type="submit" class="btn bg-gradient-info w-100 mt-4 mb-0">Sign in</button>
            </div>
            </form>
            @if($errors->has('login'))
        <div class="alert alert-danger">{{ $errors->first('login') }}</div>
        @endif

          </div>
          <div class="card-footer text-center pt-0 px-lg-2 px-1">
            <small class="text-muted">Forgot you password? Reset you password
            <a href="/login/forgot-password" class="text-info text-gradient font-weight-bold">here</a>
            </small>
            <p class="mb-4 text-sm mx-auto">
            Don't have an account?
            <a href="register" class="text-info text-gradient font-weight-bold">Sign up</a>
            </p>
          </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="oblique position-absolute top-0 h-100 d-md-block d-none me-n8">

          </div>
        </div>
        </div>
      </div>
    </section>
  </main>

@endsection