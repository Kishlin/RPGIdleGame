FROM kishlin/base-kishlin-node:16.13.2-alpine-dev

VOLUME /srv/cache

ARG DOCKER_USER
ARG DOCKER_USER_ID

RUN apk add --no-cache shadow sudo bash

# Add local user
RUN addgroup -S sudo \
	&& useradd --shell /bin/bash -u ${DOCKER_USER_ID} -o -c "" -m ${DOCKER_USER} \
    && adduser ${DOCKER_USER} sudo \
    && echo '%sudo ALL=(ALL) NOPASSWD:ALL' >> /etc/sudoers \
    && export HOME=/home/${DOCKER_USER}

RUN npm install react-scripts@latest
