# 🚀 ODFS Quick Deployment Guide

Deploy your ODFS app on Render (Backend) + Netlify (Frontend) in 5 minutes.

## Quick Start

### 1. Configure Your Deployment
**On Windows:**
```powershell
.\deploy.bat
```

**On Mac/Linux:**
```bash
chmod +x deploy.sh
./deploy.sh
```

This script will ask for:
- Backend URL (your Render app URL)
- Frontend URL (your Netlify app URL)
- Database credentials

### 2. Commit & Push
```bash
git add .
git commit -m "Setup deployment config"
git push origin main
```

### 3. Deploy Backend on Render

1. Go to [https://render.com](https://render.com)
2. Sign up and connect your GitHub
3. Click **New** → **Web Service**
4. Select your repository
5. Configure:
   - **Name:** `odfs-backend`
   - **Runtime:** PHP
   - **Start Command:** `php -S 0.0.0.0:10000 -t .`
   - **Plan:** Standard (recommended)
6. In **Environment** section, add these variables:
   ```
   DB_SERVER=your-database-host
   DB_USERNAME=your-db-user
   DB_PASSWORD=your-secure-password (mark as Private)
   DB_NAME=odfs_db
   BASE_URL=https://your-backend.onrender.com/
   ```
7. Click **Create Web Service**
8. Wait for deployment (2-3 minutes)
9. Copy your backend URL (e.g., `https://odfs-backend.onrender.com`)

### 4. Set Up Database
**Import the SQL dump:**

```bash
# Using MySQL command line
mysql -h <DB_HOST> -u <DB_USER> -p <DB_NAME> < database/odfs_db.sql
```

Or use your database provider's UI to import `database/odfs_db.sql`.

### 5. Deploy Frontend on Netlify

1. Go to [https://netlify.com](https://netlify.com)
2. Sign up and connect your GitHub
3. Click **Add new site** → **Import an existing project**
4. Select your repository
5. Configure:
   - **Build command:** (leave empty)
   - **Publish directory:** `.` (current directory)
6. In **Environment** section, add:
   ```
   API_URL=https://your-backend.onrender.com
   ```
7. Click **Deploy site**
8. Wait for deployment (1-2 minutes)
9. Your frontend URL will appear (e.g., `https://your-app.netlify.app`)

### 6. Update CORS Settings
Update `config.php` with your Netlify URL:

```php
// In config.php, update the $allowed_origins array:
$allowed_origins = array(
    'http://localhost',
    'https://your-app.netlify.app',  // Add your Netlify URL here
);
```

Then push to GitHub:
```bash
git add config.php
git commit -m "Update CORS for production"
git push
```

Both Render and Netlify will auto-redeploy.

---

## Verify Everything Works

### ✅ Backend Check
```bash
# Replace with your Render URL
curl https://odfs-backend.onrender.com/

# Should return HTML (homepage)
```

### ✅ Frontend Check
Open your Netlify URL in browser (e.g., `https://your-app.netlify.app`)
- Page should load without errors
- Check browser console (F12) for any errors
- Try logging in to test API connectivity

### ✅ Database Check
```bash
# Check if database has data
mysql -h <DB_HOST> -u <DB_USER> -p <DB_NAME> -e "SELECT * FROM category_list LIMIT 1;"

# Should return data
```

---

## Troubleshooting

| Problem | Solution |
|---------|----------|
| **Backend returns 502 Bad Gateway** | Check Render logs. Ensure PHP start command is correct. |
| **Database connection fails** | Verify DB credentials in environment variables. Check if DB exists. |
| **CORS errors in browser** | Add your Netlify URL to `$allowed_origins` in `config.php` and push. |
| **API returns 404** | Check backend URL matches Netlify `API_URL` environment variable. |
| **Frontend shows blank page** | Check Network tab in DevTools. Ensure `_redirects` file exists. |

---

## File Reference

| File | Purpose |
|------|---------|
| `.env.example` | Template for environment variables |
| `initialize.php` | Reads `.env` for configuration |
| `config.php` | Sets up CORS headers |
| `netlify.toml` | Netlify build & routing config |
| `_redirects` | Netlify SPA routing rules |
| `deploy.sh` / `deploy.bat` | Helper script (run once) |
| `DEPLOYMENT.md` | Full deployment guide |

---

## Environment Variables Reference

### Render Backend (.env)
```
DB_SERVER=your-mysql-host
DB_USERNAME=your-username
DB_PASSWORD=your-password
DB_NAME=odfs_db
BASE_URL=https://your-backend.onrender.com/
APP_TIMEZONE=Asia/Manila
APP_ENV=production
```

### Netlify Frontend
```
API_URL=https://your-backend.onrender.com
```

---

## Next Steps

After deployment:
1. Test creating a user account
2. Create a test post
3. Add a comment
4. Try admin login
5. Check all navigation links work
6. Verify file uploads work (avatars, banners)

---

## Need Help?

- **Render Docs:** https://render.com/docs
- **Netlify Docs:** https://docs.netlify.com
- **PHP MySQL:** https://www.php.net/manual/en/book.mysqli.php

Good luck! 🎉
