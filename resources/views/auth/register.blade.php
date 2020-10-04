<!DOCTYPE html>
<html lang="en">
@include('templates/head-tag', ['title' => 'BullPass - Register A New Account'])

<body>

  @include('templates/status-notifications')

  <div class="login-card close-shadow px-3 pt-2 has-background-white">
    <p class="header-underline font-bebas is-size-2	has-text-centered mb-2" style="text-align: center; margin-top: 0px;">Register A New Account</p>
    <form id="register-form" method="POST" action="{{ route('register') }}" style="margin: 0px;">
      @csrf

      <div class="field">
        <label for="email" class="label font-bebas has-text-weight-light is-size-5 mb-0">Email Address</label>
        <div class="control">
          <input id="email" type="email" maxlength="255" class="input is-expanded {{ $errors->has('email') ? ' is-danger' : '' }}" name="email" value="{{ old('email') }}" required autofocus>
        </div>
        @if ($errors->has('email'))
        <span class="help is-danger">
          {{ $errors->first('email') }}
        </span>
        @endif
      </div>

      <div class="field">
        <label for="password" class="label font-bebas has-text-weight-light is-size-5 mb-0">Password</label>
        <div class="control">
          <input id="password" type="password" maxlength="255" class="input is-expanded {{ $errors->has('password') ? ' is-danger' : '' }}" name="password" required>
        </div>
        @if ($errors->has('password'))
        <span class="help is-danger">
          {{ $errors->first('password') }}
        </span>
        @endif
      </div>

      <div class="field">
        <label for="password-confirm" class="label font-bebas has-text-weight-light is-size-5 mb-0">Confirm Password</label>
        <div class="control">
          <input id="password-confirm" type="password" maxlength="255" class="input is-expanded {{ $errors->has('password_confirmation') ? ' is-danger' : '' }}" name="password_confirmation" required>
        </div>
        @if ($errors->has('password_confirmation'))
        <span class="help is-danger">
          {{ $errors->first('password_confirmation') }}
        </span>
        @endif
      </div>

      <div class="field">
        <button type="submit" class="button font-bebas is-primary is-size-5 is-fullwidth">Register</button>
      </div>

      <div class="mb-1 has-text-centered">
        <a class="has-text-grey-dark hover-darker is-size-6" href="{{ route('login') }}">Already Have an Account?</a>
      </div>

    </form>
  </div>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
  <script src="{{ url('js/app.js') }}"></script>
  <script src="{{ url('js/encryption-libs/sha256.js') }}"></script>
  <script type="text/javascript">

  function storeDerivedKey() {
    if (typeof(Storage) !== "undefined") {
      sessionStorage.derivedSecretKey = CryptoJS.SHA256( $("#password").val() );
    }
    else {
      displayNotification("error", "Sorry, your browser does not support web storage. This application will not be compatible.");
    }
  }

    $("#register-form").submit(function(event) {
      var verify = verifyPasswordReqs( $("#password").val() );
      if (verify !== true){
        event.preventDefault();
        displayNotification("error", verify);
        $("#password").addClass('is-danger');
      }
      else{
        storeDerivedKey();
      }
    });
  </script>
</body>
</html>
