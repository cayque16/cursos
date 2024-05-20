#!/bin/bash
IMAGE="node:latest"
WORKDIR="/usr/src/myapp"

docker run --rm --name node-teste -ti -p "3000:3000" -u "node" -v "$PWD":"$WORKDIR" -w "$WORKDIR" "$IMAGE" "${@}"