# menuplanner
[![TravisCI Status](https://travis-ci.org/chefe/menuplanner.svg?branch=master)](https://travis-ci.org/chefe/menuplanner)
[![StyleCI Status](https://styleci.io/repos/119275915/shield?branch=master&style=flat)](https://styleci.io/repos/119275915)

## Installation
1. Begin by cloning this repository to your machine, and installing all Composer & NPM dependencies.

```bash
git clone git@github.com:chefe/menuplanner.git
cd menuplanner && composer install && npm install
cp .env.example .env
php artisan key:generate
npm run dev
```

2. Next, configure your database credentials in in the `.env` file.  
3. After that, you can migrate the database with the command `php artisan migrate`.
4. Finally, boot up a server and visit the application.  

## Credit
* http://zondicons.com
* https://tailwindcss.com/
* https://laravel.com/
* https://vuejs.org/
* https://router.vuejs.org/
* https://github.com/axios/axios
* https://momentjs.com/
* https://hilongjw.github.io/vue-progressbar