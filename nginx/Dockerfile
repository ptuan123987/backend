FROM nginx:latest

# Sao chép cấu hình Nginx
COPY nginx.conf /etc/nginx/nginx.conf

# Sao chép ứng dụng vào thư mục web root của Nginx
COPY . /var/www/html

# Mở cổng 80
EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]
