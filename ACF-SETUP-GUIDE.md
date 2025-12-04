# ACF Setup Guide for NutriFlow Theme

## What's Changed?

The **A Propos (About)** page template is now fully editable through the WordPress admin panel using Advanced Custom Fields (ACF).

## Requirements

You need to have **Advanced Custom Fields (ACF)** plugin installed and activated:
- Download: https://wordpress.org/plugins/advanced-custom-fields/
- Or install from WordPress Admin → Plugins → Add New → Search "Advanced Custom Fields"

## How to Edit Page Content

### 1. Go to the A Propos Page
- Log in to WordPress Admin
- Go to **Pages → A propos**
- Scroll down below the main editor

### 2. You'll see "Contenu A Propos" section with these fields:

#### **Titre Introduction**
- The main title (default: "Qui suis-je ?")
- Simple text field

#### **Texte Introduction**
- Introduction paragraph
- WYSIWYG editor (bold, italic, links)

#### **Galerie d'Images**
- Upload/select 4 images for the gallery
- Click "Add to gallery" to add images
- Drag to reorder

#### **Mon Parcours**
- Your personal story content
- Full WYSIWYG editor

#### **Titre Formations**
- Section title (default: "Mes formations")

#### **Liste des Formations**
- Click "+ Add Row" to add each formation
- Each formation can have formatted text (bold, lists)
- Click "Remove" to delete a formation
- Drag rows to reorder

#### **Titre Section Sport**
- Sport section title

#### **Contenu Sport**
- Sport section content
- Full WYSIWYG editor

### 3. Save and Preview
- Click "Update" button
- View the page to see your changes

## Fallback Content

If you don't fill in the ACF fields, the page will display the original default content. This means:
- The page won't break if fields are empty
- You can gradually migrate content
- Original content serves as a template

## Using Block Editor Instead

If you prefer, you can also use WordPress Block Editor:
1. Go to Pages → A propos
2. Add content using blocks in the main editor area
3. The page will display your block content instead of the template

## Next Steps

Would you like me to:
1. ✅ Convert other page templates (Contact, Accompagnement) to use ACF?
2. ✅ Add more field types (image uploads, colors, etc.)?
3. ✅ Create ACF options page for site-wide settings (footer, social links)?

## Technical Notes

- ACF fields are registered in: `inc/acf-fields.php`
- Field configurations will auto-export to: `acf-json/` folder
- Templates check for ACF content first, then fall back to defaults

