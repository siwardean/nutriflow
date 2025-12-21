# 🚀 Activation Pods - Guide Étape par Étape

## Problème : Vous voyez "Meta Boxes" mais aucun champ Pods

Si vous voyez la zone "Meta Boxes" vide dans l'éditeur de page, voici comment résoudre le problème :

## ✅ Étape 1 : Vérifier que Pods est activé

1. Allez dans **Extensions** (menu WordPress Admin)
2. Vérifiez que **"Pods - Custom Content Types and Fields"** est **Actif** (bouton "Désactiver" visible)
3. Si ce n'est pas activé, cliquez sur **"Activer"**

## ✅ Étape 2 : Étendre "Page" dans Pods (IMPORTANT)

**Cette étape est nécessaire avant que les champs puissent être créés !**

1. Allez dans **Pods Admin** > **Extend Existing** (menu WordPress Admin)
2. Cherchez **"Page"** dans la liste
3. Si **"Page"** n'est **PAS** dans la liste :
   - Cliquez sur **"Add New"** ou **"Extend"**
   - Sélectionnez **"Post Type"** comme type
   - Choisissez **"Page"** dans la liste déroulante
   - Cliquez sur **"Continue"** ou **"Save"**
4. Si **"Page"** est déjà dans la liste, c'est bon ✅

## ✅ Étape 3 : Activer la création automatique des champs

1. Dans WordPress Admin, allez dans **Apparence** > **Éditeur de thème**
2. Cliquez sur **inc/pods-config.php** dans la liste des fichiers
3. Trouvez la ligne 238 (cherchez `add_action( 'pods_init'`)
4. **Vérifiez qu'elle n'est PAS commentée** (pas de `//` au début) :
   
   ✅ **BON** (sans `//`) :
   ```php
   add_action( 'pods_init', 'nutriflow_setup_pods_fields', 99 );
   ```
   
   ❌ **MAUVAIS** (avec `//`) :
   ```php
   // add_action( 'pods_init', 'nutriflow_setup_pods_fields', 99 );
   ```
5. Si elle est commentée, supprimez les `//`
6. Cliquez sur **"Mettre à jour le fichier"**

## ✅ Étape 4 : Forcer la création des champs

Après avoir étendu "Page" et activé la ligne, vous devez **forcer l'exécution** :

### Option A : Recharger une page admin (simple)

1. Allez dans **Pages** > **Toutes les pages**
2. Cliquez sur **"Accueil"** (ou n'importe quelle page)
3. Les champs devraient se créer automatiquement en arrière-plan
4. **Attendez 2-3 secondes**, puis **rechargez la page** (F5)

### Option B : Aller dans Pods Admin pour forcer

1. Allez dans **Pods Admin** > **Extend Existing** > **"Page"**
2. Cliquez sur **"Edit"** ou **"Page"**
3. Cela va forcer Pods à initialiser
4. Revenez sur **Pages** > **Accueil**

### Option C : Si ça ne marche toujours pas

Ajoutez ce code temporaire dans `functions.php` pour forcer la création :

```php
// TEMPORAIRE - À supprimer après création des champs
add_action( 'admin_init', function() {
    if ( function_exists( 'nutriflow_setup_pods_fields' ) ) {
        nutriflow_setup_pods_fields();
    }
}, 999 );
```

Puis :
1. Sauvegardez
2. Rechargez une page admin
3. Les champs seront créés
4. **Supprimez ce code** après vérification

## ✅ Étape 5 : Vérifier que les champs sont créés

1. Allez dans **Pods Admin** > **Extend Existing** > **"Page"**
2. Vous devriez voir plusieurs **Groupes** créés :
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

3. Si vous voyez ces groupes, **c'est bon !** ✅

## ✅ Étape 6 : Voir les champs dans l'éditeur de page

1. Allez dans **Pages** > **Toutes les pages**
2. Cliquez sur **"Accueil"**
3. **Faites défiler vers le bas** de l'éditeur
4. Dans la zone **"Meta Boxes"**, vous devriez maintenant voir :
   - Les groupes Pods avec tous les champs
   - Une meta box **"Médias utilisés sur la page"**

## 🔍 Dépannage

### Si les champs ne s'affichent toujours pas

1. **Vérifiez les logs** : 
   - Activez WP_DEBUG dans `wp-config.php` :
     ```php
     define( 'WP_DEBUG', true );
     define( 'WP_DEBUG_LOG', true );
     ```
   - Vérifiez `wp-content/debug.log` pour des erreurs

2. **Vérifiez que "Page" est bien étendu** :
   - Pods Admin > Extend Existing > Page doit exister

3. **Testez la fonction manuellement** :
   - Ajoutez ce code temporaire dans `functions.php` :
     ```php
     add_action( 'admin_init', function() {
         if ( function_exists( 'pods_api' ) ) {
             $api = pods_api();
             $pod = $api->load_pod( array( 'name' => 'page' ) );
             if ( $pod ) {
                 error_log( 'Pods Page pod exists: ' . print_r( $pod, true ) );
             } else {
                 error_log( 'Pods Page pod does NOT exist' );
             }
         }
     } );
     ```
   - Rechargez une page admin
   - Vérifiez `wp-content/debug.log`

### Si vous voyez des erreurs dans les logs

- Copiez le message d'erreur
- Il indiquera quel champ cause problème
- Les champs problématiques sont automatiquement ignorés par le code

## 📞 Résumé de l'Ordre des Opérations

1. ✅ Pods activé
2. ✅ "Page" étendu dans Pods Admin > Extend Existing
3. ✅ Ligne dans `pods-config.php` non commentée
4. ✅ Recharger une page admin pour déclencher la création
5. ✅ Vérifier dans Pods Admin > Extend Existing > Page que les groupes existent
6. ✅ Vérifier dans l'éditeur de page que les champs apparaissent

**Si après toutes ces étapes les champs n'apparaissent toujours pas, il y a probablement une erreur PHP. Activez WP_DEBUG et vérifiez les logs !**

