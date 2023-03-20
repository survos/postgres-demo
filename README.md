# postgres-demo
demo of jsonb and fulltext search

# setup

If postgres isn't installed, run it via docker (or install it).
```bash
docker run --rm --name pg-docker -e POSTGRES_PASSWORD=docker -d -p 5432:5432 -v $HOME/docker/volumes/postgres15:/var/lib/postgresql/data postgres:15
```
`
Clone the repo and load the data
```bash
docker run --rm --name pg-docker -e POSTGRES_PASSWORD=docker -d -p 5432:5432 -v $HOME/docker/volumes/postgres15:/var/lib/postgresql/data postgres:15

git clone git@github.com:tacman/postgres-demo.git
cd postgres-demo/
composer install
yarn install && yarn dev
bin/console d:database:create
bin/console d:schema:update --force --complete
bin/console doctrine:fixtures:load -n

# 
symfony proxy:domain:attach postgres-demo
symfony server:start -d

# OR php -S localhost:8300 -t public/
```

Now go to https://postgres-demo.wip/





