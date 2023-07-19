#!/bin/bash
IMAGE="my-php-app"
WORKDIR="/usr/src/myapp"

docker run --rm --name php-teste -ti -v "$PWD":"$WORKDIR" -w "$WORKDIR" "$IMAGE" "${@}"