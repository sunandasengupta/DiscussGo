# Use the official PHP CLI image
FROM php:8.1-cli

# Set workdir
WORKDIR /app

# Install required packages and PHP extensions (mysqli and pdo_mysql)
RUN apt-get update \
    && apt-get install -y --no-install-recommends libzip-dev unzip git zip libonig-dev \
    && docker-php-ext-install mysqli pdo pdo_mysql \
    && rm -rf /var/lib/apt/lists/*

# Copy app source
COPY . /app

# Ensure app files are readable
RUN chown -R www-data:www-data /app || true

# Expose the port (Render injects $PORT at runtime)
EXPOSE 10000

# Start PHP's built-in server using Render's $PORT env var (fallback to 10000 locally)
CMD ["sh", "-c", "php -S 0.0.0.0:${PORT:-10000} -t ."]
