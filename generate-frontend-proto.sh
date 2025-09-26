#!/bin/bash

echo "Generating gRPC-Web JavaScript files for frontend..."

# Create frontend proto directory if it doesn't exist
mkdir -p frontend/src/proto

# Generate JavaScript files using Docker (since protoc might not be available locally)
echo "Using Docker to generate proto files..."

# Create a temporary Dockerfile for proto generation
cat > Dockerfile.proto << 'EOF'
FROM node:16-alpine

# Install protoc and grpc-web plugin
RUN apk add --no-cache protobuf protobuf-dev wget unzip bash && \
    wget https://github.com/grpc/grpc-web/releases/download/1.5.0/protoc-gen-grpc-web-1.5.0-linux-x86_64 \
    -O /usr/local/bin/protoc-gen-grpc-web && \
    chmod +x /usr/local/bin/protoc-gen-grpc-web

WORKDIR /app
COPY . .

# Generate gRPC-Web client stubs
RUN protoc -I=backend/proto backend/proto/echo.proto \
    --js_out=import_style=commonjs:frontend/src/proto \
    --grpc-web_out=import_style=commonjs,mode=grpcwebtext:frontend/src/proto

CMD ["echo", "Proto files generated successfully!"]
EOF

# Build and run the proto generation container
docker build -f Dockerfile.proto -t proto-gen .
docker run --rm -v "$(pwd):/app" proto-gen

# Clean up
rm Dockerfile.proto

echo "Proto files generated successfully!"
echo "Generated files in frontend/src/proto/:"
ls -la frontend/src/proto/
