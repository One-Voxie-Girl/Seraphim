# Make Us Care 3.0 (WordPress Theme)

- Bootstrap 5 (via CDN) for layout and grid
- Unbounded font from Google Fonts
- SCSS structure in `/assets/scss` with variables and typography
- Compiled CSS at `/assets/css/style.css`
- jQuery + jQuery UI enqueued (from WordPress core)
- Base templates: header, footer, index, single, archive, 404, sidebar

## Install
1. Download the ZIP and upload it in **Appearance → Themes → Add New → Upload Theme**.
2. Activate **Make Us Care 3.0**.
3. (Optional) If you want to actively author SCSS, run a Sass watcher locally and overwrite `/assets/css/style.css`:
   ```bash
   sass --watch assets/scss/style.scss:assets/css/style.css
   ```

