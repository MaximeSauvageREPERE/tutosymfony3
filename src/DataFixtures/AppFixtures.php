<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Produit;
use App\Entity\Category;
use App\Entity\Tag;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Création des catégories
        $categories = [];
        $categoriesData = [
            ['nom' => 'Électronique', 'desc' => 'Tous les produits électroniques et high-tech'],
            ['nom' => 'Vêtements', 'desc' => 'Mode et accessoires pour tous les styles'],
            ['nom' => 'Alimentation', 'desc' => 'Produits alimentaires de qualité'],
            ['nom' => 'Maison', 'desc' => 'Décoration et équipement pour la maison'],
            ['nom' => 'Sport', 'desc' => 'Équipement et accessoires sportifs'],
            ['nom' => 'Livres', 'desc' => 'Livres, BD et magazines'],
            ['nom' => 'Jouets', 'desc' => 'Jeux et jouets pour petits et grands'],
            ['nom' => 'Beauté', 'desc' => 'Cosmétiques et produits de beauté'],
        ];
        
        foreach ($categoriesData as $data) {
            $category = new Category();
            $category->setNom($data['nom']);
            $category->setDescription($data['desc']);
            $manager->persist($category);
            $categories[] = $category;
        }
        
        // Création des tags
        $tags = [];
        $tagNames = ['Nouveauté', 'Promo', 'Best-seller', 'Écologique', 'Premium', 'Tendance', 'Exclusif', 'Soldes', 'Recommandé', 'Bio'];
        
        foreach ($tagNames as $name) {
            $tag = new Tag();
            $tag->setNom($name);
            $manager->persist($tag);
            $tags[] = $tag;
        }
        
        // Flush pour obtenir les IDs
        $manager->flush();
        
        // Création de 50 produits avec données réalistes
        $produitsData = [
            ['nom' => 'Smartphone Galaxy S24', 'cat' => 0, 'prix' => 899.99, 'desc' => 'Dernier smartphone haut de gamme avec écran AMOLED 6.5" et caméra 108MP', 'tags' => [0, 4]],
            ['nom' => 'Laptop Pro M3', 'cat' => 0, 'prix' => 1499.99, 'desc' => 'Ordinateur portable puissant avec processeur M3 et 16GB RAM', 'tags' => [4, 8]],
            ['nom' => 'Écouteurs Bluetooth Pro', 'cat' => 0, 'prix' => 249.99, 'desc' => 'Écouteurs sans fil avec réduction de bruit active', 'tags' => [0, 2]],
            ['nom' => 'Montre connectée Sport', 'cat' => 4, 'prix' => 299.99, 'desc' => 'Montre intelligente avec suivi GPS et cardiaque', 'tags' => [5, 8]],
            ['nom' => 'Tablette numérique 12"', 'cat' => 0, 'prix' => 649.99, 'desc' => 'Tablette haute résolution parfaite pour le travail et loisirs', 'tags' => [4]],
            ['nom' => 'T-shirt coton bio', 'cat' => 1, 'prix' => 29.99, 'desc' => 'T-shirt confortable en coton 100% biologique', 'tags' => [3, 9]],
            ['nom' => 'Jean slim délavé', 'cat' => 1, 'prix' => 79.99, 'desc' => 'Jean tendance avec coupe slim moderne', 'tags' => [5]],
            ['nom' => 'Robe d\'été florale', 'cat' => 1, 'prix' => 59.99, 'desc' => 'Robe légère et élégante pour l\'été', 'tags' => [0, 5]],
            ['nom' => 'Chaussures running Pro', 'cat' => 4, 'prix' => 129.99, 'desc' => 'Chaussures de course avec amorti maximal', 'tags' => [2, 8]],
            ['nom' => 'Veste en cuir véritable', 'cat' => 1, 'prix' => 299.99, 'desc' => 'Veste en cuir de qualité supérieure', 'tags' => [4, 6]],
            ['nom' => 'Café premium arabica', 'cat' => 2, 'prix' => 15.99, 'desc' => 'Café d\'origine 100% arabica torréfié artisanalement', 'tags' => [4, 9]],
            ['nom' => 'Chocolat noir 70%', 'cat' => 2, 'prix' => 8.99, 'desc' => 'Chocolat noir intense aux notes fruitées', 'tags' => [3, 9]],
            ['nom' => 'Thé vert bio matcha', 'cat' => 2, 'prix' => 19.99, 'desc' => 'Thé vert japonais de qualité cérémoniale', 'tags' => [3, 9]],
            ['nom' => 'Miel artisanal lavande', 'cat' => 2, 'prix' => 12.99, 'desc' => 'Miel de lavande récolté en Provence', 'tags' => [3, 9]],
            ['nom' => 'Huile d\'olive extra vierge', 'cat' => 2, 'prix' => 16.99, 'desc' => 'Huile d\'olive première pression à froid', 'tags' => [4, 9]],
            ['nom' => 'Canapé design 3 places', 'cat' => 3, 'prix' => 899.99, 'desc' => 'Canapé moderne et confortable en tissu premium', 'tags' => [4, 5]],
            ['nom' => 'Lampe LED connectée', 'cat' => 3, 'prix' => 79.99, 'desc' => 'Lampe intelligente avec 16 millions de couleurs', 'tags' => [0, 5]],
            ['nom' => 'Coussin déco velours', 'cat' => 3, 'prix' => 24.99, 'desc' => 'Coussin élégant en velours doux', 'tags' => [5]],
            ['nom' => 'Tapis moderne géométrique', 'cat' => 3, 'prix' => 149.99, 'desc' => 'Tapis au design contemporain', 'tags' => [0, 5]],
            ['nom' => 'Miroir mural rond', 'cat' => 3, 'prix' => 89.99, 'desc' => 'Grand miroir décoratif avec cadre doré', 'tags' => [5]],
            ['nom' => 'Vélo électrique urbain', 'cat' => 4, 'prix' => 1299.99, 'desc' => 'Vélo électrique avec autonomie 80km', 'tags' => [0, 3]],
            ['nom' => 'Tapis de yoga premium', 'cat' => 4, 'prix' => 49.99, 'desc' => 'Tapis antidérapant extra épais', 'tags' => [3, 8]],
            ['nom' => 'Haltères réglables 20kg', 'cat' => 4, 'prix' => 89.99, 'desc' => 'Paire d\'haltères ajustables pour musculation', 'tags' => [2]],
            ['nom' => 'Ballon fitness suisse', 'cat' => 4, 'prix' => 29.99, 'desc' => 'Ballon de gym anti-éclatement 65cm', 'tags' => [8]],
            ['nom' => 'Corde à sauter pro', 'cat' => 4, 'prix' => 19.99, 'desc' => 'Corde à sauter avec compteur intégré', 'tags' => [2]],
            ['nom' => 'Roman best-seller 2024', 'cat' => 5, 'prix' => 22.99, 'desc' => 'Le roman le plus vendu de l\'année', 'tags' => [0, 2]],
            ['nom' => 'BD collector édition limitée', 'cat' => 5, 'prix' => 39.99, 'desc' => 'Bande dessinée en édition collector numérotée', 'tags' => [4, 6]],
            ['nom' => 'Manuel de cuisine gastronomique', 'cat' => 5, 'prix' => 34.99, 'desc' => 'Guide complet de la cuisine française', 'tags' => [8]],
            ['nom' => 'Atlas du monde illustré', 'cat' => 5, 'prix' => 49.99, 'desc' => 'Atlas grand format avec cartes détaillées', 'tags' => [4]],
            ['nom' => 'Livre audio best-seller', 'cat' => 5, 'prix' => 19.99, 'desc' => 'Version audio lue par un comédien professionnel', 'tags' => [0]],
            ['nom' => 'Puzzle 1000 pièces paysage', 'cat' => 6, 'prix' => 24.99, 'desc' => 'Puzzle de qualité représentant un magnifique paysage', 'tags' => []],
            ['nom' => 'Peluche géante ours', 'cat' => 6, 'prix' => 59.99, 'desc' => 'Grande peluche douce de 80cm', 'tags' => [0]],
            ['nom' => 'Jeu de société famille', 'cat' => 6, 'prix' => 34.99, 'desc' => 'Jeu convivial pour toute la famille', 'tags' => [2, 8]],
            ['nom' => 'Console portable gaming', 'cat' => 6, 'prix' => 249.99, 'desc' => 'Console de jeux portable dernière génération', 'tags' => [0, 4]],
            ['nom' => 'Drone avec caméra 4K', 'cat' => 6, 'prix' => 399.99, 'desc' => 'Drone avec stabilisateur et caméra haute définition', 'tags' => [0, 4]],
            ['nom' => 'Crème hydratante visage', 'cat' => 7, 'prix' => 29.99, 'desc' => 'Crème hydratante pour tous types de peau', 'tags' => [3, 9]],
            ['nom' => 'Parfum luxe 100ml', 'cat' => 7, 'prix' => 89.99, 'desc' => 'Eau de parfum aux notes florales', 'tags' => [4, 6]],
            ['nom' => 'Palette maquillage pro', 'cat' => 7, 'prix' => 49.99, 'desc' => 'Palette avec 20 teintes de fards à paupières', 'tags' => [4]],
            ['nom' => 'Brosse cheveux ionique', 'cat' => 7, 'prix' => 39.99, 'desc' => 'Brosse lissante avec technologie ionique', 'tags' => [0, 5]],
            ['nom' => 'Sérum visage anti-âge', 'cat' => 7, 'prix' => 44.99, 'desc' => 'Sérum concentré en actifs anti-âge', 'tags' => [4, 8]],
            ['nom' => 'Sac à dos voyage 40L', 'cat' => 1, 'prix' => 79.99, 'desc' => 'Sac à dos résistant pour les aventuriers', 'tags' => [8]],
            ['nom' => 'Valise cabine rigide', 'cat' => 1, 'prix' => 119.99, 'desc' => 'Valise légère et résistante aux chocs', 'tags' => [2]],
            ['nom' => 'Parapluie automatique', 'cat' => 1, 'prix' => 24.99, 'desc' => 'Parapluie ouverture/fermeture automatique', 'tags' => []],
            ['nom' => 'Lunettes de soleil polarisées', 'cat' => 1, 'prix' => 89.99, 'desc' => 'Lunettes avec verres polarisés UV400', 'tags' => [4, 5]],
            ['nom' => 'Montre classique automatique', 'cat' => 1, 'prix' => 299.99, 'desc' => 'Montre mécanique automatique en acier', 'tags' => [4, 6]],
            ['nom' => 'Clavier mécanique RGB', 'cat' => 0, 'prix' => 129.99, 'desc' => 'Clavier gaming avec switches mécaniques', 'tags' => [2, 5]],
            ['nom' => 'Souris gaming sans fil', 'cat' => 0, 'prix' => 79.99, 'desc' => 'Souris ergonomique avec capteur haute précision', 'tags' => [2, 8]],
            ['nom' => 'Webcam HD 1080p', 'cat' => 0, 'prix' => 69.99, 'desc' => 'Caméra web avec micro intégré', 'tags' => [8]],
            ['nom' => 'Casque VR immersif', 'cat' => 0, 'prix' => 449.99, 'desc' => 'Casque de réalité virtuelle nouvelle génération', 'tags' => [0, 4]],
            ['nom' => 'Enceinte Bluetooth portable', 'cat' => 0, 'prix' => 99.99, 'desc' => 'Enceinte étanche avec son 360°', 'tags' => [2, 8]],
        ];
        
        foreach ($produitsData as $data) {
            $produit = new Produit();
            $produit->setNom($data['nom']);
            $produit->setDescription($data['desc']);
            $produit->setPrix($data['prix']);
            $produit->setCategorie($categories[$data['cat']]);
            
            // Ajouter les tags
            foreach ($data['tags'] as $tagIndex) {
                $produit->addTag($tags[$tagIndex]);
            }
            
            $manager->persist($produit);
        }
        
        $manager->flush();
    }
}
