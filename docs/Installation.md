## Local Development Setup

Navigate to https://github.com/CookieMelon/solaire_entertainment_city
and click fork.

Clone your repository
```bash
git clone https://github.com/<you_username>/solaire_entertainment_city.git
cd solaire_entertainment_city
```

Make sure [Docker Desktop](https://docs.docker.com/desktop/setup/install/windows-install/) and [DDEV](https://docs.ddev.com/en/stable/users/install/docker-installation/) installed to your machine.

Start the Project
```bash
ddev start
```

Install PHP dependency
```bash
ddev composer install
```

Reguqest a Database to the owner and run the following.
```bash
ddev import-db < ./db_dump.sql
ddev drush cr
```
