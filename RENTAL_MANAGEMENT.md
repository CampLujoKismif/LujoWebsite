# Rental Management System

## Overview

The Rental Management System provides comprehensive functionality for managing facility rentals, reservations, pricing, and discount codes for rental admins and super users.

## Features

### Core Functionality
- **Reservation Management**: Create, edit, cancel, and delete rental reservations
- **Pricing Management**: Update rental pricing per person per day and deposit amounts
- **Discount Codes**: Create and manage rental-specific discount codes
- **Payment Processing**: Handle refunds for online payments
- **Analytics Dashboard**: Comprehensive analytics and reporting

### User Interface
- **Responsive Design**: Works on desktop, tablet, and mobile devices
- **Dark Mode Support**: Automatic dark/light theme switching
- **Real-time Updates**: Live search and filtering
- **Modal Forms**: Clean, user-friendly forms for all operations

## Access Control

### Roles
- **Rental Administrator** (`rental-admin`): Full access to rental management features
- **System Administrator** (`system-admin`): Full system access including rental management

### Permissions
- `view_rentals`: View rental reservations
- `create_rentals`: Create new reservations
- `edit_rentals`: Edit existing reservations
- `delete_rentals`: Delete reservations
- `manage_rental_pricing`: Update pricing settings
- `manage_rental_discounts`: Create and manage discount codes
- `process_rental_refunds`: Process refunds for online payments
- `view_rental_analytics`: Access analytics dashboard

## Usage

### Accessing the System
1. Navigate to `/dashboard/rental-admin/` (requires rental-admin or system-admin role)
2. The system will automatically check your permissions

### Managing Reservations
1. **View Reservations**: All reservations are displayed in a paginated table
2. **Create Reservation**: Click "New Reservation" button to create a new booking
3. **Edit Reservation**: Click "Edit" on any reservation to modify details
4. **Cancel Reservation**: Click "Cancel" to cancel an active reservation
5. **Delete Reservation**: Click "Delete" to permanently remove a reservation

### Managing Pricing
1. Click "Update Pricing" button
2. Set price per person per day
3. Set optional deposit amount
4. Add description for the pricing period
5. Save changes (automatically deactivates previous pricing)

### Managing Discount Codes
1. Click "Create Discount" button
2. Enter discount code (automatically converted to uppercase)
3. Choose discount type (percentage or fixed amount)
4. Set discount value
5. Configure usage limits and validity dates
6. Save the discount code

### Analytics Dashboard
The dashboard provides comprehensive analytics including:
- Total reservations count
- Pending, confirmed, cancelled, and completed reservations
- Revenue tracking (total, monthly, yearly)
- Average reservation value
- Upcoming reservations
- Recent activity (last 7 days)

## Technical Implementation

### Models
- `RentalReservation`: Main reservation model
- `RentalPricing`: Pricing configuration model
- `DiscountCode`: Discount code model

### Policies
- `RentalReservationPolicy`: Authorization for reservation operations
- `RentalPricingPolicy`: Authorization for pricing management
- `DiscountCodePolicy`: Authorization for discount code management

### Routes
- `/dashboard/rental-admin/`: Main rental management interface
- Protected by `rental-admin,system-admin` role middleware

### Security Features
- **Authorization**: All actions are protected by policies
- **Input Validation**: Comprehensive validation for all forms
- **CSRF Protection**: Built-in Laravel CSRF protection
- **Role-based Access**: Granular permission system

## Setup Instructions

1. **Run Migrations**: Ensure all rental-related migrations are run
2. **Seed Permissions**: Run the RolePermissionSeeder to add rental permissions
3. **Assign Roles**: Assign `rental-admin` role to appropriate users
4. **Configure Pricing**: Set initial rental pricing through the interface

## Best Practices

1. **Regular Backups**: Ensure database backups include rental data
2. **Monitor Analytics**: Regularly review analytics for business insights
3. **Update Pricing**: Keep pricing current and competitive
4. **Manage Discounts**: Monitor discount code usage and effectiveness
5. **Customer Communication**: Use notes field to track customer interactions

## Troubleshooting

### Common Issues
1. **Permission Denied**: Ensure user has appropriate role and permissions
2. **Validation Errors**: Check form inputs meet requirements
3. **Pricing Issues**: Verify pricing is set before creating reservations
4. **Discount Problems**: Ensure discount codes are valid and not expired

### Support
For technical issues, contact the system administrator or development team.
