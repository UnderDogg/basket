# Roles and Permissions in Basket

## Permissions

Certain features require the user to have permission to use them.

ID | Permission Code | Display Name | Description
--- | --- | --- | --- | ---
1	| merchants-management |	Merchants Management |	merchants management
2 | merchants-view |	Merchants View |	merchants show
3	| users-management |	Users Management |	user management
4	| users-view |	Users View |	user view
5	| roles-management |	Roles Management |	roles management
6	| roles-view |	Roles View |	roles view
7	| locations-view |	Locations View |	locations view
8	| locations-management |	Locations Management |	locations management
9 | applications-view |	Applications View |	applications view
10 | applications-make |	Applications Make |	applications make
11 | applications-fulfil |	Applications Fulfil |	applications fulfil
12 | applications-cancel |	Applications Cancel |	applications cancel
13 | applications-refund |	Applications Refund |	applications refund
14 | reports-view |	Reports View | reports-view
15 | applications-merchant-payments |	Merchant Payments | merchant payments

## Roles

Roles are a set of permissions that can be configured. A user can have multiple roles.

ID | Display Name | Role Code | Description
--- | --- | --- | --- | ---
1 |	System Administrator |	su |	Can do everything
2 |	Merchant Administrator |	administrator |	Merchant Administrator
3 |	Report Role |	report | run reports
4 |	Manager Role |	manager |	run reports and perform cancellations
5 |	Sales Role |	sale |	access in-store finance page and in-store details
7 |	Sales Manager |	sales-manager |	User role can view, make, fulfil, cancel and refund applications
8 |	Read Only Super User | rosu |	Read only user who can see everything
9 |	Business Role |	business | Can View and Cancel Applications

## Default Configuration
By default the roles have the following permissions

### roles

ID | Display Name | Permits
--- | --- | ---
1 |	System Administrator | {merchants-management, merchants-view, users-management, users-view, roles-management, roles-view, locations-view, locations-management, applications-view, applications-make, applications-fulfil, applications-cancel, applications-refund, reports-view, applications-merchant-payments}
2 |	Merchant Administrator | {merchants-management, merchants-view, users-management, users-view, roles-management, roles-view, locations-view, locations-management, applications-view, applications-make, applications-fulfil, applications-cancel, applications-refund, reports-view, applications-merchant-payments}
3 |	Report Role | {locations-view, applications-view, reports-view}
4 |	Manager Role | {locations-view, applications-view, applications-make, applications-fulfil, applications-cancel, applications-refund, reports-view, applications-merchant-payments}
5 |	Sales Role | {applications-view, applications-make}
7 |	Sales Manager | {applications-view, applications-make, applications-fulfil, applications-cancel, applications-refund}
8 |	Read Only Super User | {merchants-view, users-view, roles-view, locations-view, applications-view, reports-view}
9 |	Business Role |	{applications-view, applications-cancel}
