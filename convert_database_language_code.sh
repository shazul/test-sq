#!/usr/bin/env bash
# To run this script be sure to be "postgres" user before executing.
# Be sure that the database is not in use in anyway ( like PhpStorm or something like that )
FILE="/tmp/soprema_pim.sql"

if [ $# -eq 0 ]
  then
    echo "You must specified a database name arguement like 'soprema_pim'"
  else
    echo "Exporting database"
    pg_dump $1 > $FILE
    echo 'Exported database successfuly'

    echo 'Replacing...'
    sed -i 's/fr_CA/fr/g' $FILE
    sed -i 's/en_CA/en/g' $FILE
    echo 'Replace fr to fr and en to en successfully'

    echo "Dropping database"
    psql -c "DROP DATABASE $1"
    echo 'Database dropped successfully'

    echo "Creating empty database"
    psql -c "CREATE DATABASE $1"
    echo 'Database created successfuly'

    echo 'Importing database...'
    psql $1 < $FILE
    echo 'Successfuly imported new database'

    echo 'Removing database dump'
    rm $FILE 
    echo 'Database dump removed successfuly'
fi
