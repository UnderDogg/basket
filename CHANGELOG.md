## Features
- Changed layout of dashboard
- Set `Permissions`. Attach `Permissions` to `Routes`
- `AuthoriseRole` middleware
- Protect *Super Administrator* role
- Protect against `user` self delete
- Test improvements
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

## Bug Fixes
- 'Gravatar' bug fixed
- `SettlementGateway` fixes

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
