# Gestion de Projets Collaboratifs

🚀 Une application Laravel permettant aux utilisateurs de gérer des projets en équipe, d'ajouter des tâches et de collaborer efficacement.

## 📌 Fonctionnalités

- **Tableau de bord** : Vue personnalisée des projets et tâches.
- **Gestion des projets** : Création, mise à jour et attribution de rôles.
- **Gestion des tâches** : Suivi des tâches avec statut et assignation.
- **Authentification** : Inscription et connexion sécurisées.
- **Notifications** : Alertes par e-mail pour les tâches assignées.
- **Gestion des fichiers** : Upload et téléchargement des documents liés aux tâches.

## 🛠️ Installation

### Prérequis
- PHP 8+
- Composer
- MySQL
- Laravel 10+
- Un serveur local (ex : Laravel Sail, XAMPP, WAMP)

### Étapes d'installation

1. **Cloner le projet**
   ```bash
   git clone https://github.com/ebenezer-ngblogni/GestionProjet.git
   cd GestionProjet
   ```
2. **Installer les dépendances**
   ```bash
   composer install
   ```
3. **Configurer l'environnement**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   Configurez `.env` avec votre base de données.
4. **Exécuter les migrations**
   ```bash
   php artisan migrate
   ```
5. **Démarrer le serveur**
   ```bash
   php artisan serve
   ```
6. **Accéder à l'application**
   Ouvrez [http://127.0.0.1:8000](http://127.0.0.1:8000)

## 🎨 Utilisation de Tailwind CSS (via CDN)

Tailwind CSS est intégré via CDN. Aucune installation supplémentaire n'est requise.

## 📧 Contact

Si vous avez des questions, contactez-moi à [ebenezerngblogni@gmail.com](mailto:ebenezerngblogni@gmail.com).

