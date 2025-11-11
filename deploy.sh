#!/bin/bash
# ODFS Deployment Helper Script
# Prepares your project for deployment on Render (backend) and Netlify (frontend)

set -e

echo "🚀 ODFS Deployment Preparation Script"
echo "======================================"

# Check if git is initialized
if [ ! -d ".git" ]; then
    echo "❌ Not in a git repository. Initialize first:"
    echo "   git init"
    exit 1
fi

# Get deployment configuration
echo ""
echo "📋 Enter your deployment configuration:"
echo ""

read -p "Render Backend URL (e.g., https://odfs-backend.onrender.com): " BACKEND_URL
read -p "Netlify Frontend URL (e.g., https://odfs.netlify.app): " FRONTEND_URL
read -p "MySQL/Database Host: " DB_HOST
read -p "MySQL Database User: " DB_USER
read -s -p "MySQL Database Password: " DB_PASS
echo ""
read -p "MySQL Database Name (default: odfs_db): " DB_NAME
DB_NAME=${DB_NAME:-odfs_db}

# Create .env file
echo ""
echo "📝 Creating .env file..."
cat > .env << EOF
# Database Configuration
DB_SERVER=$DB_HOST
DB_USERNAME=$DB_USER
DB_PASSWORD=$DB_PASS
DB_NAME=$DB_NAME

# Application Configuration
BASE_URL=$BACKEND_URL/
APP_TIMEZONE=Asia/Manila

# Environment
APP_ENV=production
EOF

echo "✅ .env file created"

# Update config.php with frontend URL
echo ""
echo "🔧 Updating CORS configuration..."
sed -i.bak "s|'https://your-netlify-app.netlify.app'|'$FRONTEND_URL'|g" config.php
echo "✅ CORS headers updated in config.php"

# Create deployment files
echo ""
echo "📄 Creating deployment configuration files..."

# Create netlify.toml if needed
if [ ! -f "netlify.toml" ]; then
    cat > netlify.toml << 'EOF'
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
EOF
    echo "✅ netlify.toml created"
fi

if [ ! -f "_redirects" ]; then
    cat > _redirects << 'EOF'
/api/* https://odfs-backend.onrender.com/:splat 200
/* /index.html 200
EOF
    echo "✅ _redirects created"
fi

# Create render.yaml if needed
if [ ! -f "render.yaml" ]; then
    cat > render.yaml << 'EOF'
services:
  - type: web
    name: odfs-backend
    runtime: php
    plan: standard
    startCommand: php -S 0.0.0.0:10000 -t .
EOF
    echo "✅ render.yaml created"
fi

echo ""
echo "✅ Deployment preparation complete!"
echo ""
echo "📋 Next steps:"
echo "1. Review and commit changes: git add . && git commit -m 'Deployment config'"
echo "2. Push to GitHub: git push"
echo "3. Connect repository to Render for backend deployment"
echo "4. Connect repository to Netlify for frontend deployment"
echo "5. Set environment variables in both platforms"
echo ""
echo "💡 Environment Variables Needed:"
echo "   Render Backend:"
echo "   - DB_SERVER: $DB_HOST"
echo "   - DB_USERNAME: $DB_USER"
echo "   - DB_PASSWORD: (your password)"
echo "   - DB_NAME: $DB_NAME"
echo "   - BASE_URL: $BACKEND_URL/"
echo ""
echo "   Netlify Frontend:"
echo "   - API_URL: $BACKEND_URL"
echo ""
