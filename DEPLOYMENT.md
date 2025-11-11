# ODFS Deployment Guide - Render (Backend) & Netlify (Frontend)

## Project Architecture
- **Backend**: PHP monolithic application (Render)
- **Frontend**: Static HTML/JS assets (Netlify)
- **Database**: MySQL/MariaDB (Render PostgreSQL or external MySQL)

---

## Part 1: Backend Deployment on Render

### Prerequisites
1. A Render account (https://render.com)
2. A MySQL/PostgreSQL database (use Render's free PostgreSQL or external MySQL like PlanetScale)
3. Your GitHub repository with the ODFS code

### Step 1: Prepare Your GitHub Repository
```bash
# Initialize git (if not already done)
git init
git add .
git commit -m "Initial commit: ODFS backend"
git branch -M main
git remote add origin https://github.com/your-username/odfs-backend.git
git push -u origin main
```

### Step 2: Create a Render Web Service
1. Go to [Render Dashboard](https://dashboard.render.com)
2. Click **New** → **Web Service**
3. Connect your GitHub repository (odfs-backend)
4. Configure:
   - **Name**: `odfs-backend`
   - **Region**: `Singapore` (closest to Philippines/Asia)
   - **Branch**: `main`
   - **Runtime**: `PHP`
   - **Build Command**: (leave empty, no build needed)
   - **Start Command**: 
     ```
     php -S 0.0.0.0:10000 -t .
     ```
   - **Plan**: `Standard` (recommended for production)

### Step 3: Set Environment Variables on Render
In the Web Service settings, add under **Environment**:

| Key | Value | Notes |
|-----|-------|-------|
| `DB_SERVER` | `your-db-host` | From your MySQL provider (PlanetScale, Render, etc.) |
| `DB_USERNAME` | `your-db-user` | Database username |
| `DB_PASSWORD` | `your-secure-password` | ⚠️ Mark as **Private** |
| `DB_NAME` | `odfs_db` | Database name |
| `BASE_URL` | `https://odfs-backend.onrender.com/` | Will be auto-generated |
| `APP_TIMEZONE` | `Asia/Manila` | Your timezone |

### Step 4: Set Up Database
**Option A: Use Render's PostgreSQL**
1. Create a PostgreSQL database on Render
2. Use a MySQL-to-PostgreSQL migration tool or adapt code for PostgreSQL

**Option B: Use External MySQL (PlanetScale, Supabase, AWS RDS)**
1. Create a MySQL database
2. Import `database/odfs_db.sql`:
   ```bash
   mysql -h your-host -u your-user -p your-database < database/odfs_db.sql
   ```

### Step 5: Deploy
1. Push changes to GitHub:
   ```bash
   git add .
   git commit -m "Configure for Render deployment"
   git push
   ```
2. Render will auto-deploy on push
3. Monitor deployment at **Render Dashboard** → your web service

### Verify Backend is Running
```bash
curl https://odfs-backend.onrender.com/
# Should return the homepage HTML
```

---

## Part 2: Frontend Deployment on Netlify

### Step 1: Prepare Frontend Static Files
Create a separate repository for frontend, or use a subdirectory:

```bash
# Create a folder for frontend
mkdir odfs-frontend
cd odfs-frontend

# Copy only the static/public files you want to serve
# Include: *.html, *.php (will be served as-is), assets/, plugins/, dist/
```

**What to include in frontend repo:**
- `index.html` (or create a new one)
- `about.html`, `home.html`, etc.
- `assets/` (CSS, JS)
- `plugins/` (3rd-party libs)
- `dist/` (compiled resources)
- `uploads/` (if serving user-generated content)

**What NOT to include:**
- `classes/` (backend logic)
- `inc/` (server-side includes)
- `database/` (DB migrations)
- `.env` files with secrets

### Step 2: Create a Netlify-Compatible Frontend Repo
```bash
cd odfs-frontend

# Create netlify.toml for SPA routing and API proxying
cat > netlify.toml << 'EOF'
[build]
  publish = "."
  command = "echo 'Frontend ready'"

[context.production]
  environment = { API_URL = "https://odfs-backend.onrender.com" }

[context.develop]
  environment = { API_URL = "http://localhost/odfs" }

# API proxy rules
[[redirects]]
  from = "/api/*"
  to = "https://odfs-backend.onrender.com/:splat"
  status = 200
  force = true

# SPA routing
[[redirects]]
  from = "/*"
  to = "/index.html"
  status = 200
EOF

# Create _redirects file (Netlify format)
cat > _redirects << 'EOF'
/api/* https://odfs-backend.onrender.com/:splat 200
/* /index.html 200
EOF

git init
git add .
git commit -m "Initial frontend commit"
git remote add origin https://github.com/your-username/odfs-frontend.git
git push -u origin main
```

### Step 3: Deploy to Netlify
**Option A: Netlify UI**
1. Go to [Netlify](https://netlify.com)
2. Click **Add new site** → **Import an existing project**
3. Choose GitHub and select `odfs-frontend` repo
4. Configure:
   - **Publish directory**: `.` (or your static folder)
   - **Build command**: `echo "No build needed"`
5. Add environment variables:
   - `API_URL`: `https://odfs-backend.onrender.com`
6. Click **Deploy**

**Option B: Netlify CLI**
```bash
npm install -g netlify-cli

cd odfs-frontend
netlify login
netlify init
netlify deploy --prod
```

### Step 4: Configure API Calls in Frontend
Update your JavaScript to use the backend API:

**In `assets/js/scripts.js` or similar:**
```javascript
const API_BASE_URL = window.location.hostname === 'localhost' 
  ? 'http://localhost/odfs'
  : 'https://odfs-backend.onrender.com';

// Example AJAX call
function login(username, password) {
  $.ajax({
    url: API_BASE_URL + '/classes/Login.php',
    method: 'POST',
    data: { f: 'login_user', username, password },
    success: function(resp) {
      // Handle response
    }
  });
}
```

---

## Part 3: Cross-Origin Setup (CORS)

### Configure Backend for Frontend Requests
Add CORS headers to your `config.php`:

```php
<?php
// ... existing code ...

// Enable CORS for Netlify frontend
header("Access-Control-Allow-Origin: https://your-netlify-site.netlify.app");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// ... rest of config ...
?>
```

---

## Part 4: Deployment Checklist

### Backend (Render)
- [ ] GitHub repo created and pushed
- [ ] Render Web Service created
- [ ] Environment variables set (DB credentials, BASE_URL)
- [ ] Database imported (`odfs_db.sql`)
- [ ] Backend responds to `https://odfs-backend.onrender.com/`
- [ ] CORS headers configured
- [ ] Test login endpoint: `curl -X POST https://odfs-backend.onrender.com/classes/Login.php`

### Frontend (Netlify)
- [ ] Frontend repo created with static files
- [ ] `netlify.toml` and `_redirects` configured
- [ ] Deployed to Netlify
- [ ] Environment variable `API_URL` set to Render backend
- [ ] Frontend accessible at `https://your-site.netlify.app`
- [ ] AJAX calls use correct API endpoint
- [ ] Test a user action (login, create post)

### Integration Tests
- [ ] User can register
- [ ] User can log in
- [ ] User can create/view posts
- [ ] User can comment on posts
- [ ] Admin can manage categories/users
- [ ] Images upload and display correctly
- [ ] No CORS or 404 errors in browser console

---

## Part 5: Troubleshooting

### Backend issues
**Error: "Connection refused" to database**
- Check `DB_SERVER`, `DB_USERNAME`, `DB_PASSWORD` environment variables
- Ensure database is running and accessible
- Test connection: `mysql -h DB_SERVER -u DB_USERNAME -p DB_NAME`

**Error: "File not found" on Render**
- Ensure all PHP files are in the repo
- Check `START_COMMAND` in Render settings
- Render should run: `php -S 0.0.0.0:10000 -t .`

### Frontend issues
**Error: "CORS policy: No 'Access-Control-Allow-Origin' header"**
- Add CORS headers to backend `config.php`
- Test: `curl -I https://odfs-backend.onrender.com/`

**Error: API calls return 404**
- Check `API_URL` environment variable in frontend
- Verify backend endpoint paths match your code

### Database Migration
**If switching from MySQL to PostgreSQL:**
1. Export data from MySQL: `mysqldump -u user -p database > backup.sql`
2. Convert to PostgreSQL format (use online tools or `pgloader`)
3. Import to PostgreSQL
4. Update `DB_*` environment variables

---

## Part 6: Maintenance & Updates

### Deploy New Changes
```bash
# Backend
git add .
git commit -m "Your changes"
git push  # Render auto-deploys

# Frontend
git add .
git commit -m "Your changes"
git push  # Netlify auto-deploys
```

### Database Backups
```bash
# Backup MySQL
mysqldump -h your-host -u user -p database > backup_$(date +%s).sql

# Restore
mysql -h your-host -u user -p database < backup_*.sql
```

### Monitor Logs
- **Render**: Dashboard → Web Service → **Logs**
- **Netlify**: Dashboard → Site → **Deploys** → click on deployment

---

## Summary
Your ODFS application is now:
- **Backend** running on Render (PHP server)
- **Frontend** running on Netlify (static CDN)
- **Database** managed separately (MySQL/PostgreSQL)

Happy deploying! 🚀
