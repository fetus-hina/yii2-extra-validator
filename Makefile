.PHONY: all
all: test

.PHONY: test
test: vendor check-style
	vendor/bin/phpunit --group=japanese
	vendor/bin/phpunit --group=recaptcha
	vendor/bin/phpunit --group=sns
	vendor/bin/phpunit --group=url
	vendor/bin/phpunit --group=zengin

.PHONY: check-style
check-style: vendor
	find . \( -type d \( -name '.git' -or -name 'vendor' -or -name 'runtime' \) -prune \) -or \( -type f -name '*.php' -print \) | xargs -n 1 php -l
	vendor/bin/phpcs --standard=PSR12 src test

.PHONY: fix-style
fix-style: vendor
	vendor/bin/phpcbf --standard=PSR12 src test

.PHONY: clean
clean:
	rm -rf vendor composer.phar

composer.lock: composer.json composer.phar
	./composer.phar update -v
	touch $@

vendor: composer.lock composer.phar
	./composer.phar install -v
	touch $@

composer.phar:
	curl -sS https://getcomposer.org/installer | php
	touch -r composer.json $@
