.PHONY: help
PATH := $(PATH):$(shell pwd)/bin
RESET=\033[0m
GREEN=\033[92m
YELLOW=\033[1;33m

help:                 ## Show this help
	@fgrep -h "##" $(MAKEFILE_LIST) | fgrep -v fgrep | sed -e 's/\\$$//' | sed -e 's/##//'

install:              ## Install project and initialize
	@echo "${YELLOW}Creating Docker environment file...${RESET}"
	@cp .env.example .env
	@echo "${YELLOW}Creating containers...${RESET}"
	@docker-compose up -d --build --remove-orphans --force-recreate
	@echo "${YELLOW}Create Laravel environment file...${RESET}"
	@docker-compose exec php-fpm cp .env.example .env
	@echo "${YELLOW}Installing dependencies...${RESET}"
	@make dependencies
	@echo "${YELLOW}Generating new APP_KEY private key...${RESET}"
	@docker-compose exec php-fpm php artisan key:generate
	@echo "${YELLOW}Applying migrations...${RESET}"
	@docker-compose exec php-fpm php artisan migrate
	@echo "${GREEN}Install complete.${RESET}"

rebuild:              ## Rebuild all project containers
	@docker-compose up -d --build --remove-orphans --force-recreate
	@docker ps

up:                   ## Run project dev instance in Docker
	@docker-compose up -d
	@docker ps

stop:                 ## Kill ALL Docker running instances cp .env.example .env
	@docker-compose down

dependencies:         ## Install composer dependencies composer install --working-dir=/home/someuser/myproject
	@docker-compose exec php-fpm composer install
