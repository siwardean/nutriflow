# Configuration Git-friendly - Nutriflow Theme

Ce document explique comment configurer le système de champs (ACF vs Pods) de manière compatible avec Git.

## 🎯 Principe

Le thème utilise un système de flags permettant de basculer facilement entre **ACF** et **Pods** selon l'environnement, sans impacter les autres développeurs ou les déploiements.

## 📁 Fichiers de Configuration

### Fichiers versionnés (dans Git) :
- `wp-config.local.example.php` - Exemple de configuration locale
- `.env.example` - Exemple de variables d'environnement
- `.gitignore` - Fichiers à ignorer (configurations locales)

### Fichiers non versionnés (locaux, dans .gitignore) :
- `wp-config.local.php` - Votre configuration locale WordPress
- `.env` - Vos variables d'environnement locales

## ⚙️ Configuration

### Méthode 1 : wp-config.local.php (Recommandé)

1. **Créez `wp-config.local.php`** à la racine WordPress (à côté de `wp-config.php`) :
   ```php
   <?php
   // Configuration locale - Ne pas commiter dans Git
   
   // Utiliser Pods (par défaut pour développement)
   define( 'NUTRIFLOW_FIELD_SYSTEM', 'pods' );
   
   // OU forcer ACF (pour rollback ou test)
   // define( 'NUTRIFLOW_FIELD_SYSTEM', 'acf' );
   ```

2. **Chargez le fichier dans `wp-config.php`** (si pas déjà fait) :
   ```php
   // Charger la config locale si elle existe
   if ( file_exists( __DIR__ . '/wp-config.local.php' ) ) {
       require_once __DIR__ . '/wp-config.local.php';
   }
   ```

**Avantages** :
- ✅ Configuration locale non versionnée
- ✅ Chaque développeur peut avoir sa propre config
- ✅ Ne pollue pas le dépôt Git

### Méthode 2 : Variable d'environnement (.env)

1. **Créez `.env`** dans le dossier du thème :
   ```
   NUTRIFLOW_FIELD_SYSTEM=pods
   ```

2. **Chargez les variables** (si vous utilisez un système comme vlucas/phpdotenv)

**Note** : Nécessite une bibliothèque pour charger les variables d'environnement.

### Méthode 3 : Constante directe dans wp-config.php

```php
// Dans wp-config.php (sera commité dans Git)
define( 'NUTRIFLOW_FIELD_SYSTEM', 'pods' );
```

**Note** : Cette méthode modifie le fichier versionné. À éviter si plusieurs développeurs travaillent sur le projet.

## 🔄 Ordre de Priorité

Le système vérifie les configurations dans cet ordre :

1. **Constante `NUTRIFLOW_FIELD_SYSTEM`** dans `wp-config.php` ou `wp-config.local.php`
2. **Variable d'environnement `NUTRIFLOW_FIELD_SYSTEM`** (via `.env` ou serveur)
3. **Option base de données `nutriflow_field_system`**
4. **Par défaut** : `'pods'`

## 📝 Exemples d'utilisation

### Développement local (utiliser Pods)

```php
// wp-config.local.php
define( 'NUTRIFLOW_FIELD_SYSTEM', 'pods' );
```

### Production (forcer ACF si rollback nécessaire)

```php
// wp-config.php ou wp-config.local.php (selon votre setup)
define( 'NUTRIFLOW_FIELD_SYSTEM', 'acf' );
```

### Test/Staging (basculer dynamiquement)

Vous pouvez aussi utiliser l'option base de données pour changer sans modifier les fichiers :

```php
// Dans functions.php ou via un plugin
update_option( 'nutriflow_field_system', 'acf' ); // Forcer ACF
update_option( 'nutriflow_field_system', 'pods' ); // Utiliser Pods
```

## 🚀 Workflow Git

### Premier setup (nouveau développeur)

1. Clone le dépôt
2. Copie `wp-config.local.example.php` vers `wp-config.local.php`
3. Configure selon son environnement :
   ```php
   define( 'NUTRIFLOW_FIELD_SYSTEM', 'pods' ); // ou 'acf'
   ```
4. `wp-config.local.php` est dans `.gitignore`, donc ne sera pas commité

### Déploiement

- **Staging/Production** : Configurez `NUTRIFLOW_FIELD_SYSTEM` via :
  - Variables d'environnement du serveur
  - Fichier de config spécifique à l'environnement
  - Option base de données

## 🔍 Vérifier la configuration active

Pour voir quel système est actuellement utilisé, ajoutez temporairement dans `functions.php` :

```php
add_action( 'admin_notices', function() {
    $system = nutriflow_get_field_system();
    echo '<div class="notice notice-info"><p>Système de champs actif : <strong>' . esc_html( $system ) . '</strong></p></div>';
});
```

## ⚠️ Notes importantes

- **Ne jamais commiter** `wp-config.local.php` ou `.env` (déjà dans `.gitignore`)
- **Toujours commiter** `wp-config.local.example.php` et `.env.example` pour documenter les options
- Les changements de configuration prennent effet immédiatement (pas besoin de redémarrer)

## 🔗 Voir aussi

- [RUNBOOK-MIGRATION-ACF-TO-PODS.md](./RUNBOOK-MIGRATION-ACF-TO-PODS.md) - Guide complet de migration
- [PODS-MIGRATION-GUIDE.md](./PODS-MIGRATION-GUIDE.md) - Guide d'activation Pods

