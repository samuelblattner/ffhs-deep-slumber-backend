# Deep Slumber Backend

## Contents
This repository contains the backend implementation for my semester term project at FFHS (see complete Project: ...).

## Installation

### Prerequisites

In order for the backend to be fully functional, you need to install the following programs on your development system first:

* PostgreSQL 
* Redis Server (only required as Websocket-broker between backend<->hardware and backend<->browser)

With PostgreSQL, create a new database named `samworks_ffhs_deepslumber_backend` and a user that owns that database with the same name. Set the password to `thepassword` (If you wish to use different names and password, change them accordingly in the `propel.dist.yml` configuration file)

### Setup

#### Composer depencencies
Once you cloned this repository, run the following command in the backend main directory to install composer:

```bash
$ . scripts/install-composer.sh
```

Then install the dependencies with:
````bash
$ php composer.phar install
````

This will install all required third-party dependencies via Composer.

#### Generate Propel ORM classes, configs and database schema
**NOTE**: It is required that you always run the following commands from the backends main directory as the
configuration contains relative paths to database schema etc.

Create the configuration file with:

```bash
$ vendor/bin/propel config:convert
```
 
Run the following command to generate the ORM-classes:

```bash
$ vendor/bin/propel model:build
```

Then, create the SQL-schema commands with:

```bash
$ vendor/bin/propel sql:build
``` 

Finally, create the actual database schema with:
```bash
$ vendor/bin/propel sql:insert
```

### Install Frontend
Head over to the frontend repository and install the frontend in order for the backend to be usable via browser.

### Point backend to frontend
If you want to serve the frontend via php, you need to point the backend to where to frontend is.
Edit the `frontend-entry-path` setting in `config/settings.php` to the directory containing the `index.html` of the frontend.

### Run platform with built-in php server
You're now all set to run the platform. You can use any server such as Apache or NginX capable of serving PHP. 

For easy and convenient development, however, you can just use the built-in php server like this:

``` bash
php -S localhost:8080 index.php
```

If you open your browser at [localhost:8080](localhost:8080) you should see the public dashboard view.