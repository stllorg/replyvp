#!/bin/bash

PUBLIC_KEY=$(cat ./reply-core/jwt/publicKey.pem)

sed -i "s|<PUBLIC_RSA_KEY_HERE>|${PUBLIC_KEY}|g" ./reply-service-provider/kong.yml