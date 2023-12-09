### WIP ###

dependencies :
- docker compose
- npm

### How to run this project locally ###

fill in the environment variables in the .env file (uncommitted)

next run those commands
```
docker-compose up -d
source bash_aliases.sh
sk-composer install
npm install
sk-assets
sk-sf d:d:c
sk-sf d:m:m
sk-sf d:f:l # optionnal. Only if you want to load fixtures
sk-npm-dev
```

optionally, you can refer to the symfony documentation to switch to prod mode instead of dev

if you use prod mode, change the key from bin/secret-key by running the command : TODO. **WARNING :** running this command will prevent you from decrypting previously encrypted seeds
