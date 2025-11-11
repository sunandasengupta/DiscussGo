@echo off
REM ODFS Deployment Helper Script for Windows
REM Prepares your project for deployment on Render (backend) and Netlify (frontend)

echo.
echo 🚀 ODFS Deployment Preparation Script (Windows)
echo ================================================
echo.

REM Check if git is initialized
if not exist ".git" (
    echo ❌ Not in a git repository. Initialize first:
    echo    git init
    exit /b 1
)

REM Get deployment configuration
echo 📋 Enter your deployment configuration:
echo.

set /p BACKEND_URL="Render Backend URL (e.g., https://odfs-backend.onrender.com): "
set /p FRONTEND_URL="Netlify Frontend URL (e.g., https://odfs.netlify.app): "
set /p DB_HOST="MySQL/Database Host: "
set /p DB_USER="MySQL Database User: "
set /p DB_PASS="MySQL Database Password: "
set /p DB_NAME="MySQL Database Name (default: odfs_db): "
if "%DB_NAME%"=="" set DB_NAME=odfs_db

REM Create .env file
echo.
echo 📝 Creating .env file...
(
echo # Database Configuration
echo DB_SERVER=%DB_HOST%
echo DB_USERNAME=%DB_USER%
echo DB_PASSWORD=%DB_PASS%
echo DB_NAME=%DB_NAME%
echo.
echo # Application Configuration
echo BASE_URL=%BACKEND_URL%/
echo APP_TIMEZONE=Asia/Manila
echo.
echo # Environment
echo APP_ENV=production
) > .env

echo ✅ .env file created
echo.

REM Create netlify.toml
echo 📄 Creating netlify.toml...
(
echo [build]
echo   publish = "."
echo   command = "echo 'Frontend ready'"
echo.
echo [[redirects]]
echo   from = "/api/*"
echo   to = "%BACKEND_URL%/:splat"
echo   status = 200
echo   force = true
echo.
echo [[redirects]]
echo   from = "/*"
echo   to = "/index.html"
echo   status = 200
) > netlify.toml

echo ✅ netlify.toml created
echo.

REM Create _redirects
echo 📄 Creating _redirects...
(
echo /api/* %BACKEND_URL%/:splat 200
echo /* /index.html 200
) > _redirects

echo ✅ _redirects created
echo.

REM Summary
echo ✅ Deployment preparation complete!
echo.
echo 📋 Next steps:
echo 1. Review configuration files
echo 2. Commit changes: git add . ^&^& git commit -m "Deployment config"
echo 3. Push to GitHub: git push
echo 4. Connect repository to Render for backend deployment
echo 5. Connect repository to Netlify for frontend deployment
echo 6. Set environment variables in both platforms
echo.
echo 💡 Environment Variables Needed:
echo    Render Backend:
echo    - DB_SERVER: %DB_HOST%
echo    - DB_USERNAME: %DB_USER%
echo    - DB_PASSWORD: (your secure password)
echo    - DB_NAME: %DB_NAME%
echo    - BASE_URL: %BACKEND_URL%/
echo.
echo    Netlify Frontend:
echo    - API_URL: %BACKEND_URL%
echo.
