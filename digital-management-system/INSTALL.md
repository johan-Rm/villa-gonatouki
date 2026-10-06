## INSTALLATION

::: Requirements :::

* PHP :       7.4.3
* Composer :  2.6.5                                          
* Node :      16.14.2
* Yarn :      1.22.19

---
<br/>

Créer la base de données

```bash
mysql -u $MYSQL_USER -p

CREATE DATABASE dms_villa_gonatouki;
```

Importer la base de données

```bash
mysql -u $MYSQL_USER -p dms_villa_gonatouki < ./backup.sql
```

Récupérer les resources

```bash
unzip $PATH_RESOURCES/digital-management-system/resources.zip -d ./public
```

Éditer le fichier .env.local (DATABASE_URL, DIRECTORY_VIEW, DIRECTORY_RESOURCES)

```bash
cp .env .env.local && nano .env.local
```

Installer les dépendances

```bash
composer install && yarn install
```

Installer cwebp

```bash
sudo apt install webp
```

Lancer le build Webpack Encore

```bash
yarn encore dev
```

Create a user admin

```bash
./bin/console fos:user:create admin admin@admin.com admin
```

<br/>

## UTILISATION

Lancer le serveur de dev

```bash
symfony server:start --no-tls --port=8000 | ./bin/console server:run --env=dev
```

Lancer Webpack Encore

```bash
yarn encore dev --watch
```


