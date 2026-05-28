#!/bin/bash

echo "🏥 Iniciando Hospital..."

docker-compose up -d

echo "⏳ Aguardando banco de dados..."
sleep 20

echo "🔑 Gerando chave da aplicação..."
docker exec hospital_app php artisan key:generate --no-interaction --force

echo "🗄️  Rodando migrations..."
docker exec hospital_app php artisan migrate --no-interaction --force

echo "🌱 Rodando seeders..."
docker exec hospital_app php artisan db:seed --no-interaction --force

echo "🧹 Limpando cache..."
docker exec hospital_app php artisan config:clear
docker exec hospital_app php artisan cache:clear

echo ""
echo "✅ Hospital pronto!"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "🌐 Sistema:    http://localhost:8080"
echo "🗄️  phpMyAdmin: http://localhost:8081"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "👤 Login:  admin@hospital.com"
echo "🔑 Senha:  12345678"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"