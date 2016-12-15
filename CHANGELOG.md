# 1.15.0
2016-12-15

## Features
- Added **Multiple Address** support (134691997)
- Added *Quick View* functionality on the *Applications Report* (135527715)
- Using *Customer* details instead of *Applicant* when set (135692513)

## Bug Fixes
- Application action button fixes to disable when user does not have permission 

# 1.14.7
2016-12-01

## Configuration
- Updated `paybreak/paybreak-sdk-php` to `v4.9.0`

# 1.14.6
2016-12-01

## Features 
- Changes for settlement report download button to directly to call csv api (134525555)

# 1.14.5
2016-11-23

## Bug Fixes
- Fixed check for `available_installations` in `main.blade.php` (134843291)

# 1.14.4
2016-11-22

## Bug Fixes
- Improve error message and introduce logging when user deletion failed (134273227)
- Allow users to be soft deleted when they have foreign key dependencies set (134273227)
- Add missing default config key (134520549)

# 1.14.3
2016-11-18

## Bug Fixes
- Improving validation for order reference and description (134504071)

# 1.14.2
2016-11-17

## Features
- Changed log messages for assisted applications 

## Bug Fixes
- Ensuring that the button is not hidden on smaller devices (134445811)
- Correcting settlement dates (134259557)

# 1.14.1
2016-11-09

## Bug Fixes
- Fixed `phone_mobile` and `phone_home` not being in the correct order (133998285)

# 1.14.0
2016-11-07

## Features
- Refactoring out aggregate settlement reports (130356905)
- Changed `Application Link` journey (131807249)
- Changes to application status text and background colour (133426217)

## Bug Fixes
- Fixed Email Address length issue (132149593)
- Cease reporting `TokenMismatchException` leaving view as-is. (132647799)

# 1.13.5
2016-10-06

## Bug Fixes
- Re-introduced decimal support for the order amount (131612681)

# 1.13.4
2016-10-05

## Bug Fixes
- Fixed number keypad issues across different platforms (131612681)

# 1.13.3
2016-10-04

## Bug Fixes
- Fixed hidden fields not sending over correctly on initialise application screen (131615951)

# 1.13.2
2016-10-03

## Bug Fix
- Fixed potential fatal error (131530149)

# 1.13.1
2016-09-30

## Configuration
- New company address

# 1.13.0
2016-09-15

## Features
- Added `resume_url` to *Application Synchronisation Service* (131064355)

# 1.12.1
2016-09-15

## Bug Fixes
- Fixed initiali**z**e on Application status (front end)

# 1.12.0
2016-09-14

## Features
- Added ability for multiple `location` emails to be entered and used (128407249)
- Changed order of Application report to order by `updated_at` (128406917)
- Changed default validity period to 30 days, and changed input to days instead of seconds (128407051)
- Added help information to various inputs (128407127)
- Added Colours to application statuses (128407127)
- Changed cancellation so it can be requested when in a correct state

# 1.11.1
2016-09-12

## Bug Fixes
- Fixed duplicate request trying to be made on `initialise` application (130086745)

# 1.11.0
2016-08-31

## Features
- *Settlement Report* table is now responsive to screen resizing (126586279)
- *Settlement Report* shown in the view is now downloadable as a `.csv` (126586279)

## Bug Fixes
- Fixed `rosu` being selected when `su` and any other role was selected

# 1.10.2
2016-08-22

## Bug Fixes
- Removed validation for at least one application route (128648421)

# 1.10.1
2016-08-18

## Bug Fixes
- Fully namespace ApplicationEventHelper in ApplicationsController (128656113)

# 1.10.0
2016-08-18

## Features
- Added *read only super user* (128056527)

# 1.9.1
2016-08-16

## Bug Fixes
- Fixed spaces in the `mobile_number` not working for alternate applications (127916831)

# 1.9.0
2016-08-16

## Features
- Added view to basket for *Application Status History* (126577047)
- Added *Product Limits* for an *Installation* (127177411)
- Added validation for product limits (127177411)
- Add flexible finance calculator to basket (121944781)

## Bug Fixes
- Fixed limits not saving

# 1.8.0
2016-08-12

## Features
- Update Variable Deposit slider to use better implementation.

# 1.7.1
2016-08-12

## Bug Fixes
- Fixed: *Application Event Log*
- Numpad keys will now be accepted as valid input on the make application page
- Corrected an erroneous label on the view application page
- Allowed validity period to be set up to 30 days (2592000 seconds)

# 1.7.0
2016-08-10

## Features
- Add a *Cache Busting Service* to the application for use with frontend resources (127712539)

# 1.6.1
2016-08-03

## Bug Fixes
- Fixed `deposit` issues with decimal places (127537673)

# 1.6.0
2016-08-01

## Features
- Changed the flow for different Application types (126765451)
- Added `Template` configuration (127154283)
- `Template` preview added to `installation.edit` page (127154283)

# 1.5.6
2016-07-25

## Bug Fixes
- Fixed *Application Link* copy issue (126790701)

# 1.5.5
2016-07-21

## Bug Fixes
- Added `post-install` script for publishing vendor configuration

# 1.5.4
2016-07-20

## Bug Fixes
- Fixes for `paybreak/paybreak-sdk-php` version `4.4.1`

# 1.5.3
2016-07-20

## Bug Fixes
- Fixed location name not showing correctly on `application.show` (126589499)

# 1.5.2
2016-07-18

## Bug Fixes
- Standards fix

# 1.5.1
2016-07-18

## Bug Fix
- Added default *Email Template*

# 1.5.0
2016-07-15

## Features
- Added `Template` model and created pivot tables for Templates (120757529)
- Added additional flow for sending `Email Applications` (120758677)
- Added `Templates Controller` and ability to send emails stored in the database (120765999)
- Added the ability for custom order reference (120758937)
- Add `ApplicationEvent` logging functionality (120759393)
- Added functionality to change the *Deposit* on the *Initialise Application* screen (120758847)
- Added *Applicant* information to *Initialise Application* screen (120759267)
- Add applications view to list merchant payments (125524631)
- Add view to add merchant payment (125524631)
- Add seeder for new permissions `applications-merchant-payment` (125524631)
- Added footers to default `templates` (120774193)
- Added credit information call for an `Application` (120774193)

> Run `PermissionSeeder`

# 1.4.0
2016-06-20

## Features
- Changes to the settlement report (120176875)
- Minor changes to settlement report (121503853)

## Bug fixes
- Pending cancellation report colspan fix (121205327)

# 1.3.1
2016-06-17

## Bug Fixes
- Added strict filters configuration to stop all filters performing a `%like%` (120571545)

# 1.3.0
2016-06-06

## Features
- Fixes `synchronisation` for current installations (120851421)

## Configuration
- Changes to Retailer Support Details and additional helpful links (120178363)

# 1.2.2
2016-05-26

## Bug Fixes
- Fixed application link not showing when it has a status

# 1.2.1
2016-05-23

## Configuration
- Changed settlement csv filename format to `settlement-report-<Report ID>-<SettlementDate in YYYYMMDD>` (120097699)
- Changed application csv filename format to `applications-export-<datetime in YYYYMMDD-HHSS>` (120097699)

# 1.2.0
2016-05-23

## Features
- CSV downloads for settlement reports (118827323)
- Custom file names for CSV downloads (118827323)
- Added `finance_offers` field, and new route so that applications can be viewed instead of started (119172315)

# 1.1.0
2016-04-14

## Features
- Added extra testing
- Added `service` and `settlement` fee to `installation product` view (117620969)

# 1.0.1
2016-03-23

## Bug Fixes
- Converted email update bug fixed

# 1.0.0
2016-02-23

## Features
- Prevent logs from being pushed using Loggly handler, and insert into syslog instead (112319685)
- Included `Service Fee` on the `Pay Today` in the make application screen (114074193)

# 0.20.2
2016-02-11

## Bug fixes
- Fixed *Converted Email* in case of *Auto Fulfilment* (113469771)

# 0.20.1
2016-02-02

## Bug Fixes
- Removed a redundant google analytics tag (112795661)

# 0.20.0
2016-02-02

## Features
- Added *Google Tag Manager* to *Master* template (112795661)

# 0.19.0
2016-01-21

## Features
- Added `converted_email` flag on *Location*. Updated *Location* edit and show page to show `converted_email` flag

# 0.18.1
2016-01-21

## Bug Fixes
- Removed extra bracket

# 0.18.0
2016-01-21

## Features
- Changes to sizing of buttons, font etc on initialise application page
- jQuery added for deposit amount on initialise application page
- Deposit added to well and button
- Locations on the *User Locations Page*  are now restricted to the locations assigned to the user’s *Merchant*
- Added `FilterTrait`
- Added `LimitTrait`
- Added `ModelTrait`
- Fixed bugs with updating model active flag
- Minor doc changes to keep **scrutinizer** happy
- Added more testing for `Merchant`, `Locations` and `Ips`
- Added validation testing on *AccountController*, also making sure that error messages are shown
- Added validation testing on *LocationsController*, also making sure response messages are shown
- Added functionality to test protected methods in abstract classes
- Added testing on model traits, including tests against *Merchants* and Users
- Added functionality to stop duplicate tokens being added
- Now a banner appears on top of all the pages if the APP_ENV variable is set to 'test' (#111299086)

## Bug Fixes
- Location validation bug fix for email and required
- Destroy model method fixed to due to always being successful
- Handling 'Products are empty' error given when an installation has no products
- Fixed User Locations for super users
- Added a previously removed use statement for Collection

# 0.17.1
2015-12-01

## Bug Fixes
- Fixed Initialise page bug where only 1 product was showing

# 0.17.0
2015-11-24

## Features
- Content changes for email addresses

# 0.16.2
2015-11-18

## Bug Fixes
- Added *Merchant Commission* to download `csv`

# 0.16.1
2015-11-18

## Bug Fixes
- Fixed entities

# 0.16.0
2015-11-18

## Features
- Fixed application filter date
- Fixed column display issues
- Changed defaults dates for applications filter
- Removed validation on Installation URL so it is no longer required

# 0.15.2
2015-11-11

## Bug Fixes
- Fixed `reset password` bug where page displayed blank screen

# 0.15.1
2015-11-03

## Bug Fixes
- Removed entity types due to wrong api types

# 0.15.0
2015-11-03

## Features
- Pull chosen finance group into basket
- Added finance product to table.
- Update received col to show time
- Rename & add fields in CSV.
- Refactored to use SDK
- Removed all unused code
- Added FormValidation
- Validation added to fields on create, edit and login pages
- Added cancellation fields to application
- Added cancellation fee to pending cancellation list
- Changed pending cancellations to use local data instead of API data
- Added available products for an installation tab to installation view

# 0.14.1
2015-10-05

## Bug Fixes
- Fixed *Application* external ID data range issue in DB

# 0.14.0
2015-09-30

## Features
- Added Currency format helper
- Re-wrote several items in the applications list to use this for financials
- Now able to update notification url and return url for an installation
- Patch to API added to Abstract Gateway
- Installations edit page now includes notification and return url fields
- Location reference validation added
- Locations are now added as active initially

## Bug Fixes
- Edit forms changed to look the same and display correctly
- Breadcrumbs edited to work on smaller screens
- Action buttons now go smaller when on smaller devices
- Active toggle being consistently used
- Login page tabbing changed to work correctly
- Navbar fixes

# 0.13.1
2015-09-28

## Bug Fixes
- *Make Application* page fixed wrong view variables

# 0.13.0
2015-09-14

## Features
- Removed unnecessary bindings in `AppServiceProvider`
- Added favicons
- Created blade template to hold the meta directives
- Moved application-title directive into master template
- Extra validation on request cancellation
- Extra validation on partial refunds
- Settlement changes to show correct data from the API
- Title changed in master to reflect application name
- Added git ignore for versioning
- Added missing fields to CSV Application Export

## Bug Fixes
- Fixed pending cancellations views
- Breadcrumb fixes on different views

# 0.12.4
2015-09-04

## Bug Fixes
- Fixed partial refunds record actions

# 0.12.3
2015-09-04

## Bug Fixes
- Routes fix

# 0.12.2
2015-09-04

## Bug Fixes
- Redirect Fixes
- *Pending Cancellation* fixed
- *Partial Refunds* fixed

# 0.12.1
2015-09-03

## Bug Fixes
- Changed form layout in `Fulfilment`, `Cancellation` and `Partial Refund` views

# 0.12.0
2015-09-03

## Features
- Front end forms changed to Laravel instead of html
- Removed includes for forms
- Added filter functionality on controller

# 0.11.2
2015-08-28

## Bug Fixes
- Added support for floats

# 0.11.1
2015-08-28

## Bug Fixes
- Fixes messages on MAP

# 0.11.0
2015-08-28

## Features
- MAP changes for the layouts of different products (BNPL, IFC/IBC and FF)

# 0.10.2
2015-08-28

## Bug Fixes
- Version simplification

# 0.10.1
2015-08-28

## Bug Fixes
- Fixed version
- Text change on return page
- Rename of blade to standards

# 0.10.0
2015-08-27

## Features
- Removed Confirm step on MAP
- Breadcrumbs updated around Basket
- Permissions added to breadcrumbs, so you cannot click long breadcrumb parent links if you cannot visit them
- Breadcrumbs added back into applications views
- Email template changes
- Few improvements on *Application* view
- UAT changes added from Kwik Fit
- Blade templates changed
- Installations now contains disclosure and custom logo url

## Bug Fixes
- Applications view now ordered by newest to oldest
- Changed way of getting token for pending cancellations
- Fixed converted email recipients

# 0.9.1
2015-08-27

## Bug Fixes
- Fixed version on release

# 0.9.0
2015-08-25

## Features
- Release version in footer
- Download feature on Applications list
- Merchants, Installations and Locations active flags have been added and are functional
- Inactive flag will cascade downwards when made inactive
- Active flag will check parents to see if it is able to become active
- Standardise table in list view
- Standardise no records
- Consistent use of of @forelse & @empty
- Removal of panels around tables
- Login page cleaned up
- Forgot password cleaned up

## Bug Fixes
- Email notifications for converted application

# 0.8.0
2015-08-21

## Features
- Limit actions on *Applications List*
- Middleware test

##Bug Fixes
- Removed non working links
- Fixed two middleware
- Postcode filter on *Applications List*
- Currency filters on *Applications List*

# 0.7.0
2015-08-19

## Features
- Removed footer contents
- Redeveloped Application Process
- Change layout to fluid
- Error pages for 404 and 50X

## Bug Fixes
- Login page errors
- Minor fixes in view

# 0.6.1
2015-08-18

# 0.6.0
2015-08-18

## Features
- Actions on view pages have been changed to be included inside views
- Models linked to each other
- Active flag has been changed to a slider on edit page
- Confirmation screens have been made 'pretty'
- Messages moved to master
- Logo has been changed
- Validity period has been added to installation. Can be changed on edit page.
- Validity period of application is dependent on the validity period in installations
- Requester and location added to application
- Return page
- Menu structure changed
- Menu items now have a scope depending on a users permissions
- Middleware for available installation
- Added columns & filters for applications list.

## Bug Fixes
- 'Gravatar' bug fixed
- `SettlementGateway` fixes

# 0.5.0
2015-08-14

## Features
- Changed layout of dashboard
- Set `Permissions`. Attach `Permissions` to `Routes`
- `AuthoriseRole` middleware
- Protect *Super Administrator* role
- Protect against `user` self delete
- Test improvements

# 0.4.0
2015-08-11

## Features
- Assign locations to users
- Refactoring
- Added date filter to applications view and changed styling of the page to work on different screen sizes
- Changed filter on locations to a select instead of a text search
- Refactored `SettlementsController`
- Initialise Application
- Assign `User` to `Roles`
- Special case for *Super User*
- Moved `Locations` assignment on `User` to separate screen
- Added view, delete and store methods for merchants IP address management
- Created links to view IP address management page inside of merchants view
- Assign Location to Installation
- Refactored RolesController
- Partial Refund request, index and show
- Added account management pages so users can edit or view their account details
- Changed master view to include gravatar and login / logout features
- Added logo and changed favicon
- Changed tabs so they look less 'bulky' and changed the format of the forms to match this
- Changed glyphicons and added 'scripts' section to master
- Email Notification to Location for converted Application
- Email configuration
- Breadcrumbs has been hardened
- Changed the include of breadcrumbs throughout basket

## Bug Fixes
- Removed unnecessary object checks in views
- Added some *'secondary'* object checks to views such as `Roles & Permissions`
- `User` edit password fix
- Routes fix for `csrf`
- *DocBlocks* improvements
- Applications index view has been changed to display a title of 'applications' instead of 'locations'
- `ProviderApiClient` no longer throws exception if a delete request returns null `JSON` as it is expected to

# 0.3.0
2015-07-30

## Features
- Added Merchants CRUD
- Fix for Exceptions Handler Error
- Breadcrumb Improvements
- Installation Gateway
- Installation Entity
- Installation Synchronisation Services
- AbstractSynchronisationService
- Added Installations Read & Update
- Made Improvements to Breadcrumb
- Refactored Installations and Merchants to use Basket Eloquent Models
- Middleware to control if *User* can perform actions on a *Merchant*
- *Merchant* synchronisation GUI
- *Merchant* creation extended with synchronisation
- Actions o a row helper for blade
- Added Locations CRUD
- Added Applications: Read views
- Added pattern for drop downs on edit pages
- Refactored Role and Users to use plural routing pattern
- Adjusted navigation menu to reflect all available feature views
- Added `ApplicationEntity`
- Added `ApplicationGateway`
- Added `ApplicationSynchronisationService`
- Refactored Controllers for quality improvements
- Added live `DBSeeder`
- Added dev `DevSeeder`
- Added `role_id` to `User`
- Added Fulfilment request to applications
- Request Application Cancellation
- Added Method to Link External Application
- Added A Notification Catcher
- Added AuthorisePermission
- Settlement Report Views
- Limited actions and views to Merchant which User is assigned to

## Bug Fixes
- Code Standards fixes
- Fixed user migration for `merchant_id` `nullable()`
- Seeder fixes
- View fixes

# 0.2.0
2015-07-17

## Features
- Created
 - `AbstractGateway`
 - `MerchantGateway`
 - `MerchantSynchronisationService`
- Set standards to use API
- Added Roles CRUD
- Added Permission Management From Roles
- Added *Drag And Drop* Pattern For Permissions Management
- Added SQLite In Memory Configuration For Testing Environment

## Bug Fixes
- Timestamp Fix For DB Compatibility
- Fix For Travis CI Encryption Error
- Fix For CRUD Updating Errors

# 0.1.1
2015-07-15

- Updated development standards

# 0.1.0
2015-07-09

- First release!
