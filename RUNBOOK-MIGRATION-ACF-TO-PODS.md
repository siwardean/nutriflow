# Runbook : Migration ACF vers Pods - Nutriflow

## 📋 Table des matières

1. [Vue d'ensemble](#vue-densemble)
2. [Prérequis](#prérequis)
3. [Phase 1 : Préparation](#phase-1--préparation)
4. [Phase 2 : Installation et configuration de Pods](#phase-2--installation-et-configuration-de-pods)
5. [Phase 3 : Création des champs Pods](#phase-3--création-des-champs-pods)
6. [Phase 4 : Migration des données](#phase-4--migration-des-données)
7. [Phase 5 : Vérification et tests](#phase-5--vérification-et-tests)
8. [Phase 6 : Finalisation](#phase-6--finalisation)
9. [Dépannage](#dépannage)
10. [Rollback (Retour en arrière)](#rollback-retour-en-arrière)

---

## Vue d'ensemble

Ce runbook décrit la procédure complète pour migrer le thème Nutriflow d'**Advanced Custom Fields (ACF)** vers **Pods - Custom Content Types and Fields**.

### Objectifs de la migration

- ✅ Remplacer ACF par Pods pour la gestion des champs personnalisés
- ✅ Conserver toutes les données existantes
- ✅ Maintenir la compatibilité avec les templates existants
- ✅ Pré-remplir automatiquement tous les champs avec le contenu actuel
- ✅ Support Git-friendly avec configuration par environnement

### Temps estimé

- **Préparation** : 15 minutes
- **Installation Pods** : 5 minutes
- **Création des champs** : 2-5 minutes (automatique)
- **Migration des données** : 5-10 minutes
- **Vérification** : 10-15 minutes
- **Total** : ~40-50 minutes

### Configuration Git-friendly

Le thème utilise un système de flags permettant de basculer facilement entre ACF et Pods selon l'environnement :

- **Fichiers de configuration locaux** (non versionnés) :
  - `wp-config.local.php` - Configuration WordPress locale
  - `.env` - Variables d'environnement
  
- **Méthodes de configuration** (par ordre de priorité) :
  1. Constante `NUTRIFLOW_FIELD_SYSTEM` dans `wp-config.php` ou `wp-config.local.php`
  2. Variable d'environnement `NUTRIFLOW_FIELD_SYSTEM` (via `.env` ou serveur)
  3. Option base de données `nutriflow_field_system`
  4. Par défaut : `'pods'`

---

## Prérequis

### Vérifications préalables

Avant de commencer, vérifiez que :

- [ ] Vous avez un **accès administrateur** à WordPress
- [ ] Vous avez accès au **code du thème** (via FTP, SSH, ou gestionnaire de fichiers)
- [ ] **WordPress** est à jour (version 6.0+)
- [ ] Le site fonctionne correctement avec ACF
- [ ] Vous avez effectué une **sauvegarde complète** du site (voir Phase 1)

### Logiciels/Extensions nécessaires

- WordPress 6.0 ou supérieur
- Pods - Custom Content Types and Fields (sera installé)
- PHP 7.4 ou supérieur

---

## Phase 1 : Préparation

### Étape 1.1 : Sauvegarde complète du site

**⚠️ IMPORTANT : Ne jamais sauter cette étape !**

#### Option A : Via le gestionnaire Local/Staging

1. Arrêtez le site Local si nécessaire
2. Exportez une sauvegarde complète :
   - Copiez le dossier `wp-content` entier
   - Exportez la base de données via phpMyAdmin ou l'interface Local

#### Option B : Via un plugin de sauvegarde

1. Installez un plugin de sauvegarde (UpdraftPlus, BackWPup, etc.)
2. Effectuez une sauvegarde complète incluant :
   - Base de données
   - Fichiers du thème
   - Uploads/media

#### Option C : Sauvegarde manuelle

```bash
# Sauvegarde des fichiers
cp -r wp-content/themes/nutriflow wp-content/themes/nutriflow-backup-$(date +%Y%m%d)

# Export de la base de données (exemple)
mysqldump -u username -p database_name > backup-$(date +%Y%m%d).sql
```

**Vérification** : Assurez-vous que la sauvegarde contient bien :
- ✅ Le dossier du thème `nutriflow`
- ✅ La base de données complète
- ✅ Les fichiers media (images, etc.)

### Étape 1.2 : Documenter l'état actuel

Créez un document listant :

1. **Pages concernées** :
   - Page d'accueil (Front Page)
   - Page Accompagnement
   - Page À propos
   - Page Contact

2. **Champs ACF actuellement utilisés** :
   - Notez les valeurs importantes (copiez quelques valeurs clés pour référence)

3. **Extensions actives** :
   - Notez toutes les extensions actives dans WordPress
   - Notez si ACF Pro est installé (ou ACF Free)

### Étape 1.3 : Vérifier le code actuel

1. Ouvrez `wp-content/themes/nutriflow/inc/pods-config.php`
2. Vérifiez que la ligne suivante est **décommentée** (sans `//`) :
   ```php
   add_action( 'pods_init', 'nutriflow_setup_pods_fields', 99 );
   ```
   Cette ligne doit être active pour créer les champs Pods automatiquement.

3. Vérifiez que les helpers Pods sont bien inclus dans `functions.php` :
   ```php
   require_once get_template_directory() . '/inc/pods-helpers.php';
   require_once get_template_directory() . '/inc/pods-config.php';
   require_once get_template_directory() . '/inc/pods-migration-data.php';
   ```

4. **Configuration Git-friendly** : Configurez votre environnement local
   
   **Option A : Via wp-config.local.php (Recommandé)**
   
   Créez ou modifiez `wp-config.local.php` (ou ajoutez à votre `wp-config.php`) :
   ```php
   // Pour utiliser Pods (développement)
   define( 'NUTRIFLOW_FIELD_SYSTEM', 'pods' );
   
   // OU pour forcer ACF (rollback)
   // define( 'NUTRIFLOW_FIELD_SYSTEM', 'acf' );
   ```
   
   **Note** : `wp-config.local.php` est dans `.gitignore` et ne sera pas commité.
   
   **Option B : Via .env (Alternative)**
   
   Créez un fichier `.env` dans le thème :
   ```
   NUTRIFLOW_FIELD_SYSTEM=pods
   ```
   
   **Note** : `.env` est dans `.gitignore` et ne sera pas commité.

### ✅ Point de contrôle Phase 1

- [ ] Sauvegarde complète effectuée et vérifiée
- [ ] État actuel documenté
- [ ] Code vérifié et prêt
- [ ] Accès admin WordPress confirmé

**→ Si toutes les cases sont cochées, passez à la Phase 2**

---

## Phase 2 : Installation et configuration de Pods

### Étape 2.1 : Installer Pods

1. Connectez-vous à **WordPress Admin**
2. Allez dans **Extensions** > **Ajouter**
3. Recherchez **"Pods - Custom Content Types and Fields"**
4. Cliquez sur **Installer maintenant**
5. Cliquez sur **Activer**

**Alternative** : Installation manuelle

1. Téléchargez Pods depuis : https://wordpress.org/plugins/pods/
2. Uploadez le dossier dans `wp-content/plugins/`
3. Activez l'extension dans **Extensions** > **Extensions installées**

### Étape 2.2 : Configurer Pods pour le type "Page"

1. Dans WordPress Admin, allez dans **Pods Admin** (menu latéral gauche)
2. Cliquez sur **Extend Existing**
3. Cherchez **"Page"** dans la liste
4. Si **"Page"** n'existe pas :
   - Cliquez sur **"Add New"** ou **"Extend"**
   - Sélectionnez **"Post Type"** comme type
   - Choisissez **"Page"** dans la liste déroulante
   - Cliquez sur **"Continue"** puis **"Finish Setup"**
5. Si **"Page"** existe déjà : cliquez dessus pour l'ouvrir

**Note** : À ce stade, le Pod "Page" peut être vide. C'est normal, les champs seront créés automatiquement à l'étape suivante.

### Étape 2.3 : Activer la création automatique des champs

1. Ouvrez le fichier `wp-content/themes/nutriflow/inc/pods-config.php`
2. Cherchez la ligne (vers la ligne 871) :
   ```php
   // add_action( 'pods_init', 'nutriflow_setup_pods_fields', 99 );
   ```
3. **Supprimez les deux `//`** pour décommenter :
   ```php
   add_action( 'pods_init', 'nutriflow_setup_pods_fields', 99 );
   ```
4. Sauvegardez le fichier

**Méthode alternative** (si vous n'avez pas accès au code) :

1. Dans WordPress Admin, allez dans **Apparence** > **Éditeur de thème**
2. Sélectionnez **inc/pods-config.php** dans la liste
3. Décommentez la ligne comme ci-dessus
4. Cliquez sur **Mettre à jour le fichier**

### ✅ Point de contrôle Phase 2

- [ ] Pods installé et activé
- [ ] Pod "Page" créé/extendu dans Pods Admin
- [ ] Ligne `add_action` décommentée dans `pods-config.php`
- [ ] Fichier sauvegardé

**→ Si toutes les cases sont cochées, passez à la Phase 3**

---

## Phase 3 : Création des champs Pods

### Étape 3.1 : Déclencher la création automatique

Les champs seront créés automatiquement lors du prochain chargement d'une page admin.

1. Dans WordPress Admin, **rechargez n'importe quelle page** (par exemple, allez sur **Pages** > **Toutes les pages**)
2. Attendez **5-10 secondes** pour que la création se termine
3. Vous pouvez voir des messages dans la console PHP si `WP_DEBUG` est activé

### Étape 3.2 : Vérifier la création des champs

1. Allez dans **Pods Admin** > **Extend Existing** > **Page**
2. Cliquez sur **"Page"** pour ouvrir sa configuration
3. Vous devriez voir plusieurs **Groupes** créés :

   **Pour la Page d'accueil :**
   - `homepage_hero` - Section Hero
   - `homepage_about` - Section À propos
   - `homepage_consult` - Section Consultation
   - `homepage_services` - Section Services
   - `homepage_testimonials` - Section Témoignages

   **Pour la Page Accompagnement :**
   - `accompagnement_hero` - Section Hero
   - `accompagnement_pricing` - Section Tarifs
   - `accompagnement_sportif` - Section Sportif
   - `accompagnement_location` - Section Localisation

   **Pour la Page À propos :**
   - `apropos_intro` - Section Introduction
   - `apropos_story` - Section Parcours

   **Pour la Page Contact :**
   - `contact` - Contenu Contact

4. Cliquez sur chaque groupe pour voir les champs qu'il contient

### Étape 3.3 : Vérifier dans l'éditeur de page

1. Allez dans **Pages** > **Toutes les pages**
2. Ouvrez la **Page d'accueil**
3. Faites défiler vers le bas de l'éditeur
4. Vous devriez voir des **meta boxes Pods** apparaître avec les champs
5. **Important** : À ce stade, les champs sont probablement **vides** (c'est normal, on va les remplir à l'étape suivante)

### ✅ Point de contrôle Phase 3

- [ ] Champs Pods créés automatiquement
- [ ] Groupes visibles dans Pods Admin > Extend Existing > Page
- [ ] Meta boxes visibles dans l'éditeur de page
- [ ] Aucune erreur PHP dans les logs

**→ Si toutes les cases sont cochées, passez à la Phase 4**

---

## Phase 4 : Migration des données

### Étape 4.1 : Accéder à l'outil de migration

Le thème inclut un outil de pré-remplissage automatique accessible via le menu WordPress.

1. Dans WordPress Admin, allez dans **Outils** > **Migration Pods Nutriflow**
2. Vous verrez une page avec des informations sur la migration

### Étape 4.2 : Identifier les pages à migrer

L'outil liste toutes les pages concernées. Identifiez les IDs des pages :

1. Allez dans **Pages** > **Toutes les pages**
2. Notez les IDs des pages suivantes (passez la souris sur le titre pour voir l'ID dans l'URL) :
   - Page d'accueil (Front Page) - généralement ID 7 ou similaire
   - Page Accompagnement
   - Page À propos
   - Page Contact

### Étape 4.3 : Exécuter la migration automatique

1. Retournez dans **Outils** > **Migration Pods Nutriflow**
2. Cliquez sur le bouton **"Pré-remplir tous les champs Pods"** (ou similaire)
3. L'outil va :
   - Détecter les pages concernées
   - Pré-remplir tous les champs avec le contenu par défaut du thème
   - Si des données ACF existent, elles seront également copiées

**Note** : Si le bouton n'existe pas, vous pouvez exécuter la migration manuellement :

1. Ouvrez `wp-content/themes/nutriflow/inc/pods-migration-data.php`
2. Vérifiez la fonction `nutriflow_prefill_pods_fields()`
3. Vous pouvez l'appeler manuellement via le code ou via un hook WordPress

### Étape 4.4 : Vérifier le pré-remplissage

Pour chaque page, vérifiez que les champs sont pré-remplis :

1. **Page d'accueil** :
   - Ouvrez la page dans l'éditeur
   - Vérifiez les meta boxes Pods en bas
   - Les champs devraient contenir le contenu par défaut

2. **Page Accompagnement** :
   - Même procédure
   - Vérifiez notamment les cartes hero, les tarifs, et le contenu sportif

3. **Page À propos** :
   - Vérifiez les sections introduction et parcours

4. **Page Contact** :
   - Vérifiez les informations de contact

### Étape 4.5 : Migration manuelle (si nécessaire)

Si certaines données ACF n'ont pas été migrées automatiquement :

1. Ouvrez la page dans l'éditeur WordPress
2. Faites défiler vers le bas
3. **Copiez** le contenu des champs ACF (s'ils sont encore visibles)
4. **Collez** dans les champs Pods correspondants
5. **Sauvegardez** la page

**Mapping des champs** (les noms sont identiques entre ACF et Pods) :
- `homepage_hero_title` → `homepage_hero_title`
- `homepage_about_title` → `homepage_about_title`
- etc.

### ✅ Point de contrôle Phase 4

- [ ] Migration automatique exécutée
- [ ] Tous les champs pré-remplis avec le contenu par défaut
- [ ] Données ACF copiées manuellement si nécessaire
- [ ] Toutes les pages vérifiées

**→ Si toutes les cases sont cochées, passez à la Phase 5**

---

## Phase 5 : Vérification et tests

### Étape 5.1 : Test du site front-end

1. **Visitez la page d'accueil** du site
2. Vérifiez que tous les contenus s'affichent correctement :
   - Hero section avec titre, sous-titre, description
   - Section À propos
   - Section Consultation
   - Section Services (4 services)
   - Section Témoignages

3. **Visitez la page Accompagnement**
   - Vérifiez les cartes hero (4 cartes)
   - Vérifiez les tarifs (3 cartes de tarifs)
   - Vérifiez la section Sportif
   - Vérifiez la section Localisation

4. **Visitez la page À propos**
   - Vérifiez l'introduction
   - Vérifiez la galerie d'images
   - Vérifiez le parcours
   - Vérifiez les formations
   - Vérifiez la section sport

5. **Visitez la page Contact**
   - Vérifiez toutes les informations

### Étape 5.2 : Test de l'édition via Pods

1. **Éditez un champ** via Pods :
   - Ouvrez la Page d'accueil dans l'éditeur
   - Modifiez le champ `homepage_hero_title`
   - Sauvegardez la page

2. **Vérifiez sur le front-end** :
   - Visitez la page d'accueil
   - Le titre devrait être modifié

3. **Répétez pour d'autres champs** sur différentes pages

### Étape 5.3 : Vérifier la compatibilité des templates

Les templates PHP utilisent la fonction `nutriflow_get_field()` qui est compatible avec ACF et Pods.

Vérifiez que :

1. Les templates chargent correctement :
   - `front-page.php`
   - `page-accompagnement.php`
   - `page-a-propos.php`
   - `page-contact.php`

2. Aucune erreur PHP dans les logs :
   - Vérifiez `wp-content/debug.log` si `WP_DEBUG` est activé

### Étape 5.4 : Test de la visibilité conditionnelle

Le système filtre automatiquement les champs Pods selon la page éditée.

1. Ouvrez la **Page d'accueil** dans l'éditeur
   - Vous devriez voir UNIQUEMENT les groupes : `homepage_hero`, `homepage_about`, etc.
   - Vous ne devriez PAS voir les groupes `accompagnement_*`, `apropos_*`, etc.

2. Ouvrez la **Page Accompagnement** dans l'éditeur
   - Vous devriez voir UNIQUEMENT les groupes : `accompagnement_hero`, `accompagnement_pricing`, etc.

3. Vérifiez pour toutes les pages

### ✅ Point de contrôle Phase 5

- [ ] Site front-end fonctionne correctement
- [ ] Tous les contenus s'affichent
- [ ] Édition via Pods fonctionne
- [ ] Aucune erreur PHP
- [ ] Visibilité conditionnelle des champs fonctionne

**→ Si toutes les cases sont cochées, passez à la Phase 6**

---

## Phase 6 : Finalisation

### Étape 6.1 : Désactiver ACF (Optionnel)

**⚠️ ATTENTION** : Ne désactivez ACF QUE si vous êtes absolument sûr que tout fonctionne avec Pods.

1. **Vérifiez une dernière fois** que toutes les données sont bien dans Pods
2. Allez dans **Extensions** > **Extensions installées**
3. Trouvez **"Advanced Custom Fields"**
4. Cliquez sur **Désactiver** (pas "Supprimer" pour l'instant)
5. **Testez à nouveau** le site front-end
6. Si tout fonctionne, vous pouvez éventuellement **Supprimer** ACF plus tard

**Recommandation** : Gardez ACF désactivé pendant quelques jours pour vous assurer que tout fonctionne bien avant de le supprimer complètement.

### Étape 6.2 : Nettoyer les données ACF (Optionnel)

Si vous voulez supprimer les anciennes données ACF de la base de données :

**⚠️ DANGER** : Cette opération est irréversible. Faites une nouvelle sauvegarde avant.

1. **Exportez une nouvelle sauvegarde** complète
2. Utilisez un plugin de nettoyage de base de données (ex: Advanced Database Cleaner)
3. OU exécutez manuellement des requêtes SQL pour supprimer les meta ACF :
   ```sql
   DELETE FROM wp_postmeta WHERE meta_key LIKE '_%field_%' OR meta_key LIKE 'field_%';
   ```
   (Adaptez le préfixe de table si nécessaire)

**Note** : Cette étape n'est pas nécessaire si vous gardez ACF désactivé.

### Étape 6.3 : Documentation finale

Documentez ce qui a été fait :

1. **Date de migration** : ___________
2. **Pages migrées** :
   - [ ] Page d'accueil
   - [ ] Page Accompagnement
   - [ ] Page À propos
   - [ ] Page Contact

3. **Problèmes rencontrés** : ___________

4. **Actions à suivre** : ___________

### Étape 6.4 : Formation de l'équipe

Si d'autres personnes utilisent le site :

1. Expliquez que les champs sont maintenant dans **Pods** et non plus dans **ACF**
2. Montrez comment éditer les pages via Pods
3. Expliquez la visibilité conditionnelle (champs différents selon la page)

### ✅ Point de contrôle Phase 6

- [ ] ACF désactivé (optionnel mais recommandé)
- [ ] Site fonctionne toujours correctement
- [ ] Documentation mise à jour
- [ ] Équipe formée si nécessaire

**→ Migration terminée ! 🎉**

---

## Dépannage

### Problème : Les champs Pods ne se créent pas

**Symptômes** : Aucun champ n'apparaît dans Pods Admin ou dans l'éditeur.

**Solutions** :

1. **Vérifiez que Pods est activé** :
   - Extensions > Vérifiez que "Pods" est bien activé

2. **Vérifiez que la ligne est décommentée** :
   - Dans `inc/pods-config.php`, ligne 871 doit être :
     ```php
     add_action( 'pods_init', 'nutriflow_setup_pods_fields', 99 );
     ```
     (sans `//`)

3. **Rechargez une page admin** :
   - Allez sur Pages > Toutes les pages
   - Attendez quelques secondes

4. **Vérifiez les logs PHP** :
   - Activez `WP_DEBUG` dans `wp-config.php`
   - Vérifiez `wp-content/debug.log`

5. **Vérifiez que le Pod "Page" existe** :
   - Pods Admin > Extend Existing > "Page" doit exister

### Problème : Erreur "field name is reserved"

**Symptômes** : Erreur PHP lors de la création des champs.

**Solutions** :

1. Les champs réservés sont automatiquement ignorés
2. Vérifiez `wp-content/debug.log` pour voir quels champs ont été ignorés
3. Contactez le développeur si un champ important est manquant

### Problème : Les données ne sont pas migrées

**Symptômes** : Les champs Pods sont vides après la migration.

**Solutions** :

1. **Exécutez à nouveau la migration** :
   - Outils > Migration Pods Nutriflow > Pré-remplir

2. **Vérifiez que les pages existent** :
   - Pages > Toutes les pages
   - Notez les IDs des pages

3. **Migration manuelle** :
   - Copiez manuellement depuis ACF vers Pods (voir Phase 4, Étape 4.5)

### Problème : Les champs s'affichent sur toutes les pages

**Symptômes** : En éditant une page, vous voyez tous les champs de toutes les pages.

**Solutions** :

1. **Vérifiez le filtre dans `functions.php`** :
   - La fonction `nutriflow_filter_pods_groups_by_page_template` doit être présente
   - Elle doit être hookée à `pods_meta_groups_get`

2. **Vérifiez le template de page** :
   - Dans l'éditeur de page, vérifiez que le bon template est sélectionné
   - Vérifiez dans Pods Admin que les préfixes de champs sont corrects

3. **Videz le cache** :
   - Si vous utilisez un cache, videz-le
   - Rechargez la page d'édition

### Problème : Le site front-end ne s'affiche plus

**Symptômes** : Page blanche ou erreur PHP sur le front-end.

**Solutions** :

1. **Vérifiez les logs PHP** :
   - `wp-content/debug.log`
   - Identifiez l'erreur

2. **Vérifiez que `nutriflow_get_field()` existe** :
   - Le fichier `inc/pods-helpers.php` doit être inclus dans `functions.php`

3. **Re-commentez temporairement la ligne dans `pods-config.php`** :
   ```php
   // add_action( 'pods_init', 'nutriflow_setup_pods_fields', 99 );
   ```
   Cela désactivera la création automatique des champs

4. **Réactivez ACF** temporairement si nécessaire

### Problème : Les images ne s'affichent plus

**Symptômes** : Les champs d'images sont vides ou affichent des erreurs.

**Solutions** :

1. **Vérifiez que les images existent toujours** :
   - Médias > Bibliothèque
   - Vérifiez que les images sont présentes

2. **Re-uploadez les images via Pods** :
   - Ouvrez la page dans l'éditeur
   - Cliquez sur le champ image dans Pods
   - Uploadez ou sélectionnez l'image

3. **Si les images venaient d'ACF** :
   - Les images ACF sont stockées dans `wp_postmeta`
   - Elles devraient être automatiquement détectées si les IDs sont conservés

---

## Rollback (Retour en arrière)

Si vous devez revenir en arrière après la migration, plusieurs options sont disponibles :

### ⚡ Option 1 : Rollback rapide (Recommandé - Garde les deux systèmes)

**Temps estimé** : 2 minutes

Cette méthode force l'utilisation d'ACF tout en gardant Pods disponible pour une migration ultérieure.

#### Méthode A : Via wp-config.local.php (Recommandé - Git-friendly)

1. **Ouvrez ou créez `wp-config.local.php`** dans la racine WordPress
   - Ce fichier est dans `.gitignore` et ne sera pas commité
   - Si vous n'avez pas ce fichier, ajoutez-le à votre `wp-config.php` :
     ```php
     // Charger la config locale si elle existe
     if ( file_exists( __DIR__ . '/wp-config.local.php' ) ) {
         require_once __DIR__ . '/wp-config.local.php';
     }
     ```

2. **Ajoutez cette ligne** dans `wp-config.local.php` :
   ```php
   <?php
   // Force l'utilisation d'ACF au lieu de Pods (rollback)
   define( 'NUTRIFLOW_FIELD_SYSTEM', 'acf' );
   ```

3. **Sauvegardez** le fichier
4. **Réactivez ACF** si nécessaire :
   - Extensions > Activer "Advanced Custom Fields"
5. **Testez le site** : Le site devrait maintenant utiliser ACF

**Pour revenir à Pods plus tard** : Modifiez `wp-config.local.php` :
```php
// Utiliser Pods
define( 'NUTRIFLOW_FIELD_SYSTEM', 'pods' );
// OU supprimez/commentez la ligne pour utiliser Pods par défaut
```

#### Méthode B : Via wp-config.php (Alternative)

1. **Ouvrez `wp-config.php`** à la racine de WordPress
2. **Ajoutez cette ligne** juste avant `/* That's all, stop editing! */` :
   ```php
   // Force l'utilisation d'ACF au lieu de Pods (rollback)
   define( 'NUTRIFLOW_FIELD_SYSTEM', 'acf' );
   ```
3. **Sauvegardez** le fichier
4. **Réactivez ACF** si nécessaire

**Note** : Si vous utilisez Git, cette modification sera commitée. Préférez `wp-config.local.php` pour une configuration locale.

#### Méthode B : Via l'option WordPress (Recommandé pour rollback temporaire)

1. **Ajoutez ce code temporairement** dans `functions.php` (dans une section commentée) :
   ```php
   // Rollback temporaire - décommentez pour activer
   // add_action( 'init', function() {
   //     update_option( 'nutriflow_force_acf', true );
   // }, 1 );
   ```
2. **Décommentez la ligne** `update_option`
3. **Rechargez une page admin** WordPress
4. **Re-commentez la ligne** pour éviter qu'elle s'exécute à chaque chargement
5. **Réactivez ACF** si nécessaire

**Pour revenir à Pods** :
```php
// Retour à Pods
add_action( 'init', function() {
    update_option( 'nutriflow_force_acf', false );
}, 1 );
```

#### Vérification du rollback

1. **Visitez le site front-end** : Tous les contenus devraient s'afficher normalement
2. **Ouvrez une page dans l'éditeur** : Les champs ACF devraient être visibles
3. **Modifiez un champ ACF** et sauvegardez
4. **Vérifiez sur le front-end** : Le changement devrait être visible

**Avantages** :
- ✅ Rapide (2 minutes)
- ✅ Conserve les données ACF et Pods
- ✅ Réversible facilement
- ✅ Aucune perte de données

---

### Option 2 : Rollback via sauvegarde (Rollback complet)

**Temps estimé** : 15-30 minutes

Cette méthode restaure complètement l'état avant migration.

1. **Arrêtez WordPress** si possible (pour éviter des écritures pendant la restauration)

2. **Restaurez les fichiers** :
   - Remplacez `wp-content/themes/nutriflow` par la version sauvegardée
   - OU restaurez uniquement les fichiers modifiés :
     - `inc/pods-config.php` (re-commentez la ligne `add_action`)
     - `functions.php` (vérifiez que les includes Pods sont commentés)

3. **Restaurez la base de données** :
   - Via phpMyAdmin : Importez la sauvegarde SQL
   - Via votre outil de sauvegarde : Restaurez la base de données

4. **Réactivez ACF** :
   - Extensions > Activer "Advanced Custom Fields"

5. **Désactivez Pods** (optionnel) :
   - Extensions > Désactiver "Pods"

6. **Testez le site** : Vérifiez que tout fonctionne comme avant

**Avantages** :
- ✅ Retour complet à l'état initial
- ✅ Supprime toutes les données Pods

**Inconvénients** :
- ⚠️ Plus long (15-30 minutes)
- ⚠️ Perte des données Pods (si vous en aviez ajouté)

---

### Option 3 : Rollback partiel (Désactiver Pods mais garder les champs)

**Temps estimé** : 5 minutes

Cette méthode désactive Pods et force l'utilisation d'ACF.

1. **Activez le rollback rapide** (Option 1) :
   - Ajoutez `define( 'NUTRIFLOW_FORCE_ACF', true );` dans `wp-config.php`

2. **Re-commentez la ligne dans `pods-config.php`** :
   ```php
   // add_action( 'pods_init', 'nutriflow_setup_pods_fields', 99 );
   ```
   Cela empêche la création automatique de nouveaux champs Pods

3. **Réactivez ACF** :
   - Extensions > Activer "Advanced Custom Fields"

4. **Désactivez Pods** (optionnel) :
   - Extensions > Désactiver "Pods"

5. **Le site utilisera ACF** pour tous les champs

**Avantages** :
- ✅ Rapide
- ✅ Conserve les données ACF

---

### Option 4 : Rollback progressif (Page par page)

Si seules certaines pages posent problème :

1. **Activez le rollback global** (Option 1) pour forcer ACF

2. **Migrez les pages problématiques** :
   - Copiez manuellement les données Pods vers ACF pour ces pages
   - OU restaurez uniquement ces pages depuis la sauvegarde

3. **Testez chaque page** individuellement

---

### Décision : Quelle méthode choisir ?

| Situation | Méthode recommandée | Temps |
|-----------|---------------------|-------|
| Migration récente, problèmes mineurs | Option 1 (Rollback rapide) | 2 min |
| Migration ancienne, données importantes dans Pods | Option 1 (Rollback rapide) | 2 min |
| Migration complètement ratée | Option 2 (Sauvegarde complète) | 15-30 min |
| Problème sur une seule page | Option 4 (Rollback progressif) | 10 min |
| Pas de sauvegarde disponible | Option 3 (Rollback partiel) | 5 min |

---

### Vérification post-rollback

Après avoir effectué le rollback, vérifiez :

1. **Site front-end** :
   - [ ] Page d'accueil s'affiche correctement
   - [ ] Page Accompagnement s'affiche correctement
   - [ ] Page À propos s'affiche correctement
   - [ ] Page Contact s'affiche correctement
   - [ ] Tous les contenus sont présents

2. **Éditeur WordPress** :
   - [ ] Les champs ACF sont visibles dans l'éditeur
   - [ ] Les champs ACF contiennent les bonnes données
   - [ ] Modification d'un champ fonctionne

3. **Logs d'erreurs** :
   - [ ] Aucune erreur PHP dans `wp-content/debug.log`
   - [ ] Aucune erreur dans la console du navigateur

---

### Retour à Pods après rollback

Si vous voulez retenter la migration vers Pods plus tard :

1. **Supprimez ou commentez** la ligne dans `wp-config.php` :
   ```php
   // define( 'NUTRIFLOW_FORCE_ACF', true );
   ```

2. **OU mettez à jour l'option** :
   ```php
   update_option( 'nutriflow_force_acf', false );
   ```

3. **Réactivez Pods** si vous l'aviez désactivé :
   - Extensions > Activer "Pods"

4. **Vérifiez que les champs Pods sont toujours présents** :
   - Pods Admin > Extend Existing > Page

5. **Re-testez le site** : Il devrait maintenant utiliser Pods à nouveau

6. **Les données sont préservées** :
   - Les données ACF restent dans la base de données
   - Les données Pods restent dans la base de données
   - Le système choisira automatiquement Pods en priorité
   - Si un champ Pods est vide, il utilisera ACF automatiquement (fallback)

---

## Compatibilité bidirectionnelle

### Comment ça fonctionne

Le système a été conçu pour supporter **simultanément** ACF et Pods. Voici comment :

1. **Tous les templates utilisent `get_field()`** :
   - Cette fonction est wrappée par `nutriflow_get_field()`
   - Elle fonctionne avec les deux systèmes de manière transparente

2. **Logique de récupération intelligente** :
   ```
   Si FORCE_ACF activé :
     → Essaie ACF
     → Si vide → Essaie Pods (fallback)
   
   Sinon (mode normal) :
     → Essaie Pods
     → Si vide → Essaie ACF (fallback)
   ```

3. **Aucune perte de données** :
   - Les deux systèmes peuvent contenir des données en parallèle
   - Le système choisit automatiquement la meilleure source
   - Vous pouvez migrer progressivement sans risque

### Scénarios d'utilisation

#### Scénario 1 : Migration progressive

1. Activez Pods
2. Migrez seulement quelques pages vers Pods
3. Les autres pages continuent d'utiliser ACF
4. Le système choisit automatiquement la bonne source pour chaque page

#### Scénario 2 : Test Pods en production

1. Activez Pods et migrez tout
2. Si problème, activez `FORCE_ACF`
3. Le site utilise ACF immédiatement
4. Les données Pods restent intactes
5. Corrigez les problèmes
6. Désactivez `FORCE_ACF` pour revenir à Pods

#### Scénario 3 : Coexistence

1. Gardez ACF et Pods tous les deux actifs
2. Le système utilisera Pods par défaut
3. Si un champ n'existe que dans ACF, il sera utilisé automatiquement
4. Migration sans interruption de service

### Avantages de cette approche

- ✅ **Zéro downtime** : Basculer entre les systèmes en 2 minutes
- ✅ **Sécurité** : Les données sont toujours accessibles
- ✅ **Flexibilité** : Migration progressive possible
- ✅ **Testabilité** : Tester Pods sans risque
- ✅ **Réversibilité** : Retour à ACF en cas de problème

---

## Checklist finale de migration

### Avant la migration

- [ ] Sauvegarde complète effectuée
- [ ] Pods installé et activé
- [ ] Pod "Page" créé/extendu
- [ ] Code vérifié (`pods-config.php`, `functions.php`)

### Pendant la migration

- [ ] Champs Pods créés automatiquement
- [ ] Migration des données effectuée
- [ ] Données vérifiées dans l'éditeur

### Après la migration

- [ ] Site front-end testé (toutes les pages)
- [ ] Édition via Pods testée
- [ ] Visibilité conditionnelle vérifiée
- [ ] Aucune erreur PHP
- [ ] ACF désactivé (optionnel)
- [ ] Documentation mise à jour

### En cas de problème

- [ ] Logs PHP consultés
- [ ] Dépannage effectué
- [ ] Rollback effectué si nécessaire

---

## Support et ressources

### Documentation Pods

- Site officiel : https://pods.io/
- Documentation : https://docs.pods.io/
- Support : https://support.pods.io/

### Fichiers du thème concernés

- `inc/pods-config.php` - Configuration des champs Pods
- `inc/pods-helpers.php` - Fonctions helper pour récupérer les champs
- `inc/pods-migration-data.php` - Outil de migration des données
- `functions.php` - Intégration Pods dans le thème

### Templates utilisant les champs

- `front-page.php` - Page d'accueil
- `page-accompagnement.php` - Page Accompagnement
- `page-a-propos.php` - Page À propos
- `page-contact.php` - Page Contact

---

## Notes importantes

1. **Les noms de champs sont identiques** entre ACF et Pods, ce qui facilite la migration

2. **Compatibilité bidirectionnelle complète** :
   - Le code supporte **simultanément** ACF et Pods
   - Vous pouvez basculer de l'un à l'autre à tout moment sans perte de données
   - La logique de fallback garantit que les données sont toujours récupérées, quel que soit le système actif

3. **La fonction `nutriflow_get_field()`** - Logique de priorité intelligente :
   
   **Mode normal (Pods par défaut)** :
   - ✅ Essaie Pods en premier
   - ✅ Si Pods n'a pas de valeur, fallback automatique vers ACF
   - ✅ Permet une migration progressive sans perte de données
   
   **Mode FORCE_ACF (Rollback activé)** :
   - ✅ Essaie ACF en premier
   - ✅ Si ACF n'a pas de valeur, fallback automatique vers Pods
   - ✅ Permet de revenir à ACF tout en gardant accès aux données Pods

4. **Basculer entre les systèmes** :
   - **Activer Pods** : Supprimez `define( 'NUTRIFLOW_FORCE_ACF', true );` de `wp-config.php`
   - **Revenir à ACF** : Ajoutez `define( 'NUTRIFLOW_FORCE_ACF', true );` dans `wp-config.php`
   - Les deux systèmes peuvent coexister dans la base de données

5. **Rollback possible à tout moment** :
   - Ajoutez `define( 'NUTRIFLOW_FORCE_ACF', true );` dans `wp-config.php` pour revenir à ACF
   - Les données Pods restent intactes et accessibles
   - Voir la section "Rollback" pour plus de détails

6. **Les champs sont filtrés par page** : Seuls les champs pertinents s'affichent dans l'éditeur selon la page éditée

7. **Les champs WYSIWYG** permettent un formatage riche, contrairement aux anciens champs texte

8. **Les champs de type "table" ont été remplacés** par des champs individuels pour plus de simplicité

9. **Gardez ACF installé** (même désactivé) pour permettre un rollback rapide en cas de problème

10. **Migration progressive possible** :
    - Vous pouvez migrer page par page
    - Les pages non migrées continueront d'utiliser ACF
    - Les pages migrées utiliseront Pods, avec fallback vers ACF si nécessaire

---

**Dernière mise à jour** : 2024
**Version du runbook** : 1.0

