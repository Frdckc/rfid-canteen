# How to Access Your Website on Localhost

## Quick Steps to Fix Localhost Access Issues

### Step 1: Start XAMPP Services
1. Open **XAMPP Control Panel** (search for "XAMPP Control Panel" in Windows Start menu)
2. Make sure **Apache** is running (should show "Running" in green)
3. Make sure **MySQL** is running (should show "Running" in green)
4. If they're not running, click the **Start** button next to each service

### Step 2: Access Your Website
Open your web browser and go to:
```
http://localhost/rfid/
```
or
```
http://127.0.0.1/rfid/
```

### Step 3: Set Up Database (If Not Done Already)
1. Open **phpMyAdmin** by going to: `http://localhost/phpmyadmin/`
2. Create a new database named `rfid`
3. Import the `rfid.sql` file:
   - Click on the `rfid` database
   - Go to the "Import" tab
   - Click "Choose File" and select `rfid.sql` from your project folder
   - Click "Go" to import

### Common Issues and Solutions

#### Issue 1: "This site can't be reached" or "Connection refused"
**Solution:**
- Apache is not running. Start it from XAMPP Control Panel
- Check if port 80 is being used by another application (Skype, IIS, etc.)
- Try changing Apache port: In XAMPP Control Panel → Apache → Config → httpd.conf → Change `Listen 80` to `Listen 8080`, then access via `http://localhost:8080/rfid/`

#### Issue 2: "404 Not Found" or "Page not found"
**Solution:**
- Make sure you're using the correct URL: `http://localhost/rfid/` (with the `/rfid/` part)
- Check that your files are in `C:\xampp\htdocs\rfid\`
- Verify `index.php` exists in the `rfid` folder

#### Issue 3: Database Connection Error
**Solution:**
- Make sure MySQL is running in XAMPP Control Panel
- Verify database exists: Go to `http://localhost/phpmyadmin/` and check if `rfid` database exists
- If database doesn't exist, create it and import `rfid.sql`
- Check `main/connect.php` - database credentials should be:
  - Host: `localhost`
  - User: `root`
  - Password: `` (empty)
  - Database: `rfid`

#### Issue 4: Port Already in Use
**Solution:**
- Apache port conflict: Change port in XAMPP Control Panel → Apache → Config → httpd.conf
- MySQL port conflict: Change port in XAMPP Control Panel → MySQL → Config → my.ini
- Or stop the conflicting service (IIS, Skype, etc.)

#### Issue 5: PHP Errors or White Screen
**Solution:**
- Check PHP error logs: `C:\xampp\apache\logs\error.log`
- Enable error display: In `main/connect.php`, uncomment `error_reporting(0);` or change to `error_reporting(E_ALL);`
- Check file permissions (usually not an issue on Windows)

### Quick Checklist
- [ ] XAMPP Control Panel is open
- [ ] Apache service is running (green)
- [ ] MySQL service is running (green)
- [ ] Database `rfid` exists in phpMyAdmin
- [ ] Using correct URL: `http://localhost/rfid/`
- [ ] Files are in `C:\xampp\htdocs\rfid\`

### Testing Your Setup
1. First, test if XAMPP is working: Go to `http://localhost/` - you should see the XAMPP dashboard
2. Then test your site: Go to `http://localhost/rfid/` - you should see the login page
3. Test database: Go to `http://localhost/phpmyadmin/` - you should see phpMyAdmin interface

### Still Having Issues?
- Check XAMPP Control Panel logs (click "Logs" button next to Apache/MySQL)
- Check Windows Firewall (might be blocking Apache)
- Try restarting your computer
- Make sure you're using the latest version of XAMPP

