import React from 'react';
import { ThemeProvider } from './providers/ThemeProvider';
import Layout from './Layout';
import Home from './pages/Home';

const App: React.FC = () => {
    return (
        <ThemeProvider>
            <Layout>
                <Home />
            </Layout>
        </ThemeProvider>
    );
};

export default App;