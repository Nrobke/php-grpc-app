import React from 'react';
import { render, screen, fireEvent, waitFor } from '@testing-library/react';
import '@testing-library/jest-dom';
import App from './App';

// Mock the gRPC client
jest.mock('./proto/echo_grpc_web_pb', () => ({
  GreeterServiceClient: jest.fn().mockImplementation(() => ({
    ping: jest.fn()
  }))
}));

// Mock the protobuf messages
jest.mock('./proto/echo_pb', () => ({
  PingRequest: jest.fn().mockImplementation(() => ({
    setMessage: jest.fn()
  }))
}));

describe('App Component', () => {
  let mockClient;
  let mockPingRequest;

  beforeEach(() => {
    const { GreeterServiceClient } = require('./proto/echo_grpc_web_pb');
    const { PingRequest } = require('./proto/echo_pb');
    
    mockClient = {
      ping: jest.fn()
    };
    mockPingRequest = {
      setMessage: jest.fn()
    };
    
    GreeterServiceClient.mockImplementation(() => mockClient);
    PingRequest.mockImplementation(() => mockPingRequest);
  });

  afterEach(() => {
    jest.clearAllMocks();
  });

  test('renders the main heading', () => {
    render(<App />);
    expect(screen.getByText('gRPC PHP 7.3 + React Demo')).toBeInTheDocument();
  });

  test('renders the description', () => {
    render(<App />);
    expect(screen.getByText('This demonstrates PHP 7.3 workaround with gRPC')).toBeInTheDocument();
  });

  test('renders input field and button', () => {
    render(<App />);
    expect(screen.getByPlaceholderText('Enter a message to send to PHP backend')).toBeInTheDocument();
    expect(screen.getByText('Send to PHP 7.3 Backend')).toBeInTheDocument();
  });

  test('button is disabled when input is empty', () => {
    render(<App />);
    const button = screen.getByText('Send to PHP 7.3 Backend');
    expect(button).toBeDisabled();
  });

  test('button is enabled when input has text', () => {
    render(<App />);
    const input = screen.getByPlaceholderText('Enter a message to send to PHP backend');
    const button = screen.getByText('Send to PHP 7.3 Backend');
    
    fireEvent.change(input, { target: { value: 'test message' } });
    expect(button).not.toBeDisabled();
  });

  test('sends message to backend when button is clicked', async () => {
    const mockResponse = {
      getMessage: () => 'PHP 7.3 Server received: test message'
    };
    
    mockClient.ping.mockImplementation((request, metadata, callback) => {
      callback(null, mockResponse);
    });

    render(<App />);
    
    const input = screen.getByPlaceholderText('Enter a message to send to PHP backend');
    const button = screen.getByText('Send to PHP 7.3 Backend');
    
    fireEvent.change(input, { target: { value: 'test message' } });
    fireEvent.click(button);

    await waitFor(() => {
      expect(mockClient.ping).toHaveBeenCalled();
      expect(screen.getByText('PHP 7.3 Server received: test message')).toBeInTheDocument();
    });
  });

  test('handles gRPC errors gracefully', async () => {
    const mockError = new Error('gRPC connection failed');
    
    mockClient.ping.mockImplementation((request, metadata, callback) => {
      callback(mockError, null);
    });

    render(<App />);
    
    const input = screen.getByPlaceholderText('Enter a message to send to PHP backend');
    const button = screen.getByText('Send to PHP 7.3 Backend');
    
    fireEvent.change(input, { target: { value: 'test message' } });
    fireEvent.click(button);

    await waitFor(() => {
      expect(screen.getByText('Error: gRPC connection failed')).toBeInTheDocument();
    });
  });

  test('shows loading state during request', async () => {
    let resolveCallback;
    const promise = new Promise((resolve) => {
      resolveCallback = resolve;
    });
    
    mockClient.ping.mockImplementation((request, metadata, callback) => {
      promise.then(() => {
        callback(null, { getMessage: () => 'response' });
      });
    });

    render(<App />);
    
    const input = screen.getByPlaceholderText('Enter a message to send to PHP backend');
    const button = screen.getByText('Send to PHP 7.3 Backend');
    
    fireEvent.change(input, { target: { value: 'test message' } });
    fireEvent.click(button);

    expect(screen.getByText('Sending to PHP 7.3 Backend...')).toBeInTheDocument();
    expect(button).toBeDisabled();

    resolveCallback();
    
    await waitFor(() => {
      expect(screen.getByText('Send to PHP 7.3 Backend')).toBeInTheDocument();
      expect(button).not.toBeDisabled();
    });
  });

  test('sends message when Enter key is pressed', async () => {
    const mockResponse = {
      getMessage: () => 'PHP 7.3 Server received: test message'
    };
    
    mockClient.ping.mockImplementation((request, metadata, callback) => {
      callback(null, mockResponse);
    });

    render(<App />);
    
    const input = screen.getByPlaceholderText('Enter a message to send to PHP backend');
    
    fireEvent.change(input, { target: { value: 'test message' } });
    fireEvent.keyPress(input, { key: 'Enter', code: 'Enter' });

    await waitFor(() => {
      expect(mockClient.ping).toHaveBeenCalled();
      expect(screen.getByText('PHP 7.3 Server received: test message')).toBeInTheDocument();
    });
  });

  test('does not send empty messages', () => {
    render(<App />);
    
    const input = screen.getByPlaceholderText('Enter a message to send to PHP backend');
    const button = screen.getByText('Send to PHP 7.3 Backend');
    
    fireEvent.change(input, { target: { value: '   ' } });
    fireEvent.click(button);

    expect(mockClient.ping).not.toHaveBeenCalled();
  });
});
