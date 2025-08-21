#!/bin/bash
IMAGE="node-vue:latest"
WORKDIR="/usr/src/myapp"
# -u "node"
docker run --rm --name node-teste -ti -p "8080:8080" -v "$PWD":"$WORKDIR" -w "$WORKDIR" "$IMAGE" "${@}"