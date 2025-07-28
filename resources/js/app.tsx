import React from 'react';
import { createRoot } from 'react-dom/client';
import SimpleApp from './components/SimpleApp.jsx';
import './bootstrap';

console.log('app.tsx loaded');

const container = document.getElementById('app');
console.log('Container found:', container);

if (container) {
    try {
        const root = createRoot(container);
        root.render(<SimpleApp />);
        console.log('React app rendered');
    } catch (error) {
        console.error('Error rendering React app:', error);
    }
} else {
    console.error('Container #app not found');
}