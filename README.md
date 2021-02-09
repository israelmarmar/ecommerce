# Ecommerce

To run the application, have NodeJS, XAMPP (Apache, PHP, MariaDB) and Composer installed on your machine.
First, set the environment variables in the .env.example file, enter the database settings, the JWT_SECRET and the Stripe API key and rename the env.sample file to .env.

Set the admin user in /database/seeders/DatabaseSeeder.php

Then run the commands:
    npm install && npm run dev && composer update && php artisan migrate && php artisan db:seed && php artisan key:generate.

Finally run the server with the command:
    php artisan serve.

Open the URL in the browser: http://localhost:8000

