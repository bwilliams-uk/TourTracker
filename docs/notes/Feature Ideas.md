# Feature Ideas
Ideas that may later be a prioritsed as TODO items.

## General

[ ] Deleting a tour should initially be a 'soft' delete, meaning it is just hidden from the user. Data is still retained and collected for tour to improve data insights. Recreating the tour means no data is lost.

## Alerts

[ ] Alerts should be 'dismissable' instead of just a log of changes. This would involve refactoring and creating an 'Alert' entity type so properties such as 'isDismissed' can be recorded.

[ ] 'Alerts' UI button shows number of new alerts.

## Advanced Filtering

[ ] Filter watch list by dates.

[ ] Filter by tours/departures by country. Extractor classes will need to be ammended to gather country data from the operators website. 


## Calendar Display

[ ] Display Watchlist as a calendar, this should help the user identify watched tours within the same dates ranges.

[ ] Extending the calendar, users can add their own events. E.g. known flights. Once a tour is booked, the user can convert the tours date range to an event.


## Pricing and Availability

[ ] The departures view should show an indication of how 'good' the current price is, compared with past data about the tour/departure.

[ ] Offer analysis of best time to book tour to get the best prices.

[ ] Offer prediction of whether the price of a departure is likely to rise or fall.

[ ] The departures view should indicate a departure as 'selling fast' based on recent changes to availability.

## Adjoining Tours Feature

[ ] Specify tours which you want to do in succession, The application will automatically check for each possibility that fits and give you an overall price in each case.
[ ] You should be able to specify the min/max time between each tour.
[ ] You should be able to specify earliest/latest start and end dates.