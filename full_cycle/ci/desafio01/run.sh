#!/bin/bash
WORRDIR='/src/usr/app'
docker run -it --rm -v $(pwd)/:$WORRDIR -w $WORRDIR node bash

# npx jest --watchAll