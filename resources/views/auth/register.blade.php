@extends('layouts.master')

@section('content')
  <div class="container">
    <div class="row">
      <div class="col-md-8 col-md-offset-2">
        <h2>Registration</h2>
        <p>Registering an account allows you some additional benefits.</p>
        <ul>
          <li>Save your maps (unlimited maps)</li>
          <li>Come back to your saved maps to edit further</li>
          <li>Duplicate saved maps, to use as your standard starting-point map perhaps.</li>
          <li>Create your own marker pins and save them for re-use.</li>
          <li>Place your saved map code once and update your maps on the fly from your EZ Map control panel.</li>
          <li>...more to come.</li>
        </ul>
        <p class="lead">...and best of all it's FREE, forever!</p>
        <hr>
        <div class="panel panel-default">
          <div class="panel-heading">Register</div>
          <div class="panel-body">
            <form class="form-horizontal" role="form" method="POST" action="{{ url('/register') }}">
              {!! csrf_field() !!}

              <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                <label for="name-field" class="col-md-4 control-label">Name</label>

                <div class="col-md-6">
                  <input id="name-field" type="text" class="form-control" name="name" value="{{ old('name') }}">

                  @if ($errors->has('name'))
                    <span class="help-block"><strong>{{ $errors->first('name') }}</strong></span>
                  @endif
                </div>
              </div>

              <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                <label for="email-field" class="col-md-4 control-label">E-Mail Address</label>

                <div class="col-md-6">
                  <input id="email-field" type="email" class="form-control" name="email" value="{{ old('email') }}">

                  @if ($errors->has('email'))
                    <span class="help-block"><strong>{{ $errors->first('email') }}</strong></span>
                  @endif
                </div>
              </div>

              <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                <label for="password-field" class="col-md-4 control-label">Password</label>

                <div class="col-md-6">
                  <input id="password-field" type="password" class="form-control" name="password">

                  @if ($errors->has('password'))
                    <span class="help-block"><strong>{{ $errors->first('password') }}</strong></span>
                  @endif
                </div>
              </div>

              <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                <label for="confirm-field" class="col-md-4 control-label">Confirm Password</label>
                <div class="col-md-6">
                  <input id="confirm-field" type="password" class="form-control" name="password_confirmation">
                </div>
              </div>

              <div class="form-group {{ $errors->has('g-recaptcha-response') ? ' has-error' : '' }}">
                <label for="captcha-field" class="col-md-4 control-label">Bot check</label>
                <div class="col-md-6">
                  {!! htmlFormSnippet() !!}
                  @if ($errors->has('g-recaptcha-response'))
                    <span class="help-block"><strong>{{ $errors->first('g-recaptcha-response') }}</strong></span>
                  @endif
                </div>
              </div>

              <div class="form-group">
                <div class="col-md-6 col-md-offset-4">
                  <button type="submit" class="btn btn-primary">
                    <i class="fa fa-btn fa-user"></i> Register
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
