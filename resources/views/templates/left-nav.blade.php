<div class="sidenav">
  <div class="sidelink-container @if (\Request::route()->getName() == 'acc-options') active @endif">
    <a href="{{ route('acc-options') }}"><i class="fas fa-user"></i>Account Options</a>
    <hr>
  </div>
  <div class="sidelink-container @if (\Request::route()->getName() == 'database') active @endif">
    <a href="{{ route('database') }}"><i class="fas fa-unlock-alt"></i>Password Database</a>
  </div>
  <div class="sidelink-container @if (\Request::route()->getName() == 'generate') active @endif">
    <a href="{{ route('generate') }}"><i class="fas fa-plus"></i>Generate a Password</a>
  </div>
  <div class="sidelink-container @if (\Request::route()->getName() == 'reuse') active @endif">
    <a href="{{ route('reuse') }}"><i class="fas fa-sync"></i>Check for Reuse</a>
  </div>
  <div class="sidelink-container @if (\Request::route()->getName() == 'help') active @endif">
    <a href="{{ route('help') }}"><i class="fas fa-question"></i>Help and Information</a>
  </div>
  <div class="sidelink-container" style="position: absolute; bottom: 30px;">
    <hr>
    <a href="{{ route('logout') }}"><i class="fas fa-sign-out-alt"></i>Logout</a>
  </div>
</div>
