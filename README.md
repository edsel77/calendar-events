# calendar-events
Coding challenge

## Installation

- `git clone https://github.com/edsel77/calendar-events.git`
- edit `.env` and set your database connection details
- run `composer install`
- run `php artisan key:generate` and `php artisan jwt:secret`
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
