# <center>Hermes Trade Engine Documentation</center>

#### Latest revision:

2022 March 18 by [Ali Khedmati](https://khedmati.ir)

## What is Hermes?

The Greek god <b>Hermes</b> (the Roman Mercury ) was the god of translators and interpreters. He was the most clever of
the Olympian gods, and served as messenger for all the other gods. He ruled over wealth, good fortune, commerce,
fertility, and thievery. Because of his speed, he was sometimes considered a god of winds.
<b>Hermes</b> is a trade engine which currently supports <code>LIMIT, MARKET, STOPLOSSLIMIT</code> type of orders.it
also provides an elegant virtual match-making based on [Nobitex](https://nobitex.ir) for IRT markets
and [Binance](https://binance.com) for other markets.

## Technologies

* [PHP 8.1.3 LTS](https://php.net)
* [Laravel 9.5 LTS](https://laravel.com)
* [Mongodb 5.0.6 LTS](https://mongodb.com)
* [Redis 6.2 LTS](https://redis.io)
* [Laravel Horizon 5.9.2 LTS](https://laravel.com/docs/9.x/horizon)
* [Laravel Octane 1.2 LTS](https://laravel.com/docs/9.x/octane)

## System Requirements

* <b>Minimum system requirements</b>:
    * Ubuntu 16.04.
    * 2 GB of Ram.
    * 2 Core of CPU.
    * 100 MB of free HDD storage.

* <b> Recommended system requirements</b>:

    * Ubuntu 20.04.
    * 16 GB of Ram.
    * 16-24 Core of CPU.
    * 1 GB of free SSD / NVMe storage.

## Architecture

* #### NGINX
  NGINX is a high performance, highly scalable, highly available web server, reverse proxy server, and web accelerator (
  combining the features of an HTTP load balancer, content cache, and more). NGINX offers a highly scalable architecture
  that is very different from that of Apache (and many other open source and commercial products in the same category).
  NGINX has a modular, event driven, asynchronous, single-threaded architecture that scales extremely well on generic
  server hardware and across multi-processor systems. NGINX uses all of the underlying power of modern operating systems
  like Linux to optimize the usage of memory, CPU, and network, and extract the maximum performance out of a physical or
  virtual server. The end result is that NGINX can often serve at least 10x more (and often 100–1000x more) requests per
  server compared to Apache – that means more connected users per server, better bandwidth utilization, less CPU and RAM
  consumed, and a greener environment too.
* #### PHP
  PHP is an open-source server-side scripting language. A scripting language is a language that interprets scripts at
  runtime. Scripts are usually embedded into other software environments.
* #### Laravel
  Laravel is a free and open-source PHP framework that provides a set of tools and resources to build modern PHP
  applications. With a complete ecosystem leveraging its built-in features, and a variety of compatible packages and
  extensions, Laravel has seen its popularity grow rapidly in the past few years, with many developers adopting it as
  their framework of choice for a streamlined development process.
  Laravel provides powerful database tools including an ORM (Object Relational Mapper) called Eloquent, and built-in
  mechanisms for creating database migrations and seeders. With the command-line tool Artisan, developers can bootstrap
  new models, controllers, and other application components, which speeds up the overall application development.
  The main/default design pattern of Laravel is MVC (Model / View / Controller).
* #### Laravel Octane
  Laravel Octane is an open-source package that will boost your Laravel application performance. Under the hood, Octane
  makes use of Swoole and RoadRunner - two application servers, that take care of serving and booting up your Laravel
  application. Why is it faster, you might ask. Let me explain.
  With a traditional PHP application that gets served via a webserver like nginx, every incoming request is going to
  spawn an PHP-FPM worker. This means that each request starts up one individual PHP process, that will run through all
  the necessary tasks in order to serve that one request.
  In the case of Laravel, this means that the Framework needs to be booted, all service providers register their
  services within the container, all providers get booted themselves, the request goes through a list of middleware
  classes, hits your controller, a view gets rendered, etc. until we eventually get a response from our server.
  With Swoole or RoadRunner in place, we still have a worker for each incoming HTTP request, but they all share the same
  booted framework. This means that only the first incoming request is going to bootstrap the framework (including all
  service providers, etc.) while every other request can then make use of a ready-to-go framework. And this is what
  makes Octane so insanely fast.
* ### Laravel Horizon
  The Laravel horizon package is used to manage Laravel queues. It provides a good-looking dashboard for the queues.
  This package allows users to configure jobs, generate analytics, and monitor the different types of queue-related
  tasks, including job run-time, failure, throughput, etc. The configuration information of all team members of the
  project is stored in a single file that can be controlled centrally. This package is free to use in the Laravel
  project, but it is not included with the core code.
  Some important features of Laravel horizon are mentioned below:
    * It is an open-source package
    * It shows all queues and job information using a beautiful dashboard.
    * It provides information about pending jobs, completed jobs, and failed jobs.
    * It provides queues and job information using metrics.
    * It monitors the jobs by using tags.
* #### MongoDB
  MongoDB is an open-source NoSQL document database built on a scale-out architecture that has become popular with
  developers of all kinds who are building scalable applications using agile methodologies.
  Here are a few of the problems that MongoDB solves:
    * Integrating large amounts of diverse data: If you are bringing together tens or hundreds of data sources, the
      flexibility and power of the document model can create a unified single view in ways that other databases cannot.
      MongoDB has succeeded in bringing such projects to life when approaches using other databases failed.
    * Describing complex data structures that evolve: Document databases allow embedding of documents to describe nested
      structures and easily tolerate variations in data in generations of documents. Specialized data formats like
      geospatial are efficiently supported. This results in a repository that is resilient and doesn’t break or need to
      be redesigned every time something changes.
    * Delivering data in high-performance applications: MongoDB’s scale-out architecture can support huge numbers of
      transactions on humongous databases. Unlike other databases that either cannot support such scale or can only do
      so with massive amounts of engineering and additional components, MongoDB has a clear path to scalability because
      of the way it was designed. MongoDB is scalable out of the box.
    * Supporting hybrid and multi-cloud applications: MongoDB can be deployed and run on a desktop, a huge cluster of
      computers in a data center, or in a public cloud, either as installed software or through MongoDB Atlas, a
      database as a service product. If you have applications that need to run wherever they make sense, MongoDB
      supports any configuration now and in the future.
    * Supporting agile development and collaboration: Document databases put developers in charge of the data. Data
      becomes like code that is friendly to developers. This is far different from making developers use a strange
      system that requires a specialist. Document databases also allow evolution of the structure of the data as needs
      are better understood. Collaboration and governance can take place by allowing one team to control one part of a
      document and another team to control another part.
* #### Redis
  Redis, which stands for Remote Dictionary Server, is a fast, open-source, in-memory key-value data store for use as a
  database, cache, message broker, and queue. Redis delivers sub-millisecond response times enabling millions of
  requests per second for real-time applications in Gaming, Ad-Tech, Financial Services, Healthcare, and IoT. Redis is a
  popular choice for caching, session management, gaming, leaderboards, real-time analytics, geospatial, ride-hailing,
  chat/messaging, media streaming, and pub/sub apps.

## Deployment instructions

<b>Hermes</b> can get deployed automatically via <a href="https://docker.com">Docker</a> containers or in manual. We
will explain each method in details in below parts, by the way, we highly recommend you to use Docker method if you
don't want to have argues with manual deployment and waste of time:)

## 1. Deploy via [Docker](https://docker.com)

First, make sure you have installed Docker and Docker-Compose, Then run this command to start containers:

```shell
docker-compose up -d
```

This will:

* Create and start 4 services listed in below:
    * app
    * mongodb
    * redis
    * nginx
    * Create bridge network called after <code>hermes</code>. All containers will work and communicate with each other
      on this network

If everything goes as expected, this will be the output:

```shell
Creating network "hermes_hermes" with driver "bridge"
Creating hermes-backend ... done
Creating hermes-mongodb ... done
Creating hermes-redis   ... done
Creating hermes-nginx   ... done
```

You can check the status of containers by this command:

```shell
docker-compose ps
```

If everything is cool, This would be the output:

```shell
     Name                   Command               State                                           Ports                                         
------------------------------------------------------------------------------------------------------------------------------------------------
hermes-backend   docker-php-entrypoint php    Up      9000/tcp                                                                              
hermes-mongodb   docker-entrypoint.sh mongod      Up      0.0.0.0:27017->27017/tcp,:::27017->27017/tcp                                          
hermes-nginx     /docker-entrypoint.sh ngin ...   Up      0.0.0.0:443->443/tcp,:::443->443/tcp, 80/tcp, 0.0.0.0:8000->8000/tcp,:::8000->8000/tcp
hermes-redis     docker-entrypoint.sh redis ...   Up      0.0.0.0:6379->6379/tcp,:::6379->6379/tcp   
```

If there is an update to your <code>app</code> container, you have to rebuild your <code>app</code> container by this
command:

```shell
docker-compose build app
```

You can also <code>pause</code>, <code>unpause</code>, <code>down</code> or <code>scale</code> containers as needed. For
more information about the great abilities docker will offer you, Please read
the [Official Documentations](https://docs.docker.com).

## 2. Manual deploy</h3>

> This manual will guid you to build fresh instance of <b>Hermes</b> from scratch.<br>You don't need to be an expert to
> deploy <b>Hermes</b>! we have had put our best to make it all easy and understandable to all kind of users.<br>We
> assumed that you have fresh VPS with at least one <b>IPV4</b>, <b>Ubuntu 20.04 LTS</b>, SSH root access and at least 4GB
> of available RAM and minimum 2 core of CPUs.<br><b>Note:</b> <b>Hermes</b> doesn't force you to use <b>Ubuntu</b> and
> you can feel free if you want to use other distros such as <b>Centos</b> or ...

### 0. DNS Management (Optional)

If you want to point your desired domain to your server, login to your domain DNS management panel and create an A
record. the value of this A record has to be <code>IPV4</code> of your server.
The name of this A record, could be <code>@</code> if you want to point the main domain or it would be the name of your
desired sub-domain.<br>However, you can skip this step if you want to access <b>Hermes</b> via an <code>IPV4</code>.
Also, we strongly recommend you to use services like [Cloudflare](https://cloudflare.com)
or [Arvan Cloud](https://arvancloud.com).

### 1. Login via ssh

```shell
ssh root@ip
```

### 2. Create new user

```shell
sudo adduser hermes
``` 

This will prompt for further information such as password and new user's name.

### 3. Grant <code>sudo</code> permission

```shell
sudo usermod -aG sudo hermes
```

### 4. Switch to recently created user

```shell
su hermes
```

### 5. Update the system and install required tools

```shell
sudo apt-get update 
sudo apt-get upgrade
sudo apt-get install git wget nano zip unzip htop curl
```

### 6. Install MongoDB

Unfortunately, the MongoDB package provided by <code>apt-get</code> is not maintained by official MongoDB team, So we
have to add it manually. <br>Following instructions had been gathered
from [Official MongoDB Installation Guide](https://docs.mongodb.com/manual/tutorial/install-mongodb-on-ubuntu/).

* Import PGP of MongoDB
    ```shell
    wget -qO - https://www.mongodb.org/static/pgp/server-5.0.asc | sudo apt-key add -
    ``` 
  The operation should respond with an <code>OK</code>.
* Create a list file for MongoDB:
     ```shell
     echo "deb [ arch=amd64,arm64 ] https://repo.mongodb.org/apt/ubuntu focal/mongodb-org/5.0 multiverse" | sudo tee /etc/apt/sources.list.d/mongodb-org-5.0.list
     ```
* Reload local package database:
     ```shell
     sudo apt-get update
     ```
* Install the MongoDB packages:
    ```shell
    sudo apt-get install mongodb-org
    ```
* Start MongoDB:
     ```shell
     sudo systemctl start mongod
     ```
* Ensure that MongoDB will start following a system reboot:
    ```shell
    sudo systemctl enable mongod
    ```
* Finally, Verify that MongoDB has started successfully:
    ```shell
    sudo systemctl status mongod
    ```
* If MongoDB had been installed and started successfully, you should see something like this:
    ```shell
    ● mongod.service - MongoDB Database Server
    Loaded: loaded (/lib/systemd/system/mongod.service; enabled; vendor preset: enabled)
    Active: active (running) since Wed 2021-09-08 21:47:10 +0430; 8h ago
    Docs: https://docs.mongodb.org/manual
    Main PID: 2573 (mongod)
    Memory: 167.6M
    CGroup: /system.slice/mongod.service
    └─2573 /usr/bin/mongod --config /etc/mongod.conf
    Sep 08 21:47:10 hermes systemd[1]: mongod.service: Succeeded.   
    ```

> MongoDB doesn’t have authentication enabled by default, meaning that any user with access to the server where the
> database is installed can add and delete data without restriction. In order to secure this vulnerability, we will create
> an administrative user and enabling authentication.<br>

* Connect to MongoDB shell:
    ```shell
    mongosh
    ```
* Connect to <code>admin</code> database inside MongoDB shell:
    ```shell
    use admin
    ```
* Create new user:
    ```shell
    db.createUser({
        user: "hermes",
        pwd: passwordPrompt(),
        roles: [{role: "userAdminAnyDatabase",db: "admin"},"readWriteAnyDatabase"]
    })
    ```
  This will ask you to enter a password, Choose strong one and enter it.
  > <b>Attention: Write down your password in somewhere safe. We still need to add this credential inside our
  laravel <code>.env</code> file in further steps.</b>

* Now, check status of creating new user
  ```shell
  show users  
  ```
  You should see something like this if everything goes as expected:
    ```shell
    {
       _id: 'admin.hermes',
       userId: UUID("0011b35d-2d33-4f45-90b5-789eeab76a8c"),
       user: 'hermes',
       db: 'admin',
       roles: [
       { role: 'readWriteAnyDatabase', db: 'admin' },
       { role: 'userAdminAnyDatabase', db: 'admin' }
       ],
       mechanisms: [ 'SCRAM-SHA-1', 'SCRAM-SHA-256' ]
   }
   ```
* After doing these steps, the regular <code>mongosh</code> command won't work and if you need to connect to MongoDB
  shell, you have to do it like:
    ```shell
    mongosh -u hermes -p
    ```

### 7. Install Redis

* Install redis-server:
    ```shell
    sudo apt-get install redis-server
    ```
* Open the Redis config file:
    ```shell
    sudo nano /etc/redis/redis.conf
    ```
  Inside this file:
    * find <code>supervised</code> directive which is set as <code>no</code> by default and change it to <code>
      systemd</code>.
    * locate <code>bind 127.0.0.1::1</code> and make sure it is uncommented (remove the # if it exists):
    * scroll to the SECURITY section and look for a commented directive that says <code># requirepass foobared</code>.
      Uncomment it and replace your strong password with <code>foobared</code>

* reload the redis service:
    ```shell
    sudo systemctl restart redis.service
    ```
* To check if redis had been installed and configured correctly, run this command:
    ```shell
    sudo systemctl status redis
    ```
* If Redis had been installed and started successfully, you should see something like this:
    ```shell
    ● redis-server.service - Advanced key-value store
    Loaded: loaded (/lib/systemd/system/redis-server.service; enabled; vendor >
    Active: active (running) since Thu 2021-09-09 21:53:32 +0430; 2min 30s ago
     Docs: http://redis.io/documentation,
           man:redis-server(1)
    Process: 24402 ExecStart=/usr/bin/redis-server /etc/redis/redis.conf (code=>
    Main PID: 24417 (redis-server)
    Tasks: 4 (limit: 2280)
    Memory: 1.8M
    CGroup: /system.slice/redis-server.service
           └─24417 /usr/bin/redis-server 127.0.0.1:6379
    
    Sep 09 21:53:31 hermes systemd[1]: Starting Advanced key-value store...
    Sep 09 21:53:32 hermes systemd[1]: redis-server.service: Can't open PID file /r>
    Sep 09 21:53:32 hermes systemd[1]: Started Advanced key-value store.
    ```
* Finally, to check if your authentication had been set successfully, login to your Redis-cli:
   ```shell
   redis-cli 
   ```
  Now, type this command:
   ```shell
   auth YOUR_REDIS_PASSWORD
   ```

### 8.Install PHP and its necessary modules

* Add Apt repository.
    ```shell
    sudo apt install software-properties-common
    sudo add-apt-repository ppa:ondrej/php
    sudo apt update
    ```
* Install PHP
    ```shell
    sudo apt-get install php8.1 php8.1-dev php8.1-swoole php8.1-mongodb php8.1-redis php8.1-curl php8.1-zip php8.1-xml php8.1-bcmath php8.1-mbstring
    ```
* After installing PHP, run the following command to make sure PHP had been successfully installed:
    ```shell
    php --version
    ```
* Install MongoDB extension:
    ```shell
    sudo pecl install mongodb
    ```
* Open <code>php.ini</code>:
    ```shell
    sudo nano /etc/php/8.1/cli/php.ini
    ```
  Change <code>upload_max_filesize</code> and <code>post_max_size</code> like this:
    ```shell
    upload_max_filesize = 20M
    post_max_size = 20M
    ```

### 9. Install Composer

* Make sure you’re in your home directory, then retrieve the installer using curl:
    ```shell
    cd ~
    curl -sS https://getcomposer.org/installer -o composer-setup.php
    ```
* Next, To install composer globally, use the following command which will download and install Composer as a
  system-wide command named composer, under <code>/usr/local/bin</code>:
    ```shell
    sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer
    ```
* After installing composer, run the following command to make sure composer had been successfully installed:
    ```shell
    composer --version
    ```
* Finally, remove <code>composer-setup.php</code>:
    ```shell
    rm -rf composer-setup.php
    ```

### 10. Clone and configure application

* Change your working directory:
     ```shell
     cd /var/www
     ```
* Clone application:
     ```shell
     sudo git clone https://git.vorna.dev/ali.khedmati/hermes-backend.git
     ```
* Manage permissions and ownerships:
     ```shell
     sudo chown -R $USER:www-data /var/www/hermes-backend/
     sudo usermod -aG www-data hermes
     ```
* Change your working directory to <code>hermes-backend</code>:
     ```shell
     cd /var/www/hermes-backend
     ```
* Copy <code>.env.example</code> to <code>.env</code>, open it and modify the attributes as you wish:
     ```shell
     cp .env.example .env
     nano .env
     ```
  > Attention: Do not use HTTPS yet! build your instance in HTTP protocol and after fetching SSL certs, comeback and
  change protocols to HTTPS.
* Install dependencies:
     ```shell
     composer install --optimize-autoloader --no-dev
     ```
* Set <code>APP_KEY</code>:
     ```shell
     php artisan key:generate
     ```
* Link storages:
     ```shell
     php artisan storage:link
     ```
* Run database migrations:
     ```shell
     php artisan migrate --seed
     ```
* Finally, optimize the application:
     ```shell
     php artisan optimize
     ```

### 11. Install and configure Nginx

* Install Nginx:
  ```shell
  sudo apt-get install nginx
  ```
* Open <code>nginx.conf</code>:
  ```shell
  sudo nano /etc/nginx/nginx.conf
  ```
    * set <code>user</code> to hermes.
    * set <code>worker_connection</code> to 2048.
    * Uncomment <code>multi_accept_on</code>

* Create new Nginx configuration file for hermes:
    ```shell
    sudo touch /etc/nginx/sites-available/hermes-backend.conf
    ```
* Copy and Paste the following snippet and change <code>yourdomain</code> as you wish:
    ```
    map $http_upgrade $connection_upgrade {
        default upgrade;
        ''      close;
    }
    
    server {
        listen 80;
        listen [::]:80;
        server_name domain.com;
        server_tokens off;
        root /var/www/hermes-backend/public;
    
        index index.php;
    
        charset utf-8;
    
        location /index.php {
            try_files /not_exists @octane;
        }
    
        location / {
            try_files $uri $uri/ @octane;
        }
     
        location = /favicon.ico { access_log off; log_not_found off; }
        location = /robots.txt  { access_log off; log_not_found off; }
    
        add_header Strict-Transport-Security "max-age=63072000; includeSubdomains;" always;
        add_header X-Frame-Options "SAMEORIGIN";
        add_header X-XSS-Protection "1; mode=block" always;
        add_header X-Content-Type-Options "nosniff" always;
        add_header Referrer-Policy "strict-origin-when-cross-origin" always;
  
        access_log off;     
        error_page 404 /index.php;
    
        location @octane {
            set $suffix "";
     
            if ($uri = /index.php) {
                set $suffix ?$query_string;
            }
     
            proxy_http_version 1.1;
            proxy_set_header Host $http_host;
            proxy_set_header Scheme $scheme;
            proxy_set_header SERVER_PORT $server_port;
            proxy_set_header REMOTE_ADDR $remote_addr;
            proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
            proxy_set_header Upgrade $http_upgrade;
            proxy_set_header Connection $connection_upgrade;
     
            proxy_pass http://127.0.0.1:9581$suffix;
        }
    }
    ```
* Activate your configuration by linking to the config file from Nginx’s sites-enabled directory:
     ```shell
     sudo ln -s /etc/nginx/sites-available/hermes-backend.conf /etc/nginx/sites-enabled/
     ```
* Unlink the default configuration file from the <code>/sites-enabled/</code> directory:

     ```shell
     sudo unlink /etc/nginx/sites-enabled/default
     ```
* Reload and restart Nginx to apply the changes:

     ```shell
     sudo systemctl reload nginx
     sudo systemctl restart nginx
     ```

### 12. Install and configure SSL Certificates:

* Install Certbot:
    ```shell
    sudo apt-get install certbot python3-certbot-nginx
    ```
* Obtain new certificate:
    ```shell
    sudo certbot --nginx
    ```
* After finishing the wizard, you have to edit <code>.env</code> in <code>/var/www/hermes-backend</code> and update the
  location of your local certificates (Usually they're in <code>/etc/letsencrypt/live/</code>). Then, clear optimized
  files:
    ```shell
    php artisan optimize:clear
    ```

### 13. Install and configure Supervisor

* Install Supervisor:
    ```shell
    sudo apt-get install supervisor
    ```
* Create new configuration file:
    ```shell
    sudo nano /etc/supervisor/conf.d/hermes-backend.conf
    ```

- Put the following snippet in your <code>.conf</code> file:

    ```shell
    [program:hermes-octane]
    process_name=%(program_name)s_%(process_num)02d
    command=/usr/bin/php /var/www/hermes-backend/artisan octane:start --max-requests=1000 --workers=4 --task-workers=12 --port=9581
    autostart=true
    autorestart=true
    stopasgroup=true
    killasgroup=true
    startsecs=0
    user=hermes
    redirect_stderr=false
  
    [program:hermes-horizon]
    process_name=%(program_name)s_%(process_num)02d
    command=/usr/bin/php /var/www/hermes-backend/artisan horizon
    autostart=true
    autorestart=true
    stopasgroup=true
    killasgroup=true
    startsecs=0
    user=hermes
    redirect_stderr=false
   
    [program:hermes-websockets-serve]
    process_name=%(program_name)s_%(process_num)02d
    command=/usr/bin/php /var/www/hermes-backend/artisan websockets:serve --port=6000
    autostart=true
    autorestart=true
    stopasgroup=true
    killasgroup=true
    startsecs=0
    user=root
    redirect_stderr=false
  
    [program:hermes-websockets-listen]
    process_name=%(program_name)s_%(process_num)02d
    command=/usr/bin/php /var/www/hermes-backend/artisan websockets:listen
    autostart=true
    autorestart=true
    stopasgroup=true
    killasgroup=true
    startsecs=0
    user=hermes
    redirect_stderr=false
   
    [program:hermes-orders-update]
    process_name=%(program_name)s_%(process_num)02d
    command=/usr/bin/php /var/www/hermes-backend/artisan orders:update
    autostart=true
    autorestart=true
    stopasgroup=true
    killasgroup=true
    startsecs=0
    user=hermes
    redirect_stderr=false
   
    [program:hermes-markets-broadcast]
    process_name=%(program_name)s_%(process_num)02d
    command=/usr/bin/php /var/www/hermes-backend/artisan markets:broadcast
    autostart=true
    autorestart=true
    stopasgroup=true
    killasgroup=true
    startsecs=0
    user=hermes
    redirect_stderr=false
    
    [program:hermes-markets-update]
    process_name=%(program_name)s_%(process_num)02d
    command=/usr/bin/php /var/www/hermes-backend/artisan markets:update
    autostart=true
    autorestart=true
    stopasgroup=true
    killasgroup=true
    startsecs=0
    user=hermes
    redirect_stderr=false
    ```
  > <b>Attention:</b> We have to run <code>hermes-streams</code> program via root user because it needs read access to
  SSL certificates.

* Make Supervisor to re-read newly created <code>.conf</code> file:

    ```shell
    sudo supervisorctl reread
    ```

* Finally, update Supervisor:

    ```shell
    sudo supervisorctl update
    sudo supervisorctl restart all
    ```

* You can check Supervisor's status:

    ```shell
    sudo supervisorctl status
    ```

### 14. Install and configure Websocket stream server:

* Create new configuration file:
     ```shell
     sudo touch /etc/nginx/sites-available/hermes-stream.conf
     ```
* Copy and Paste the following snippet and change <code>yourdomain</code> as you wish + update the real address of ssl
  certs:

     ```shell
     upstream stream {
         server yourdomain:6000;
     }

     server {
         listen 6001 ssl http2;
         server_name yourdomain;
         location / {
             proxy_pass https://stream;
             proxy_http_version 1.1;
             proxy_set_header Upgrade $http_upgrade;
             proxy_set_header Connection 'upgrade';
             proxy_set_header Host $host;
             proxy_cache_bypass $http_upgrade;
             proxy_set_header X-Forwarded-For    $proxy_add_x_forwarded_for;
             proxy_set_header X-Forwarded-Proto  https;
             proxy_set_header X-VerifiedViaNginx yes;
             proxy_read_timeout                  60;
             proxy_send_timeout                  60;
             proxy_connect_timeout               60;
             send_timeout                        60;
             proxy_redirect                      off;
         }
         ssl_certificate /etc/letsencrypt/live/hermes-backend.vorna.dev/fullchain.pem;
         ssl_certificate_key /etc/letsencrypt/live/hermes-backend.vorna.dev/privkey.pem;
         include /etc/letsencrypt/options-ssl-nginx.conf;
         ssl_dhparam /etc/letsencrypt/ssl-dhparams.pem;
     }
     ```
* Activate your configuration by linking to the config file from Nginx’s sites-enabled directory:
     ```shell
     sudo ln -s /etc/nginx/sites-available/hermes-stream.conf /etc/nginx/sites-enabled/
     ```
* Reload and restart Nginx to apply the changes:
     ```shell
     sudo systemctl reload nginx
     sudo systemctl restart nginx
     ```

### 15. Manage cronjob via crontab

Hermes has 2 command to be place in system's crontab:

* <b>Laravel Cronjob</b>
* <b>Certbot SSL certificates renewal Cronjob</b>

To apply cronjobs:

* Open <code>crontab</code>:
     ```shell
     crontab -e
     ```
* Copy and paste the following snippet and save the file:

     ```shell
     0 12 * * * /usr/bin/certbot renew --quiet
     * * * * *  /usr/bin/php /var/www/hermes-backend/artisan schedule:run >> /dev/null 2>&1
     ```

### 16. Firewall

## How does Hermes works?

Todo.

## Transactions per second (TPS) / Performance

Todo.

## Scalability and numbers

Todo.

## Graph of stress/load tests

Todo.

## Postman / OpenAPI / API Documentation

Todo.

## Updating

Updating <b>Hermes</b> is very simple and easy.

- Update system/machine:

    ```shell
    sudo apt-get upgrade
    sudo apt-get update
    ```

- Fetch the latest changes of application's master branch:

    ```shell
    git pull origin master --tags
    ```

- Update the application dependencies:

    ```shell
    composer install --optimize-autoloader --no-dev
    ```

- Migrate database:

    ```shell
    php artisan migrate
    ```

- Terminate optimizations and re-optimize framework:

    ```shell
    php artisan optimize:clear
    php artisan optimize
    ```

## Security Vulnerabilities

If you discover any security vulnerabilities, Please feel free to send an e-mail to Ali Khedmati
via [ali@khedmati.ir](mailto:ali@khedmati.ir).

## TODO:

- [ ] Deploy instructions for Orchestration platforms (Docker swarm or Kubernetes).
- [ ] Develop Admin Dashboard.
