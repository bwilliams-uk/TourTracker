/*
Query to show Average price and availability
for a tour by days before start date */

SELECT 
tour_id, 
days_prior, 
ROUND(AVG(price)) avg_price, 
ROUND(AVG(availability)) avg_availability 
FROM (
SELECT departure.tour_id, 
ROUND(DATEDIFF(departure.start_date,departure_update.created_at)/5,0)*5 AS days_prior,
price,
availability
FROM departure 
JOIN departure_update 
ON departure.id=departure_update.departure_id

-- Set Tour ID here:
WHERE departure.tour_id = 35


) q 
GROUP BY tour_id,days_prior
ORDER BY days_prior;