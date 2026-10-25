variable "app_secret" {
  type      = string
  sensitive = true
}

data "aws_ssm_parameter" "ami" {
  name = "/aws/service/ami-amazon-linux-latest/al2023-ami-kernel-default-x86_64"
}

resource "aws_instance" "app" {
  ami                    = data.aws_ssm_parameter.ami.value
  instance_type          = "t3.micro"
  subnet_id              = aws_subnet.public[0].id
  vpc_security_group_ids = [aws_security_group.app.id]

  user_data_replace_on_change = true
  user_data = <<-EOT
    #!/bin/bash
    dnf install -y docker
    systemctl enable --now docker
    mkdir -p /usr/local/lib/docker/cli-plugins /opt/app
    curl -SL https://github.com/docker/compose/releases/download/v2.29.7/docker-compose-linux-x86_64 -o /usr/local/lib/docker/cli-plugins/docker-compose
    chmod +x /usr/local/lib/docker/cli-plugins/docker-compose
    cd /opt/app

    cat > nginx.conf <<'NGINX'
    server {
      listen 80;
      root /var/www/html/public;
      location / { try_files $uri /index.php$is_args$args; }
      location ~ ^/index\.php(/|$) {
        fastcgi_pass app:9000;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
      }
    }
    NGINX

    cat > docker-compose.yml <<'COMPOSE'
    services:
      app:
        image: ghcr.io/rokaya-mk/symfony-7-store:latest
        restart: always
        env_file: app.env
        volumes: ["code:/var/www/html"]
      web:
        image: nginx:alpine
        restart: always
        ports: ["80:80"]
        volumes:
          - "code:/var/www/html"
          - "./nginx.conf:/etc/nginx/conf.d/default.conf"
        depends_on: [app]
    volumes:
      code:
    COMPOSE

    cat > app.env <<'ENVF'
    APP_ENV=prod
    APP_SECRET=${var.app_secret}
    DEFAULT_URI=http://localhost
    DATABASE_URL=mysql://app:${var.db_password}@${aws_db_instance.main.address}:3306/symfonystore?serverVersion=8.0
    ENVF

    docker compose up -d
    sleep 20
    docker compose exec -T app php bin/console doctrine:schema:update --force
  EOT

  tags = { Name = "${local.name}-app" }
}