# ImageGallery with Vue & Laravel

This project is a web application built using Laravel for the backend and Vue.js for the frontend. It includes features such as image upload and user authentication.

## Prerequisites

- PHP ^8.2
- Composer
- Node.js and npm

## Installation

### Backend (Laravel)

1. Clone the repository:

   ```sh
   git clone https://github.com/Chi-G/laravel-with-vue-js.git
   cd your-repo/backend

2. Install the dependencies:

    ```sh
    composer install
    cp .env.example .env
    php artisan key:generate
    php artisan storage:link
    php artisan migrate
    php artisan serve


### Usage

Open your browser and navigate to <http://localhost:8000> to access the Laravel backend.
