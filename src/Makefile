CONSOLE		= bin/console
COMPOSER	= composer
YARN		= yarn

##
###------------#
###    Help    #
###------------#
##

.DEFAULT_GOAL := help

help:	## Display all help messages
		@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-20s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

.PHONY:	help

##
###---------------------------#
###    Project commands (SF)  #
###---------------------------#
##

install:	.env.local vendor node_modules assets db-init ## Launch project : copy the env and start the project with vendors, assets and install SQLite DB

sf-console\:%:	## Calling Symfony console
				$(CONSOLE) $* $(ARGS)

.PHONY:	install

##
###----------------------------#
###    Rules based on files    #
###----------------------------#
##

vendor:	./composer.json ## Install dependencies (vendor) (might be slow)
		@echo "Might be very slow for the first launch."
		$(COMPOSER) install --prefer-dist --no-progress

node_modules:	./package.json ## Yarn install
				$(YARN) install --frozen-lockfile

.env.local:	./.env ## Create env.local
			@echo '\033[1;42m/\ The .env.local was just created. Feel free to put your config in it.\033[0m';
			cp ./.env ./.env.local;

##
###-------------------------#
###    Doctrine commands    #
###-------------------------#
##

db-destroy:	## Execute doctrine:database:drop --force command
			$(CONSOLE) doctrine:database:drop --force
			$(CONSOLE) doctrine:database:drop --force --env=test

db-create:	## Execute doctrine:database:create
			$(CONSOLE) doctrine:database:create -vvv

db-migrate:	## Execute doctrine:migrations:migrate
			$(CONSOLE) doctrine:migrations:migrate --allow-no-migration --no-interaction --all-or-nothing

db-fixtures:	## Execute doctrine:fixtures:load
				$(CONSOLE) doctrine:fixtures:load --no-interaction --purge-with-truncate

db-fixtures-test: 	## Execute doctrine:fixtures:load fo test env
					$(CONSOLE) doctrine:database:create -vvv --env=test
					$(CONSOLE) doctrine:migrations:migrate --allow-no-migration --no-interaction --all-or-nothing --env=test
					$(CONSOLE) doctrine:fixtures:load --no-interaction --env=test

db-init:	vendor db-create db-migrate db-fixtures db-fixtures-test ## Initialize database e.g : wait, create database, migrations and load fixtures

.PHONY:	db-destroy db-create db-migrate db-fixtures db-fixtures-test db-init

##
###------------#
###    Utils   #
###------------#
##

assets:	node_modules ## Install node_modules and compile with Yarn
		$(YARN) run dev

watch:	node_modules ## Install node_modules and compile with Yarn with the --watch option
		$(YARN) run watch

clear-assets:	## Remove build directory
				rm -rvf ./public/build

clean:	qa-clean-conf ## Remove all generated files
		rm -rvf ./var ./vendor ./node_modules

clear:	clear-assets db-destroy clean ## Remove all generated files, assets and database

cc:		## Clear cache
		$(CONSOLE) cache:clear

update:	## Update dependencies
		$(COMPOSER) update --lock --no-interaction --no-scripts
		$(YARN) upgrade

.PHONY:	assets watch clear-assets clean clear cc update

##
###-------------------#
###    PhpUnit Tests  #
###-------------------#
##

tu:	vendor ## Run unit tests (might be slow for the first time)
	./bin/phpunit --exclude-group functional

tf:	vendor ## Run functional tests
	./bin/phpunit --group functional

tw:	vendor ## Run wip tests
	./bin/phpunit --group wip

coverage:	vendor ## Run code coverage of PHPunit suite
			./bin/phpunit --coverage-html ./var/coverage

phpunit:	tu tf tw coverage ## Alias coupling all PHPUnit tests

.PHONY:	tu tf tw coverage phpunit

##
###----------------#
###    Q&A tools   #
###----------------#
##

lt:	vendor ## Lint twig templates
	$(CONSOLE) lint:twig ./templates

ly:	vendor ## Lint yaml config
	$(CONSOLE) lint:yaml ./config

lc:	vendor ## Ensures that arguments injected into services match type declarations
	$(CONSOLE) lint:container

lint:	lt ly lc ## All linter rules

security:	vendor ## Check security of your dependencies (https://security.sensiolabs.org/)
			./vendor/bin/security-checker security:check

.PHONY:	lt ly lc lint security

##
###-------------------#
###    PHP-CS-FIXER   #
###-------------------#
##

.php_cs:	./.php_cs.dist ## Create php_cs based on the dist one
			cp ./.php_cs.dist ./.php_cs

php-cs-fixer:	.php_cs vendor ## Fix php code style
				./vendor/bin/php-cs-fixer fix

qa-php-cs-fixer:	.php_cs vendor ## Q&A php code style check
					./vendor/bin/php-cs-fixer fix -v --dry-run --stop-on-violation --using-cache=no

.PHONY:	php-cs-fixer qa_php-cs-fixer

##
###----------------------#
###    PHP Code Sniffer  #
###----------------------#
##

phpcs.xml:	./phpcs.xml.dist ## Create phpcs.xml based on the dist one
			cp ./phpcs.xml.dist ./phpcs.xml

phpcs:	phpcs.xml vendor ## Execute PHP Code Sniffer
		./vendor/bin/phpcs -v ./src --ignore=src/Migrations/

phpcbf:	phpcs.xml vendor ## Execute PHP Code Sniffer
		./vendor/bin/phpcbf -v ./src

.PHONY:	phpcs phpcbf

##
###-----------------------#
###    Tests / Q&A Alias  #
###-----------------------#
##

qa-clean-conf:	## Erasing all quality assurance conf files
				rm -rvf ./.php_cs ./phpcs.xml ./.phpcs-cache ./.php_cs.cache ./phpmd.xml ./.phpunit.result.cache

qa:	lt ly lc php-cs-fixer phpcs phpcbf ## Alias to run/apply Q&A tools

.PHONY:	qa-clean-conf qa
