@include('templates/head-tag', ['title' => 'BullPass - Register A New Account'])

<body style="background-image: url('images/funky-lines.png');">

  @include('templates/status-notifications')

  <div class="login-card">
    <p class="header-text" style="text-align: center; margin-top: 0px;">Register A New Account</p>
    <form id="register-form" method="POST" action="{{ route('register') }}" style="margin: 0px;">
      @csrf

      <label>Email Address</label><br>
      <input id="email" type="email" maxlength="255" class="{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>
      @if ($errors->has('email'))
      <span class="invalid-feedback">
        <strong>{{ $errors->first('email') }}</strong>
      </span>
      @endif

      <br>

      <label>Password</label><br>
      <input id="password" type="password" maxlength="255" class="{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
      @if ($errors->has('password'))
      <span class="invalid-feedback">
        <strong>{{ $errors->first('password') }}</strong>
      </span>
      @endif

      <br>

      <label>Confirm Password</label><br>
      <input id="password_confirmation" type="password" maxlength="255" class="{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}" name="password_confirmation" required>
      @if ($errors->has('password_confirmation'))
      <span class="invalid-feedback">
        <strong>{{ $errors->first('password_confirmation') }}</strong>
      </span>
      @endif

      <br>

      <div class="center-wrapper">
        <button type="submit" class="login-button">Register</button>
      </div>

      <div class="center-wrapper" style="margin: 10px 0px 10px 0px;">
        <span class="subtitle">&nbsp;</span>
        <a class="login-subtext subtitle" href="{{ route('login') }}">Already Have an Account?</a>
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
        $("#password").addClass('is-invalid');
      }
      else{
        storeDerivedKey();
      }
    });
  </script>
</body>
