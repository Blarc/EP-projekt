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

#### shoppingLists statusi:
* 0 - unprocessed
* 1 - processed
* 2 - stornated
* 3 - checkout


#### Uporabniki
* admin: jakob@ep.si asdfasdf
* seller: franc@ep.si asdfasdf
* customer: jan@ep.si asdfasdf

## How to install

#### Configure manual updates
```
dpkg --configure -a
```

#### Update apt
```
apt-get update
```

#### Download nodejs setup files
```
apt-get install nodejs-dev node-gyp libssl1.0-dev -y
```

#### Install required packages
```
apt install composer -y
apt install npm -y
```

#### Configure apache2
```
cp ep.conf /etc/apache2/sites-available/
mkdir /etc/apache2/ssl
cp ./certs/localhost.pem /etc/apache2/ssl
cp ./certs/epca.crt /etc/apache2/ssl
cp ./certs/epca-crt.pem /etc/apache2/ssl
```

#### Enable site
```
a2dissite 000-default.conf
a2ensite ep.conf
```

#### Enable apache2 rewrite/ssl
```
sudo a2enmod rewrite
sudo a2enmod ssl
```

#### Restart apache2 server
```
systemctl reload apache2
```

#### Change directory to WebApp
```
cd WebApp
```

#### Install and compile npm dependencies
```
npm install
```

#### Install composer dependencies
```
composer install
composer update
```
If needed:
* ( composer require spatie/laravel-permission )
* ( composer require ingria/laravel-x509-auth )

#### Add laravel database to mysql instance
```
mysql -u root -pep -e 'CREATE DATABASE IF NOT EXISTS laravel'
```

#### Create .env file
```
cp .eny.example .env
```

#### Migrate and seed data
```
php artisan migrate
php artisan db:seed
```

#### Generate Laravel key
```
php artisan key:generate
```

#### Set some permissions
```
chown -R $USER:www-data .
chmod -R 755 .
```
Id needed:
* ( chown -R $USER:www-data bootstrap/cache )
* ( chmod -R 775 storage )
* ( chmod -R 775 bootstrap/cache )



