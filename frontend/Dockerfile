# Use Node.js for frontend
FROM node:16-alpine

# Set working directory
WORKDIR /app

# Copy package files and install deps
COPY frontend/package.json frontend/package-lock.json ./
RUN npm install

# Copy frontend source code
COPY frontend/ .

# Build frontend
RUN npm run build

# Expose port (optional if serving directly)
EXPOSE 3000

CMD ["npm", "start"]
