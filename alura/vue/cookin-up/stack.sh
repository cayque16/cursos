#!/bin/bash
IMAGE="node:18.17.0"
WORKDIR="/usr/src/myapp"
# -u "node"
docker run --rm --name node-teste -ti -p "5173:5173" -v "$PWD":"$WORKDIR" -w "$WORKDIR" "$IMAGE" "${@}"