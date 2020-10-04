<aside class="menu is-hidden-touch is-pulled-left pl-2 py-2">
  <p class="menu-label">Password Management</p>
  <ul class="menu-list">
    <li>
      <a href="{{ route('database') }}" @if (\Request::route()->getName() == 'database') class="is-active" @endif><i class="fas fa-unlock-alt pr-1"></i>Password Database</a>
    </li>
    <li>
      <a href="{{ route('generate') }}" @if (\Request::route()->getName() == 'generate') class="is-active" @endif><i class="fas fa-plus pr-1"></i>Generate a Password</a>
    </li>
    <li>
      <a href="{{ route('reuse') }}" @if (\Request::route()->getName() == 'reuse') class="is-active" @endif><i class="fas fa-sync pr-1"></i>Check for Reuse</a>
    </li>
  </ul>
  <p class="menu-label">Account</p>
  <ul class="menu-list">
    <li>
      <a href="{{ route('acc-options') }}" @if (\Request::route()->getName() == 'acc-options') class="is-active" @endif><i class="fas fa-user pr-1"></i>Account Options</a>
    </li>
    <li>
      <a href="{{ route('help') }}" @if (\Request::route()->getName() == 'help') class="is-active" @endif><i class="fas fa-question pr-1"></i>Help and Information</a>
    </li>
    <li>
      <a href="javascript:void" onclick="removeDerivedKey(); $('#logout-form').submit();"><i class="fas fa-sign-out-alt pr-1"></i>Logout</a>
      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        {{ csrf_field() }}
      </form>
    </li>
  </ul>
</aside>

<nav class="navbar has-background-primary is-fixed-bottom is-hidden-desktop">
  <div class="level is-mobile is-size-4" style="min-height: 3.25rem;">
    <p class="level-item has-text-centered">
      <a href="{{ route('database') }}" @if (\Request::route()->getName() == 'database') class="is-active" @endif aria-label="Password Database"><i class="fas fa-unlock-alt icon"></i></a>
    </p>
    <p class="level-item has-text-centered">
      <a href="{{ route('generate') }}" @if (\Request::route()->getName() == 'generate') class="is-active" @endif><i class="fas fa-plus"></i></a>
    </p>
    <p class="level-item has-text-centered">
      <a href="{{ route('reuse') }}" @if (\Request::route()->getName() == 'reuse') class="is-active" @endif><i class="fas fa-sync"></i></a>
    </p>
    <p class="level-item has-text-centered">
      <a href="{{ route('acc-options') }}" @if (\Request::route()->getName() == 'acc-options') class="is-active" @endif><i class="fas fa-user"></i></a>
    </p>
    <p class="level-item has-text-centered">
      <a href="{{ route('help') }}" @if (\Request::route()->getName() == 'help') class="is-active" @endif><i class="fas fa-question"></i></a>
    </p>
    <p class="level-item has-text-centered">
      <a href="javascript:void" onclick="removeDerivedKey(); $('#logout-form').submit();"><i class="fas fa-sign-out-alt pr-1"></i></a>
    </p>
  </div>
</nav>
