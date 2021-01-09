DROP FUNCTION if exists matchRoute;

CREATE FUNCTION matchRoute(
    start_pt geography(POINT),
    end_pt geography(POINT),
	routeTime TIMESTAMP,
	repeat_param INTEGER
)
RETURNS table (
		id integer,
		src_dst double precision,
		dst_dst double precision
	) 
AS $$
DECLARE 
    
BEGIN
	CREATE TEMP TABLE routes ON COMMIT DROP AS (
			SELECT r.id, ST_Distance(start_pt, r.way::geography) as src_dst,
				ST_Distance(end_pt, r.way::geography) as dst_dst,
				r.time - routeTime as timeDiff,
				r.repeat
				
			FROM route as r
			WHERE ST_Distance(start_pt, r.way::geography) 
				< r.zachadzka * 1000 AND r.repeat >= repeat_param
		);
		
	
	DELETE FROM routes as r WHERE 
		EXTRACT(hour from r.timeDiff) != 0 OR
		ABS(EXTRACT(minute from r.timeDiff)) > 10;
	
	IF repeat_param = 1 then
		DELETE FROM routes as r WHERE 
			EXTRACT(day from r.timeDiff)::integer % 7 != 0 AND
			r.repeat = 1;
		IF EXTRACT(isodow from routeTime) > 5 then
			DELETE FROM routes as r WHERE 
				r.repeat = 2;
		END IF;
	ELSEIF repeat_param = 0 then
		DELETE FROM routes as r WHERE 
			r.timeDiff > interval '00:10:00' AND
			r.repeat = 0;
	END IF;
	return query select r.id, r.src_dst, r.dst_dst from routes as r
		order by r.dst_dst;
	-- TODO CHECK DIRECTION
END;
$$ LANGUAGE plpgsql;