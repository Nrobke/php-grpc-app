import React, { useState } from 'react';

function App() {
    const [inputText, setInputText] = useState('');
    const [response, setResponse] = useState('');
    const [loading, setLoading] = useState(false);

    const handlePing = async () => {
        if (!inputText.trim()) return;

        setLoading(true);
        
        try {
            const response = await fetch('http://localhost:8080/ping', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ message: inputText })
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            setResponse(data.message);
        } catch (error) {
            console.error('HTTP Error:', error);
            setResponse('Error: ' + error.message);
        } finally {
            setLoading(false);
        }
    };

    return (
        <div style={{ padding: '20px', maxWidth: '600px', margin: '0 auto' }}>
            <h1>gRPC PHP 7.3 + React Demo</h1>
            <p>This demonstrates PHP 7.3 workaround with gRPC</p>
            
            <div style={{ marginBottom: '20px' }}>
                <input
                    type="text"
                    value={inputText}
                    onChange={(e) => setInputText(e.target.value)}
                    placeholder="Enter a message to send to PHP backend"
                    style={{
                        padding: '10px',
                        width: '100%',
                        marginBottom: '10px',
                        fontSize: '16px',
                        border: '1px solid #ddd',
                        borderRadius: '4px'
                    }}
                    onKeyPress={(e) => e.key === 'Enter' && handlePing()}
                />
                <button
                    onClick={handlePing}
                    disabled={loading || !inputText.trim()}
                    style={{
                        padding: '10px 20px',
                        fontSize: '16px',
                        backgroundColor: loading ? '#ccc' : '#007bff',
                        color: 'white',
                        border: 'none',
                        borderRadius: '4px',
                        cursor: loading ? 'not-allowed' : 'pointer',
                        width: '100%'
                    }}
                >
                    {loading ? 'Sending to PHP 7.3 Backend...' : 'Send to PHP 7.3 Backend'}
                </button>
            </div>

            {response && (
                <div style={{
                    padding: '15px',
                    backgroundColor: '#f8f9fa',
                    border: '1px solid #dee2e6',
                    borderRadius: '4px',
                    marginTop: '20px'
                }}>
                    <strong>Response from PHP 7.3 backend:</strong>
                    <p style={{ 
                        margin: '10px 0 0 0', 
                        padding: '10px',
                        backgroundColor: 'white',
                        borderRadius: '4px',
                        fontFamily: 'monospace'
                    }}>{response}</p>
                </div>
            )}
        </div>
    );
}

export default App;