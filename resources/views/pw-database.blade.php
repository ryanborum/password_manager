<!DOCTYPE html>
<html lang="en">
@include('templates/head-tag', ['title' => 'Password Database'])

<body>
  @include('templates/status-notifications')

  @if (Auth::user()->account_options->password_age_notification && !$expired_passwords->isEmpty())
  <div id="pw-expiration-modal" class="modal">
    <div class="modal-background"></div>
    <div class="modal-content">
      <p class="header-underline font-bebas is-size-2">Expired Passwords</p>
      <ul>
        @foreach ($expired_passwords as $exp_pw)
        <li><strong>{{ $exp_pw->password_name}}</strong> expired on: {{ $exp_pw->expiration_date->format('m/d/y') }}</li>
        @endforeach
      </ul>
      <p class="is-size-6 mt-2">You can disable this notification in the <a href="{{ route('acc-options') }}">Account Options</a> page</p>
    </div>
  </div>
  @endif

  <div id="pw-edit-modal" class="modal">
    <div class="modal-background"></div>
    <div class="modal-content has-background-white px-4 py-4">
      <p id="modal-header" class="header-underline font-bebas is-size-2">Edit A Password</p>

      <input id="password_id" type="hidden">

      <div class="field">
        <label for="password_name" class="label">Name/Title</label>
        <div class="control">
          <input id="password_name" class="input" type="text" maxlength="255" autocomplete="off">
        </div>
      </div>
      <div class="field-body">
        <div class="field">
          <label for="username_email" class="label">Username/Email</label>
          <div class="control">
            <input id="username_email" class="input" type="text" maxlength="255" autocomplete="off">
          </div>
        </div>
        <div class="field">
          <label for="saved_password" class="label">Password</label>
          <div class="control">
            <input id="saved_password" class="input" type="text" maxlength="255" autocomplete="off">
          </div>
        </div>
      </div>
      <div class="field">
        <label for="expiration_date" class="label">Expiration Date:</label>
        <input id="expiration_date" class="input" type="date" autocomplete="off">
        <p id="expires_in" class="help">Password Expires In: 10 Days</p>
      </div>
      <div class="field">
        <label for="notes" class="label">Additional Notes</label>
        <div class="control">
          <textarea id="notes" class="textarea"></textarea>
        </div>
      </div>
      <div class="field">
        <span id="last_updated">Last Updated:</span>
      </div>

      <div class="modal-footer-buttons">
        <div style="float: right;">
          <button class="is-primary button" id="save-password"><i class="fas fa-save pr-2"></i> Save</button>
          <button class="is-primary button" id="delete-password"><i class="fas fa-trash pr-2"></i> Delete</button>
        </div>
      </div>
    </div>
    <button class="modal-close is-large" aria-label="close"></button>
  </div>

  <div class="container close-shadow has-background-white is-clipped px-2 pb-4 my-4">
    <p class="header-underline font-bebas is-size-2">Password Database</p>
    <div class="columns">
      <div class="column is-narrow">
        @include('templates/navigation-template')
      </div>
      <div class="column">
        <div class="columns mt-2 is-multiline">
          <div class="column is-7 field has-addons">
            <div class="control is-expanded">
              <input id="db-search" class="input text-search" type="text" maxlength="100" placeholder="Search by password name">
            </div>
            <div class="control">
              <button id="db-search-submit" class="button is-primary" aria-label="Submit database filter search">
                <i class="fas fa-search"></i>
              </button>
            </div>
          </div>
          <div class="column columns">
            <div class="control column">
              <button id="add-password" class="is-primary button is-fullwidth"><i class="fas fa-plus pr-2"></i> Add A New Password</button>
            </div>
            <div class="field column">
              <div class="control has-icons-left">
                <div class="select is-fullwidth">
                  <select id="sort-option">
                    <option>Alphabetical</option>
                    <option>Last Updated</option>
                    <option>Expiration Date</option>
                  </select>
                </div>
                <div class="icon is-small is-left">
                  <i class="fas fa-sort-amount-down"></i>
                </div>
              </div>
            </div>

          </div>
        </div>

        <div id="password-panels" class="columns is-mobile is-multiline">
          @foreach ($passwords as $password)
          <div class="column is-full-mobile is-half-tablet is-one-third-desktop">
            <div class="card pw-panel box px-2 py-1 is-full-mobile is-unselectable" data-pwname="{{ $password->password_name }}" data-pid="{{ $password->id }}">
              <div class="card-content" >
                <div class="content is-size-6">
                  <p>
                    <strong class="is-size-5">{{ $password->LengthCorrectedName }}</strong>
                    <br>
                    {{ $password->username_email ?? ''}}
                    <br>
                    Last Updated: {{ $password->updated_at->format('m/d/y') ?? 'N/A'}}
                  </p>
                </div>
              </div>
              <footer class="card-footer">
                <div class="card-footer-item font-bebas is-size-5 py-1">
                  Expires In:&nbsp;<span class="is-size-4" style="color:{{ $password->ExpirationColor ?? 'black' }}">{{ $password->DaysUntilExpiration }}</span>&nbsp;days
                </div>
                <div class="card-footer-item is-size-4">
                  <i title="Copy to Clipboard" class="fas fa-copy copy-button hover-darker"></i>
                </div>
              </footer>
              <div class="level is-mobile is-clipped">

              </div>
            </div>
          </div>
          @endforeach
        </div>
      </div>

    </div>
  </div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
  <script src="{{ url('js/app.js') }}"></script>
  <script src="{{ url('js/encryption-libs/aes.js') }}"></script>
  <script type="text/javascript">
  function search_filter(){
    var searchTerm = $('#db-search').val();
    $(".pw-panel").each(function(index) { //Iterate every password panel
      if (String($(this).data('pwname')).toLowerCase().indexOf(searchTerm.toLowerCase()) >= 0){ //Case-insenitive check for the search term within the 'pwname' data field
        $(this).parent().fadeIn(200);
      }
      else{
        $(this).parent().fadeOut(200);
      }
    });
  }

  $('#db-search-submit').click(function() {
    search_filter();
  });
  $('#db-search').keypress(function (e) {
    if (e.which == 13) { //'Enter'
      search_filter();
    }
  });

  function populateModal(data){
    if (data.encrypted_pass){
      var plaintextPass = CryptoJS.AES.decrypt(data.encrypted_pass, sessionStorage.derivedSecretKey).toString(CryptoJS.enc.Utf8).replace(data.salt_string,'');
    }
    //Handles populating modal where exp date is not explicity set
    if (data.expiration_date){
      var exp_date = new Date(data.expiration_date).toISOString().slice(0,10);
    }
    $("#password_id").val(data.id);
    $('#password_name').val(data.password_name);
    $('#username_email').val(data.username_email);
    $('#saved_password').val(plaintextPass);
    $('#notes').val(data.notes);
    $('#expiration_date').val(exp_date);

    $("#last_updated").text("Last Updated: " + data.updated_at);
    $("#expires_in").text("Password Expires In: " + data.DaysUntilExpiration + " days");

    $("#pw-edit-modal").addClass("is-active");
    $("#password_name").focus();
  }

  function addPasswordPanel(panel_data, replace_pid = false){
    var new_panel = "\
    <div class='pw-panel is-unselectable' data-pwname=\"" + String(panel_data.password_name) + "\" data-pid=" + panel_data.id + ">\
    <div class='date-field'>\
    <span class='day-counter' style='color: " + panel_data.ExpirationColor + "'>" + panel_data.DaysUntilExpiration + "&nbsp;</span>days\
    </div>\
    <div class='panel-right'>\
    <h3 class='panel-title'>" + panel_data.LengthCorrectedName + "</h3>\
    <p class='subtitle'>" + panel_data.username_email + "</p>\
    <p class='subtitle'>Last Updated: " + new Date(panel_data.updated_at).toISOString().slice(0,10) + "</p>\
    </div>\
    <p class='panel-links'><i class='fas fa-copy copy-button'></i></p>\
    </div>\
    ";

    if (replace_pid){
      $(".pw-panel[data-pid=" + replace_pid + "]").replaceWith(new_panel);
    }
    else{
      $("#password-panels").append(new_panel);
    }
  }

  function getPasswordData(passID){
    return $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: "{{ route('get-password') }}" + "/" + passID,
      method: "GET",
    })
  }

  function postPasswordUpdate(){
    $('#save-password').prop("disabled", true);
    var salt = Math.random().toString(36).slice(2) + Math.random().toString(36).slice(2) + Math.random().toString(36).slice(2);
    var pageData = {
      password_id : $('#password_id').val(),
      password_name : $('#password_name').val(),
      username_email : $('#username_email').val(),
      salt_string : salt,
      encrypted_pass : CryptoJS.AES.encrypt($('#saved_password').val() + salt, sessionStorage.derivedSecretKey).toString(),
      notes : $('#notes').val(),
      expiration_date : $('#expiration_date').val(),
    };
    $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: "{{ route('update-password') }}",
      method: "POST",
      data: pageData
    })
    .done(function(data){
      if ($('#password_id').val() != "new"){
        addPasswordPanel(data, $('#password_id').val()); // Replace old panel with updated one
      }
      else{
        addPasswordPanel(data);
      }
      $('#pw-edit-modal').removeClass("is-active");
      displayNotification("success", "Password updated successfully", 5000);
    })
    .fail(function(data){
      if (data.status == 422){
        displayNotification("error", data.responseJSON.errors, 10000);
      }
      else{
        displayNotification("error", "An error occurred while updating this password. Try again in a few minutes", 5000);
      }
    })
    .always(function(data){
      $('#save-password').prop("disabled", false);
    })
  }

  function postPasswordDelete(){
    $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: "{{ route('delete-password') }}",
      method: "POST",
      data: { password_id : $('#password_id').val() }
    })
    .done(function(data){
      displayNotification("success", "Password deleted successfully", 5000);
      $(".pw-panel[data-pid=" + $('#password_id').val() + "]").remove();
      $("#pw-edit-modal").removeClass("is-active");
    })
    .fail(function(data){
      if (data.status = 422){
        displayNotification("error", data.responseJSON.errors, 10000);
      }
      else{
        displayNotification("error", "An error occurred while attempting to delete this password", 5000);
      }
    })
  }

  $('#save-password').click(function(){
    postPasswordUpdate();
  });

  $('#config-password').click(function(){
    $("#config-options").toggle();
  });

  $('#add-password').click(function(){
    $("#modal-header").text("Add A New Password");
    $("#delete-password,#config-password").prop("disabled", true);
    $("#config-options").hide();

    populateModal( {id : 'new'} );
  });

  $('#delete-password').click(function(){
    if (confirm("Are you sure you'd like to delete this password? This action cannot be undone.")) {
      postPasswordDelete();
    }
  });

  $('.copy-button').click(function(){
    getPasswordData( $(this).closest(".pw-panel").data('pid') ).then(function(data) {
      copyToClipboard(CryptoJS.AES.decrypt(data.encrypted_pass, sessionStorage.derivedSecretKey).toString(CryptoJS.enc.Utf8).replace(data.salt_string,''));
    });
    displayNotification("success", "Password copied to clipboard.", 1000);
  });

  // Uses "on" listener to support dynamically added password panels
  $('#password-panels').on("click", ".pw-panel", function(event) {
    //Prevent "copy button" clicks from opening modal
    if ($(event.target).hasClass('copy-button')){
      return;
    }

    $("#modal-header").text("Edit A Password");
    $("#delete-password,#config-password").prop("disabled", false);
    getPasswordData( $(this).data('pid') ).then(function(data) {
      populateModal(data);
    });
  });

  $('.modal-background,.modal-close').click(function() {
    $('#pw-edit-modal').removeClass("is-active");
  });
  </script>
</body>
</html>
