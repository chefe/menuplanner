# menuplanner
[![TravisCI Status](https://travis-ci.org/chefe/menuplanner.svg?branch=master)](https://travis-ci.org/chefe/menuplanner)
[![StyleCI Status](https://styleci.io/repos/119275915/shield?branch=master&style=flat)](https://styleci.io/repos/119275915)
[![Codeship Status for chefe/menuplanner](https://app.codeship.com/projects/a9a73c90-e523-0138-a119-0262a986c13d/status?branch=master)](https://app.codeship.com/projects/410865)
[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=chefe_menuplanner&metric=alert_status)](https://sonarcloud.io/dashboard?id=chefe_menuplanner)

An application to plan your meals for a given timeframe.

## Installation
1. Begin by cloning this repository to your machine, and installing all Composer & NPM dependencies.

```bash
git clone git@github.com:chefe/menuplanner.git
cd menuplanner && composer install && npm install
cp .env.example .env
php artisan key:generate
npm run dev
```

2. Next, configure your database credentials in the `.env` file.
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
