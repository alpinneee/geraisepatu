@echo off
echo Building assets for production...
echo.

echo Installing dependencies...
npm install

echo.
echo Building assets...
npm run build

echo.
echo Build completed! Upload the public/build folder to your hosting.
pause