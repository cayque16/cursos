#!/bin/bash
IMAGE="node:latest"
WORKDIR="/usr/src/myapp"

docker run --rm --name node-teste -ti -u "node" -v "$PWD":"$WORKDIR" -w "$WORKDIR" "$IMAGE" "${@}"