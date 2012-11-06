fauna_data_access
=================



The idea behind this project is to allow API / programmatic access to ALA species data.
Atlas of Living Australia (ALA) - http://www.ala.org.au/

it takes time to understand how the data is organised and how to get a hold of a specific piece of data,
so I have put together some PHP scripts that can be used to extract data you want.


Main things you will want to do are:

* Species Names        - a list of species names and their associated unique ID's as per ALA

* Species Information  - Information stored by ALA for a Species / query

* Locations            - Latitude and Longitude of species occurences 




REFERENCE
=======================================================================
ALA offers excellent webservices  - 
        http://www.ala.org.au/about-the-atlas/downloadable-tools/web-services/
        http://spatial.ala.org.au/ws/



CODE REPOSITORY
=======================================================================
PHP code is available from "git-hub" ...............  https://github.com/afakes/fauna_data_access

note: if you plan to use this code directly then 
you will also need the Utilities project as well  ..  https://github.com/afakes/utilities



OPERATING SYSTEM
=======================================================================
Linux variant  .... This project was built with Debian, 
                    (I would recommend Debian or Ubuntu)
                            
    
if you like Windows and want to keep it, I would suggest you setup Virtualbox 
(an open source virtual machine environment) and run Debian insdie it.

https://www.virtualbox.org/
https://www.virtualbox.org/wiki/Downloads



Command line programs 
=======================================================================
I have written this php scripts to work under a Linux environment and expect 
standard Linux commands to be available.


Web pages 
=======================================================================
the web pages just access the 'Command line programs' and then display the output on a web page




Direct ALA URL's
=======================================================================
If you want to get the data yourself direct of ALA, below are some URLs 
and what they can get you. Some URls' and their example data output.


Field Names   
-----------------------------------------------------------------------
used in  URL as  '&fl=field,field,field',, 
useful fields have been  marked with '-->'

        assertions
    --> basis_of_record
        biogeographic_region
        catalogue_number
        collection_code
        collector
        collector_text
        collectors
        common_name_and_lsid
        coordinate_uncertainty
        country
        data_provider
        data_provider_uid
        data_resource
        data_resource_uid
    --> geospatial_kosher
        ibra
        id
    --> lat_long
    --> latitude
    --> longitude
    --> month
        multimedia
    --> names_and_lsid
    --> occurrence_date
    --> occurrence_year
        outlier_layer_count
        places
        point-0.0001
        point-0.001
        point-0.01
        point-0.1
        point-1
        provenance
        raw_basis_of_record
    --> raw_taxon_name
        record_type
        row_key
        state
        system_assertions
        taxonomic_kosher
        user_assertions
        user_id
    --> year


NOTE: this field name list is not complete but only the useful/obvious are listed



example:: get the  latitude & longitude  for all species  grouped in the "Macropus" 
------------------------------------------------------------------------------------------------------------------

This quickest way to download the whole lot is to request it as a gZipped (GZ) file and then undo that file later.
- I have tested a few ways to do this via JSOn data blocks and others and the GZ file is by far the fastest

the URL
-------
http://biocache.ala.org.au/ws/webportal/occurrences.gz?q=macropus&fl=names_and_lsid,raw_taxon_name,basis_of_record,longitude,latitude&pageSize=999999999


the description
---------------

So if we break this up you  will find these parts

DOMAIN ......... http://biocache.ala.org.au/
folder ......... ws/webportal/
CGI script ..... occurrences.gz
query string ... ?q=macropus&fl=raw_taxon_name,basis_of_record,longitude,latitude&pageSize=999999999

                 ***
                 *** if we break up the query string we see these parts   q,fl,pageSize
                 *** 
                        q=macropus   
                                   ...... send 'macropus' as the value if 'q' is this is what we want to query

                       fl=raw_taxon_name,basis_of_record,longitude,latitude,names_and_lsid

                                   ...... set the list of fields we want returned 
                                          --> raw_taxon_name
                                          --> basis_of_record 
                                          --> longitude,
                                          --> latitude
                                          --> names_and_lsid

                       pageSize=999999999
                                   ...... set the size of each 'page' of data,  
                                          if you set this to a very large value then you will get all the 'pages' / rows of data
                                          if you leave it out then you will onlt get the first 10  or some default number of rows


                       


the result  (file)
------------------
a GZipped  file called  'occurrences.gz'


the result (unGZipped)
----------------------
a CSV structured file , one header line and multiple data lines separated by COMMA ',', and text enclosed in QUOTES '"'

    raw_taxon_name,basis_of_record,longitude,latitude
    "Macropus sp.","PreservedSpecimen","140.87637","-33.94085"
    "Macropus sp.","HumanObservation","140.87637","-33.94085"
    "Macropus sp.","HumanObservation","140.9798","-33.95471"
    "Macropus sp.","HumanObservation","140.96858","-33.93926"
    "Macropus sp.","HumanObservation","138.07677","-34.13145"
    "Macropus sp.","HumanObservation","138.10839","-34.12406"
    "Macropus sp.","HumanObservation","137.44256","-34.93364"
    "Macropus sp.","HumanObservation","137.44256","-34.93364"
    "Macropus sp.","HumanObservation","137.43554","-35.04074"
    "Macropus sp.","HumanObservation","137.43554","-35.04074"
    "Macropus sp.","HumanObservation","137.43554","-35.04074"



If you want to download the entire locations database for Animals
--------------------------------------------------------------------------------
     http://biocache.ala.org.au/ws/webportal/occurrences.gz?q=ANIMALIA&fl=longitude,latitude,year,month,raw_taxon_name,names_and_lsid&pageSize=99999999

WARNING:: THis would probably take too long and would probably fail.


but a way to do it would be to break it up, with ALA you can request parts of the download.
so you could request say 2000 rows at a time

     http://biocache.ala.org.au/ws/webportal/occurrences.gz?start=0&q=ANIMALIA&fl=longitude,latitude,year,month,raw_taxon_name,names_and_lsid&pageSize=99999999
     http://biocache.ala.org.au/ws/webportal/occurrences.gz?start=2000&q=ANIMALIA&fl=longitude,latitude,year,month,raw_taxon_name,names_and_lsid&pageSize=99999999
     http://biocache.ala.org.au/ws/webportal/occurrences.gz?start=4000&q=ANIMALIA&fl=longitude,latitude,year,month,raw_taxon_name,names_and_lsid&pageSize=99999999
     http://biocache.ala.org.au/ws/webportal/occurrences.gz?start=6000&q=ANIMALIA&fl=longitude,latitude,year,month,raw_taxon_name,names_and_lsid&pageSize=99999999

then each GZ file would contain 2000 rows of data 









Species Names  ... names.php 
=======================================================================


 names.php [--delim=|] [--rank=TaxanomicRank] --name=SpeciesName    ... list of species like 
 names.php [--delim=|] [--rank=TaxanomicRank] --name=*              ... list of all species 
 default delimiter '|' 
 
 --page_count=n  .... stop after this number of pages (useful for testing)
 --page_size=100 .... each page of data is this many rows
 
 --rank=
 value list
 ---------------
 class
 family
 genus
 infraorder
 kingdom
 order
 phylum
 rank
 species
 suborder
 subspecies
 superfamily
 superorder
 variety

 
A useful command line to list out the 'rank(s)'
php get_species_names.php --name='*' --page_count=3 | cut -d'|' -f7 | sort | uniq 



