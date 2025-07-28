import React from 'react';

interface BannerProps {
    title: string;
    subtitle?: string;
    backgroundImage?: string;
    className?: string;
}

const Banner: React.FC<BannerProps> = ({ 
    title, 
    subtitle, 
    backgroundImage,
    className = ""
}) => {
    return (
        <section 
            className={`relative bg-gradient-to-r from-blue-600 to-purple-600 dark:from-blue-800 dark:to-purple-800 text-white ${className}`}
            style={{
                backgroundImage: backgroundImage ? `url(${backgroundImage})` : undefined,
                backgroundSize: 'cover',
                backgroundPosition: 'center',
            }}
        >
            <div className="absolute inset-0 bg-black bg-opacity-40"></div>
            <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 text-center">
                <h1 className="text-4xl md:text-6xl font-bold mb-4">
                    {title}
                </h1>
                {subtitle && (
                    <p className="text-xl md:text-2xl opacity-90 max-w-3xl mx-auto">
                        {subtitle}
                    </p>
                )}
            </div>
        </section>
    );
};

export default Banner;