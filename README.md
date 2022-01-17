## Sample app to send multiple emails asynchronously over API in Laravel

###Overview:

- Build a simple API that accepts an array of emails, each of them
  having subject, body, attachments (could be many or none) and the email
  address where is the email going to
- Emails are sent asynchronously using queues with redis as a queue connection
- Lists all sent emails with email, subject, body and
  downloadable attachments
- Test the routes


### Points to be noted before running this sample
1. "composer install" need to be run to


2. .evn file needs to be added and updated with DB and MAIL related environment variables


3. 'predis' client was used in this demo instead of default 'phpredis'. 
If you intend to use default redis client make sure to comment out 'Redis' alias in config/app.php (otherwise it will conflict with redis clients class)


4. An 'api' guard (that uses 'token' driver) has been added in config/auth.php 
and the token is used as a URI parameter in the request api_token={{your_api_token}}

   
5. In phpunit tests 'mysql' database has been used


5. Because horizon has been used so following commands needs to be run because horizon related assets and service provider has not been committed
   php artisan horizon:install


8. Following queue related migrations need to be run:
    
- php artisan queue:table

- php artisan migrate

- php artisan horizon

9. One command 'test' has been added in composer.json to run the test cases 
i.e. composer test 
