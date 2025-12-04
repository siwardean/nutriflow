# Canva to WordPress Export Guide

This guide will help you export all assets from your Canva website (https://nutriflow-florence.my.canva.site/) and integrate them into this WordPress theme.

## Step 1: Export Images from Canva

### Method 1: Download Individual Elements
1. Open your Canva site: https://nutriflow-florence.my.canva.site/
2. For each image/graphic element:
   - Right-click on the element
   - Select "Download"
   - Choose format:
     - **PNG** (with transparent background if needed)
     - **JPG** (for photos)
   - Set quality to **100%** or highest available
   - Save to your computer

### Method 2: Screenshot Entire Sections
1. Take full-page screenshots of each section
2. Use browser tools (Chrome DevTools) to capture full page
3. Save sections as PNG files

### Method 3: Export All Assets at Once
1. In Canva, click the **Share** button
2. Select **Download**
3. Choose **All pages** if multiple pages exist
4. Format: **PDF** (then extract images) or **PNG**
5. Quality: **High quality**

## Step 2: Organize Your Exported Files

Save images to these directories (create if needed):

```
wp-content/themes/nutriflow/assets/images/
├── logo/
│   └── logo.png (main logo)
├── hero/
│   └── hero-image.jpg (main hero image)
├── services/
│   ├── service-1.jpg
│   ├── service-2.jpg
│   └── service-3.jpg
├── programs/
│   ├── program-1.jpg
│   ├── program-2.jpg
│   └── program-3.jpg
├── testimonials/
│   ├── testimonial-1.jpg
│   └── testimonial-2.jpg
└── blog/
    └── (blog post images)
```

## Step 3: Extract Design Information from Canva

Before exporting, note down:

### Colors
1. Open Canva design
2. Note the hex codes for:
   - Primary color: `#______`
   - Secondary color: `#______`
   - Accent color: `#______`
   - Background: `#______`
   - Text color: `#______`

### Fonts
1. Note the font names used:
   - Heading font: `________________`
   - Body font: `________________`

### Spacing & Layout
- Section padding
- Card border radius
- Grid gaps
- Max content width

## Step 4: Update the Theme Files

### A. Update Colors in `style.css`

Open `wp-content/themes/nutriflow/style.css` and update the `:root` variables (lines 15-30) with your Canva colors:

```css
:root {
    --nf-primary: #YOUR_PRIMARY_COLOR;
    --nf-secondary: #YOUR_SECONDARY_COLOR;
    /* etc... */
}
```

### B. Update Images in `front-page.php`

1. Replace hero image placeholder (around line 111):
```php
<div class="nf-hero__media" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/hero/hero-image.jpg');">
```

2. Add images to services, programs, testimonials sections similarly.

### C. Update Content

1. Open `wp-content/themes/nutriflow/front-page.php`
2. Replace the placeholder content (starting around line 10) with exact text from your Canva site
3. Update:
   - Headlines
   - Descriptions
   - Statistics
   - Testimonials
   - Contact information

## Step 5: Match Typography

### Option 1: Use Canva's Fonts
1. If Canva uses custom fonts, download them
2. Upload to `wp-content/themes/nutriflow/assets/fonts/`
3. Add `@font-face` declarations to `style.css`

### Option 2: Use Google Fonts Alternative
1. Identify similar Google Fonts
2. Update `style.css` line 41 with your font choice

## Step 6: Extract CSS Styles

To get exact CSS from Canva:

1. **Inspect Canva Site:**
   - Open https://nutriflow-florence.my.canva.site/
   - Press `F12` (or right-click > Inspect)
   - Use the element selector to see styles
   - Copy relevant CSS properties

2. **Update `style.css`:**
   - Match exact spacing
   - Match exact colors
   - Match exact font sizes
   - Match exact border radius
   - Match exact shadows

## Step 7: Test & Refine

1. Activate the theme in WordPress
2. View the front page
3. Compare side-by-side with Canva site
4. Adjust CSS as needed to match exactly

## Quick Reference: File Locations

- **Main CSS:** `wp-content/themes/nutriflow/style.css`
- **Homepage Template:** `wp-content/themes/nutriflow/front-page.php`
- **Header:** `wp-content/themes/nutriflow/header.php`
- **Footer:** `wp-content/themes/nutriflow/footer.php`
- **Images:** `wp-content/themes/nutriflow/assets/images/`

## Need Help?

If you need to extract specific elements:
1. Take a screenshot of the Canva page
2. Share what section needs adjustment
3. I can help update the CSS to match exactly

