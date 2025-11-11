# ODFS Frontend Setup for Netlify

This file contains the configuration needed to serve the ODFS frontend on Netlify while communicating with the Render backend.

## Setup Instructions

### 1. Create Frontend Repository
```bash
mkdir odfs-frontend
cd odfs-frontend
git init
```

### 2. Copy Frontend Assets
Copy these directories and files into `odfs-frontend/`:
- `assets/` - CSS, JS, images
- `plugins/` - Third-party libraries (Bootstrap, AdminLTE, etc.)
- `dist/` - Compiled/minified resources
- `uploads/` - User-generated content (optional)
- `*.html` files - Homepage, about, etc.
- `_redirects` - Netlify routing rules
- `netlify.toml` - Build and environment config

### 3. Create netlify.toml
```toml
[build]
  publish = "."
  command = "echo 'Frontend ready'"

[[redirects]]
  from = "/api/*"
  to = "https://odfs-backend.onrender.com/:splat"
  status = 200
  force = true

[[redirects]]
  from = "/*"
  to = "/index.html"
  status = 200

[context.production]
  environment = { API_URL = "https://odfs-backend.onrender.com" }

[context.develop]
  environment = { API_URL = "http://localhost/odfs" }
```

### 4. Create _redirects
```
/api/* https://odfs-backend.onrender.com/:splat 200
/* /index.html 200
```

### 5. Update JavaScript to Use Backend
In your main JS file (e.g., `assets/js/scripts.js`):

```javascript
// Detect environment and set API base URL
const isProduction = !window.location.hostname.includes('localhost');
const API_BASE = isProduction 
  ? 'https://odfs-backend.onrender.com'
  : 'http://localhost/odfs';

// Use in AJAX calls
$.ajax({
  url: API_BASE + '/classes/Login.php',
  method: 'POST',
  data: { f: 'login', username, password },
  success: function(resp) { /* ... */ }
});
```

### 6. Deploy to Netlify
```bash
git add .
git commit -m "Frontend setup for Netlify"
git push origin main

# Then in Netlify UI:
# 1. Connect GitHub repo
# 2. Set build command: (leave empty)
# 3. Set publish directory: .
# 4. Add environment variable: API_URL = https://odfs-backend.onrender.com
# 5. Deploy
```

## Troubleshooting

**CORS errors in browser console**
- Backend CORS headers not set. Check `config.php` has CORS headers.
- Ensure your Netlify domain is in the `$allowed_origins` array in backend.

**API returns 404**
- Check backend URL is correct
- Verify endpoint path matches your PHP file locations
- Check backend logs on Render dashboard

**Frontend shows blank page**
- Check Network tab in DevTools for failed requests
- Ensure `_redirects` file exists in Netlify root
- Verify `netlify.toml` is present
