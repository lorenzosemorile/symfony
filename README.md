# Install

Open the prompt.
Choose your directory destination.
Clone this repository following this instruction: 
<br />
`$ git clone https://github.com/lorenzosemorile/symfony.git`
<br />
<br />
Install all dependencies of the project.
<br />
`composer install`
<br />
<br />
If you have Symfony client installed, start the server
<br />
`$ symfony serve`
<br />
<br />
Otherwise, run
<br /> 
`php bin/console server:run.`
<br />
<br />
This project is for testing only.
<br />
For this reason i've used mysqlite.
<br />
Be sure to have in your application the `app.db` file in var directory.
<br />
After few seconds navigate in
https://localhost:8000/product/create
