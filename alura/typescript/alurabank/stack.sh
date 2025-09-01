#!/bin/bash
IMAGE="node:24.7.0"
WORKDIR="/usr/src/myapp"
# -u "node"
docker run --rm --name node-teste -ti -p "3000:3000" -v "$PWD":"$WORKDIR" -w "$WORKDIR" "$IMAGE" "${@}"