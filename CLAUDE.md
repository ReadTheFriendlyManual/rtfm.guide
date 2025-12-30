<frontend_aesthetics>
You tend to converge toward generic, "on distribution" outputs. In frontend design, this creates what users call the "AI slop" aesthetic. Avoid this: make creative, distinctive frontends that surprise and delight. Focus on:

Typography: Choose fonts that are beautiful, unique, and interesting. Avoid generic fonts like Arial and Inter; opt instead for distinctive choices that elevate the frontend's aesthetics.

Color & Theme: Commit to a cohesive aesthetic. Use CSS variables for consistency. Dominant colors with sharp accents outperform timid, evenly-distributed palettes. Draw from IDE themes and cultural aesthetics for inspiration.

Motion: Use animations for effects and micro-interactions. Prioritize CSS-only solutions for HTML. Use Motion library for React when available. Focus on high-impact moments: one well-orchestrated page load with staggered reveals (animation-delay) creates more delight than scattered micro-interactions.

Backgrounds: Create atmosphere and depth rather than defaulting to solid colors. Layer CSS gradients, use geometric patterns, or add contextual effects that match the overall aesthetic.

Avoid generic AI-generated aesthetics:
- Overused font families (Inter, Roboto, Arial, system fonts)
- Clichéd color schemes (particularly purple gradients on white backgrounds)
- Predictable layouts and component patterns
- Cookie-cutter design that lacks context-specific character

Interpret creatively and make unexpected choices that feel genuinely designed for the context. Vary between light and dark themes, different fonts, different aesthetics. You still tend to converge on common choices (Space Grotesk, for example) across generations. Avoid this: it is critical that you think outside the box!
</frontend_aesthetics>
<laravel-boost-guidelines>
=== foundation rules ===

# Laravel Boost Guidelines

The Laravel Boost guidelines are specifically curated by Laravel maintainers for this application. These guidelines should be followed closely to enhance the user's satisfaction building Laravel applications.

## Foundational Context
This application is a Laravel application and its main Laravel ecosystems package & versions are below. You are an expert with them all. Ensure you abide by these specific packages & versions.

<<<<<<< Updated upstream
- php - 8.4.15
- inertiajs/inertia-laravel (INERTIA) - v2
=======
- php - 8.4.16
>>>>>>> Stashed changes
- laravel/fortify (FORTIFY) - v1
- laravel/framework (LARAVEL) - v12
- laravel/nightwatch (NIGHTWATCH) - v1
- laravel/pennant (PENNANT) - v1
- laravel/prompts (PROMPTS) - v0
- laravel/sanctum (SANCTUM) - v4
- laravel/scout (SCOUT) - v10
- laravel/socialite (SOCIALITE) - v5
<<<<<<< Updated upstream
=======
- livewire/flux (FLUXUI_FREE) - v2
- livewire/livewire (LIVEWIRE) - v3
>>>>>>> Stashed changes
- laravel/mcp (MCP) - v0
- laravel/pint (PINT) - v1
- laravel/sail (SAIL) - v1
- pestphp/pest (PEST) - v4
- phpunit/phpunit (PHPUNIT) - v12
- @inertiajs/vue3 (INERTIA) - v2
- tailwindcss (TAILWINDCSS) - v4
- vue (VUE) - v3

## Conventions
- You must follow all existing code conventions used in this application. When creating or editing a file, check sibling files for the correct structure, approach, naming.
- Use descriptive names for variables and methods. For example, `isRegisteredForDiscounts`, not `discount()`.
- Check for existing components to reuse before writing a new one.

## Verification Scripts
- Do not create verification scripts or tinker when tests cover that functionality and prove it works. Unit and feature tests are more important.

## Application Structure & Architecture
- Stick to existing directory structure - don't create new base folders without approval.
- Do not change the application's dependencies without approval.

## Frontend Bundling
- If the user doesn't see a frontend change reflected in the UI, it could mean they need to run `npm run build`, `npm run dev`, or `composer run dev`. Ask them.

## Replies
- Be concise in your explanations - focus on what's important rather than explaining obvious details.

## Documentation Files
- You must only create documentation files if explicitly requested by the user.


=== boost rules ===

## Laravel Boost
- Laravel Boost is an MCP server that comes with powerful tools designed specifically for this application. Use them.

## Artisan
- Use the `list-artisan-commands` tool when you need to call an Artisan command to double check the available parameters.

## URLs
- Whenever you share a project URL with the user you should use the `get-absolute-url` tool to ensure you're using the correct scheme, domain / IP, and port.

## Tinker / Debugging
- You should use the `tinker` tool when you need to execute PHP to debug code or query Eloquent models directly.
- Use the `database-query` tool when you only need to read from the database.

## Reading Browser Logs With the `browser-logs` Tool
- You can read browser logs, errors, and exceptions using the `browser-logs` tool from Boost.
- Only recent browser logs will be useful - ignore old logs.

## Searching Documentation (Critically Important)
- Boost comes with a powerful `search-docs` tool you should use before any other approaches. This tool automatically passes a list of installed packages and their versions to the remote Boost API, so it returns only version-specific documentation specific for the user's circumstance. You should pass an array of packages to filter on if you know you need docs for particular packages.
- The 'search-docs' tool is perfect for all Laravel related packages, including Laravel, Inertia, Livewire, Filament, Tailwind, Pest, Nova, Nightwatch, etc.
- You must use this tool to search for Laravel-ecosystem documentation before falling back to other approaches.
- Search the documentation before making code changes to ensure we are taking the correct approach.
- Use multiple, broad, simple, topic based queries to start. For example: `['rate limiting', 'routing rate limiting', 'routing']`.
- Do not add package names to queries - package information is already shared. For example, use `test resource table`, not `filament 4 test resource table`.

### Available Search Syntax
- You can and should pass multiple queries at once. The most relevant results will be returned first.

1. Simple Word Searches with auto-stemming - query=authentication - finds 'authenticate' and 'auth'
2. Multiple Words (AND Logic) - query=rate limit - finds knowledge containing both "rate" AND "limit"
3. Quoted Phrases (Exact Position) - query="infinite scroll" - Words must be adjacent and in that order
4. Mixed Queries - query=middleware "rate limit" - "middleware" AND exact phrase "rate limit"
5. Multiple Queries - queries=["authentication", "middleware"] - ANY of these terms


=== php rules ===

## PHP

- Always use curly braces for control structures, even if it has one line.

### Constructors
- Use PHP 8 constructor property promotion in `__construct()`.
    - <code-snippet>public function __construct(public GitHub $github) { }</code-snippet>
- Do not allow empty `__construct()` methods with zero parameters.

### Type Declarations
- Always use explicit return type declarations for methods and functions.
- Use appropriate PHP type hints for method parameters.

<code-snippet name="Explicit Return Types and Method Params" lang="php">
protected function isAccessible(User $user, ?string $path = null): bool
{
    ...
}
</code-snippet>

## Comments
- Prefer PHPDoc blocks over comments. Never use comments within the code itself unless there is something _very_ complex going on.

## PHPDoc Blocks
- Add useful array shape type definitions for arrays when appropriate.

## Enums
- Typically, keys in an Enum should be TitleCase. For example: `FavoritePerson`, `BestLake`, `Monthly`.


=== tests rules ===

## Test Enforcement

- Every change must be programmatically tested. Write a new test or update an existing test, then run the affected tests to make sure they pass.
- Run the minimum number of tests needed to ensure code quality and speed. Use `php artisan test` with a specific filename or filter.


=== inertia-laravel/core rules ===

## Inertia Core

- Inertia.js components should be placed in the `resources/js/Pages` directory unless specified differently in the JS bundler (vite.config.js).
- Use `Inertia::render()` for server-side routing instead of traditional Blade views.
- Use `search-docs` for accurate guidance on all things Inertia.

<code-snippet lang="php" name="Inertia::render Example">
// routes/web.php example
Route::get('/users', function () {
    return Inertia::render('Users/Index', [
        'users' => User::all()
    ]);
});
</code-snippet>


=== inertia-laravel/v2 rules ===

## Inertia v2

- Make use of all Inertia features from v1 & v2. Check the documentation before making any changes to ensure we are taking the correct approach.

### Inertia v2 New Features
- Polling
- Prefetching
- Deferred props
- Infinite scrolling using merging props and `WhenVisible`
- Lazy loading data on scroll

### Deferred Props & Empty States
- When using deferred props on the frontend, you should add a nice empty state with pulsing / animated skeleton.

### Inertia Form General Guidance
- The recommended way to build forms when using Inertia is with the `<Form>` component - a useful example is below. Use `search-docs` with a query of `form component` for guidance.
- Forms can also be built using the `useForm` helper for more programmatic control, or to follow existing conventions. Use `search-docs` with a query of `useForm helper` for guidance.
- `resetOnError`, `resetOnSuccess`, and `setDefaultsOnSuccess` are available on the `<Form>` component. Use `search-docs` with a query of 'form component resetting' for guidance.


=== laravel/core rules ===

## Do Things the Laravel Way

- Use `php artisan make:` commands to create new files (i.e. migrations, controllers, models, etc.). You can list available Artisan commands using the `list-artisan-commands` tool.
- If you're creating a generic PHP class, use `php artisan make:class`.
- Pass `--no-interaction` to all Artisan commands to ensure they work without user input. You should also pass the correct `--options` to ensure correct behavior.

### Database
- Always use proper Eloquent relationship methods with return type hints. Prefer relationship methods over raw queries or manual joins.
- Use Eloquent models and relationships before suggesting raw database queries
- Avoid `DB::`; prefer `Model::query()`. Generate code that leverages Laravel's ORM capabilities rather than bypassing them.
- Generate code that prevents N+1 query problems by using eager loading.
- Use Laravel's query builder for very complex database operations.

### Model Creation
- When creating new models, create useful factories and seeders for them too. Ask the user if they need any other things, using `list-artisan-commands` to check the available options to `php artisan make:model`.

### APIs & Eloquent Resources
- For APIs, default to using Eloquent API Resources and API versioning unless existing API routes do not, then you should follow existing application convention.

### Controllers & Validation
- Always create Form Request classes for validation rather than inline validation in controllers. Include both validation rules and custom error messages.
- Check sibling Form Requests to see if the application uses array or string based validation rules.

### Queues
- Use queued jobs for time-consuming operations with the `ShouldQueue` interface.

### Authentication & Authorization
- Use Laravel's built-in authentication and authorization features (gates, policies, Sanctum, etc.).

### URL Generation
- When generating links to other pages, prefer named routes and the `route()` function.

### Configuration
- Use environment variables only in configuration files - never use the `env()` function directly outside of config files. Always use `config('app.name')`, not `env('APP_NAME')`.

### Testing
- When creating models for tests, use the factories for the models. Check if the factory has custom states that can be used before manually setting up the model.
- Faker: Use methods such as `$this->faker->word()` or `fake()->randomDigit()`. Follow existing conventions whether to use `$this->faker` or `fake()`.
- When creating tests, make use of `php artisan make:test [options] {name}` to create a feature test, and pass `--unit` to create a unit test. Most tests should be feature tests.

### Vite Error
- If you receive an "Illuminate\Foundation\ViteException: Unable to locate file in Vite manifest" error, you can run `npm run build` or ask the user to run `npm run dev` or `composer run dev`.


=== laravel/v12 rules ===

## Laravel 12

- Use the `search-docs` tool to get version specific documentation.
- Since Laravel 11, Laravel has a new streamlined file structure which this project uses.

### Laravel 12 Structure
- No middleware files in `app/Http/Middleware/`.
- `bootstrap/app.php` is the file to register middleware, exceptions, and routing files.
- `bootstrap/providers.php` contains application specific service providers.
- **No app\Console\Kernel.php** - use `bootstrap/app.php` or `routes/console.php` for console configuration.
- **Commands auto-register** - files in `app/Console/Commands/` are automatically available and do not require manual registration.

### Database
- When modifying a column, the migration must include all of the attributes that were previously defined on the column. Otherwise, they will be dropped and lost.
- Laravel 11 allows limiting eagerly loaded records natively, without external packages: `$query->latest()->limit(10);`.

### Models
- Casts can and likely should be set in a `casts()` method on a model rather than the `$casts` property. Follow existing conventions from other models.


=== pennant/core rules ===
<<<<<<< Updated upstream
=======

## Laravel Pennant

- This application uses Laravel Pennant for feature flag management, providing a flexible system for controlling feature availability across different organizations and user types.
- Use the `search-docs` tool if available, in combination with existing codebase conventions, to assist the user effectively with feature flags.


=== fluxui-free/core rules ===
>>>>>>> Stashed changes

## Laravel Pennant

- This application uses Laravel Pennant for feature flag management, providing a flexible system for controlling feature availability across different organizations and user types.
- Use the `search-docs` tool if available, in combination with existing codebase conventions, to assist the user effectively with feature flags.


=== pint/core rules ===

## Laravel Pint Code Formatter

- You must run `vendor/bin/pint --dirty` before finalizing changes to ensure your code matches the project's expected style.
- Do not run `vendor/bin/pint --test`, simply run `vendor/bin/pint` to fix any formatting issues.


=== pest/core rules ===

## Pest
### Testing
- If you need to verify a feature is working, write or update a Unit / Feature test.

### Pest Tests
- All tests must be written using Pest. Use `php artisan make:test --pest {name}`.
- You must not remove any tests or test files from the tests directory without approval. These are not temporary or helper files - these are core to the application.
- Tests should test all of the happy paths, failure paths, and weird paths.
- Tests live in the `tests/Feature` and `tests/Unit` directories.
- Pest tests look and behave like this:
<code-snippet name="Basic Pest Test Example" lang="php">
it('is true', function () {
    expect(true)->toBeTrue();
});
</code-snippet>

### Running Tests
- Run the minimal number of tests using an appropriate filter before finalizing code edits.
- To run all tests: `php artisan test`.
- To run all tests in a file: `php artisan test tests/Feature/ExampleTest.php`.
- To filter on a particular test name: `php artisan test --filter=testName` (recommended after making a change to a related file).
- When the tests relating to your changes are passing, ask the user if they would like to run the entire test suite to ensure everything is still passing.

### Pest Assertions
- When asserting status codes on a response, use the specific method like `assertForbidden` and `assertNotFound` instead of using `assertStatus(403)` or similar, e.g.:
<code-snippet name="Pest Example Asserting postJson Response" lang="php">
it('returns all', function () {
    $response = $this->postJson('/api/docs', []);

    $response->assertSuccessful();
});
</code-snippet>

### Mocking
- Mocking can be very helpful when appropriate.
- When mocking, you can use the `Pest\Laravel\mock` Pest function, but always import it via `use function Pest\Laravel\mock;` before using it. Alternatively, you can use `$this->mock()` if existing tests do.
- You can also create partial mocks using the same import or self method.

### Datasets
- Use datasets in Pest to simplify tests which have a lot of duplicated data. This is often the case when testing validation rules, so consider going with this solution when writing tests for validation rules.

<code-snippet name="Pest Dataset Example" lang="php">
it('has emails', function (string $email) {
    expect($email)->not->toBeEmpty();
})->with([
    'james' => 'james@laravel.com',
    'taylor' => 'taylor@laravel.com',
]);
</code-snippet>


=== pest/v4 rules ===

## Pest 4

- Pest v4 is a huge upgrade to Pest and offers: browser testing, smoke testing, visual regression testing, test sharding, and faster type coverage.
- Browser testing is incredibly powerful and useful for this project.
- Browser tests should live in `tests/Browser/`.
- Use the `search-docs` tool for detailed guidance on utilizing these features.

### Browser Testing
- You can use Laravel features like `Event::fake()`, `assertAuthenticated()`, and model factories within Pest v4 browser tests, as well as `RefreshDatabase` (when needed) to ensure a clean state for each test.
- Interact with the page (click, type, scroll, select, submit, drag-and-drop, touch gestures, etc.) when appropriate to complete the test.
- If requested, test on multiple browsers (Chrome, Firefox, Safari).
- If requested, test on different devices and viewports (like iPhone 14 Pro, tablets, or custom breakpoints).
- Switch color schemes (light/dark mode) when appropriate.
- Take screenshots or pause tests for debugging when appropriate.

### Example Tests

<code-snippet name="Pest Browser Test Example" lang="php">
it('may reset the password', function () {
    Notification::fake();

    $this->actingAs(User::factory()->create());

    $page = visit('/sign-in'); // Visit on a real browser...

    $page->assertSee('Sign In')
        ->assertNoJavascriptErrors() // or ->assertNoConsoleLogs()
        ->click('Forgot Password?')
        ->fill('email', 'nuno@laravel.com')
        ->click('Send Reset Link')
        ->assertSee('We have emailed your password reset link!')

    Notification::assertSent(ResetPassword::class);
});
</code-snippet>

<code-snippet name="Pest Smoke Testing Example" lang="php">
$pages = visit(['/', '/about', '/contact']);

$pages->assertNoJavascriptErrors()->assertNoConsoleLogs();
</code-snippet>


=== inertia-vue/core rules ===

## Inertia + Vue

- Vue components must have a single root element.
- Use `router.visit()` or `<Link>` for navigation instead of traditional links.

<code-snippet name="Inertia Client Navigation" lang="vue">

    import { Link } from '@inertiajs/vue3'
    <Link href="/">Home</Link>

</code-snippet>


=== inertia-vue/v2/forms rules ===

## Inertia + Vue Forms

<code-snippet name="`<Form>` Component Example" lang="vue">

<Form
    action="/users"
    method="post"
    #default="{
        errors,
        hasErrors,
        processing,
        progress,
        wasSuccessful,
        recentlySuccessful,
        setError,
        clearErrors,
        resetAndClearErrors,
        defaults,
        isDirty,
        reset,
        submit,
  }"
>
    <input type="text" name="name" />

    <div v-if="errors.name">
        {{ errors.name }}
    </div>

    <button type="submit" :disabled="processing">
        {{ processing ? 'Creating...' : 'Create User' }}
    </button>

    <div v-if="wasSuccessful">User created successfully!</div>
</Form>

</code-snippet>


=== tailwindcss/core rules ===

## Tailwind Core

- Use Tailwind CSS classes to style HTML, check and use existing tailwind conventions within the project before writing your own.
- Offer to extract repeated patterns into components that match the project's conventions (i.e. Blade, JSX, Vue, etc..)
- Think through class placement, order, priority, and defaults - remove redundant classes, add classes to parent or child carefully to limit repetition, group elements logically
- You can use the `search-docs` tool to get exact examples from the official documentation when needed.

### Spacing
- When listing items, use gap utilities for spacing, don't use margins.

    <code-snippet name="Valid Flex Gap Spacing Example" lang="html">
        <div class="flex gap-8">
            <div>Superior</div>
            <div>Michigan</div>
            <div>Erie</div>
        </div>
    </code-snippet>


### Dark Mode
- If existing pages and components support dark mode, new pages and components must support dark mode in a similar way, typically using `dark:`.


=== tailwindcss/v4 rules ===

## Tailwind 4

- Always use Tailwind CSS v4 - do not use the deprecated utilities.
- `corePlugins` is not supported in Tailwind v4.
- In Tailwind v4, configuration is CSS-first using the `@theme` directive — no separate `tailwind.config.js` file is needed.
<code-snippet name="Extending Theme in CSS" lang="css">
@theme {
  --color-brand: oklch(0.72 0.11 178);
}
</code-snippet>

- In Tailwind v4, you import Tailwind using a regular CSS `@import` statement, not using the `@tailwind` directives used in v3:

<code-snippet name="Tailwind v4 Import Tailwind Diff" lang="diff">
   - @tailwind base;
   - @tailwind components;
   - @tailwind utilities;
   + @import "tailwindcss";
</code-snippet>


### Replaced Utilities
- Tailwind v4 removed deprecated utilities. Do not use the deprecated option - use the replacement.
- Opacity values are still numeric.

| Deprecated |	Replacement |
|------------+--------------|
| bg-opacity-* | bg-black/* |
| text-opacity-* | text-black/* |
| border-opacity-* | border-black/* |
| divide-opacity-* | divide-black/* |
| ring-opacity-* | ring-black/* |
| placeholder-opacity-* | placeholder-black/* |
| flex-shrink-* | shrink-* |
| flex-grow-* | grow-* |
| overflow-ellipsis | text-ellipsis |
| decoration-slice | box-decoration-slice |
| decoration-clone | box-decoration-clone |


=== laravel/fortify rules ===

## Laravel Fortify

Fortify is a headless authentication backend that provides authentication routes and controllers for Laravel applications.

**Before implementing any authentication features, use the `search-docs` tool to get the latest docs for that specific feature.**

### Configuration & Setup
- Check `config/fortify.php` to see what's enabled. Use `search-docs` for detailed information on specific features.
- Enable features by adding them to the `'features' => []` array: `Features::registration()`, `Features::resetPasswords()`, etc.
- To see the all Fortify registered routes, use the `list-routes` tool with the `only_vendor: true` and `action: "Fortify"` parameters.
- Fortify includes view routes by default (login, register). Set `'views' => false` in the configuration file to disable them if you're handling views yourself.

### Customization
- Views can be customized in `FortifyServiceProvider`'s `boot()` method using `Fortify::loginView()`, `Fortify::registerView()`, etc.
- Customize authentication logic with `Fortify::authenticateUsing()` for custom user retrieval / validation.
- Actions in `app/Actions/Fortify/` handle business logic (user creation, password reset, etc.). They're fully customizable, so you can modify them to change feature behavior.

## Available Features
- `Features::registration()` for user registration.
- `Features::emailVerification()` to verify new user emails.
- `Features::twoFactorAuthentication()` for 2FA with QR codes and recovery codes.
  - Add options: `['confirmPassword' => true, 'confirm' => true]` to require password confirmation and OTP confirmation before enabling 2FA.
- `Features::updateProfileInformation()` to let users update their profile.
- `Features::updatePasswords()` to let users change their passwords.
- `Features::resetPasswords()` for password reset via email.
</laravel-boost-guidelines>

# Tailwind CSS Rules and Best Practices

## Core Principles

- **Always use Tailwind CSS v4.1+** - Ensure the codebase is using the latest version
- **Do not use deprecated or removed utilities** - ALWAYS use the replacement
- **Never use `@apply`** - Use CSS variables, the `--spacing()` function, or framework components instead
- **Check for redundant classes** - Remove any classes that aren't necessary
- **Group elements logically** to simplify responsive tweaks later

## Upgrading to Tailwind CSS v4

### Before Upgrading

- **Always read the upgrade documentation first** - Read https://tailwindcss.com/docs/upgrade-guide and https://tailwindcss.com/blog/tailwindcss-v4 before starting an upgrade.
- Ensure the git repository is in a clean state before starting

### Upgrade Process

1. Run the upgrade command: `npx @tailwindcss/upgrade@latest` for both major and minor updates
2. The tool will convert JavaScript config files to the new CSS format
3. Review all changes extensively to clean up any false positives
4. Test thoroughly across your application

## Breaking Changes Reference

### Removed Utilities (NEVER use these in v4)

| ❌ Deprecated           | ✅ Replacement                                    |
| ----------------------- | ------------------------------------------------- |
| `bg-opacity-*`          | Use opacity modifiers like `bg-black/50`          |
| `text-opacity-*`        | Use opacity modifiers like `text-black/50`        |
| `border-opacity-*`      | Use opacity modifiers like `border-black/50`      |
| `divide-opacity-*`      | Use opacity modifiers like `divide-black/50`      |
| `ring-opacity-*`        | Use opacity modifiers like `ring-black/50`        |
| `placeholder-opacity-*` | Use opacity modifiers like `placeholder-black/50` |
| `flex-shrink-*`         | `shrink-*`                                        |
| `flex-grow-*`           | `grow-*`                                          |
| `overflow-ellipsis`     | `text-ellipsis`                                   |
| `decoration-slice`      | `box-decoration-slice`                            |
| `decoration-clone`      | `box-decoration-clone`                            |

### Renamed Utilities (ALWAYS use the v4 name)

| ❌ v3              | ✅ v4              |
| ------------------ | ------------------ |
| `bg-gradient-*`    | `bg-linear-*`      |
| `shadow-sm`        | `shadow-xs`        |
| `shadow`           | `shadow-sm`        |
| `drop-shadow-sm`   | `drop-shadow-xs`   |
| `drop-shadow`      | `drop-shadow-sm`   |
| `blur-sm`          | `blur-xs`          |
| `blur`             | `blur-sm`          |
| `backdrop-blur-sm` | `backdrop-blur-xs` |
| `backdrop-blur`    | `backdrop-blur-sm` |
| `rounded-sm`       | `rounded-xs`       |
| `rounded`          | `rounded-sm`       |
| `outline-none`     | `outline-hidden`   |
| `ring`             | `ring-3`           |

## Layout and Spacing Rules

### Flexbox and Grid Spacing

#### Always use gap utilities for internal spacing

Gap provides consistent spacing without edge cases (no extra space on last items). It's cleaner and more maintainable than margins on children.

```html
<!-- ❌ Don't do this -->
<div class="flex">
  <div class="mr-4">Item 1</div>
  <div class="mr-4">Item 2</div>
  <div>Item 3</div>
  <!-- No margin on last -->
</div>

<!-- ✅ Do this instead -->
<div class="flex gap-4">
  <div>Item 1</div>
  <div>Item 2</div>
  <div>Item 3</div>
</div>
```

#### Gap vs Space utilities

- **Never use `space-x-*` or `space-y-*` in flex/grid layouts** - always use gap
- Space utilities add margins to children and have issues with wrapped items
- Gap works correctly with flex-wrap and all flex directions

```html
<!-- ❌ Avoid space utilities in flex containers -->
<div class="flex flex-wrap space-x-4">
  <!-- Space utilities break with wrapped items -->
</div>

<!-- ✅ Use gap for consistent spacing -->
<div class="flex flex-wrap gap-4">
  <!-- Gap works perfectly with wrapping -->
</div>
```

### General Spacing Guidelines

- **Prefer top and left margins** over bottom and right margins (unless conditionally rendered)
- **Use padding on parent containers** instead of bottom margins on the last child
- **Always use `min-h-dvh` instead of `min-h-screen`** - `min-h-screen` is buggy on mobile Safari
- **Prefer `size-*` utilities** over separate `w-*` and `h-*` when setting equal dimensions
- For max-widths, prefer the container scale (e.g., `max-w-2xs` over `max-w-72`)

## Typography Rules

### Line Heights

- **Never use `leading-*` classes** - Always use line height modifiers with text size
- **Always use fixed line heights from the spacing scale** - Don't use named values

```html
<!-- ❌ Don't do this -->
<p class="text-base leading-7">Text with separate line height</p>
<p class="text-lg leading-relaxed">Text with named line height</p>

<!-- ✅ Do this instead -->
<p class="text-base/7">Text with line height modifier</p>
<p class="text-lg/8">Text with specific line height</p>
```

### Font Size Reference

Be precise with font sizes - know the actual pixel values:

- `text-xs` = 12px
- `text-sm` = 14px
- `text-base` = 16px
- `text-lg` = 18px
- `text-xl` = 20px

## Color and Opacity

### Opacity Modifiers

**Never use `bg-opacity-*`, `text-opacity-*`, etc.** - use the opacity modifier syntax:

```html
<!-- ❌ Don't do this -->
<div class="bg-red-500 bg-opacity-60">Old opacity syntax</div>

<!-- ✅ Do this instead -->
<div class="bg-red-500/60">Modern opacity syntax</div>
```

## Responsive Design

### Breakpoint Optimization

- **Check for redundant classes across breakpoints**
- **Only add breakpoint variants when values change**

```html
<!-- ❌ Redundant breakpoint classes -->
<div class="px-4 md:px-4 lg:px-4">
  <!-- md:px-4 and lg:px-4 are redundant -->
</div>

<!-- ✅ Efficient breakpoint usage -->
<div class="px-4 lg:px-8">
  <!-- Only specify when value changes -->
</div>
```

## Dark Mode

### Dark Mode Best Practices

- Use the plain `dark:` variant pattern
- Put light mode styles first, then dark mode styles
- Ensure `dark:` variant comes before other variants

```html
<!-- ✅ Correct dark mode pattern -->
<div class="bg-white text-black dark:bg-black dark:text-white">
  <button class="hover:bg-gray-100 dark:hover:bg-gray-800">Click me</button>
</div>
```

## Gradient Utilities

- **ALWAYS Use `bg-linear-*` instead of `bg-gradient-*` utilities** - The gradient utilities were renamed in v4
- Use the new `bg-radial` or `bg-radial-[<position>]` to create radial gradients
- Use the new `bg-conic` or `bg-conic-*` to create conic gradients

```html
<!-- ✅ Use the new gradient utilities -->
<div class="h-14 bg-linear-to-br from-violet-500 to-fuchsia-500"></div>
<div
  class="size-18 bg-radial-[at_50%_75%] from-sky-200 via-blue-400 to-indigo-900 to-90%"
></div>
<div
  class="size-24 bg-conic-180 from-indigo-600 via-indigo-50 to-indigo-600"
></div>

<!-- ❌ Do not use bg-gradient-* utilities -->
<div class="h-14 bg-gradient-to-br from-violet-500 to-fuchsia-500"></div>
```

## Working with CSS Variables

### Accessing Theme Values

Tailwind CSS v4 exposes all theme values as CSS variables:

```css
/* Access colors, and other theme values */
.custom-element {
  background: var(--color-red-500);
  border-radius: var(--radius-lg);
}
```

### The `--spacing()` Function

Use the dedicated `--spacing()` function for spacing calculations:

```css
.custom-class {
  margin-top: calc(100vh - --spacing(16));
}
```

### Extending theme values

Use CSS to extend theme values:

```css
@import "tailwindcss";

@theme {
  --color-mint-500: oklch(0.72 0.11 178);
}
```

```html
<div class="bg-mint-500">
  <!-- ... -->
</div>
```

## New v4 Features

### Container Queries

Use the `@container` class and size variants:

```html
<article class="@container">
  <div class="flex flex-col @md:flex-row @lg:gap-8">
    <img class="w-full @md:w-48" />
    <div class="mt-4 @md:mt-0">
      <!-- Content adapts to container size -->
    </div>
  </div>
</article>
```

### Container Query Units

Use container-based units like `cqw` for responsive sizing:

```html
<div class="@container">
  <h1 class="text-[50cqw]">Responsive to container width</h1>
</div>
```

### Text Shadows (v4.1)

Use text-shadow-\* utilities from text-shadow-2xs to text-shadow-lg:

```html
<!-- ✅ Text shadow examples -->
<h1 class="text-shadow-lg">Large shadow</h1>
<p class="text-shadow-sm/50">Small shadow with opacity</p>
```

### Masking (v4.1)

Use the new composable mask utilities for image and gradient masks:

```html
<!-- ✅ Linear gradient masks on specific sides -->
<div class="mask-t-from-50%">Top fade</div>
<div class="mask-b-from-20% mask-b-to-80%">Bottom gradient</div>
<div class="mask-linear-from-white mask-linear-to-black/60">
  Fade from white to black
</div>

<!-- ✅ Radial gradient masks -->
<div class="mask-radial-[100%_100%] mask-radial-from-75% mask-radial-at-left">
  Radial mask
</div>
```

## Component Patterns

### Avoiding Utility Inheritance

Don't add utilities to parents that you override in children:

```html
<!-- ❌ Avoid this pattern -->
<div class="text-center">
  <h1>Centered Heading</h1>
  <div class="text-left">Left-aligned content</div>
</div>

<!-- ✅ Better approach -->
<div>
  <h1 class="text-center">Centered Heading</h1>
  <div>Left-aligned content</div>
</div>
```

### Component Extraction

- Extract repeated patterns into framework components, not CSS classes
- Keep utility classes in templates/JSX
- Use data attributes for complex state-based styling

## CSS Best Practices

### Nesting Guidelines

- Use nesting when styling both parent and children
- Avoid empty parent selectors

```css
/* ✅ Good nesting - parent has styles */
.card {
  padding: --spacing(4);

  > .card-title {
    font-weight: bold;
  }
}

/* ❌ Avoid empty parents */
ul {
  > li {
    /* Parent has no styles */
  }
}
```

## Common Pitfalls to Avoid

1. **Using old opacity utilities** - Always use `/opacity` syntax like `bg-red-500/60`
2. **Redundant breakpoint classes** - Only specify changes
3. **Space utilities in flex/grid** - Always use gap
4. **Leading utilities** - Use line-height modifiers like `text-sm/6`
5. **Arbitrary values** - Use the design scale
6. **@apply directive** - Use components or CSS variables
7. **min-h-screen on mobile** - Use min-h-dvh
8. **Separate width/height** - Use size utilities when equal
9. **Arbitrary values** - Always use Tailwind's predefined scale whenever possible (e.g., use `ml-4` over `ml-[16px]`)
