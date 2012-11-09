fauna_data_access
=================


    Table of Contents
    =================

    SECTION 1 - Information
    -------------------------------------------------
        1.1) Project description 

        1.2) REFERENCE 

        1.3) CODE REPOSITORY

        1.4) OPERATING SYSTEM
             * Virtualbox

        1.5) Command line programs 

        1.6) Other software 
             * APT-GET
             * Other useful softwares



    SECTION 2 - Atlas of Living Australia URLs
    -------------------------------------------------

        2.1) Direct ALA URL's
            2.1.1) example:: get the  latitude & longitude  

            2.1.2) example URLs
                a) Get all location points for 'macropus'
                b) Get all location points for 'Red Kangaroo'
                c) Get all location points for 'Macropus rufus'  using it's Life Science Identifier  (LSID)


            2.1.3) Field Names 

            2.1.4) the resultant unGZipped file

            2.1.5) the CSV file structure.


        2.2) DOWNLOAD OF DATA FROM Atlas of Living Australia
             * Warning on requesting too much data 
             * Requesting data blocks


    SECTION 3 - BULK DATA ACCESS
    -------------------------------------------------
        * AUTOMATED DOWNLOAD OF DATA FROM Atlas of Living Australia
            * wget

        * PHP SCRIPTS
            * occurences.php
            .... example usage 
            .... output  (gz, csv)

            * 




=======================================================================
=======            SECTION 1 - Information                      ======= 
=======================================================================



1.1)  Project description 
=======================================================================

    The idea behind this project is to allow API / programmatic access to ALA species data.
    Atlas of Living Australia (ALA) - http://www.ala.org.au/

    it takes time to understand how the data is organised and how to get a hold of a specific piece of data,
    so I have put together some PHP scripts that can be used to extract data you want.


    Main things you will want to do are:

    * Species Names        - a list of species names and their associated unique ID's as per ALA

    * Species Information  - Information stored by ALA for a Species / query

    * Locations            - Latitude and Longitude of species occurences 





1.2) REFERENCE (sites for this data)
=======================================================================
    Atlas of Living Australia: 
                    http://www.ala.org.au/

    web services:
                    http://www.ala.org.au/about-the-atlas/downloadable-tools/web-services/
                    http://spatial.ala.org.au/ws/



1.3) CODE REPOSITORY
=======================================================================
    PHP code is available from "git-hub" ...............  https://github.com/afakes/fauna_data_access

    note: if you plan to use this code directly then 
    you will also need the Utilities project as well  ..  https://github.com/afakes/utilities




1.4) OPERATING SYSTEM
=======================================================================
    Linux variant  .... This project was built with Debian, 
                        (I would recommend Debian or Ubuntu)


    if you like Windows and want to keep it, I would suggest you setup Virtualbox 
    (an open source virtual machine environment) and run Debian inside it.


    Virtualbox
    ----------

    https://www.virtualbox.org/
    https://www.virtualbox.org/wiki/Downloads


    If you want to find a prebuilt Debian mage have a look here -  
    site:: http://virtualboxes.org/images/debian/


                Debian GNU/Linux 6.0.6 alias squeeze
                Size (compressed/uncompressed): 1.8 GBytes / 5.16 GBytes

                Link: http://downloads.sourceforge.net/virtualboximage/debian_6.0.6.vdi.7z

                Active user account(s)      username  password
                                            -------------------------
                                            root      toor
                                            debian    reverse

                Notes: GNOME desktop environment, Guest Additions installed





1.4) Command line programs 
=======================================================================
    I have written this php scripts to work under a Linux environment and expect 
    standard Linux commands to be available. To setup PHP to run in Linux is quite simple
    once you are at the Linux prompt
    

    APT-GET - How to install programs in Debian
    ----------------------------------------------

        jc166922@afakes:~$

        command:  'su'

        jc166922@afakes:~$ su <enter>
        Password:  <enter the password for root>


        root@afakes:/home/jc166922#    <---  this is the root prompt   (usually with the # at the end)

        command:  'apt-get update'

        root@afakes:/home/jc166922#  apt-get update <enter>

        command:  'apt-get install php5 php5-cli php5-cgi php5-curl php5-gd php5-mysql'

        root@afakes:/home/jc166922#  apt-get install php5 php5-cli php5-cgi php5-curl php5-gd php5-mysql <enter>


        if you see something like

                he following package was automatically installed and is no longer required:
                libgdal1-1.6.0-grass
                Use 'apt-get autoremove' to remove them.
                Suggested packages:
                php-pear
                The following NEW packages will be installed:
                php5-cgi
                0 upgraded, 1 newly installed, 0 to remove and 3 not upgraded.
                Need to get 5,889 kB of archives.
                After this operation, 15.6 MB of additional disk space will be used.
                Do you want to continue [Y/n]? 


        And you are usually ask 'Do you want to continue [Y/n]? '

        hit >enter> and that will select Yes, PHP will install 

        --- PHP will then  be downloaded and installed

                Get:1 http://mirror.aarnet.edu.au/debian/ squeeze/main php5-cgi amd64 5.3.3-7+squeeze14 [5,889 kB]
                Fetched 5,889 kB in 6s (908 kB/s)   

        -- to test   'php -v'

                PHP 5.3.3-7+squeeze14 with Suhosin-Patch (cli) (built: Aug  6 2012 14:18:06) 
                Copyright (c) 1997-2009 The PHP Group
                Zend Engine v2.3.0, Copyright (c) 1998-2010 Zend Technologies
                    with Suhosin v0.9.32.1, Copyright (c) 2007-2010, by SektionEins GmbH




    Other useful softwares
    ----------------------
        Now you know how to install with 'apt-get' other items you may want to install:
    
        apache Web server::                apt-get install apache
        terminal quick text editor::       apt-get install nano     
        simple graphic text editor::       apt-get install gedit
        terminal directory tree::          apt-get install tree 
        GIT-HUB code access::              apt-get install git
        download urls to files::           apt-get install wget



1.5) Other software 
=======================================================================

    Netbeans::  This must be one of the best development environments  http://netbeans.org/
                When you download, either the PHP only or the full version.


=======================================================================
=======            Atlas of Living Australia - URLs             ======= 
=======================================================================



2.1) Direct ALA URL's
=======================================================================
    If you want to get the data yourself direct of ALA, below are some URLs 
    and what they can get you. Some URls' and their example data output.


    Web Services:
            http://www.ala.org.au/about-the-atlas/downloadable-tools/web-services/

    Spatial Web Services:
            http://spatial.ala.org.au/ws/




    example:: get the  latitude & longitude  for all species  grouped in the "Macropus" 
    ------------------------------------------------------------------------------------------------------------------

        This quickest way to download the whole lot is to request it as a gZipped (GZ) file and then undo that file later.


        NOTE: I have tested a few ways to do this via JSON data blocks and others and the 
              GZ file is by far the fastest

    
        the URL:: http://biocache.ala.org.au/ws/webportal/occurrences.gz?q=macropus&fl=names_and_lsid,raw_taxon_name,basis_of_record,longitude,latitude&pageSize=999999999
        -------

        This URL would return you a GZipped file conatining all known locations (that ALA knows about) 
        with the columns "names_and_lsid, raw_taxon_name, basis_of_record, longitude, latitude"

        unGZipping this file and then opening the CSV you find a set of rows, though not all rows seem 
        to have Lat and Long so you will have to clean out any rows that do not contain this useful data,


        description of the URL
        ----------------------

            So if we break this up you  will find these parts


            DOMAIN ......... http://biocache.ala.org.au/
            folder ......... ws/webportal/
            CGI script ..... occurrences.gz
            query string ... ?q=macropus&fl=raw_taxon_name,basis_of_record,longitude,latitude&pageSize=999999999

                            ***
                            *** if we break up the query string we see these parts   q,fl,pageSize
                            *** 

                                q=macropus   
                                            ...... send 'macropus' as the value if 'q'  (this is what we want to query)

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
                                                    if you leave it out then you will only get the first 10  or some default number of rows
                                                    NOTE: if you ask for a q= that would cause a lot of rows to be returned then the query / web page call will fail)


        example URLs
        ------------------------------------

        example:: Get all location points for 'macropus'

                http://biocache.ala.org.au/ws/webportal/occurrences.gz?q=macropus&fl=names_and_lsid,raw_taxon_name,basis_of_record,longitude,latitude&pageSize=999999999


        example::Get all location points for 'Red Kangaroo'

                http://biocache.ala.org.au/ws/webportal/occurrences.gz?q=red%20kangaroo&fl=names_and_lsid,raw_taxon_name,basis_of_record,longitude,latitude&pageSize=999999999

                http://biocache.ala.org.au/ws/webportal/occurrences.gz?q=red+kangaroo&fl=names_and_lsid,raw_taxon_name,basis_of_record,longitude,latitude&pageSize=999999999

                NOTE (1):: when creating a URL  you must replace 'space' with either '+'  or  '%20'  - the hex value for decimal 32 (because it's ASCII character code 32)

                NOTE (2):: After running this query I noticed that this returns not just 'Red Kangaroo',
                           but most things with 'Red' in the name

                If you only want 'Red Kangaroo'  aka  'Macropus rufus'
                it's better to use the LSID 

                


        example::  Get all location points for 'Macropus rufus'  using it's Life Science Identifier  (LSID)

                Macropus rufus  ..... urn:lsid:biodiversity.org.au:afd.taxon:31a9b8b8-4e8f-4343-a15f-2ed24e0bf1ae       


                http://biocache.ala.org.au/ws/webportal/occurrences.gz?q=lsid:urn:lsid:biodiversity.org.au:afd.taxon:31a9b8b8-4e8f-4343-a15f-2ed24e0bf1ae&fl=names_and_lsid,raw_taxon_name,basis_of_record,longitude,latitude&pageSize=999999999


                note:: we see that if we use   'q=lsid:<Life Science ID>
                                                q=lsid:urn:lsid:biodiversity.org.au:afd.taxon:31a9b8b8-4e8f-4343-a15f-2ed24e0bf1ae

                       we get back the correct file set.



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




        the resultant unGZipped file
        ----------------------------

            a CSV structured file , one header line and multiple data lines separated by COMMA ',', and text enclosed in QUOTES '"'
            (if you are using a web browser to download the file, the default filename is 'occurences.gz'

            the best command to GUnzip this file is 


                example::    gunzip -c occurences.gz >   Macropus.csv

                            this is extract the text from the archive and save it out to 'Macropus.csv'

                            note: if you don't use '-c' option then the 'occurences.gz' will be decompressed 
                                in place and you will get a file called  'occurences' (with no extension)
                                and you will lose the GZ file.
        

        the CSV file structure.
        ---------------------------------

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


            NOTE:  when you make the request URL, the order in which you list the 
                field names is the order of the outputted columns


                so the above file is from the field list  'fl=names_and_lsid,raw_taxon_name,basis_of_record,longitude,latitude'




2.2) DOWNLOAD OF DATA FROM Atlas of Living Australia
=======================================================================

    Warning on requesting too much data 
    -----------------------------------

        If you want to download the entire locations database for Animals (ANIMALIA)

        http://biocache.ala.org.au/ws/webportal/occurrences.gz?q=ANIMALIA&fl=longitude,latitude,year,month,raw_taxon_name,names_and_lsid&pageSize=99999999

        ########  DON'T DO THIS  ########  


        WARNING:: This WILL take too long and will probably fail. If you are running it from a web page 
                and waiting for apache to return it WILL die.   


    Requesting data blocks
    ----------------------

        a way to do it would be to break it up, with ALA you can request parts of the download. so you could request say 2000 rows at a time

            http://biocache.ala.org.au/ws/webportal/occurrences.gz?start=0&q=ANIMALIA&fl=longitude,latitude,year,month,raw_taxon_name,names_and_lsid&pageSize=2000
            http://biocache.ala.org.au/ws/webportal/occurrences.gz?start=2000&q=ANIMALIA&fl=longitude,latitude,year,month,raw_taxon_name,names_and_lsid&pageSize=2000
            http://biocache.ala.org.au/ws/webportal/occurrences.gz?start=4000&q=ANIMALIA&fl=longitude,latitude,year,month,raw_taxon_name,names_and_lsid&pageSize=2000
            http://biocache.ala.org.au/ws/webportal/occurrences.gz?start=6000&q=ANIMALIA&fl=longitude,latitude,year,month,raw_taxon_name,names_and_lsid&pageSize=2000

        then each GZ file would contain 2000 rows of data 


        WARNING:: I have tested 100000 rows and it downloads badly, 

        I have tested 50,000 rows and it downloads happily, so the lines below would download the first 150000 rows of  ANIMALIA

            http://biocache.ala.org.au/ws/webportal/occurrences.gz?&q=ANIMALIA&fl=longitude,latitude,year,month,raw_taxon_name,names_and_lsid&pageSize=50000&start=0
            http://biocache.ala.org.au/ws/webportal/occurrences.gz?&q=ANIMALIA&fl=longitude,latitude,year,month,raw_taxon_name,names_and_lsid&pageSize=50000&start=100000
            http://biocache.ala.org.au/ws/webportal/occurrences.gz?&q=ANIMALIA&fl=longitude,latitude,year,month,raw_taxon_name,names_and_lsid&pageSize=50000&start=150000


        So the next step would be to automate this so it downloads the whole lot. 




=======================================================================
=======            SECTION 3 - BULK DATA ACCESS                 ======= 
=======================================================================


3.1) AUTOMATED DOWNLOAD OF DATA FROM Atlas of Living Australia
=======================================================================

    wget
    ----

        using WGET is a much safer and useful way to download webpages and other files from the net/

            eg.  wget -O example.html http://www.electrictoolbox.com/wget-save-different-filename/

                this line will save the page page / file at "http://www.electrictoolbox.com/wget-save-different-filename/" 
                to a local file named "example.html"


        So if we want to download the location data for Animals (ANIMALIA)

            wget -O occurence_ANIMALIA_00000000.gz http://biocache.ala.org.au/ws/webportal/occurrences.gz?&q=ANIMALIA&fl=longitude,latitude,year,month,raw_taxon_name,names_and_lsid&pageSize=50000&start=0
            wget -O occurence_ANIMALIA_00100000.gz http://biocache.ala.org.au/ws/webportal/occurrences.gz?&q=ANIMALIA&fl=longitude,latitude,year,month,raw_taxon_name,names_and_lsid&pageSize=50000&start=100000
            wget -O occurence_ANIMALIA_00150000.gz http://biocache.ala.org.au/ws/webportal/occurrences.gz?&q=ANIMALIA&fl=longitude,latitude,year,month,raw_taxon_name,names_and_lsid&pageSize=50000&start=150000

    
3.2 PHP SCRIPTS 
=======================================================================
    I have written these php scripts to work under a Linux environment and expect 
    standard Linux commands to be available.  (refer above to for information installing Linux)



3.2.1) occurences.php
=======================================================================
    This script will handle downloading the data in "pages",  you can tell it how big you want each page / GZ file to be
    and then you can tell how many pages you want, 


    usage
    --------------------------
        Run the script with no parameters to get the simple  help page


        $ php occurences.php 

            php occurences.php --name=SpeciesName      --output_folder=foldername     .... occurence points for this name and place gz files in this folder
            php occurences.php --name=lsid:(ALA LSID)  --output_folder=foldername     .... occurence points for this LSID and place gz files in this folder

            --page_count=-1          .... stop after this number of pages (useful for testing)
            --page_size=50000        .... each page of data is this many rows (tested to work up to 50000 rows - becomes less responsive over this)

            --info_only=true         .... display information about the results but NOT the results
            --count_only=true        .... display record count only - no data

            --connection_sleep=10    .... Number of seconds to wait before requesting next page of data




    example 
    --------------------------------

    Download all the location data available for 'Rattus' (Genus Rattus - rats, and some other rodents )
    and save it in the 'data' folder. using default page size (50,000 rows) and page count (default page count is ALL use -1)

        command lines you can use to do the same thing

            php occurences.php  --name=Rattus  --output_folder=data  

            php occurences.php  --name=Rattus  --output_folder=data  --page_size=50000 

            php occurences.php  --name=Rattus  --output_folder=data  --page_size=50000 --page_count=-1



    output from occurences.php 
    --------------------------
    The GZ files are kept after download, the last part of the script to to gUnzip each file and concatenate
    it to a larger single CSV file.


    gz files 
    --------

        $ ls -1 data/Rattus*

        data/Rattus_00000000.gz     - gZipped file downloaded   rows 0 --page_size  
        data/Rattus_00050000.gz
        data/Rattus.csv             - ungzipped and combined complete CSV file.
                                      note: this name is created from the 'output_folder' and the 'name'


    csv files 
    ---------

        $ head -n10 data/Rattus.csv

        - first 10 rows,shows header and column values.

            longitude,latitude,year,month,raw_taxon_name,names_and_lsid
            "140.45986","-37.915","1997","03","Rattus sp.","||||"
            "140.85154","-37.96805","1997","03","Rattus sp.","||||"
            "140.58831","-37.71235","1997","04","Rattus sp.","||||"
            "140.53233","-37.62219","1997","04","Rattus sp.","||||"
            "140.7026","-37.69748","1997","03","Rattus sp.","||||"
            "140.33733","-37.17628","1997","04","Rattus sp.","||||"
            "138.68731","-34.97152","1986","11","Rattus sp.","||||"
            "138.68731","-34.97152","1986","11","Rattus sp.","||||"
            "139.31395","-35.14854","2004","08","Rattus sp.","||||"





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



