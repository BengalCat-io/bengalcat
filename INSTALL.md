# BengalCat PHP Framework - Local Development Installation

#### Running

- nginx/1.10.3 
- php7.0
- mysql Ver 14.14 Distrib 5.7.20,

---

## Local Development

### Use [**DockerLocal**](https://github.com/amurrell/DockerLocal) & [**ProxyLocal**](https://github.com/amurrell/ProxyLocal)

**DockerLocal** is used for setting up a site to be served on localhost:PORT and runs on docker containers - nginx + php7.0-fpm, memcached, mysql and your project's web application files.

**ProxyLocal** is used for using a domain with your project locally.

Review the DockerLocal [README](https://github.com/amurrell/DockerLocal) for more commands/info.

Review the ProxyLocal [README](https://github.com/amurrell/ProxyLocal) for more commands/info.

#### Requirements

- Docker (Tested with Docker version 17.03.1-ce, build c6d412e)
- Docker-Compose (Tested with docker-compose version 1.12.0, build b31ff33)
- Bash 4+ (MacOS default 3.2.57, needs brew install)

#### Get Docker for Mac

[Docker + Docker Compose](https://docs.docker.com/docker-for-mac/install/)

#### Update Bash for MacOS
```
/bin/bash --version
brew install bash
/usr/local/bin/bash --version
```
---

#### Install

- Repo Install

    ```
    cd ~/vhosts
    git clone git@github.com:youruser/your-site-repo.git
    ```

- ProxyLocal install

    If you do not have a ProxyLocal installation already...

    ```
    cd ~/vhosts
    git clone git@github.com:amurrell/ProxyLocal.git
    cd ProxyLocal
    cp sites-example.yml sites.yml
    ```

    Setup the sites.yml in that project with (choose random port, ie 3001): 

    ```
    nano sites.yml
    ### Add to sites, the project port and domain name.
    sites:
        3001: docker.your-site.com
    ```

   **Tip** If not using Proxylocal, site will be available at localhost:3001 instead.

- Dockerlocal Install

	```
	cd ~/vhosts/your-site-repo
	git clone git@github.com:amurrell/DockerLocal.git
	cd DockerLocal
	echo 3001 > port
	# nano nginx.site.conf (mentioned above)
	```
	
---

#### Commands

- ##### Create and use a local database `your_db`:
    
    ```
    cd DockerLocal/commands
    ./site-up -c=your_db
    ```

- ##### Shut down. Start it up again (the same database will get used because of a new file DockerLocal/database)
	
    ```
    cd DockerLocal/commands
    ./site-down
    ./site-up
    ```

- ##### Use remote database:

    Copy `DockerLocal/databases-example.yml`, replace with the following config:

    ```
    databases:
        host: dev.your-site-that-exists.com
        user: your.db.user
        pass: XXX
        port: 3306
        3001: dev_your_db
    ```

    Now, it'll automatically pull the db (and store it locally in the container as a copy)

    ```
    cd DockerLocal/commands
    ./site-up
    ```

- ##### Want to refresh that remote database?

    ```
    cd/DockerLocal/commands
    ./site-db
    ```
	
- ##### Want to share your site with others temporarily? (one dockerlocal project at a time)

    [Install ngrok](https://github.com/amurrell/DockerLocal#install-ngrok) first.

    ```
    cd DockerLocal/commands
    ./site-ngrok
    ```

- ##### Want to ssh into the containers for some reason?

    - `./site-ssh -h=mysql` && `mysql -u root -p1234` to get into mysql
    - `./site-ssh -h=web` && `cd /var/www/site/` to see your site, or `cd /etc/php/7.0/fpm/pool.d` for php-fpm conf, or `cd /etc/nginx/` for nginx conf
    - `./site-ssh -h=memcached` .. there's really no reason to be here.


----

## Node Setup

If you do not have a theme, copy the `html/themes/bengalcat/` theme and modify it to be your new theme. (Change app/config/settingsDefaults.php to reflect theme choice)

You do not have to utilize build. Edit your `html/themes/yourtheme/src/templates/head.php` to
alter the style sheet used to `/assets/css/style.css`.

### If you want to use gulp...

1. Edit your `html/themes/yourtheme/src/templates/head.php` to alter the style sheet used to
 `/assets/build/main.css`.

Install NodeJS, npm etc - fastest most consistent way I've ever found:

1. Download **binaries** appropriate for your computer, ie mac etc from: https://nodejs.org/en/download/
1. `cd /usr/local`
1. `tar --strip-components 1 -xzf /path/to/downloaded/tar/your-zip.tar.gz`

Then go to `html/themes/yourtheme/assets` and run `npm install`

`npm run watch` to watch and `npm run build` to build with gulp.
