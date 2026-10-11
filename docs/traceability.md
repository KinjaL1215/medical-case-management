# Requirements Traceability

This document maps the Medical Case Management assignment requirements to their implementation in the project.

| Requirement | Implementation | Status |
|---|---|---|
| Admin and Viewer roles | `users.role` and Admin middleware | Implemented |
| Login and logout | Web `AuthController` | Implemented |
| Dashboard counts | `MedicalCaseController@index` | Implemented |
| Case listing | Case index Blade view | Implemented |
| Search by case number and surgeon | Case index query filters | Implemented |
| Filter by status | Case index query filter | Implemented |
| Pagination | Laravel pagination, 10 cases per page | Implemented |
| Create medical case | Web controller and create form | Implemented |
| Edit medical case | Web controller and edit form | Implemented |
| View case details | Case detail Blade view | Implemented |
| Admin-only web actions | `admin` middleware | Implemented |
| Sanctum API authentication | API authentication controller and `auth:sanctum` | Implemented |
| List and view cases API | `GET /api/cases` and `GET /api/cases/{id}` | Implemented |
| Create case API | `POST /api/cases` | Implemented |
| Update case API | `PUT /api/cases/{id}` | Implemented |
| Delete case API | `DELETE /api/cases/{id}` | Implemented |
| Database constraints | Migrations for users and cases | Implemented |
| Sample data | `MedicalCaseSeeder` | Implemented |
| Automated feature tests | `MedicalCaseManagementTest` | Implemented |
| Database documentation | `docs/database.md` | Documented |
| Requirements traceability | `docs/traceability.md` | Documented |

## Testing Notes

The automated test suite currently passes all 8 tests with 18 assertions.

Manual API tests have verified successful login, case listing, single-case retrieval, case creation, case updates, and case deletion. Viewer deletion was also tested and returned `403 Forbidden`.

## Assumptions

- Patient references and case records use dummy data for demonstration.
- Only Admin users may create, update, or delete medical cases.
- Viewer users have read-only access.
- The application uses Laravel, Blade, MySQL, and Laravel Sanctum.
