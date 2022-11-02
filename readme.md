# Pimeo

## Installation

Suivre l'installation de la [VM](https://gitlab.libeo.com/Soprema/Soprema-pim-vm)

Depuis la VM:

``` bash
$ composer install
$ composer setup-local
```

## Build theme

Construire les assets en local la vagrant n'a pas de NodeJs.

```bash
$ npm install
$ gulp
```

### Synchronisation avec les données et fichiers du PROD (sauver du temps)

Backup et sync pour la base de donnée.

    ssh prod
    sudo su
    /etc/burp/pre.d/backup-postgres-0-sh ( backup )
    cp /var/backups/postgres/0/soprema.restore /home/{$USER}
    exit ( sur votre poste )
    scp soprema.ca:soprema.restore .
    mv soprema.restore dans website/
    vagrant ssh
    sudo su postgres
    psql
        DROP DATABASE soprema_pim;
        CREATE DATABASE soprema_pim;
    \q

    pg_restore -d soprema_pim -O {$PATH}/soprema.restore
    exit vagrant ssh

Synchronisation des fichiers

    cd website/files ( si pas existant, créer le )
    rsync -avzP soprema.ca:/www/sites/files.soprema.ca/public/ .
    vagrant ssh
    rm -rf /www/sites/files.soprema.local/*
    ln -s /www/sites/pim.soprema.local/files/ /www/sites/files.soprema.local/public
