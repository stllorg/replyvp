# Use the official OpenResty image as the base
FROM openresty/openresty:bionic

# Install curl
RUN apt-get update && apt-get install -y curl

# Download and extract the nginx-jwt.tar file without the 'z' flag
RUN curl -L https://github.com/auth0/nginx-jwt/releases/download/v1.0.1/nginx-jwt.tar.gz | tar -xv -C /usr/local/openresty/lualib/

# Copy the Nginx configuration file
COPY nginx.conf /usr/local/openresty/nginx/conf/nginx.conf

# Expose port 80
EXPOSE 80

# Start OpenResty
CMD ["/usr/local/openresty/bin/openresty", "-g", "daemon off;"]