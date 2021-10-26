# PSTUian-web

## INSTALLATION
1. Install XAMPP(for windows) or WAMP(for MAC)
2. Clone the repo inside the localhost directory
3. Open phpMyAdmin and create two databases: pstuian.db and pstuian_dev.db
4. Import the sql(api/mobile/v1/backup/pstuian.sql)

## DEPLOY
1. Change the base url with appropriate environment in root website, admin and api
2. Update DEPLOYPATH in **.cpanel.yml** file with appropriate directory in the server
3. Push the code to git
4. Merge with proper branch
5. Pull the code from cpanel

That's it, you are ready to go!
