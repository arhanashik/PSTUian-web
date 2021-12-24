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

1.1.0 - Faculty Icon
----------------------
- Faculty icon can be added from admin panel

1.2.0 - Pagination
----------------------
- Paging added for following tables in Admin panel: Authentication, Device, Donation, Log, Notification, User Inquiry
- Default banner image updated

1.3.0 - Blood Donation, Check In, Account Verification, Multiple Devie
----------------------
- Blood donation and donation request option
- Check in and create check in location option
- Email verification is a must to see all information except the home page 
- Users can sign in from multiple devices
- Sign in/sign out is possible from the website also
- User can see the signed in devices and can sign out from all of them

## DEPLOY
- Change the base url with appropriate environment in root website, admin and api
- Update db credentials in constant.php file under admin/api and api/movile/v1 directories
- Update DEPLOYPATH in **.cpanel.yml** file with appropriate directory in the server
- Push the code to git
- Merge with proper branch
- Pull the code from cpanel

That's it, you are ready to go!
