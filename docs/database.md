# Database Documentation

## Database Overview

The Medical Case Management System uses MySQL as its relational database. Laravel migrations are used to create and manage the database schema.

## 1. Users Table

The `users` table stores user authentication and role information.

| Column | Description |
|---|---|
| `id` | Primary key; automatically generated |
| `name` | User's name |
| `email` | Unique login email |
| `password` | Hashed user password |
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
| `case_number` | Required, unique case identifier |
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

- Each medical case is associated with a user through the `created_by` foreign key.
- The `case_number` column must contain a unique value.
- The `status` column is indexed to support status-based filtering.
- Deleting a user cascades to their associated medical cases, according to the configured foreign key constraint.
- Laravel validation checks required fields, valid status and priority values, and duplicate case numbers.

## 4. Roles and Permissions

| Role | View Cases | Create Cases | Update Cases | Delete Cases |
|---|---|---|---|---|
| Admin | Yes | Yes | Yes | Yes |
| Viewer | Yes | No | No | No |

Role-based middleware, authorization policies, and request validation help protect restricted operations.

## 5. Sample Data

The database seeder creates an Admin user, a Viewer user, and sample medical cases for development and testing.

Use only dummy patient information during development and testing.
