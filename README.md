# 🚀 Happy Portfolio WordPress Theme

[![Version](https://img.shields.io/badge/Version-1.0.0-blue.svg)](https://github.com/IndrajeetS/wordpress-happy-portfolio)
[![License](https://img.shields.io/badge/License-ISC-green.svg)](LICENSE)

A modern, minimal, and performance-focused WordPress theme designed for developers and creatives. This theme serves as a complete digital resume, featuring dedicated areas for showcasing work experience, technical skills, and personal projects/reading lists.

Built with a utility-first approach using **Tailwind CSS** and a clean, modular architecture.

---

## ✨ Key Features

- **Dedicated Resume Modules:** Utilizes **Custom Post Types** (CPTs) to manage non-blog content cleanly:
  - Work Experience
  - Tech Stack / Tools
  - Reading Lists & Updates
- **Utility-First Styling:** Styled entirely with **Tailwind CSS** for rapid development and highly optimized production CSS.
- **Modular Architecture:** Components are split into granular files using `template-parts/` for high maintainability.
- **Modern Build Pipeline:** Includes NPM scripts for compiling production-ready, purged CSS and bundling the final theme ZIP.
- **Clean Separation of Concerns:** Logic is strictly separated from presentation.
- **Empty-State Safe Content Blocks:** Sections such as the recent writing grid return nothing when there are no posts, preventing empty frontend blocks from rendering.

---

## 🧩 Content Empty-State Handling

The theme uses defensive queries before rendering collection modules. For example, the recent writing section checks whether any blog posts exist before outputting the header and card grid.

If the query returns zero posts, the function exits early and returns an empty string instead of rendering an empty section in the frontend. This keeps the homepage and other layouts cleaner and avoids placeholder noise when content is not available.

Example behavior:

```php
if (!$posts_query->have_posts()) {
    wp_reset_postdata();
    return '';
}
```

This pattern is useful for any archive or homepage module that should only appear when there is actual content to display.

---

## 🛠️ Technology Stack

| Technology                 | Role                                         |
| :------------------------- | :------------------------------------------- |
| **WordPress**              | Core CMS                                     |
| **PHP 8.x**                | Backend Theme Logic                          |
| **Tailwind CSS**           | Utility-first styling framework              |
| **PostCSS & esbuild**      | Asset compilation, JS bundling, & minification |
| **Vanilla JS (ES6+)**      | Zero-jQuery, high-performance SPA routing    |
| **bestzip**                | Used for automated production theme bundling |

## 🚀 Production Setup

Complete these steps after activating the theme. The page slugs below are part of the theme's routing and meta-box logic; keep them exactly as shown.

### Required Pages

Create these WordPress Pages with the matching title, slug, and template:

| Page title | Required slug | Template | Purpose |
| :--------- | :------------ | :------- | :------ |
| Home | `home` | Set as **Homepage** in **Settings > Reading** | Loads the portfolio homepage. |
| About | `about` | **About Page** | Profile, updates, tech information, work experience, and contact details. |
| Projects | `projects` | **Projects Page** | Filterable list of Projects. |
| Reading | `reading` | **Reading Page** | Filterable list of Reading Lists. |
| Resources | `resources` | **Resources Page** | Filterable list of Resources. |
| Tools | `tools` | **Tools Page** | Filterable list of Tech Stack entries. |
| Writing | `writing` | **Writing Page** | Blog posts and the recent writing grid. |
| Contact | `contact` | Regular page | Contact fields and the contact modal. |

Set **Home** as the static front page. Do not use `blog` as the Writing page slug. The theme uses the `writing` page and WordPress-generated permalinks. If the site already has content under another slug, change that page's slug before creating navigation links.

### Navigation Menus

Create and assign menus under **Appearance > Menus**:

- **Primary Menu** (`primary_menu`): Home, About, Projects, and Writing.
- **Resources Menu** (`resources_menu`): Reading, Resources, and Tools.
- **Connect Menu** (`connect_menu`): Contact and external social links.

The custom navigation walker uses each menu item's title as its route key. Use the page titles above, or ensure custom menu titles match the corresponding slugs. After creating or changing pages, visit **Settings > Permalinks** and click **Save Changes** once to refresh rewrite rules.

### Naming Compatibility Rules

- Page template display names use title case: **About Page**, **Projects Page**, **Reading Page**, **Resources Page**, **Tools Page**, **Writing Page**, and **Single Post Page**.
- `writing` is the canonical Writing page slug. `blog` is only a legacy route alias and is not the required page slug.
- CPT keys and taxonomy keys in the tables below are canonical database identifiers. Do not rename them on an existing site: `techtools`, `resource_tools`, `reading_list`, `personal_update`, `projects`, and `working_experience` are referenced by queries, AJAX, meta boxes, and stored content.
- The `wedo_` function/meta-key prefix is legacy naming retained for compatibility with existing saved data. New theme-level functions should use the `happy_` or `happy_portfolio_` prefix; do not rename existing `wedo_` functions or meta keys without a migration.
- The contact template filenames contain the legacy `model` spelling (`content-contact-model.php` and `content-contact-model-content.php`), while the rendered component is a contact **modal**. This spelling is retained because WordPress loads these filenames directly.
- `content-tool-item.php` and `content-list-tool-item.php` are shared presentation templates used by Projects, Resources, and Tech Stack entries; their generic names are intentional.

## 🧱 Custom Post Types

The theme registers **6 custom post types (CPTs)**. Add entries from their own WordPress admin menu. CPT archive behavior and frontend placement are listed below.

| CPT label | Post type key | Single URL slug | Archive | Add content here | Appears on |
| :-------- | :------------ | :-------------- | :------ | :--------------- | :--------- |
| Projects | `projects` | `project-item` | No | Title, featured image, optional external link, short description, Project Category | Projects page and homepage Projects section |
| Reading Lists | `reading_list` | `reading-list` | Yes | Title, featured image, external link, Reading List Category | Reading page and homepage Reading List section |
| Resources | `resource_tools` | `resources` | Yes | Title, editor content, featured image, optional external link, Resource Category | Resources page |
| Tech Stack | `techtools` | `tech-tools` | Yes | Title, featured image, optional external link, short description, Tech Tool Category | Tools page and About page tech tools section |
| Personal Updates | `personal_update` | `personal-update` | Yes | Title, editor content, featured image, optional external link | About page and homepage updates section |
| Working Experience | `working_experience` | `working-experience` | No | Title, editor content, featured image | About page Career section |

The CPT key is the internal identifier used by the theme; the Single URL slug is the public URL segment. CPT archive links are not required for the main portfolio navigation because the dedicated pages above render the relevant listings and filters.

### Taxonomies

The theme registers four taxonomies for filtering:

| Taxonomy | Used by | URL slug |
| :------- | :------ | :------- |
| `reading_list_category` | `reading_list` | `reading-list-category` |
| `resource_category` | `resource_tools` | `resource-category` |
| `techtool_category` | `techtools` | `techtool-category` |
| `project_category` | `projects` | `project-category` |

Create terms before assigning them to entries. The Reading, Resources, Tools, and Projects pages use these taxonomies in their filter controls.

### About and Contact Fields

- Edit the `about` page to use the About-specific meta boxes for portfolio history, tech information, and career information.
- Add Tech Stack and Working Experience entries separately; the About page loads them into the matching sections.
- Edit the `contact` page to fill in contact email, calendar, Twitter, GitHub, and related contact fields used by the contact modal.
- Personal Updates are managed from the **Personal Updates** CPT menu but are displayed within About rather than on a separate required page.

### Blog Writing

Use normal WordPress **Posts** for Writing. The `writing` page displays the post grid; individual posts use the theme's single-post layout. Set the site's **Posts page** only if another plugin or WordPress workflow needs it; the portfolio navigation uses the required `writing` page.

---

## 🏗️ Internal Structure & Architecture

The theme utilizes a standardized WordPress folder structure with key extensions for modularity and logic separation.

### 📁 Core Logic (`/inc`)

This folder houses all PHP files responsible for theme registration, custom functionality, and hooks. This keeps the root `functions.php` file clean.

- `inc/theme-setup.php`: Enqueuing assets, registering navigation menus, basic theme support.
- `inc/custom-post-types/`: Definition and registration of all custom content types (e.g., `cpt-working-experience.php`).
- `inc/meta-boxes/`: Registration and handling of custom fields for the Admin Dashboard (e.g., `meta-about.php`).

### 📁 Views & Components (`/template-parts`)

All reusable HTML partials and components are stored here. This follows the official WordPress component standard.

- `template-parts/components/`: Small, reusable elements (e.g., `app-navigation.php`).
- `template-parts/pages/`: Content blocks specific to full-page layouts (e.g., `content-home.php`).

### 📁 Assets & Build (`/assets`)

This contains all static files, including source and compiled CSS/JS.

- `assets/css/input.css`: The main source file where Tailwind directives are included.
- `assets/css/output.css`: The compiled and purged CSS file used in production.
- `assets/js/`: Individual JavaScript modules (e.g., `main.js`, `helper.js`).

---

## ⚙️ Local Development Setup

To start developing and compiling assets, you need Node.js and NPM installed.

1.  **Clone the Repository:**

    ```bash
    git clone [https://github.com/IndrajeetS/wordpress-happy-portfolio](https://github.com/IndrajeetS/wordpress-happy-portfolio)
    cd happy-portfolio-theme
    ```

2.  **Install Dependencies:**

    ```bash
    npm install
    ```

    _(This installs PostCSS, Tailwind, esbuild, and the `bestzip` utility.)_

3.  **Start the Watcher:**
    Run this command during development. It watches your files and automatically recompiles both CSS and JS whenever you make changes.
    ```bash
    npm run watch
    ```

---

## 📦 Building for Production

To create a clean, compressed, production-ready version of the theme, use the `bundle` script.

1.  **Create the Zip File:**

    ```bash
    npm run bundle
    ```

2.  **Output:** This script performs the final Tailwind purge (removing all unused CSS), bundles and minifies all JavaScript using `esbuild`, and creates a clean zip file named `happy-creative-resume-wp.zip` in the root directory, excluding `node_modules`, config files, and other development dependencies.

3.  **Deployment:** Upload the resulting `happy-creative-resume-wp.zip` via the WordPress admin panel (**Appearance** > **Themes** > **Add New**).

---

## ✅ Validation

PHP syntax checks were run to confirm the theme files remain valid after the empty-state update.

```bash
php -l inc/render-writing-grid.php
```

Result:

```text
No syntax errors detected in inc/render-writing-grid.php
```

---

## 👤 Author

- **[Indrajeet Singh]** - [https://indrajeet.space/]
