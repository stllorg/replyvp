FROM alpine:latest
RUN apk add --no-cache openssl bash
COPY keygen.sh /keygen.sh
RUN chmod +x /keygen.sh
CMD ["/keygen.sh"]