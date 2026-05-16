.PHONY: all
all: test

.PHONY: test
test: vendor check-style
	php -d memory_limit=512M vendor/bin/phpunit

.PHONY: check-style
check-style: vendor
	@xargs_opts=; \
		if [ "$$(php -r 'echo PHP_VERSION_ID;')" -lt 80300 ]; then \
			xargs_opts='-n 1'; \
		fi; \
		find . \( -type d \( -name '.git' -or -name 'vendor' -or -name 'runtime' \) -prune \) -or \( -type f -name '*.php' -print \) \
			| xargs $$xargs_opts php -l
	vendor/bin/phpcs src test
	vendor/bin/phpstan --memory-limit=1G

.PHONY: fix-style
fix-style: vendor
	vendor/bin/phpcbf src test

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
