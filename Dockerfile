FROM phpswoole/swoole:5.0.1-php8.2-alpine

RUN set -ex \
    && pecl channel-update pecl.php.net

# Set the working directory in the container
WORKDIR /app

# Copy the project files to the container
COPY . /app

# Install project dependencies using Composer
RUN composer install --no-dev --optimize-autoloader


# Set the default command to run the stress test
CMD ["php", "./run/http-stress-test.php"]