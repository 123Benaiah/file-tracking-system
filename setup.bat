@echo off
echo Setting up MOHA File Tracking System...
echo.

REM Create Livewire directories
if not exist "app\Livewire\Dashboard" mkdir "app\Livewire\Dashboard"
if not exist "app\Livewire\Files" mkdir "app\Livewire\Files"
if not exist "app\Livewire\Registry" mkdir "app\Livewire\Registry"

REM Create view directories
if not exist "resources\views\livewire\dashboard" mkdir "resources\views\livewire\dashboard"
if not exist "resources\views\livewire\files" mkdir "resources\views\livewire\files"
if not exist "resources\views\livewire\registry" mkdir "resources\views\livewire\registry"

echo Created directory structure.
echo.

REM Copy from the incorrectly created files to correct location
echo Moving Livewire view files to correct location...

REM Move dashboard files
if exist "resources\views\components\dashboard\registry-dashboard.blade.php" (
    move "resources\views\components\dashboard\registry-dashboard.blade.php" "resources\views\livewire\dashboard\"
)
if exist "resources\views\components\dashboard\department-dashboard.blade.php" (
    move "resources\views\components\dashboard\department-dashboard.blade.php" "resources\views\livewire\dashboard\"
)

REM Move files components
if exist "resources\views\components\files\create-file.blade.php" (
    move "resources\views\components\files\create-file.blade.php" "resources\views\livewire\files\"
)
if exist "resources\views\components\files\send-file.blade.php" (
    move "resources\views\components\files\send-file.blade.php" "resources\views\livewire\files\"
)
if exist "resources\views\components\files\receive-files.blade.php" (
    move "resources\views\components\files\receive-files.blade.php" "resources\views\livewire\files\"
)
if exist "resources\views\components\files\track-file.blade.php" (
    move "resources\views\components\files\track-file.blade.php" "resources\views\livewire\files\"
)

REM Move registry components
if exist "resources\views\components\registry\user-management.blade.php" (
    move "resources\views\components\registry\user-management.blade.php" "resources\views\livewire\registry\"
)

echo.
echo Running Laravel setup commands...

REM Run Laravel setup
php artisan migrate:fresh --seed
php artisan storage:link
php artisan optimize:clear

echo.
echo Building assets...
npm run build

echo.
echo Setup complete!
echo.
echo To start the application:
echo 1. Run: php artisan serve
echo 2. Open browser to: http://localhost:8000
echo 3. Login with:
echo    - Registry Head: REGHEAD001 / Moha@2024
echo    - Legal Officer: EMP002 / Password123
echo.
pause
