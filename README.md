Public School API
=================

The smallest JSON API for U.S. Department of Education data about Public Elementary and 
Secondary schools. The Feds' API is too slow to be something you might actually use and
also those URLs?!

<https://inventory.data.gov/api/1/util/snippet/api_info.html?resource_id=102fd9bd-4737-401b-b88f-5c5b0fab94ec&datastore_root_url=https%3A%2F%2Finventory.data.gov%2Fapi%2Faction>

This here code lets you host your own copy of the data.

Installation
------------

1. `composer install`
1. Create a database in MySQL or whatever.
1. Import the schema at `res/schema.sql` in this new database.
1. Download the [CSV][] file for the "Public Elementary/Secondary Listing" at
   <http://www.ed.gov/developers>. The file download was unreliable for me, so you will
   want to use `wget -c` and also verify a checksum. You should end up with 103,345,840
   bytes. See below.
1. Import the CSV file into the `schools` table in your new database. I used Sequel Pro
   but do what you want.
1. `cp .env.example .env`
1. Edit `.env` and set your database parameters.
1. `php -S localhost:8000`

Usage
-----

You can use any column name as a query parameter. All the filters are made with LIKE so
it's going to be very greedy.

* <http://localhost:8000/schools?MSTATE09=IL&MCITY09=CHICAGO>
* <http://localhost:8000/schools?MSTATE09=IL&MCITY09=CHICAGO&SCHNAM09=gage>

How about those column names? All 291 of them! None of the columns are indexed, so that's
something you will want to fix before you do anything serious with this.

You can also look up a specific school by its `NCESSCH` also known as "Unique NCES public
school ID (7-digit NCES agency ID (LEAID) + 5-digit NCES school ID (SCHNO)."

* <http://localhost:8000/schools/170993000809>

Below
-----

```bash
wget -c https://inventory.data.gov/dataset/c325a86a-a0da-479d-bb87-cdbf88833b25/resource/102fd9bd-4737-401b-b88f-5c5b0fab94ec/download/userssharedsdfpublicelementarysecondaryunivsrvy200910.csv
```

Then calculate the checksum:

```bash
shasum userssharedsdfpublicelementarysecondaryunivsrvy200910.csv
```

Output will be:

```
feda6149fa02ee9289b1872f63ae9d229bba71b9  userssharedsdfpublicelementarysecondaryunivsrvy200910.csv
```

Acknowledgements
----------------

Thanks to the social scientists at *National Center for Education Statistics* and
*Education Statistics Services Institute* and who did the research that produced this
dataset. Their valuable work is documented at <http://nces.ed.gov/ccd/pdf/INsc09101a.pdf>.

[CSV]: https://inventory.data.gov/dataset/c325a86a-a0da-479d-bb87-cdbf88833b25/resource/102fd9bd-4737-401b-b88f-5c5b0fab94ec/download/userssharedsdfpublicelementarysecondaryunivsrvy200910.csv
