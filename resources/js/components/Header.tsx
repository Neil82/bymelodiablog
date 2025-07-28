import React from 'react';
import { Sun, Moon } from 'lucide-react';
import { useTheme } from './providers/ThemeProvider';

const Header: React.FC = () => {
    const { theme, toggleTheme } = useTheme();

    return (
        <header className="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700 transition-colors duration-300">
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div className="flex justify-between items-center h-16">
                    <div className="flex items-center">
                        <img 
                            src={theme === 'dark' ? '/images/bymelodia_blanco.png' : '/images/bymelodia_negro.png'}
                            alt="ByMelodia" 
                            className="h-8 w-auto"
                        />
                    </div>
                    
                    <nav className="hidden md:flex space-x-8">
                        <a href="#" className="text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                            Inicio
                        </a>
                        <a href="#" className="text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                            Temas
                        </a>
                        <a href="#" className="text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                            Acerca de
                        </a>
                    </nav>

                    <div className="flex items-center space-x-4">
                        <button
                            onClick={toggleTheme}
                            className="p-2 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors"
                            aria-label="Toggle theme"
                        >
                            {theme === 'dark' ? (
                                <Sun className="h-5 w-5 text-yellow-500" />
                            ) : (
                                <Moon className="h-5 w-5 text-gray-700" />
                            )}
                        </button>
                    </div>
                </div>
            </div>
        </header>
    );
};

export default Header;