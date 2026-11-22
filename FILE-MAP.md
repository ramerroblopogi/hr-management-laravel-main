# Appendix A: Where Each File Is Finished

Files finished at each step, in guide order. Generated from this branch history.

## Step 1-4: Initialize Laravel project

- Laravel 12 skeleton (`composer.json`, `artisan`, `bootstrap/`, `config/`, `database/`, `public/`, `resources/`, `storage/`, `tests/`)
- `routes/api.php` and `bootstrap/app.php` - API wiring from `php artisan install:api`

## Step 5: API Response

- `app/Traits/ApiResponses.php` (new)

## Step 6: Designing the URL

- `app/Models/Product.php` (new)
- `database/factories/ProductFactory.php` (new)
- `database/migrations/2026_11_22_145722_create_products_table.php` (new)
- `database/seeders/DatabaseSeeder.php`
- `routes/api.php`

## Step 7: How to Version Your API

- `app/Http/Controllers/Api/V1/ProductController.php` (new)
- `bootstrap/app.php`
- `routes/api.php`
- `routes/api_v1.php` (new)

## Step 8: Authentication with JWT

- `app/Http/Controllers/Api/V1/AuthController.php` (new)
- `app/Http/Requests/Api/LoginUserRequest.php` (new)
- `app/Http/Requests/Api/RegisterUserRequest.php` (new)
- `app/Models/User.php`
- `composer.json`
- `config/auth.php`
- `config/jwt.php` (new)
- `routes/api_v1.php`

## Step 9: Designing Response Payloads

- `app/Http/Controllers/Api/V1/ProductController.php`
- `app/Http/Resources/V1/ProductResource.php` (new)
- `routes/api_v1.php`

## Step 10: Conditionally Omitting and Including Data

- `app/Http/Controllers/Api/V1/AuthorsController.php` (new)
- `app/Http/Resources/V1/ProductResource.php`
- `app/Http/Resources/V1/UserResource.php` (new)
- `app/Models/Product.php`
- `app/Models/User.php`
- `routes/api_v1.php`

## Step 11: Using Optional Parameters to Load Optional Data

- `app/Http/Controllers/Api/V1/APIController.php` (new)
- `app/Http/Controllers/Api/V1/AuthorsController.php`
- `app/Http/Controllers/Api/V1/ProductController.php`
- `app/Http/Resources/V1/ProductResource.php`
- `app/Http/Resources/V1/UserResource.php`

## Step 12: Writing Filters

- `app/Http/Controllers/Api/V1/ProductController.php`
- `app/Http/Filters/V1/ProductFilter.php` (new)
- `app/Http/Filters/V1/QueryFilter.php` (new)
- `app/Models/Product.php`

## Step 13: Using (and Filtering) Nested Resources

- `app/Http/Controllers/Api/V1/UserProductsController.php` (new)
- `routes/api_v1.php`

## Step 14: Sorting Data

- `app/Http/Controllers/Api/V1/AuthorsController.php`
- `app/Http/Filters/V1/AuthorFilter.php` (new)
- `app/Http/Filters/V1/ProductFilter.php`
- `app/Http/Filters/V1/QueryFilter.php`
- `app/Models/User.php`

## Step 15: Creating Resources with POST Requests

- `app/Http/Controllers/Api/V1/APIController.php`
- `app/Http/Controllers/Api/V1/ProductController.php`
- `app/Http/Requests/Api/V1/BaseProductRequest.php` (new)
- `app/Http/Requests/Api/V1/StoreProductRequest.php` (new)
- `routes/api_v1.php`

## Step 16: Deleting Resources with DELETE Requests

- `app/Http/Controllers/Api/V1/ProductController.php`
- `routes/api_v1.php`

## Step 17: Replacing Resources with PUT Requests

- `app/Http/Controllers/Api/V1/ProductController.php`
- `app/Http/Requests/Api/V1/ReplaceProductRequest.php` (new)
- `routes/api_v1.php`

## Step 18: Updating Resources with PATCH Requests

- `app/Http/Controllers/Api/V1/ProductController.php`
- `app/Http/Requests/Api/V1/UpdateProductRequest.php` (new)
- `routes/api_v1.php`

## Step 19: Using Policies for User Authorization

- `app/Http/Controllers/Api/V1/APIController.php`
- `app/Http/Controllers/Api/V1/ProductController.php`
- `app/Policies/V1/ProductPolicy.php` (new)
- `app/Providers/AppServiceProvider.php`
- `app/Traits/ApiResponses.php`

## Step 20: Controlling Access with Token Abilities

- `app/Http/Controllers/Api/V1/ProductController.php`
- `app/Models/User.php`
- `app/Permissions/V1/Abilities.php` (new)
- `app/Policies/V1/ProductPolicy.php`
- `app/Providers/AppServiceProvider.php`
- `database/migrations/2026_11_22_150000_add_is_manager_to_users_table.php` (new)
- `database/seeders/DatabaseSeeder.php`

## Step 21: Applying Granular Permissions

- `app/Http/Requests/Api/V1/StoreProductRequest.php`
- `app/Http/Requests/Api/V1/UpdateProductRequest.php`

## Step 22: Implementing the Users Products Controller

- `app/Http/Controllers/Api/V1/UserProductsController.php`
- `routes/api_v1.php`

## Step 23: Managing Users

- `app/Http/Controllers/Api/V1/UserController.php` (new)
- `app/Http/Requests/Api/V1/BaseUserRequest.php` (new)
- `app/Http/Requests/Api/V1/ReplaceUserRequest.php` (new)
- `app/Http/Requests/Api/V1/StoreUserRequest.php` (new)
- `app/Http/Requests/Api/V1/UpdateUserRequest.php` (new)
- `app/Http/Resources/V1/UserResource.php`
- `app/Policies/V1/UserPolicy.php` (new)
- `app/Providers/AppServiceProvider.php`
- `routes/api_v1.php`

## Step 24: The Principle of Least Privilege

- `app/Http/Requests/Api/V1/StoreProductRequest.php`
- `app/Http/Requests/Api/V1/UpdateProductRequest.php`

## Step 25: Handling Errors

- `app/Exceptions/Handler.php` (new)
- `app/Traits/ApiResponses.php`
- `bootstrap/app.php`

## Step 26: API Documentation with Scribe

- `.gitignore`
- `.scribe/auth.md` (new)
- `.scribe/intro.md` (new)
- `app/Http/Controllers/Api/V1/AuthController.php`
- `app/Http/Controllers/Api/V1/ProductController.php`
- `app/Http/Requests/Api/V1/StoreProductRequest.php`
- `app/Http/Requests/Api/V1/UpdateProductRequest.php`
- `composer.json`
- `config/scribe.php` (new)

