# Railway Deployment Guide

Railway is the simplest way to deploy your ODFS application. It handles both database and backend in one platform.

## Architecture
```
┌─────────────────────────────────────┐
│      Netlify Frontend               │
│  (your-app.netlify.app)            │
└──────────────────┬──────────────────┘
                   │ API Calls
                   ↓
┌─────────────────────────────────────┐
│      Railway Backend (PHP)          │
│  (your-app.railway.app)            │
└──────────────────┬──────────────────┘
                   │ Query
                   ↓
┌─────────────────────────────────────┐
│    Railway PostgreSQL Database      │
│  (Auto-managed by Railway)          │
└─────────────────────────────────────┘
```

---

## Step-by-Step Deployment

### STEP 1: Sign Up for Railway
1. Go to https://railway.app
2. Click **Start Free**
3. Sign up with GitHub
4. Authorize Railway to access your GitHub account

### STEP 2: Create New Project
1. Click **New Project**
2. Choose **Provision PostgreSQL**
3. Railway creates database + project automatically

### STEP 3: Add Your Backend Code
1. In Railway dashboard, click **+ New**
2. Select **GitHub Repo**
3. Search for `sunandasengupta/DiscussGo`
4. Click to add it

### STEP 4: Configure the PHP Service
1. Click on the GitHub service in Railway
2. Go to **Settings** tab
3. Set **Start Command**:
   ```
   php -S 0.0.0.0:$PORT -t .
   ```
4. Set **Publish Directory**: `.` (everything)

### STEP 5: Add Environment Variables
1. Still in Railway, go to **Variables** tab
2. Add these variables:

| Key | Value | Notes |
|-----|-------|-------|
| `DB_HOST` | `${DATABASE_URL_HOST}` | Railway auto-provides |
| `DB_PORT` | `${DATABASE_URL_PORT}` | Railway auto-provides |
| `DB_NAME` | `${DATABASE_NAME}` | Railway auto-provides |
| `DB_USERNAME` | `${DATABASE_PUBLIC_URL_USER}` | Railway auto-provides |
| `DB_PASSWORD` | `${DATABASE_PUBLIC_URL_PASSWORD}` | Railway auto-provides |
| `BASE_URL` | Copy from Railway **Domains** tab | e.g., https://discussgo-production.up.railway.app/ |
| `APP_TIMEZONE` | `Asia/Manila` | Your timezone |
| `APP_ENV` | `production` | Environment |

### STEP 6: Update Code to Use Railway Variables
Your code already supports `.env` files. Railway automatically injects variables as environment variables.

The `initialize.php` file reads these automatically:
```php
if(!defined('DB_SERVER')) define('DB_SERVER', getenv('DB_HOST') ?: "localhost");
if(!defined('DB_USERNAME')) define('DB_USERNAME', getenv('DB_USERNAME') ?: "root");
if(!defined('DB_PASSWORD')) define('DB_PASSWORD', getenv('DB_PASSWORD') ?: "");
if(!defined('DB_NAME')) define('DB_NAME', getenv('DB_NAME') ?: "odfs_db");
```

### STEP 7: Import Your Database
After Railway deploys, import your SQL schema:

1. Go to Railway dashboard → **Postgres** service
2. Click **Connect** tab
3. Copy the PostgreSQL connection string
4. In terminal, run:
```bash
psql postgresql://user:password@host:port/database < database/odfs_db.sql
```

Or manually in Railway's Postgres UI:
1. Click **Data** tab
2. Click **Execute Query**
3. Copy-paste contents of `database/odfs_db.sql`

### STEP 8: Deploy
1. Click **Deploy** button in Railway
2. Watch the logs - should show PHP server starting
3. Once green, copy your domain from **Domains** tab

### STEP 9: Update Frontend (Netlify)
1. Go to Netlify dashboard
2. Go to **Site settings** → **Build & deploy** → **Environment**
3. Update `API_URL` to your Railway domain:
   ```
   API_URL=https://your-railway-domain.railway.app
   ```
4. Netlify will auto-redeploy

### STEP 10: Update CORS in Backend
In `config.php`, update the allowed origins:
```php
$allowed_origins = array(
    'http://localhost',
    'https://your-app.netlify.app',  // Your Netlify domain
    'https://your-app.railway.app'   // Your Railway domain
);
```

Then push to GitHub:
```bash
git add config.php
git commit -m "Update CORS for production domains"
git push origin main
```

---

## Verification Checklist

- [ ] Railway shows service as **Running** (green)
- [ ] Railway shows **Active Deployment**
- [ ] Database is **Healthy**
- [ ] Can access backend at `https://your-domain.railway.app/`
- [ ] Netlify frontend loads at `https://your-app.netlify.app`
- [ ] No CORS errors in browser console (F12)
- [ ] Can register a new user
- [ ] Can log in
- [ ] Can create a post
- [ ] Can view categories
- [ ] File uploads work (avatars, etc.)

---

## Troubleshooting

### Problem: Backend shows 502 Bad Gateway
**Solution:**
1. Check Railway logs (click service → Logs tab)
2. Look for PHP errors
3. Verify start command is correct: `php -S 0.0.0.0:$PORT -t .`

### Problem: Database connection fails
**Solution:**
1. Verify environment variables match Railway's Postgres service
2. Check database is imported (has data)
3. Test locally first: `psql <connection-string>`

### Problem: CORS errors in browser
**Solution:**
1. Update `config.php` with your Netlify domain
2. Push changes to GitHub
3. Wait for Railway to redeploy

### Problem: Frontend can't reach backend
**Solution:**
1. Verify `API_URL` environment variable in Netlify
2. Test backend directly: `curl https://your-railway-domain.railway.app/`
3. Check browser Network tab for actual API URL being called

---

## Cost

Railway offers:
- **Free tier:** $5 credit/month (usually enough for testing)
- **After credit:** ~$5-10/month for a small PHP app
- No credit card needed to start

---

## Next Steps After Deployment

1. Test all features work
2. Monitor Railway logs for errors
3. Set up automatic backups of database
4. Configure custom domain (optional)
5. Monitor cost usage on Railway dashboard

---

## Quick Links

- Railway Dashboard: https://railway.app/dashboard
- Netlify Dashboard: https://app.netlify.com
- Railway Docs: https://docs.railway.app
- PostgreSQL Docs: https://www.postgresql.org/docs/

Good luck! 🚀
