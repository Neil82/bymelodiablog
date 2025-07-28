import React from 'react';

function SimpleApp() {
    return (
        <div style={{ padding: '20px', fontFamily: 'Arial, sans-serif' }}>
            <h1 style={{ color: '#333' }}>¡ByMelodia funciona!</h1>
            <p>React se está ejecutando correctamente.</p>
            <button 
                onClick={() => alert('¡Hola desde React!')}
                style={{
                    padding: '10px 20px',
                    backgroundColor: '#007bff',
                    color: 'white',
                    border: 'none',
                    borderRadius: '5px',
                    cursor: 'pointer'
                }}
            >
                Hacer clic aquí
            </button>
        </div>
    );
}

export default SimpleApp;