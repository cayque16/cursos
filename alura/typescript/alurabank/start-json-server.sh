#!/bin/bash
IMAGE="node-test:alpine"
WORKDIR="/usr/src/myapp"

docker run --rm --name json-server -ti -p "8080:8080" -v "$PWD":"$WORKDIR" -w "$WORKDIR" "$IMAGE" json-server --watch db.json --port 8080