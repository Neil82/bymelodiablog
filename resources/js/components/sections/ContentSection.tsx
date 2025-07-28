import React from 'react';
import clsx from 'clsx';

interface ContentSectionProps {
    title: string;
    content: string;
    imagePosition?: 'left' | 'right' | 'top' | 'bottom';
    imageSrc?: string;
    imageAlt?: string;
    className?: string;
}

const ContentSection: React.FC<ContentSectionProps> = ({
    title,
    content,
    imagePosition = 'right',
    imageSrc,
    imageAlt,
    className = ""
}) => {
    const renderImage = () => {
        if (!imageSrc) return null;
        
        return (
            <div className="flex-shrink-0">
                <img
                    src={imageSrc}
                    alt={imageAlt || title}
                    className="w-full h-64 object-cover rounded-lg shadow-lg"
                />
            </div>
        );
    };

    const renderContent = () => (
        <div className="flex-1">
            <h2 className="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                {title}
            </h2>
            <div 
                className="text-gray-600 dark:text-gray-300 text-lg leading-relaxed"
                dangerouslySetInnerHTML={{ __html: content }}
            />
        </div>
    );

    return (
        <section className={`py-12 ${className}`}>
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                {imagePosition === 'top' && (
                    <div className="space-y-8">
                        {renderImage()}
                        {renderContent()}
                    </div>
                )}
                
                {imagePosition === 'bottom' && (
                    <div className="space-y-8">
                        {renderContent()}
                        {renderImage()}
                    </div>
                )}
                
                {(imagePosition === 'left' || imagePosition === 'right') && (
                    <div className={clsx(
                        "flex flex-col lg:flex-row items-center gap-8",
                        imagePosition === 'right' ? 'lg:flex-row' : 'lg:flex-row-reverse'
                    )}>
                        {renderContent()}
                        {renderImage()}
                    </div>
                )}
            </div>
        </section>
    );
};

export default ContentSection;