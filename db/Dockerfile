FROM mysql:9.1

ENV MYSQL_ROOT_PASSWORD=${MYSQL_ROOT_PASSWORD}
ENV MYSQL_DATABASE=api
ENV MYSQL_DEFAULT_AUTHENTICATION_PLUGIN: mysql_native_password

RUN echo "[mysqld]" > /etc/mysql/conf.d/docker-custom.cnf && \
    echo "character-set-server=utf8mb4" >> /etc/mysql/conf.d/docker-custom.cnf && \
    echo "collation-server=utf8mb4_unicode_ci" >> /etc/mysql/conf.d/docker-custom.cnf

COPY ./init/script.sql /docker-entrypoint-initdb.d/

EXPOSE 3306