#!/bin/bash
IMAGE="node-vue:latest"
WORKDIR="/usr/src/myapp"
# -u "node"
docker run --rm --name json-server -ti -p "3000:3000" -v "$PWD":"$WORKDIR" -w "$WORKDIR" "$IMAGE" json-server --watch db.json