all: init

init: install-composer depends-install

install-composer: composer.phar

depends-install: install-composer
	php composer.phar install

depends-update: install-composer
	php composer.phar self-update
	php composer.phar update

test:
	vendor/bin/phpunit --group=japanese
	vendor/bin/phpunit --group=phone
	vendor/bin/phpunit --group=postalcode
	vendor/bin/phpunit --group=recaptcha
	vendor/bin/phpunit --group=sns
	vendor/bin/phpunit --group=url
	vendor/bin/phpunit --group=zengin

clover.xml: japanese.cov phone.cov postalcode.cov recaptcha.cov sns.cov url.cov zengin.cov
	vendor/bin/phpcov merge --clover=clover.xml build

japanese.cov: FORCE
	vendor/bin/phpunit --group=japanese --coverage-php=build/japanese.cov

phone.cov: FORCE
	vendor/bin/phpunit --group=phone --coverage-php=build/phone.cov

postalcode.cov: FORCE
	vendor/bin/phpunit --group=postalcode --coverage-php=build/postalcode.cov

recaptcha.cov: FORCE
	vendor/bin/phpunit --group=recaptcha --coverage-php=build/recaptcha.cov

sns.cov: FORCE
	vendor/bin/phpunit --group=sns --coverage-php=build/sns.cov

url.cov: FORCE
	vendor/bin/phpunit --group=url --coverage-php=build/url.cov

zengin.cov: FORCE
	vendor/bin/phpunit --group=zengin --coverage-php=build/zengin.cov

check-style: FORCE
	vendor/bin/phpmd src text codesize,controversial,design,naming,unusedcode
	vendor/bin/phpcs --standard=PSR2 src test

fix-style:
	vendor/bin/phpcbf --standard=PSR2 src test

clean:
	rm -rf vendor composer.phar clover.xml build/*.cov

composer.phar:
	curl -sS https://getcomposer.org/installer | php

.PHONY: all init install-composer depends-install depends-update test clean check-style fix-style FORCE
