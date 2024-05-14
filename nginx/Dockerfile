# Sử dụng image chứa PHP-FPM
FROM php:8.0-fpm

# Cài đặt các gói phụ thuộc
RUN apt-get update && apt-get install -y nginx

# Copy cấu hình Nginx vào container
COPY nginx.conf /etc/nginx/nginx.conf

# Copy ứng dụng Laravel vào thư mục /var/www/html
COPY . /var/www/html

# Mở cổng 80
EXPOSE 80

# Bật PHP-FPM
CMD ["php-fpm"]
