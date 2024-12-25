# Variables
DOCKER = docker
DOCKER_COMPOSE = docker-compose
EXEC = $(DOCKER_COMPOSE) exec www
PHP = $(EXEC) php
COMPOSER = $(EXEC) composer
SYMFONY_CONSOLE = $(PHP) bin/console


# Colors
GREEN = /bin/echo -e "\x1b[32m\#\# $1\x1b[0m"

init: ## Init the project
	$(MAKE) start
	@$(call GREEN,"The application is available at: http://127.0.0.1:8001/.")

install:
	$(DOCKER_COMPOSE) exec www composer install

create-db: 
	$(PHP) bin/console doctrine:database:create

make-migration: 
	$(PHP) bin/console make:migration 

migrate: 
	$(PHP) bin/console doctrine:migrations:migrate

bash:
	$(DOCKER_COMPOSE) exec www bash


# Cibles
.PHONY: up down build start stop restart logs bash composer install migrate



# Démarrer les services Docker
up:
	$(DOCKER_COMPOSE) up -d

# Arrêter les services Docker
down:
	$(DOCKER_COMPOSE) down

# Construire les images Docker
build:
	$(DOCKER_COMPOSE) build

# Démarrer les services Docker
start:
	$(DOCKER_COMPOSE) start

# Arrêter les services Docker
stop:
	$(DOCKER_COMPOSE) stop

# Redémarrer les services Docker
restart:
	$(DOCKER_COMPOSE) restart

# Afficher les logs des services Docker
logs:
	$(DOCKER_COMPOSE) logs -f


