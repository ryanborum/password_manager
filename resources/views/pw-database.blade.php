@include('templates/head-tag', ['title' => 'Password Database'])

<body>
  @include('templates/left-nav')
  @include('templates/status-notifications')

  <div id="pw-edit-modal" class="modal">
    <div class="modal-content">
      <div class="modal-container">
        <span class="modal-close-btn">×</span>
        <p id="modal-header" class="header-text" style="margin-top: 0px; font-size: 30px;">Edit A Password</p>

        <input id="password_id" type="hidden">
        <div class="modal-input" style="width: 100%;">
          <label for="password_name">Name/Title:</label><br>
          <input id="password_name" type="text" maxlength="225" autocomplete="off">
        </div>
        <div class="modal-input" style="width: 47%;">
          <label for="username_email">Username/Email:</label><br>
          <input id="username_email" type="text" maxlength="225" autocomplete="off">
        </div>
        <div class="modal-input" style="width: 47%; margin-left: 5%;">
          <label for="saved_password">Password:</label><br>
          <input id="saved_password" type="text" autocomplete="off">
        </div>
        <div class="modal-input" style="width: 100%;">
          <label for="notes">Additional Notes:</label><br>
          <textarea id="notes"></textarea>
        </div>
        <div class="modal-input">
          <span id="last_updated">Last Updated:</span><br>
          <span>Password Expires In: 15 days</span>
        </div>

        <div class="modal-footer-buttons" style="display: block; margin-top: 15px;">
          <button class="green-button" id="config-password"><i class="fas fa-cog"></i> Configure</button>
          <div class="right-buttons" style="float: right;">
            <button class="green-button" id="save-password"><i class="fas fa-save"></i> Save</button>
            <button class="green-button" id="delete-password"><i class="fas fa-trash"></i> Delete</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="container">
    <p class="header-text">Password Database</p>
    <div class="top-search" style="display: inline-block;">
      <input id="db-search" class="text-search" type="text" style="float: left;" placeholder="Search by password name">
      <div id="db-search-submit" class="search-button">
        <i class="fas fa-search"></i>
      </div>
      <button id="add-password" class="green-button" style="margin-left: 50px; margin-top: 20px; height: 40px;"><i class="fas fa-plus"></i> Add A New Password</button>
    </div>

    <div id="password-panels">
      @foreach ($passwords as $password)
        <div class="pw-panel no-select" data-pwname="{{ $password->password_name }}" data-pid="{{ $password->id }}">
          <div class="date-field">
            <span class="day-counter">14&nbsp;</span>days
          </div>
          <div class="panel-right">
            <h3 class="panel-title">{{ $password->password_name }}</h3>
            <p class="subtitle">{{ $password->username_email ?? '-'}}</p>
            <p class="subtitle">Last Updated: {{ $password->updated_at->format('m/d/y') ?? '-'}}</p>
          </div>
          <p class="panel-links"><i class="fas fa-copy copy-button"></i></p>
        </div>
      @endforeach
    </div>
  </div>
  <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
  <script src="js/app_functions.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.2/rollups/aes.js" integrity="sha256-/H4YS+7aYb9kJ5OKhFYPUjSJdrtV6AeyJOtTkw6X72o=" crossorigin="anonymous"></script>
  <script type="text/javascript">
  function search_filter(){
    var searchTerm = $('#db-search').val();
    $(".pw-panel").each(function(index) { //Iterate every password panel
      if ($(this).data('pwname').toLowerCase().indexOf(searchTerm.toLowerCase()) >= 0){ //Case-insenitive check for the search term within the 'pwname' data field
        $(this).fadeIn(200);
      }
      else{
        $(this).fadeOut(200);
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
    var plaintextPass = CryptoJS.AES.decrypt(data.encrypted_pass, sessionStorage.derivedEncyptionKey).toString(CryptoJS.enc.Utf8).replace(data.salt_string,'');
    $("#password_id").val(data.id),
    $('#password_name').val(data.password_name),
    $('#username_email').val(data.username_email),
    $('#saved_password').val(plaintextPass),
    $('#notes').val(data.notes),

    $("#last_updated").text("Last Updated: " + data.updated_at);

    $("#pw-edit-modal").show();
    $("#password_name").focus();
  }

  function addPasswordPanel(panel_data){
    var new_panel = "\
    <div class='pw-panel no-select' data-pwname=" + String(panel_data.password_name) + " data-pid=" + panel_data.id + ">\
      <div class='date-field'>\
        <span class='day-counter'>14&nbsp;</span>days\
      </div>\
      <div class='panel-right'>\
        <h3 class='panel-title'>" + panel_data.password_name + "</h3>\
        <p class='subtitle'>" + panel_data.username_email + "</p>\
      </div>\
      <p class='panel-links'><i class='fas fa-copy copy-button'></i></p>\
    </div>\
    ";

    $("#password-panels").append(new_panel);
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
    var salt = Math.random().toString(36).slice(2);
    var pageData = {
      password_id : $('#password_id').val(),
      password_name : $('#password_name').val(),
      username_email : $('#username_email').val(),
      salt_string : salt,
      encrypted_pass : CryptoJS.AES.encrypt($('#saved_password').val() + salt, sessionStorage.derivedEncyptionKey).toString(),
      notes : $('#notes').val(),
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
      populateModal(data);
      displayNotification("success", "Password updated successfully", 5000);
    })
    .fail(function(data){
      if (data.status = 422){
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
      $("#pw-edit-modal").hide();
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
    //Open config options
  });

  $('#add-password').click(function(){
    $("#modal-header").text("Add A New Password");
    populateModal( {id : 'new'} );
  });

  $('#delete-password').click(function(){
    if (confirm("Are you sure you'd like to delete this password? This action cannot be undone.")) {
      postPasswordDelete();
    }
  });

  $('.copy-button').click(function(){
    getPasswordData( $(this).closest(".pw-panel").data('pid') ).then(function(data) {
      copyToClipboard(CryptoJS.AES.decrypt(data.encrypted_pass, sessionStorage.derivedEncyptionKey).toString(CryptoJS.enc.Utf8).replace(data.salt_string,''));
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
    getPasswordData( $(this).data('pid') ).then(function(data) {
      populateModal(data);
    });
  });

  $('.modal-close-btn').click(function() {
    $(this).closest(".modal").hide();
  });
  </script>
</body>
