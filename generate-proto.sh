#!/bin/bash

echo "Generating gRPC-Web JavaScript files..."

# Check if protoc and plugins are available
if ! command -v protoc &> /dev/null; then
    echo "Error: protoc not found. Please install protocol buffer compiler."
    exit 1
fi

# Create frontend proto directory
mkdir -p frontend/src/proto

# Generate JavaScript files
protoc -I=backend/proto echo.proto \
  --js_out=import_style=commonjs:frontend/src/proto \
  --grpc-web_out=import_style=commonjs,mode=grpcwebtext:frontend/src/proto

echo "Proto files generated successfully!"
echo "Generated files in frontend/src/proto/:"
ls -la frontend/src/proto/