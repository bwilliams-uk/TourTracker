SELECT id
FROM tour_operator
WHERE (:web IS NULL OR :web LIKE CONCAT('%',web,'%'))
