# gRPC Full Stack Application - PHP 7.3 + React

A full-stack gRPC application demonstrating PHP 7.3 compatibility with gRPC using Spiral PHP gRPC, featuring a React frontend that communicates with a PHP backend through gRPC-Web.

## 🚀 Features

- **Backend**: PHP 7.3 gRPC server using Spiral PHP gRPC
- **Frontend**: React application with gRPC-Web client
- **Proxy**: Envoy proxy for gRPC-Web support
- **Testing**: Comprehensive tests for both backend and frontend
- **Docker**: Complete containerized setup
- **PHP 7.3 Workaround**: Demonstrates gRPC compatibility with older PHP versions

## 📋 Prerequisites

- Docker and Docker Compose
- Node.js 16+ (for local development)
- PHP 7.3+ (for local development)
- Composer (for local development)

## 🏗️ Architecture

```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│   React App     │───▶│  Envoy Proxy    │───▶│  PHP 7.3 gRPC   │
│   (Port 3000)   │    │  (Port 8080)    │    │   (Port 9001)   │
└─────────────────┘    └─────────────────┘    └─────────────────┘
```

## 🚀 Quick Start

### Using Docker (Recommended)

1. **Clone and navigate to the project:**
   ```bash
   git clone <repository-url>
   cd grpc-app
   ```

2. **Start all services:**
   ```bash
   docker-compose up --build
   ```

3. **Access the application:**
   - Frontend: http://localhost:3000
   - Backend gRPC: localhost:9001
   - Envoy Admin: http://localhost:9901

### Manual Setup

#### Backend Setup

1. **Navigate to backend directory:**
   ```bash
   cd backend
   ```

2. **Install dependencies:**
   ```bash
   composer install
   ```

3. **Generate protobuf files:**
   ```bash
   protoc --php_out=src/ --plugin=protoc-gen-php-grpc=/usr/local/bin/protoc-gen-php-grpc --php-grpc_out=src/ proto/echo.proto
   ```

4. **Run tests:**
   ```bash
   ./vendor/bin/phpunit tests/ --verbose
   ```

5. **Start the server:**
   ```bash
   php src/server.php
   ```

#### Frontend Setup

1. **Navigate to frontend directory:**
   ```bash
   cd frontend
   ```

2. **Install dependencies:**
   ```bash
   npm install
   ```

3. **Run tests:**
   ```bash
   npm test
   ```

4. **Start development server:**
   ```bash
   npm start
   ```

## 🧪 Testing

### Backend Tests

The backend includes comprehensive tests covering:

- **Unit Tests**: EchoService functionality
- **Integration Tests**: PHP 7.3 compatibility and extension requirements
- **Edge Cases**: Empty messages, special characters, unicode, long messages

Run backend tests:
```bash
cd backend
./vendor/bin/phpunit tests/ --verbose
```

### Frontend Tests

The frontend includes React component tests covering:

- **Component Rendering**: UI elements and state
- **User Interactions**: Button clicks, input changes, keyboard events
- **gRPC Communication**: Successful requests and error handling
- **Loading States**: Request progress indicators

Run frontend tests:
```bash
cd frontend
npm test
```

## 🔧 Configuration

### Backend Configuration

- **PHP Version**: 7.3 (Alpine Linux)
- **gRPC Library**: Spiral PHP gRPC v1.4.0
- **Server**: RoadRunner v1.8.2
- **Port**: 9001

### Frontend Configuration

- **React Version**: 17.0.2
- **gRPC-Web**: v1.3.1
- **Protocol Buffers**: v3.19.4
- **Port**: 3000

### Envoy Configuration

- **Version**: v1.21-latest
- **gRPC-Web Port**: 8080
- **Admin Port**: 9901
- **CORS**: Enabled for all origins

## 📁 Project Structure

```
grpc-app/
├── backend/
│   ├── src/
│   │   ├── Myecho/           # Generated protobuf classes
│   │   ├── EchoService.php   # Main service implementation
│   │   └── server.php        # Server entry point
│   ├── proto/
│   │   └── echo.proto        # Protocol buffer definition
│   ├── tests/                # Backend tests
│   ├── composer.json         # PHP dependencies
│   ├── phpunit.xml          # PHPUnit configuration
│   └── Dockerfile           # Backend container
├── frontend/
│   ├── src/
│   │   ├── proto/           # Generated gRPC-Web stubs
│   │   ├── App.js           # Main React component
│   │   └── App.test.js      # Frontend tests
│   ├── package.json         # Node.js dependencies
│   └── Dockerfile          # Frontend container
├── envoy/
│   └── envoy.yaml          # Envoy proxy configuration
├── docker-compose.yaml     # Service orchestration
└── README.md              # This file
```

## 🔍 API Reference

### gRPC Service

**Service**: `myecho.GreeterService`

**Method**: `Ping`

**Request**:
```protobuf
message PingRequest {
  string message = 1;
}
```

**Response**:
```protobuf
message PingResponse {
  string message = 1;
}
```

**Example Usage**:
```javascript
const request = new PingRequest();
request.setMessage("Hello from React!");
client.ping(request, {}, (err, response) => {
  if (err) {
    console.error('Error:', err);
  } else {
    console.log('Response:', response.getMessage());
  }
});
```

## 🐛 Troubleshooting

### Common Issues

1. **Port Conflicts**:
   - Ensure ports 3000, 8080, and 9001 are available
   - Check `docker-compose ps` for running containers

2. **gRPC Connection Issues**:
   - Verify Envoy proxy is running on port 8080
   - Check backend service is accessible on port 9001
   - Review Envoy logs: `docker-compose logs envoy`

3. **PHP 7.3 Compatibility**:
   - The application uses Spiral PHP gRPC for PHP 7.3 support
   - Custom protobuf classes are used instead of generated ones
   - RoadRunner handles the gRPC server implementation

4. **Frontend Build Issues**:
   - Clear node_modules: `rm -rf node_modules && npm install`
   - Check protobuf file generation
   - Verify gRPC-Web client configuration

### Debugging

**View logs for all services:**
```bash
docker-compose logs -f
```

**View logs for specific service:**
```bash
docker-compose logs -f backend
docker-compose logs -f frontend
docker-compose logs -f envoy
```

**Access Envoy admin interface:**
- URL: http://localhost:9901
- View cluster health and configuration

## 🚀 Deployment

### Production Considerations

1. **Security**:
   - Update CORS configuration in Envoy
   - Use environment variables for sensitive data
   - Enable HTTPS/TLS

2. **Performance**:
   - Configure proper resource limits in Docker
   - Use production-optimized PHP settings
   - Enable gRPC compression

3. **Monitoring**:
   - Add health check endpoints
   - Configure logging and metrics
   - Set up monitoring dashboards

## 📝 Development Notes

### PHP 7.3 Workaround

This project demonstrates how to use gRPC with PHP 7.3 by:

1. Using Spiral PHP gRPC library instead of the official gRPC extension
2. Creating custom protobuf message classes compatible with PHP 7.3
3. Using RoadRunner as the gRPC server runtime
4. Implementing proper namespace structure for autoloading

### Key Differences from PHP 8.1

- Manual protobuf class implementation
- Custom service interface definitions
- RoadRunner-based server instead of native gRPC server
- Compatibility layer for older PHP features

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Add tests for new functionality
4. Ensure all tests pass
5. Submit a pull request

## 📄 License

This project is licensed under the MIT License - see the LICENSE file for details.

## 🙏 Acknowledgments

- [Spiral PHP gRPC](https://github.com/spiral/php-grpc) for PHP 7.3 compatibility
- [RoadRunner](https://roadrunner.dev/) for high-performance PHP server
- [Envoy Proxy](https://envoyproxy.io/) for gRPC-Web support
- [gRPC-Web](https://github.com/grpc/grpc-web) for browser compatibility
