import React from 'react';
import { createRoot } from 'react-dom/client';
import './bootstrap';

function App() {
    const [theme, setTheme] = React.useState('light');

    const toggleTheme = () => {
        const newTheme = theme === 'light' ? 'dark' : 'light';
        setTheme(newTheme);
        document.body.style.backgroundColor = newTheme === 'dark' ? '#1a1a1a' : '#ffffff';
        document.body.style.color = newTheme === 'dark' ? '#ffffff' : '#333333';
    };

    return (
        <div style={{ padding: '20px', fontFamily: 'Arial, sans-serif' }}>
            <header style={{ borderBottom: '1px solid #eee', paddingBottom: '20px', marginBottom: '20px' }}>
                <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                    <h1 style={{ margin: 0, color: theme === 'dark' ? '#fff' : '#333' }}>ByMelodia</h1>
                    <button 
                        onClick={toggleTheme}
                        style={{
                            padding: '8px 16px',
                            backgroundColor: theme === 'dark' ? '#333' : '#f8f9fa',
                            color: theme === 'dark' ? '#fff' : '#333',
                            border: '1px solid ' + (theme === 'dark' ? '#555' : '#ddd'),
                            borderRadius: '5px',
                            cursor: 'pointer'
                        }}
                    >
                        {theme === 'dark' ? '☀️ Modo Claro' : '🌙 Modo Oscuro'}
                    </button>
                </div>
            </header>

            <main>
                <div style={{ 
                    background: theme === 'dark' ? '#2a2a2a' : '#f8f9fa', 
                    padding: '40px', 
                    borderRadius: '8px', 
                    textAlign: 'center',
                    marginBottom: '30px'
                }}>
                    <h2 style={{ 
                        fontSize: '2.5rem', 
                        margin: '0 0 20px 0',
                        color: theme === 'dark' ? '#fff' : '#333'
                    }}>
                        Bienvenido a ByMelodia
                    </h2>
                    <p style={{ 
                        fontSize: '1.2rem', 
                        margin: 0,
                        color: theme === 'dark' ? '#ccc' : '#666'
                    }}>
                        Descubre los temas más juveniles y emocionantes
                    </p>
                </div>

                <div style={{ display: 'grid', gap: '20px', gridTemplateColumns: 'repeat(auto-fit, minmax(300px, 1fr))' }}>
                    <div style={{ 
                        background: theme === 'dark' ? '#2a2a2a' : '#fff', 
                        padding: '20px', 
                        borderRadius: '8px',
                        border: '1px solid ' + (theme === 'dark' ? '#444' : '#eee')
                    }}>
                        <h3 style={{ color: theme === 'dark' ? '#fff' : '#333' }}>Últimos Artículos</h3>
                        <p style={{ color: theme === 'dark' ? '#ccc' : '#666' }}>
                            Aquí encontrarás los artículos más recientes sobre música, cultura juvenil y tendencias actuales.
                        </p>
                    </div>

                    <div style={{ 
                        background: theme === 'dark' ? '#2a2a2a' : '#fff', 
                        padding: '20px', 
                        borderRadius: '8px',
                        border: '1px solid ' + (theme === 'dark' ? '#444' : '#eee')
                    }}>
                        <h3 style={{ color: theme === 'dark' ? '#fff' : '#333' }}>Tendencias Musicales</h3>
                        <p style={{ color: theme === 'dark' ? '#ccc' : '#666' }}>
                            Mantente al día con las últimas tendencias musicales y descubre nuevos artistas emergentes.
                        </p>
                    </div>
                </div>
            </main>
        </div>
    );
}

console.log('React app loading...');

const container = document.getElementById('app');
if (container) {
    const root = createRoot(container);
    root.render(<App />);
    console.log('React app loaded successfully!');
}