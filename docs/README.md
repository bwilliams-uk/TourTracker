# TourTracker

## About

TourTracker is a web-based application which aims to help users find deals on small group tours and prevent dissapointment of missing the last available spaces on departures they have registered an interest in. It achieves this by extracting price and availability information from tour operator's websites and monitoring for changes relevant to the user.

## Present Features

The project is in ongoing development, however the current version offers proof of concept with the folllowing features implemented:

- The user can add a tour by copy and pasting the URL of any *G Adventures* or *Intrepid* tour. The name of the tour is automatically obtained from the website.

- The user can 'Synchronise' a tour, meaning up-to-date departure, pricing and availability is automatically retrieved from the tour's website and saved within application for subsequent quick retrieval.

- A list of upcoming departures for a selected tour can be displayed showing most-recent pricing and availability information (Tours with no availability are greyed out).

- A record of past prices and availability can be viewed for selected departure, providing insights into whether prices are increasing/decreasing and how quickly the tour is selling.

- The user can mark a selected departure as 'Watched', making it easier to find the departure again later by going to their 'Watchlist'.

> Screenshots at the end of this page are provided to clarify these features.  

## Technologies

Backend development with PHP and MySQL. PHP's cURL library is used to source data from the tour operator's website/API. Frontend is developed with HTML, CSS and JavaScript.

## Current Issues

Consideration to how the code base is structured is required before any further features are implemented to ensure maintainability as the project scales. In particular, an appropriate design pattern needs to be identified to allow the mapping database table records to PHP entity objects.

## Future Direction

The following stories have been identified for how the project may be improved in future:

- Support for other tour operators.

- Ability to filter departures by date ranges.

- A calendar-like display for watched departures so that overlapping or adjoining tours can be identified.

- Price predictions based on past data. I.e. the ability to answer the question 'Is now a good time book?'


## Screenshots

Tours View.

![](img\2024-03-14-12-12-23-image.png)

Departures View for Trips to Bali.

![](img\2024-03-14-12-13-10-image.png)

View price and availability History for a selected departure.

![](img\2024-03-14-12-13-25-image.png)

Watchlist: view latest information for all watched departures.

![](img\2024-03-14-12-14-44-image.png)
