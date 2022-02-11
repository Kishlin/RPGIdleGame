FROM kishlin/base-kishlin-php:8.1-fpm-dev

VOLUME /srv/cache

ARG DOCKER_USER
ARG DOCKER_USER_ID

# Add local user
RUN useradd --shell /bin/bash -u ${DOCKER_USER_ID} -o -c "" -m ${DOCKER_USER} \
    && adduser ${DOCKER_USER} sudo \
    && echo '%sudo ALL=(ALL) NOPASSWD:ALL' >> /etc/sudoers \
    && export HOME=/home/${DOCKER_USER}

ADD config/php/custom-php.ini /usr/local/etc/php/conf.d/custom-php.ini
ADD config/php/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini.dis

RUN wget https://github.com/symfony/cli/releases/download/v4.27.5/symfony_linux_amd64.gz \
    && gzip -d symfony_linux_amd64.gz \
    && chmod +x symfony_linux_amd64 \
    && mv symfony_linux_amd64 /usr/local/bin/symfony \
    && symfony self:update \
