DROP TABLE IF EXISTS planet_osm_line_noded CASCADE;
ALTER TABLE planet_osm_line ADD PRIMARY KEY (id);				
--create noded
select pgr_nodenetworkMY('planet_osm_line', 0, the_geom:='way', rows_where:='highway IS NOT NULL');
