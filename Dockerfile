FROM ubuntu:20.04

RUN apt-get update && \
    DEBIAN_FRONTEND=noninteractive apt-get install -y \
    php \
    php-cli \
    php-pdo \
    php-sqlite3 \
    php-json \
    curl \
    && apt-get clean

WORKDIR /rest-api-php

COPY . /rest-api-php

EXPOSE 8080

CMD ["php", "main.php"]