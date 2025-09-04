#!/bin/bash
IMAGE="node:22.19.0-alpine"
WORKDIR="/usr/src/myapp"
# -u "node"
docker run --rm --name node-teste -ti -v "$PWD":"$WORKDIR" -w "$WORKDIR" "$IMAGE" "${@}"