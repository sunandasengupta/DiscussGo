# Railway Quick Setup Checklist

Copy-paste this into your terminal to quickly set up for Railway:

## 1. Commit Recent Changes
```powershell
cd c:\xampp\htdocs\odfs
git add .
git commit -m "Update for Railway deployment"
git push origin main
```

## 2. Create Railway Project
- Go to https://railway.app
- Click **New Project**
- Click **Provision PostgreSQL**

## 3. Add Your GitHub Repo
- In Railway, click **+ New**
- Select **GitHub Repo**
- Search: `sunandasengupta/DiscussGo`
- Add it

## 4. Configure PHP Service
- Click on the GitHub service
- Go to **Settings** tab
- **Start Command:** `php -S 0.0.0.0:$PORT -t .`

## 5. Add Environment Variables
In Railway **Variables** tab, add:
```
DB_HOST=${DATABASE_URL_HOST}
DB_PORT=${DATABASE_URL_PORT}
DB_NAME=${DATABASE_NAME}
DB_USERNAME=${DATABASE_PUBLIC_URL_USER}
DB_PASSWORD=${DATABASE_PUBLIC_URL_PASSWORD}
BASE_URL=https://your-railway-domain.railway.app/
APP_TIMEZONE=Asia/Manila
APP_ENV=production
```

(Copy your actual Railway domain from **Domains** tab)

## 6. Deploy
Click **Deploy** button and wait for green status.

## 7. Import Database
Get PostgreSQL connection from **Postgres** service → **Connect** tab:

```bash
# Example (replace with your actual credentials)
psql postgresql://postgres:password@rail.proxy.rlwy.net:12345/railway < database/odfs_db.sql
```

## 8. Test Backend
Open in browser: `https://your-railway-domain.railway.app/`
Should show ODFS homepage.

## 9. Update Netlify
1. Go to https://app.netlify.com
2. Site settings → Build & deploy → Environment
3. Update `API_URL=https://your-railway-domain.railway.app`

## 10. Test Complete Flow
1. Frontend loads ✓
2. Can register user ✓
3. Can log in ✓
4. Can create post ✓
5. No console errors ✓

---

## Environment Variable Reference

| Variable | Railway Auto-Provides | Example |
|----------|----------------------|---------|
| `DB_HOST` | `${DATABASE_URL_HOST}` | rail.proxy.rlwy.net |
| `DB_PORT` | `${DATABASE_URL_PORT}` | 12345 |
| `DB_NAME` | `${DATABASE_NAME}` | railway |
| `DB_USERNAME` | `${DATABASE_PUBLIC_URL_USER}` | postgres |
| `DB_PASSWORD` | `${DATABASE_PUBLIC_URL_PASSWORD}` | (auto) |

---

That's it! Railway handles everything else automatically. 🚀
