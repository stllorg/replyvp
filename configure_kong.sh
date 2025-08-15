#!/bin/bash

PUBLIC_KEY=$(cat ./replyp-quarkus/jwt/publicKey.pem)

sed -i "s|<PUBLIC_RSA_KEY_HERE>|${PUBLIC_KEY}|g" ./kong/declarative.yml