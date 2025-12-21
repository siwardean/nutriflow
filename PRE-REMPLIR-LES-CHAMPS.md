# 🎯 Pré-remplir les Champs Pods avec le Contenu Actuel

## Problème Résolu

Maintenant que les champs Pods sont créés, ils sont vides. Cette fonction permet de **pré-remplir automatiquement tous les champs** avec le contenu actuel du site.

## 🚀 Comment Utiliser

### Option 1 : Via le Menu WordPress Admin (RECOMMANDÉ)

1. Allez dans **Outils** > **Migration Pods** (dans le menu WordPress Admin à gauche)
2. Vous verrez une page avec un bouton **"🚀 Pré-remplir tous les champs Pods"**
3. Cliquez sur le bouton
4. Un message de confirmation apparaîtra : **"✅ Migration terminée ! Tous les champs ont été pré-remplis avec le contenu actuel."**
5. **C'est tout !** Allez maintenant dans **Pages** > **Accueil** (ou n'importe quelle page) et vous verrez tous les champs remplis

### Option 2 : Automatique au chargement (Alternative)

Si vous préférez que la migration se fasse automatiquement, vous pouvez décommenter cette ligne dans `inc/pods-migration-data.php` :

```php
// Ligne 213 - Décommentez pour activer :
add_action( 'admin_init', 'nutriflow_prefill_pods_fields', 999 );
```

⚠️ **Note :** Cette option exécute la migration à chaque chargement de page admin. Elle n'écrase que les champs vides, mais c'est moins propre que l'Option 1.

## 📋 Ce qui sera Pré-rempli

### Page d'Accueil
- ✅ Section Hero (Titre, Sous-titre, Description, Bouton)
- ✅ Section À propos (Titre, Texte gauche, Texte droite)
- ✅ Section Consultation (Titre, Texte, Bouton)
- ✅ Section Services (Titre, 4 services avec titre et description)
- ✅ Section Témoignages (Titre, Témoignage de Nina Rozenberg)

### Page Accompagnement
- ✅ Section Hero (Titre, 4 cartes avec titre et description)
- ✅ Section Tarifs (Titre, 3 cartes de tarifs avec prix et items)
- ✅ Section Sportif (Titre, Titre carte, 3 items)
- ✅ Section Localisation (Titre)

### Page À propos
- ✅ Section Introduction (Titre, Texte)
- ✅ Section Parcours (Contenu, Titre formations, 2 formations)
- ✅ Section Sport (Titre, Contenu)

### Page Contact
- ✅ Titre, Sous-titre, Texte CTA, Texte Bouton
- ✅ Items Contact (2 items : localisation et horaires)

## ⚠️ Comportement Important

- **La migration ne remplace QUE les champs vides**
- Si un champ contient déjà une valeur, elle ne sera **PAS modifiée**
- Vous pouvez donc exécuter la migration plusieurs fois sans risque
- Si vous voulez tout réinitialiser, videz d'abord les champs dans l'éditeur de page

## 🔍 Vérifier que ça a Fonctionné

1. Allez dans **Pages** > **Toutes les pages**
2. Cliquez sur **"Accueil"** (ou n'importe quelle page)
3. Faites défiler vers le bas dans l'éditeur
4. Dans les **Meta Boxes**, vous devriez voir tous les champs remplis avec le contenu actuel

## 📝 Champs de Type "Table" (Services, Témoignages, etc.)

Les champs de type **"Table"** (anciennement "Repeater" dans ACF) sont remplis avec des tableaux de données. Par exemple :

- `homepage_services` contient un tableau avec 4 services
- `homepage_testimonials` contient un tableau avec 1 témoignage
- `hero_cards` contient un tableau avec 4 cartes
- `pricing_cards` contient un tableau avec 3 cartes de tarifs
- etc.

Ces champs apparaissent comme des **tables éditable** dans Pods avec des lignes et des colonnes.

## 🆘 Problèmes ?

### Les champs ne se remplissent pas

1. Vérifiez que Pods est activé
2. Vérifiez que "Page" est étendu dans Pods Admin > Extend Existing
3. Vérifiez que vous avez bien cliqué sur le bouton dans **Outils** > **Migration Pods**
4. Activez WP_DEBUG pour voir les erreurs éventuelles

### Les valeurs ne correspondent pas exactement au site

- Les valeurs sont basées sur le contenu hardcodé dans les templates PHP (`front-page.php`, `page-accompagnement.php`, etc.)
- Si votre site utilise déjà ACF avec des valeurs personnalisées, elles ne seront pas migrées automatiquement
- Vous devrez copier manuellement ces valeurs depuis l'ancien système ACF

### Erreur "Pod not found"

- Assurez-vous que "Page" est bien étendu dans Pods Admin
- Vérifiez que les slugs de pages sont corrects (par exemple `a-propos` et non `a-propos-2`)

## ✅ Résumé Rapide

1. **Outils** > **Migration Pods**
2. Cliquez sur **"🚀 Pré-remplir tous les champs Pods"**
3. Vérifiez dans **Pages** > **Accueil** que les champs sont remplis
4. **C'est fait !** 🎉

