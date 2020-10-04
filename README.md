## Repo Roadmap

Fork of [USF-CIS-4935/password_manager](https://github.com/USF-CIS-4935/password_manager) intended to focus on individual improvements to code quality, functionality, and usability

### Styling
- CSS made mobile-compatible
- Move away from \<fieldset\> formatting.
- Reformat password re-use results (move to a modal)
- CSS rewritten to use SASS/CSS framework [Bulma](https://bulma.io/)

### Code Quality
- Addition of unit tests
- Full documentation of JS components
- Full documentation of PHP components
- Move on-page JS to external files
- Modularization of JS functions

### Functionality
- Add dark mode
- Add geolocation data to IP addresses in "Login History"
- Allow custom entropy source(s) for password generation
- "Password Re-Use" will allow you to select a password card to check
- Allow for "re-use" check from a button on the password card
- Add sort options to password database

### Architecture
- ~~Upgrade to Laravel 8.X~~
- ~~Refactor models into dedicated "Models" directory~~
- Tie styling and JS into [Laravel Mix](https://laravel.com/docs/8.x/mix)
- Encryption of all database content (?)
- Utilize Laravel templates
- Replace User and Password auto-incrementing ID's with UUIDs
- Implement automatic text compression + minification for downloaded resources
- Defer non-essential CSS loading

### Small Fix To-Do List
- "Get Started" message on blank password database page
- "Copied to clipboard" message
- ~~Move encryption libs to their own directory~~
- Add <meta> information to page tabs
- Automatic log out/ 401 handling for expired async requests

### To-Do Bugfixes
- Fix "iPad" browser preview matching "mobile" breakpoint
- Fix session cookies persisting without derived key in session storage
