#!/bin/bash
IMAGE="node-test:alpine"
WORKDIR="/usr/src/myapp"
# -u "node"
docker run --rm --name node-teste -ti -p "3000:3000" -v "$PWD":"$WORKDIR" -w "$WORKDIR" "$IMAGE" "${@}"