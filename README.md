# calendar-events
Coding exam challenge

## Installation

- `composer create-project --prefer-dist edsel77/calendar-events`
- Edit `.env` and set your database connection details
- (When installed via git clone or download, run `php artisan key:generate` and `php artisan jwt:secret`)
- `php artisan migrate`
- `npm install`

## Usage

#### Development

```bash
#laravel server
php artisan serve

# build and watch
npm run watch
```

#### Production

```bash
npm run production
```
