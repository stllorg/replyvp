mkdir -p keys/jwt
openssl genrsa -out keys/jwt/rsaPrivateKey.pem 2048
openssl rsa -pubout -in keys/jwt/rsaPrivateKey.pem -out keys/jwt/publicKey.pem
openssl pkcs8 -topk8 -nocrypt -inform pem -in keys/jwt/rsaPrivateKey.pem -outform pem -out keys/jwt/privateKey.pem
chmod 600 keys/jwt/publicKey.pem
chmod 600 keys/jwt/rsaPrivateKey.pem
chmod 600 keys/jwt/privateKey.pem