FROM php:8.2-apache

# 安裝系統相依套件
RUN apt-get update && apt-get install -y \
    gnupg \
    unixodbc-dev \
    && rm -rf /var/lib/apt/lists/*

# 加入 Microsoft repository key 和 list
RUN curl https://packages.microsoft.com/keys/microsoft.asc | apt-key add - \
    && curl https://packages.microsoft.com/config/debian/11/prod.list > /etc/apt/sources.list.d/mssql-release.list

# 安裝 Microsoft ODBC Driver for SQL Server
RUN apt-get update && ACCEPT_EULA=Y apt-get install -y msodbcsql18

# 安裝 PHP 擴充功能 (MySQL)
RUN docker-php-ext-install mysqli pdo pdo_mysql

# 安裝 PHP 擴充功能 (SQL Server)
RUN pecl install sqlsrv pdo_sqlsrv \
    && docker-php-ext-enable sqlsrv pdo_sqlsrv
