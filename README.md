# Install

Open the prompt.
Choose your directory destination.
Clone this repository following this instruction: 

`$ git clone https://github.com/lorenzosemorile/symfony.git`

Install all dependencies of the project.
`composer install`

If you have Symfony client installed, start the server
`$ symfony serve`

Otherwise, run 
`php bin/console server:run.`

This project is for testing only.
For this reason i've used mysqlite.
Be sure to have in your application the app.db file in var directory.

After few seconds navigate in
https://localhost/product/create
