# Optimisations SEO pour Nutriflow

Ce document décrit toutes les optimisations SEO implémentées pour améliorer la visibilité du site.

## ✅ Optimisations Implémentées

### 1. Meta Tags Essentials
- **Meta Description** : Automatique avec fallback intelligent
- **Title Tags** : Optimisés pour chaque page avec format personnalisé
- **Canonical URLs** : Pour éviter le contenu dupliqué
- **Robots Meta** : Exclusion des pages 404 et recherche

### 2. Open Graph Tags (Réseaux Sociaux)
- `og:title` - Titre optimisé pour le partage
- `og:description` - Description pour les aperçus
- `og:image` - Image de partage (featured image ou logo par défaut)
- `og:url` - URL canonique
- `og:type` - Type de contenu (website/article)
- `og:locale` - fr_BE pour la Belgique

### 3. Twitter Cards
- `twitter:card` - Format large image
- `twitter:title` - Titre optimisé
- `twitter:description` - Description
- `twitter:image` - Image de partage

### 4. Schema.org Structured Data (Rich Snippets)

#### LocalBusiness Schema
Le site inclut un schéma LocalBusiness complet avec :
- Nom et description de l'entreprise
- Coordonnées (téléphone, email, adresse)
- Géolocalisation (Flagey, Ixelles, Bruxelles)
- Réseaux sociaux (Instagram, LinkedIn)
- Catalogue de services (consultation en nutrithérapie)
- Informations sur la fondatrice (Florence Van Hecke)

#### BreadcrumbList Schema
- Navigation structurée pour les pages
- Améliore l'affichage dans les résultats de recherche

### 5. Optimisation des Images
- Alt text automatique si manquant
- Utilisation de l'image mise en avant pour Open Graph
- Fallback vers le logo si aucune image

### 6. Robots.txt
- Fichier `robots.txt` configuré
- Exclusion des dossiers WordPress sensibles
- Référence au sitemap XML

### 7. Sitemap XML
- Utilisation du sitemap natif WordPress (wp-sitemap.xml)
- Accessible automatiquement

## 📊 Fonctionnalités du Module SEO

### Fonctions Disponibles

#### `nutriflow_get_seo_title( $post_id )`
Récupère le titre SEO optimisé pour une page/post.

#### `nutriflow_get_seo_description( $post_id )`
Récupère la description SEO avec fallback intelligent :
1. Meta description personnalisée (Pods/custom field)
2. Extrait de la page
3. Extrait du contenu (30 mots)
4. Description par défaut

#### `nutriflow_get_og_image( $post_id )`
Récupère l'image pour Open Graph :
1. Image mise en avant
2. Logo par défaut

## 🎯 Prochaines Étapes Recommandées

### Court Terme
1. **Google Search Console**
   - Soumettre le sitemap : `https://nutriflow.be/wp-sitemap.xml`
   - Vérifier l'indexation
   - Surveiller les erreurs

2. **Google My Business**
   - Créer/optimiser le profil Google My Business
   - Ajouter les mêmes coordonnées que dans le Schema

3. **Contenu Optimisé**
   - Ajouter des mots-clés naturels : "nutrithérapeute Bruxelles", "nutritionniste Ixelles"
   - Créer du contenu régulier (blog/articles)

### Moyen Terme
4. **Backlinks**
   - Participer à des annuaires locaux (PagesJaunes, etc.)
   - Partenariats avec des professionnels de santé

5. **Référencement Local**
   - Optimiser pour les recherches "nutrithérapeute près de moi"
   - Créer des pages de service spécifiques

6. **Performance**
   - Optimiser les images (compression, WebP)
   - Mettre en cache (plugin de cache)
   - CDN si nécessaire

### Long Terme
7. **Contenu de Qualité**
   - Articles de blog réguliers sur la nutrition
   - Réponses aux questions fréquentes
   - Témoignages et études de cas

8. **SEO Technique Avancé**
   - HTTPS (si pas déjà fait)
   - Vitesse de chargement < 3 secondes
   - Mobile-first indexing optimisé

## 📝 Configuration Personnalisable

Pour personnaliser les meta descriptions et titres par page, vous pouvez utiliser Pods pour ajouter :
- `seo_title` - Titre SEO personnalisé
- `seo_description` - Description SEO personnalisée

## 🔍 Vérification

Pour vérifier que tout fonctionne :

1. **Meta Tags** : Voir le code source (Ctrl+U) et chercher les balises `<meta>`
2. **Open Graph** : Utiliser le [Facebook Sharing Debugger](https://developers.facebook.com/tools/debug/)
3. **Schema** : Utiliser le [Google Rich Results Test](https://search.google.com/test/rich-results)
4. **Sitemap** : Visiter `https://nutriflow.be/wp-sitemap.xml`

## 📧 Support

Pour toute question sur les optimisations SEO, consultez :
- [Google Search Central](https://developers.google.com/search)
- [Schema.org Documentation](https://schema.org/)

