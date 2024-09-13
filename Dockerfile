# Используем официальный PHP образ
FROM php:8.2-cli

# Устанавливаем необходимые пакеты и расширения
RUN apt-get update && \
    apt-get install -y \
    git \
    unzip \
    && docker-php-ext-install pdo pdo_mysql

# Устанавливаем Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Рабочая директория
WORKDIR /app

# Копируем файлы проекта
COPY . .

# Устанавливаем зависимости Composer
RUN composer install

# Открываем порт для встроенного PHP сервера
EXPOSE 8000

# Запускаем встроенный PHP сервер
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]