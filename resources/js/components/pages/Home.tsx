import React from 'react';
import Banner from '../sections/Banner';
import ContentSection from '../sections/ContentSection';

const Home: React.FC = () => {
    return (
        <div className="space-y-8">
            <Banner 
                title="Bienvenido a ByMelodia"
                subtitle="Descubre los temas más juveniles y emocionantes"
                backgroundImage="/images/hero-bg.jpg"
            />
            
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <ContentSection
                    title="Últimos Artículos"
                    content="Aquí encontrarás los artículos más recientes sobre música, cultura juvenil y tendencias actuales."
                    imagePosition="right"
                    imageSrc="/images/content-1.jpg"
                />
                
                <ContentSection
                    title="Tendencias Musicales"
                    content="Mantente al día con las últimas tendencias musicales y descubre nuevos artistas emergentes."
                    imagePosition="left"
                    imageSrc="/images/content-2.jpg"
                />
            </div>
        </div>
    );
};

export default Home;