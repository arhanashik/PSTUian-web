# PSTUian-web

## INSTALLATION
1. Install XAMPP(for windows) or WAMP(for MAC)
2. Clone the repo inside the localhost directory
3. Open phpMyAdmin and create two databases: pstuian.db and pstuian_dev.db
4. Import the sql(api/mobile/v1/backup/pstuian.sql)

## CHANGELOG
1.0.0 - Initail
----------------
- PSTU website
- Admin panel 
- Admin api for Admin panel
- Mobile Api for website and mobile app

## DEPLOY
- Change the base url with appropriate environment in root website, admin and api
- Update db credentials in constant.php file under admin/api and api/movile/v1 directories
- Update DEPLOYPATH in **.cpanel.yml** file with appropriate directory in the server
- Push the code to git
- Merge with proper branch
- Pull the code from cpanel

That's it, you are ready to go!
