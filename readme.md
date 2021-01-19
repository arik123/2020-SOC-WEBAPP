# Cestujeme spoločne
Je to webová aplikácia vytvorená na SOC.
## Inštalácia Databázy
1. Treba nainštalovať [postgreSQL](https://www.postgresql.org/download/) databázu
2. Pri inštalácii treba nainštalovať aj modul [postGIS](https://postgis.net/) a [pgRouting](https://pgrouting.org/), pri inštalácii týchto modulov postupujeme podľa návodouv na ich stránkach.
3. Aktualizujte [./sql-qurey/slovakia.osm.pbf](http://download.geofabrik.de/europe/slovakia-latest.osm.pbf)
4. Pustite skript `./sql-qurey/import_test.bat`
5. Pustite skript `./sql-query/runAll.bat`

## Inštalácia Webu
Potrebujete mať nainštalovaný [composer](https://getcomposer.org/download/)
1. spustite `npm i`
2. spustite `npm run build`
3. spustite `composer install`
4. pripojte sa na váš webový server
5. Skopirujte naň
	```
		./config
		./public
		./src
		./templates
		./vendor
		./.env
	```
6. nastavte `.env` aby obsahovalo správne údaje o databáze a serveri
