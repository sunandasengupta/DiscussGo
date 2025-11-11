# Database Deployment Options for ODFS

Choose the best database hosting option based on your needs, budget, and deployment strategy.

---

## Option 1: **Render PostgreSQL** (Free, Recommended for Render users)

### Pros:
✅ Free tier available  
✅ Integrated with Render (single dashboard)  
✅ Auto-backups  
✅ Easy environment variable setup  

### Cons:
❌ Free tier limited (5GB storage, shared resources)  
❌ Requires code migration from MySQL to PostgreSQL  

### Setup Steps:
1. Go to [Render Dashboard](https://dashboard.render.com)
2. Click **New** → **PostgreSQL**
3. Configure:
   - **Name**: `odfs-db`
   - **Plan**: Free (or Starter for production)
4. Render creates connection string automatically
5. Add to environment variables:
   ```
   DB_SERVER=dpg-xxxxx.onrender.com
   DB_USERNAME=odfs_user
   DB_PASSWORD=auto-generated
   DB_NAME=odfs_db
   ```

### ⚠️ Note:
Your PHP code uses MySQL syntax. To use PostgreSQL, you'd need to:
- Modify query syntax (OR use `mysqli` which works with both)
- Convert schema (use tools like `pgloader`)

---

## Option 2: **PlanetScale** (MySQL-Compatible, Recommended)

### Pros:
✅ **FREE tier with 5 GB storage**  
✅ 100% MySQL compatible (no code changes!)  
✅ No infrastructure management  
✅ Automated backups & branching  
✅ Perfect for your PHP app  

### Cons:
❌ Free tier has limits (5GB only)  
⚠️ Some advanced MySQL features not supported  

### Setup Steps:

1. **Sign up:** https://planetscale.com
2. **Create a database:**
   - Click **Create new database**
   - Name: `odfs_db`
   - Region: **Singapore** (closest to Philippines)
   - Plan: **Free**
3. **Get connection string:**
   - Go to your database → **Connect**
   - Copy credentials from "Connect from Render" section
4. **Add to Render environment:**
   ```
   DB_SERVER=pscale_xxxxxx.mysql.planetscale.com
   DB_USERNAME=your-username
   DB_PASSWORD=your-password
   DB_NAME=odfs_db
   ```
5. **Import database:**
   ```bash
   mysql -h pscale_xxxxxx.mysql.planetscale.com -u your-username -p odfs_db < database/odfs_db.sql
   ```

---

## Option 3: **AWS RDS (MySQL)**

### Pros:
✅ Production-grade  
✅ Auto-scaling, high availability  
✅ 1 year free tier eligible  
✅ Full MySQL support  

### Cons:
❌ Can get expensive
⚠️ More setup required  
⚠️ Complex networking/security groups  

### Estimated Cost:
- Free tier: $0 (12 months, t2.micro, 20GB)
- After: ~$15-30/month

### Setup Steps:
1. Go to [AWS RDS Console](https://console.aws.amazon.com/rds)
2. Click **Create database**
3. Configure:
   - **Engine**: MySQL
   - **Version**: 8.0 (or compatible with your code)
   - **Instance class**: `db.t2.micro` (free tier)
   - **Storage**: 20 GB (free tier)
4. Create master user credentials
5. Configure security group (allow port 3306)
6. Get endpoint from RDS dashboard
7. Import database:
   ```bash
   mysql -h your-rds-endpoint.amazonaws.com -u admin -p odfs_db < database/odfs_db.sql
   ```

---

## Option 4: **Supabase (PostgreSQL)**

### Pros:
✅ Free tier: 500 MB database  
✅ Built-in REST API (optional)  
✅ Real-time features  
✅ Auto-backups  

### Cons:
❌ PostgreSQL (requires code migration)  
❌ Less storage than PlanetScale  

---

## Option 5: **Railway** (MySQL/PostgreSQL)

### Pros:
✅ Free tier with $5 credit/month  
✅ MySQL support (no code changes!)  
✅ Easy deployment  

### Cons:
❌ Limited free tier  
❌ Will need paid plan after credit expires  

---

## Option 6: **Keep MySQL Locally (Not Recommended for Production)**

### Setup on Render:
1. Connect Render backend to your local machine's MySQL
2. Requires exposing your MySQL port (security risk!)
3. No redundancy or backups
4. ❌ **Not recommended for production**

---

## 🎯 RECOMMENDED SETUP FOR YOU

Based on your setup, I recommend:

**PlanetScale (FREE, MySQL-compatible)**

1. **Why?**
   - ✅ Your PHP code is MySQL-based (no changes needed!)
   - ✅ FREE tier with 5 GB
   - ✅ Works perfectly with Render
   - ✅ Easy to migrate to paid later

2. **Quick Setup:**

```bash
# 1. Create PlanetScale account
# https://planetscale.com

# 2. Create database "odfs_db"

# 3. Get connection string from PlanetScale dashboard

# 4. Import your database
mysql -h YOUR_PLANETSCALE_HOST -u YOUR_USERNAME -p odfs_db < database/odfs_db.sql

# 5. Update Render environment variables:
# DB_SERVER: YOUR_PLANETSCALE_HOST
# DB_USERNAME: YOUR_USERNAME
# DB_PASSWORD: YOUR_PASSWORD
# DB_NAME: odfs_db
```

---

## Comparison Table

| Option | Cost | MySQL | Setup | Storage |
|--------|------|-------|-------|---------|
| **PlanetScale** | FREE | ✅ Yes | Easy | 5 GB |
| Render PostgreSQL | FREE | ❌ No | Medium | 5 GB |
| AWS RDS | FREE (1yr) | ✅ Yes | Hard | 20 GB |
| Railway | $5/mo | ✅ Yes | Easy | Limited |
| Supabase | FREE | ❌ No | Medium | 500 MB |
| Local MySQL | FREE | ✅ Yes | N/A | ⚠️ Risk |

---

## How to Import Database

### Using MySQL CLI:
```bash
mysql -h your-host -u your-username -p your-database < database/odfs_db.sql
```

### Using GUI (phpMyAdmin, etc):
1. Open your database provider's UI
2. Click **Import**
3. Select `database/odfs_db.sql`
4. Click **Import**

### Verify Data Imported:
```bash
mysql -h your-host -u your-username -p your-database -e "SELECT COUNT(*) FROM category_list;"
# Should return: count = 4 (or number of categories)
```

---

## Final Architecture

After setup:
```
┌─────────────────────────────────────────┐
│         Netlify Frontend                │
│  (your-app.netlify.app)                │
└──────────────────┬──────────────────────┘
                   │
                   ↓ API Calls
┌─────────────────────────────────────────┐
│         Render Backend                  │
│  (your-backend.onrender.com)           │
└──────────────────┬──────────────────────┘
                   │
                   ↓ Query
┌─────────────────────────────────────────┐
│         PlanetScale MySQL               │
│  (your-host.mysql.planetscale.com)     │
└─────────────────────────────────────────┘
```

---

## Next Steps

1. **Choose PlanetScale** (if following my recommendation)
2. **Sign up:** https://planetscale.com
3. **Create database** named `odfs_db`
4. **Get credentials** (host, username, password)
5. **Import database** using SQL dump
6. **Add to Render** environment variables
7. **Deploy & test**

Need help with any specific database provider? Just let me know! 🚀
