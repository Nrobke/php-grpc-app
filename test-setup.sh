#!/bin/bash

echo "🧪 Testing gRPC Full Stack Application Setup"
echo "=============================================="

# Check if Docker is running
if ! docker info > /dev/null 2>&1; then
    echo "❌ Docker is not running. Please start Docker and try again."
    exit 1
fi

echo "✅ Docker is running"

# Check if docker-compose is available
if ! command -v docker-compose &> /dev/null; then
    echo "❌ docker-compose is not installed. Please install docker-compose."
    exit 1
fi

echo "✅ docker-compose is available"

# Build and start services
echo "🚀 Building and starting services..."
docker-compose up --build -d

# Wait for services to be ready
echo "⏳ Waiting for services to be ready..."
sleep 30

# Test backend health
echo "🔍 Testing backend service..."
if curl -f http://localhost:9001/health.php > /dev/null 2>&1; then
    echo "✅ Backend service is responding"
else
    echo "⚠️  Backend service may not be ready yet"
fi

# Test Envoy proxy
echo "🔍 Testing Envoy proxy..."
if curl -f http://localhost:8080 > /dev/null 2>&1; then
    echo "✅ Envoy proxy is responding"
else
    echo "⚠️  Envoy proxy may not be ready yet"
fi

# Test frontend
echo "🔍 Testing frontend service..."
if curl -f http://localhost:3000 > /dev/null 2>&1; then
    echo "✅ Frontend service is responding"
else
    echo "⚠️  Frontend service may not be ready yet"
fi

echo ""
echo "🎉 Setup test completed!"
echo ""
echo "📋 Service URLs:"
echo "   Frontend: http://localhost:3000"
echo "   Backend gRPC: localhost:9001"
echo "   Envoy Proxy: http://localhost:8080"
echo "   Envoy Admin: http://localhost:9901"
echo ""
echo "📊 View logs: docker-compose logs -f"
echo "🛑 Stop services: docker-compose down"
echo ""
echo "🧪 To run tests:"
echo "   Backend: docker-compose exec backend ./vendor/bin/phpunit tests/ --verbose"
echo "   Frontend: docker-compose exec frontend npm test"
