FROM mcr.microsoft.com/playwright:v1.58.0-noble

RUN apt-get update \
    && apt-get install -y --no-install-recommends wget ca-certificates \
    && version=$(wget -qO- https://api.github.com/repos/gohugoio/hugo/releases/latest | grep -oP '"tag_name": "v\K[^"]+') \
    && wget -qO /tmp/hugo.tar.gz \
        "https://github.com/gohugoio/hugo/releases/download/v${version}/hugo_extended_${version}_linux-amd64.tar.gz" \
    && tar -xzf /tmp/hugo.tar.gz -C /usr/local/bin hugo \
    && rm /tmp/hugo.tar.gz \
    && apt-get purge -y wget \
    && rm -rf /var/lib/apt/lists/* \
    && corepack enable

WORKDIR /work
