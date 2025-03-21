FROM php:8-apache

# Installation de PDO MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Activation du module Apache pour réécrire les URLs (optionnel mais recommandé)
RUN a2enmod rewrite
