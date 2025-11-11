# For Render.com - Use the Dashboard Instead of render.yaml

Since Render's YAML configuration has limited support for PHP, it's easier to configure via the web dashboard.

## Manual Setup on Render Dashboard

### Step 1: Create Web Service
1. Go to https://dashboard.render.com
2. Click **New** → **Web Service**
3. Connect your GitHub repository

### Step 2: Configure Service
- **Name:** `odfs-backend`
- **Region:** `Singapore` (closest to Asia)
- **Branch:** `main`
- **Build Command:** (leave empty - no build needed)
- **Start Command:** 
  ```
  php -S 0.0.0.0:10000 -t .
  ```
- **Plan:** `Standard` or `Pro` (depends on traffic)

### Step 3: Add Environment Variables
In the **Environment** section, add these:

| Key | Value | Private |
|-----|-------|---------|
| `DB_SERVER` | `your-host.mysql.planetscale.com` | No |
| `DB_USERNAME` | `your-username` | No |
| `DB_PASSWORD` | `your-secure-password` | ✅ Yes |
| `DB_NAME` | `odfs_db` | No |
| `BASE_URL` | `https://your-backend.onrender.com/` | No |
| `APP_TIMEZONE` | `Asia/Manila` | No |
| `APP_ENV` | `production` | No |

### Step 4: Deploy
Click **Create Web Service** - Render will deploy automatically.

### Step 5: Monitor
- Go to **Logs** to see deployment progress
- Once green, your backend is live at `https://your-backend.onrender.com`

---

## Alternative: Use Render Native PHP Support

If dashboard config doesn't work, use this `render.yaml` instead:

```yaml
services:
  - type: web
    name: odfs-backend
    env: php
    plan: standard
    startCommand: php -S 0.0.0.0:10000 -t .
    envVars:
      - key: DB_SERVER
        value: your-mysql-host.mysql.planetscale.com
      - key: DB_USERNAME
        value: your-username
      - key: DB_PASSWORD
        isPrivate: true
        value: your-password
      - key: DB_NAME
        value: odfs_db
      - key: BASE_URL
        value: https://odfs-backend.onrender.com/
```

---

## If render.yaml Still Fails:

**Delete render.yaml** and use Render's web dashboard instead:
```powershell
git rm render.yaml
git commit -m "Remove render.yaml - use dashboard configuration"
git push origin main
```

Then configure manually on the Render dashboard (Step 1-5 above).

---

## Troubleshooting

**Error: "No available runtime"**
- Delete `render.yaml`
- Use Render dashboard to configure

**Error: "Service failed to start"**
- Check **Logs** tab for PHP errors
- Verify start command: `php -S 0.0.0.0:10000 -t .`
- Check environment variables are set

**Error: "Cannot connect to database"**
- Verify PlanetScale credentials in environment variables
- Check database is created and imported with data
- Test connection locally first
