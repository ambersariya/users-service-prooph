cache-clear:
    @test -f bin/console && bin/console cache:clear --no-warmup || rm -rf var/cache/*
.PHONY: cache-clear

cache-warmup: cache-clear
    @test -f bin/console && bin/console cache:warmup || echo "cannot warmup the cache (needs symfony/console)"
.PHONY: cache-warmup

generate-jwt-keys:
    php openssl genrsa -passout env:JWT_PASSPHRASE -out config/jwt/private.pem -aes256 4096
    php openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem -passin env:JWT_PASSPHRASE
.PHONY: generate-jwt-keys

