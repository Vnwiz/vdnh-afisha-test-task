FROM nginx:alpine

# Copy custom nginx.conf with resolver configuration
COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf

# Copy our nginx vhost config into the default location
COPY docker/nginx/conf.d/app.conf /etc/nginx/conf.d/default.conf