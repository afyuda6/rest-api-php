FROM php:8.1-cli

RUN apt-get update && \
    DEBIAN_FRONTEND=noninteractive apt-get install -y \
    sqlite3 \
    curl \
    && apt-get clean

WORKDIR /rest-api-php

COPY . /rest-api-php

EXPOSE 8080

CMD ["php", "main.php"]