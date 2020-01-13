### Spletna aplikacija
Spletna aplikacija pri predmetu elektronsko poslovanje.

##### Priprava okolja (windows)
Za pogon spletne aplikacije potrebujemo XAMP.
(https://youtu.be/H3uRXvwXz1o)

Kot virtual host naslov uproabi nekaj drugega kot .dev, saj
na firefox/chrome to ne dela. Npr. `laravel.test`

Projekt ne rabi biti nujno v apache2/htdocs, vendar če
želimo da je kje drugje, je potrebno spremeniti pot do
direktorija v config-u (glej video).

Za delovanje je potrebno preimenovati datoteko `.env.example` v `.env`.

##### Podatkovna baza (mySQL)
V datoteki `.env` nastavimo potrebne parametere:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=password
```
Za uporabo paketa laravel/permission:
`composer require spatie/laravel-permission`

Poženemo migracijo:
`php artisan migrate`

Če želimo napolniti bazo poženemo:
`php artisan db:seed`

####shoppingLists statusi:
* 0 - unprocessed
* 1 - processed
* 2 - stornated
* 3 - checkout

####Uporabniški računi
admin: admin@gmail.com asdfasdf
seller: seller@gmail.com adsdfasdf
customer: customer@gmail.com asdfasdf

