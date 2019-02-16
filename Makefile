install: init book

init: docker
	docker-compose exec web bash -c "composer install"
	docker-compose exec web bash -c "php bin/console d:s:u --force"
	docker-compose exec web bash -c "php bin/console hautelook:fixtures:load --purge-with-truncate -q"
	docker-compose exec web bash -c "php bin/console app:create-admin adminViaMakefile@admin.com"

docker: .env
	docker-compose up -d

.env:
	cp .env.dist .env;

book: docker-compose.yml
	#
	# API Budget_Cards_Sub
	#
	# API: http://127.0.0.1:81
	# phpMyAdmin: http://127.0.0.1:8080
	#