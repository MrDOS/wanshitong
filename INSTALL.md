Wan Shi Tong Installation
=========================

1. Create a database for the project.
2. Create the schema and import sample data:

        $ cd db
        $ mysql -u <user> -p <database> < schema.sql
        $ mysql -u <user> -p <database> --local-infile=1 < populate.sql

3. Create the configuration file:

        $ cp config.sample.php config.php

4. Edit the configuration file and update at minimum the following definitions:

        * `DB_HOST`, the database server hostname (usually `localhost`)
        * `DB_NAME`, the name of the database
        * `DB_USER`, a database user with access to the chosen database
        * `DB_PASS`, the database user's password

5. Navigate to the installation directory in your web browser.

Example credentials are "dbenoit"/"password" and "jgriffin"/"password".