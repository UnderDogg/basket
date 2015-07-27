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

## Bug Fixes
- Code Standards fixes
- Fixed user migration for `merchant_id` `nullable()`

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
