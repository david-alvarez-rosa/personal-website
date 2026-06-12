FROM ubuntu:noble

ARG HUGO_VERSION=0.163.1
ARG PLAYWRIGHT_VERSION=1.58.0
ARG NODE_MAJOR=22

ENV DEBIAN_FRONTEND=noninteractive

RUN apt-get update \
    && apt-get install -y --no-install-recommends ca-certificates curl gnupg \
    && curl -fsSL "https://deb.nodesource.com/setup_${NODE_MAJOR}.x" | bash - \
    && apt-get install -y --no-install-recommends nodejs \
    && corepack enable \
    && curl -fsSL -o /tmp/hugo.tar.gz \
        "https://github.com/gohugoio/hugo/releases/download/v${HUGO_VERSION}/hugo_extended_${HUGO_VERSION}_linux-amd64.tar.gz" \
    && tar -xzf /tmp/hugo.tar.gz -C /usr/local/bin hugo \
    && rm /tmp/hugo.tar.gz \
    && npx -y playwright@${PLAYWRIGHT_VERSION} install --with-deps chromium \
    && apt-get purge -y curl gnupg \
    && apt-get autoremove -y \
    && rm -rf /var/lib/apt/lists/* /tmp/*

WORKDIR /work
