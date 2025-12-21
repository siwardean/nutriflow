# Guide d'Activation Pods - Nutriflow

Ce guide vous explique **étape par étape** comment activer Pods pour le thème Nutriflow.

## ✅ Étape 1 : Vérifier que Pods est installé et activé

1. Dans WordPress Admin, allez dans **Extensions** (menu de gauche)
2. Cherchez **"Pods - Custom Content Types and Fields"**
3. Si vous voyez **"Activer"**, cliquez dessus
4. Si vous voyez **"Désactiver"**, c'est que Pods est déjà activé ✅

## 🔧 Étape 2 : Activer la création automatique des champs

**IMPORTANT** : Le thème Nutriflow peut créer automatiquement tous les champs. Vous devez juste l'activer.

### Option A : Via l'éditeur de code (Recommandé)

1. Dans WordPress Admin, allez dans **Apparence** > **Éditeur de thème**
2. Dans la liste de fichiers à droite, cliquez sur **inc/pods-config.php**
3. Cherchez la ligne qui contient :
   ```php
   // add_action( 'pods_init', 'nutriflow_setup_pods_fields', 99 );
   ```
4. **Supprimez les deux `//` au début** pour que ça devienne :
   ```php
   add_action( 'pods_init', 'nutriflow_setup_pods_fields', 99 );
   ```
5. Cliquez sur **Mettre à jour le fichier**

### Option B : Via FTP/File Manager

1. Connectez-vous à votre serveur (FTP ou gestionnaire de fichiers)
2. Ouvrez `wp-content/themes/nutriflow/inc/pods-config.php`
3. Trouvez la ligne 238 (ou cherchez `// add_action( 'pods_init'`)
4. Supprimez les `//` au début de la ligne
5. Sauvegardez le fichier

## 🚀 Étape 3 : Créer les champs automatiquement

Une fois la ligne décommentée :

1. **Rechargez n'importe quelle page dans WordPress Admin** (par exemple, cliquez sur **Pages** dans le menu)
2. Les champs seront créés automatiquement en arrière-plan
3. **Attendez quelques secondes** pour que la création se termine

## ✔️ Étape 4 : Vérifier que tout fonctionne

1. Allez dans **Pods Admin** > **Extend Existing** (dans le menu de gauche)
2. Cherchez **"Page"** dans la liste
3. Cliquez sur **"Page"** pour ouvrir sa configuration
4. Vous devriez voir **plusieurs groupes** créés :
   - Section Hero (Page d'accueil)
   - Section À propos (Page d'accueil)
   - Section Consultation (Page d'accueil)
   - Section Services (Page d'accueil)
   - Section Témoignages (Page d'accueil)
   - Section Hero (Page Accompagnement)
   - Section Tarifs (Page Accompagnement)
   - Section Sportif (Page Accompagnement)
   - Section Localisation (Page Accompagnement)
   - Section Introduction (Page À propos)
   - Section Parcours (Page À propos)
   - Contenu Contact (Page Contact)

5. **Testez dans l'éditeur de page** :
   - Allez dans **Pages** > **Toutes les pages**
   - Ouvrez une page (par exemple la page d'accueil)
   - Vous devriez voir les **champs Pods** apparaître dans des boîtes en bas de la page

## 📋 Liste des Champs Créés

### Page d'Accueil (Front Page)

**Section Hero** :
- `homepage_hero_title` - Titre Hero
- `homepage_hero_subtitle` - Sous-titre Hero
- `homepage_hero_description` - Description Hero
- `homepage_hero_button` - Texte du bouton Hero
- `homepage_hero_image` - Image Hero

**Section À propos** :
- `homepage_about_title` - Titre À propos
- `homepage_about_text_left` - Texte À propos (gauche)
- `homepage_about_text_right` - Texte À propos (droite)
- `homepage_about_image` - Image À propos

**Section Consultation** :
- `homepage_consult_title` - Titre Consultation
- `homepage_consult_text` - Texte Consultation
- `homepage_consult_button` - Bouton Consultation
- `homepage_consult_image` - Image Consultation

**Section Services** :
- `homepage_services_heading` - Titre Section Services
- `homepage_services` - Services (Table avec colonnes : `service_title`, `service_description`)

**Section Témoignages** :
- `homepage_testimonials_heading` - Titre Témoignages
- `homepage_testimonials` - Témoignages (Table avec colonnes : `testimonial_text`, `testimonial_author`)

### Page Accompagnement

**Section Hero** :
- `hero_title` - Titre Hero
- `hero_background` - Image de fond Hero
- `hero_cards` - Cartes Hero (Table avec colonnes : `card_title`, `card_description`)

**Section Tarifs** :
- `pricing_title` - Titre Tarifs
- `pricing_card_1_title` - Carte Tarif 1 - Titre
- `pricing_card_1_price` - Carte Tarif 1 - Prix
- `pricing_card_1_items` - Carte Tarif 1 - Liste des items
- `pricing_card_2_title` - Carte Tarif 2 - Titre
- `pricing_card_2_price` - Carte Tarif 2 - Prix
- `pricing_card_2_items` - Carte Tarif 2 - Liste des items
- `pricing_card_3_title` - Carte Tarif 3 - Titre
- `pricing_card_3_price` - Carte Tarif 3 - Prix
- `pricing_card_3_items` - Carte Tarif 3 - Liste des items
- `pricing_card_4_title` - Carte Tarif 4 - Titre (optionnel)
- `pricing_card_4_price` - Carte Tarif 4 - Prix (optionnel)
- `pricing_card_4_items` - Carte Tarif 4 - Liste des items (optionnel)

**Section Sportif** :
- `sportif_title` - Titre Section Sportif
- `sportif_card_title` - Titre Carte Sportif
- `sportif_items` - Liste Sportif (Table avec colonne : `sportif_item`)

**Section Localisation** :
- `location_title` - Titre Localisation
- `location_info` - Informations Localisation
- `location_image` - Image Localisation

### Page À propos

**Section Introduction** :
- `intro_title` - Titre Introduction
- `intro_text` - Texte Introduction
- `gallery_images` - Galerie d'Images (multi-sélection)

**Section Parcours** :
- `story_content` - Mon Parcours
- `formations_title` - Titre Formations
- `formations_list` - Liste des Formations (Table avec colonne : `formation_item`)
- `sport_title` - Titre Section Sport
- `sport_content` - Contenu Sport

### Page Contact

- `contact_image` - Image Contact
- `contact_title` - Titre Contact
- `contact_subtitle` - Sous-titre Contact
- `contact_items` - Items Contact (Table avec colonnes : `item_text`, `item_category`, `item_link`)
- `contact_cta_text` - Texte CTA Contact
- `contact_button_text` - Texte Bouton Contact

## ⚠️ Note sur les Champs Table

Les champs de type **Table** (qui remplacent les repeaters ACF) peuvent nécessiter une configuration manuelle des colonnes :

1. Allez dans **Pods Admin** > **Extend Existing** > **Page**
2. Cliquez sur un champ de type **Table** (ex: `homepage_services`)
3. Configurez les colonnes dans les options du champ

**Colonnes à configurer** :
- `homepage_services` : `service_title` (text), `service_description` (textarea)
- `homepage_testimonials` : `testimonial_text` (textarea), `testimonial_author` (text)
- `hero_cards` : `card_title` (text), `card_description` (textarea)
- `sportif_items` : `sportif_item` (wysiwyg)
- `formations_list` : `formation_item` (wysiwyg)
- `contact_items` : `item_text` (text), `item_category` (text), `item_link` (text)

## 🔍 Vérification dans l'Éditeur de Page

Une fois les champs créés :

1. Allez dans **Pages** > **Toutes les pages**
2. Ouvrez une page (par exemple la page d'accueil)
3. **Faites défiler vers le bas** de l'éditeur
4. Vous devriez voir des **meta boxes Pods** avec tous les champs
5. Les champs avec valeurs par défaut seront **pré-remplis**

## 📦 Meta Box "Médias utilisés sur la page"

Une meta box spéciale apparaît dans la barre latérale droite qui affiche :
- Toutes les images utilisées sur la page
- Les liens directs vers ces images
- Instructions pour les modifier

## 🆘 Dépannage

### Les champs ne se créent pas

1. **Vérifiez que Pods est activé** : Extensions > vérifiez que "Pods" est activé
2. **Vérifiez que la ligne est décommentée** : Dans `inc/pods-config.php`, ligne 238 doit être :
   ```php
   add_action( 'pods_init', 'nutriflow_setup_pods_fields', 99 );
   ```
   (sans les `//` au début)
3. **Rechargez une page admin** : Allez sur Pages > Toutes les pages
4. **Vérifiez les logs** : Si WP_DEBUG est activé, vérifiez `wp-content/debug.log`

### Erreur "field name is reserved"

Si vous voyez une erreur :
1. Les champs problématiques sont automatiquement ignorés et loggés
2. Vérifiez `wp-content/debug.log` pour voir quels champs ont été ignorés
3. Contactez le développeur si nécessaire

### "Page" n'apparaît pas dans Extend Existing

1. Allez dans **Pods Admin** > **Extend Existing**
2. Cliquez sur **"Add New"** ou **"Extend"**
3. Sélectionnez **"Post Type"** comme type
4. Choisissez **"Page"** dans la liste
5. Cliquez sur **"Continue"**

## 🔄 Migration depuis ACF (si applicable)

Si vous aviez déjà des données dans ACF :

1. **Notez les valeurs** actuelles dans ACF
2. Une fois Pods activé et les champs créés
3. **Ouvrez chaque page** dans l'éditeur WordPress
4. **Copiez les valeurs** depuis ACF vers les champs Pods
5. **Sauvegardez** la page
6. Répétez pour toutes les pages
7. Une fois toutes les données migrées, vous pouvez désactiver ACF

**Astuce** : Les noms de champs sont identiques entre ACF et Pods, donc c'est juste un copier-coller !

## 📝 Résumé des Étapes

1. ✅ **Activer Pods** : Extensions > Activer "Pods"
2. ✅ **Décommenter la ligne** : Dans `inc/pods-config.php`, ligne 238
3. ✅ **Recharger une page admin** : Pour déclencher la création automatique
4. ✅ **Vérifier** : Pods Admin > Extend Existing > Page
5. ✅ **Tester** : Ouvrir une page dans l'éditeur pour voir les champs
6. ✅ **Configurer les Tables** : Si nécessaire, configurer les colonnes des champs Table

**C'est tout !** 🎉
