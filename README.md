# laraveljetstreamblog

Simple Multi-user blog based off Laravel [Jetstream](https://jetstream.laravel.com) and [Inertia.js](https://inertiajs.com) with Vue.js v3.  The package includes:
- Multiple user post support.
- Full CRUD (create, read, update, delete) support for user posts.
- Automated population of the posts table through the Google News Feed RSS, via the SimplePie wrapper by [Will Vincent](https://github.com/willvincent).
- Live Post Title Search.
- Create/Update Post Modal.
- Flash messages are configured as a toast/snackbar.
- Form validation error messages are configured.
- Front end that shows a random ordering of the posts and view posts.
- Email verification if enabled via Jetstream.
- Two Factor Authentication if enabled via Jetstream.

## Installation

You should first have the lastest version of composer and npm installed, along with your favorite database server.  I used MySQL for development, but the code should run without issue on other database engines.

- First clone the project to your computer.  Switch to your clone directory.
- Run ```composer install```.
- Run ```npm install```.
- Run ```npm run dev```.
- Create a new .env file ```cp .env.example .env```.
- Create a new SQL database and fill out the required database parameters in .env
- Run ```php artisan key:generate```.
- Run ```php artisan migrate```.
- Run ```php artisan vendor:publish```.
- Optional: ```npm run watch```. (if making javascript or css changes)
- Finally run ```php artisan serve``` to start up a local development server.
- Optional: Setup the laravel smtp config to enable email verification.  Out of the box, mailhog is the default.

## Discussion

This Jetstream (w/ Inertia.js) project uses Tailwind.css, Vue.js templates, and [Laravel 8 Authentication](https://laravel.com/docs/8.x/authentication) with client-side routing assisted by [Ziggy](https://github.com/tighten/ziggy).  If you are looking for similar functionality without the javascript, check out my other Laravel Breeze based [repo](https://github.com/kevinrider/laravelbreezeblog).

To enable flash messages to be passed as props to the Vue SFC (from the Laravel route) add the code found in the ```HandleInertiaRequests.php``` share function.  This is the same flash message handling as [PingCRM](https://github.com/inertiajs/pingcrm).  Flash messages are styled in the ```Shared/FlashMessages.vue``` component.

Pagination is enabled by default (passed as a prop) and is styled in the ```Shared/Pagination.vue``` component.

To enable Laravel Sanctum protected API axios requests from within a Vue SFC add  ```EnsureFrontendRequestsAreStateful``` to ```Kernel.php``` in the ```[api]``` Middleware group.

Form validation errors are passed automatically and can be handled in a similar manner to Blade templates if the Inertia.js [Form Helper](https://inertiajs.com/forms) is used.

## Inertia.js, Modals, and API endpoints

Inertia.js is an excellent way to reduce the complexity of creating an app with a SPA feel, while leveraging a developers existing knowledge of Laravel.  As long as each step in the CRUD process is kept within its own page render like [PingCRM](https://github.com/inertiajs/pingcrm), no seperate API is necessary.  However it may not be possible to avoid using some API endpoints if additional data is necessary to display any action taken within the same Vue SFC, which is what happens when using Modals.

The Post table in this project is simple.  It is straight forward to populate the Update Post Modal by simply passing the ```showEditModal``` method in ```Pages/Posts/Index.vue``` the required post id, title, and body.  However, what if each post had additional data such as extensive category groupings, additional authors, or other meta-data, that was not passed as a prop to the index view?  The easiest solution is simply to create an API endpoint from which this data can be fetched on a per post basis, then populate the Update Post Modal when called.  An example of how to do this with axios is commented out in the ```showEditModal``` method in ```Pages/Posts/Index.vue``` and a single API endpoint has been created in the ```routes/api.php``` file.