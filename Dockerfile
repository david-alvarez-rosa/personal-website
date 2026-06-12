FROM mcr.microsoft.com/playwright:v1.58.0-noble

ARG HUGO_VERSION=0.163.1

RUN apt-get update \
    && apt-get install -y --no-install-recommends wget ca-certificates \
    && wget -qO /tmp/hugo.tar.gz \
        "https://github.com/gohugoio/hugo/releases/download/v${HUGO_VERSION}/hugo_extended_${HUGO_VERSION}_linux-amd64.tar.gz" \
    && tar -xzf /tmp/hugo.tar.gz -C /usr/local/bin hugo \
    && rm /tmp/hugo.tar.gz \
    && apt-get purge -y wget \
    && rm -rf /var/lib/apt/lists/* \
    && corepack enable

WORKDIR /work
