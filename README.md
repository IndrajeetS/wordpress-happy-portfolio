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

## 👤 Author

- **[Indrajeet Singh]** - [https://indrajeet.space/]
