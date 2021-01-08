psql -h localhost -d osm_test -U osmuser -p 5432 -b -f ./pg_nodeNetworkMy.sql
psql -h localhost -d osm_test -U osmuser -p 5432 -b -f ./createNoded.sql
psql -h localhost -d osm_test -U osmuser -p 5432 -b -f ./speeds.sql
psql -h localhost -d osm_test -U osmuser -p 5432 -b -f ./makeRoutable.sql
psql -h localhost -d osm_test -U osmuser -p 5432 -b -f ./fnRouteCar.sql
psql -h localhost -d osm_test -U osmuser -p 5432 -b -f ./fnRouteFoot.sql