# Usar la imagen oficial de PHP con Apache
FROM php:8.2-apache

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
  git \
  curl \
  libpng-dev \
  libonig-dev \
  libxml2-dev \
  libzip-dev \
  libjpeg-dev \
  libfreetype6-dev \
  libssl-dev \
  libmcrypt-dev \
  zip \
  unzip \
  mariadb-client \
  && rm -rf /var/lib/apt/lists/*

# Instalar extensiones de PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
  && docker-php-ext-install -j$(nproc) \
  pdo_mysql \
  mysqli \
  mbstring \
  exif \
  pcntl \
  bcmath \
  gd \
  zip \
  sockets \
  opcache \
  intl

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Habilitar mod_rewrite de Apache
RUN a2enmod rewrite

# Configurar PHP para mostrar errores (solo desarrollo)
RUN echo "display_errors = On" >> /usr/local/etc/php/conf.d/errors.ini \
  && echo "error_reporting = E_ALL" >> /usr/local/etc/php/conf.d/errors.ini

# Crear estructura de directorios necesaria
RUN mkdir -p /opt/lampp/htdocs/facturacion-electronica-europa \
  && ln -s /var/www/html /opt/lampp/htdocs

# Configurar el directorio de trabajo
WORKDIR /var/www/html

# Copiar archivos de configuración personalizados si los tienes
# COPY php.ini /usr/local/etc/php/conf.d/custom.ini

# Exponer el puerto 80
EXPOSE 80

# Comando por defecto
CMD ["apache2-foreground"]