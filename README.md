Google Mini OneBox Module Example

Google Mini offers the option of using OneBox modules that expand on items that are queried when someone runs a search. These can be triggered all the time or triggered by an algorithm. If you go to google.com and search for "flights to London" or "pizza in New York" you will see different types of results outside of the standard results. These are OneBox modules that Google uses and are similar to what you can use in your Google Mini searches.

The developers guide can be found here:
https://developers.google.com/search-appliance/documentation/50/oneboxguide

When configured, you specify a URL and the Mini will make a GET request to that page. You specify the time-out settings, but if the page takes too long, those results will be ignored.

googleMiniEmployeeSearch.php is a PHP example of querying an employee database.