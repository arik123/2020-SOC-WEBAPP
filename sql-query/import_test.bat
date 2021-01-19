createuser -U postgres -P osmuser 
createdb -U postgres --encoding=UTF8 --owner=osmuser osm_test 
psql -U postgres --command='CREATE EXTENSION postgis;' osm_test 
psql -U postgres --command='CREATE EXTENSION hstore;' osm_test 
psql -U postgres --command='CREATE EXTENSION pgRouting;' osm_test 
psql -U postgres --command='GRANT EXECUTE ON ALL FUNCTIONS IN SCHEMA public TO osmuser;' osm_test 
osm2pgsql/osm2pgsql.exe -c --database=osm_test -U postgres --style=./compatible.lua -v -C 8000 --output=flex ./slovakia.osm.pbf -l -W