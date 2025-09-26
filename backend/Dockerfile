
FROM php:7.3-alpine

# Install dependencies
RUN apk add --no-cache \
    wget \
    unzip \
    git \
    protoc

# Install Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    php -r "unlink('composer-setup.php');"

# Download and install protoc-gen-php-grpc
RUN wget -q https://github.com/spiral/php-grpc/releases/download/v1.4.0/protoc-gen-php-grpc-1.4.0-linux-amd64.tar.gz && \
    tar -xzf protoc-gen-php-grpc-1.4.0-linux-amd64.tar.gz && \
    mv protoc-gen-php-grpc-1.4.0-linux-amd64/protoc-gen-php-grpc /usr/local/bin/ && \
    chmod +x /usr/local/bin/protoc-gen-php-grpc && \
    rm -rf protoc-gen-php-grpc-1.4.0-linux-amd64*

# Download RoadRunner
RUN wget -q https://github.com/roadrunner-server/roadrunner/releases/download/v1.8.2/roadrunner-1.8.2-linux-amd64.tar.gz && \
    tar -xzf roadrunner-1.8.2-linux-amd64.tar.gz && \
    mv roadrunner-1.8.2-linux-amd64/rr /usr/local/bin/ && \
    chmod +x /usr/local/bin/rr && \
    rm -rf roadrunner-1.8.2-linux-amd64*

WORKDIR /app
COPY . .

# Install PHP dependencies (including dev dependencies for tests)
RUN composer install

# Run tests
RUN ./vendor/bin/phpunit tests/ --verbose

EXPOSE 9001

# Start the simple HTTP server
CMD ["php", "src/simple-server.php"]
