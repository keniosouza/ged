# Usa a imagem oficial PHP 8.4 com Apache
FROM php:8.4-apache

# Atualiza o sistema e instala dependências básicas
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    curl \
    git \
    libzip-dev \
    && docker-php-ext-install pdo pdo_mysql mysqli zip

# Habilita o módulo de reescrita do Apache (útil para Laravel ou outros frameworks)
RUN a2enmod rewrite

# Substitui a configuração padrão do Apache para permitir .htaccess
COPY ./apache/000-default.conf /etc/apache2/sites-available/000-default.conf

# Copia os arquivos do projeto para a pasta padrão do Apache
COPY . /var/www/html/

# Define permissões apropriadas
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Define o diretório de trabalho
WORKDIR /var/www/html

# Expõe a porta padrão HTTP
EXPOSE 80

# Comando de inicialização (padrão do Apache na imagem oficial)
CMD ["apache2-foreground"]
