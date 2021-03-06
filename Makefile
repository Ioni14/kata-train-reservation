build:
	docker build -t php8composer --target=runtime .
	docker build -t php8composer_xdebug --target=xdebug .

psalm:
	vendor/bin/psalm --show-info=true

phpunit:
	vendor/bin/phpunit

infection:
	XDEBUG_MODE=coverage vendor/bin/infection -j20
