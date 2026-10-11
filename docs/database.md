# Database Documentation

## Database Overview

The Medical Case Management application uses MySQL as its relational database. Laravel migrations are used to create and manage the database schema.

## 1. Users Table

The `users` table stores login and role information.

| Column | Description |
|---|---|
| `id` | Primary key |
| `name` | User's name |
| `email` | Unique login email |
| `password` | Hashed password |
| `role` | User role: Admin or Viewer |
| `email_verified_at` | Email verification timestamp |
| `remember_token` | Remember-me authentication token |
| `created_at` | Record creation timestamp |
| `updated_at` | Record update timestamp |

## 2. Cases Table

The `cases` table stores medical case information.

| Column | Description |
|---|---|
| `id` | Primary key; automatically generated |
| `case_number` | Required unique case identifier |
| `patient_reference` | Dummy patient reference code |
| `surgeon_name` | Name of the surgeon |
| `implant_type` | Type of implant |
| `status` | Draft, Planning, In Review, Approved, Completed, or Cancelled |
| `priority` | Low, Medium, or High |
| `surgery_date` | Optional surgery date |
| `created_by` | Foreign key referencing `users.id` |
| `created_at` | Record creation timestamp |
| `updated_at` | Record update timestamp |

## 3. Relationships and Constraints

- Each medical case is associated with a user through `created_by`.
- `case_number` must be unique.
- The `status` column is indexed to support filtering.
- Deleting a user cascades to their associated cases, according to the configured foreign key.
- Laravel validation checks required fields, valid status and priority values, and unique case numbers.

## 4. Roles

- **Admin:** Can view, create, update, and delete medical cases.
- **Viewer:** Can view medical cases but cannot create, update, or delete them.

## 5. Sample Data

The database seeder creates an Admin user, a Viewer user, and sample medical cases for development and testing.

Use only dummy patient information in this project.
