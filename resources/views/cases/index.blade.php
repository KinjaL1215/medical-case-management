<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Case Management</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f4f7fb;
            margin: 0;
            padding: 30px;
            color: #1f2937;
        }

        .container {
            max-width: 1200px;
            margin: auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        h1 {
            color: #1d4ed8;
        }

        .dashboard {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
            gap: 16px;
            margin-bottom: 25px;
        }

        .card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 8px #00000010;
            border-left: 5px solid #1d4ed8;
        }

        .card h3 {
            margin: 0 0 12px;
            color: #4b5563;
            font-size: 15px;
        }

        .card p {
            margin: 0;
            font-size: 30px;
            font-weight: bold;
            color: #1f2937;
        }

        .card.planning {
            border-left-color: #d97706;
        }

        .card.review {
            border-left-color: #7c3aed;
        }

        .card.approved {
            border-left-color: #15803d;
        }

        .card.completed {
            border-left-color: #0891b2;
        }

        .filters {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        input, select, button {
            padding: 10px;
            border: 1px solid #d1d5db;
            border-radius: 5px;
        }

        button, .btn {
            background: #1d4ed8;
            color: white;
            cursor: pointer;
            border: none;
            padding: 10px 14px;
            border-radius: 5px;
            display: inline-block;
            text-decoration: none;
            font-size: 14px;
        }

        .logout-btn {
            background: #4b5563;
        }

        .create-btn {
            background: #15803d;
        }

        .delete-btn {
            background: #dc2626;
        }

        .reset-link {
            padding: 10px;
        }

        .table-wrapper {
            overflow-x: auto;
            background: white;
            border-radius: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        th, td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
            white-space: nowrap;
        }

        th {
            background: #e8efff;
        }

        .actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .actions form {
            margin: 0;
        }

        .pagination {
            margin-top: 20px;
        }

        .message {
            background: #fee2e2;
            color: #991b1b;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
        }

        .success {
            background: #dcfce7;
            color: #166534;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 5px;
        }

        @media (max-width: 600px) {
            body {
                padding: 15px;
            }

            .header {
                align-items: flex-start;
            }

            .filters input,
            .filters select {
                width: 100%;
            }
        }
    </style>
</head>

<body>
<div class="container">

    <div class="header">
        <div>
            <h1>Medical Case Management</h1>
            <p>Manage and track medical cases in one place.</p>
            <p>
                Welcome, {{ auth()->user()->name }}
                ({{ auth()->user()->role }})
            </p>
        </div>

        <div class="actions">
            @if (auth()->user()->role === 'Admin')
                <a class="btn create-btn" href="{{ route('cases.create') }}">
                    + Create New Case
                </a>
            @endif

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">
                    Logout
                </button>
            </form>
        </div>
    </div>

    @if (session('success'))
        <div class="success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="message">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <!-- Dashboard Count Cards -->
    <div class="dashboard">

        <div class="card">
            <h3>Total Cases</h3>
            <p>{{ $totalCases }}</p>
        </div>

        <div class="card planning">
            <h3>Planning</h3>
            <p>{{ $planningCases }}</p>
        </div>

        <div class="card review">
            <h3>In Review</h3>
            <p>{{ $inReviewCases }}</p>
        </div>

        <div class="card approved">
            <h3>Approved</h3>
            <p>{{ $approvedCases }}</p>
        </div>

        <div class="card completed">
            <h3>Completed</h3>
            <p>{{ $completedCases }}</p>
        </div>

    </div>

    <!-- Search and Status Filter -->
    <form class="filters" method="GET" action="{{ route('cases.index') }}">

        <input
            type="text"
            name="search"
            placeholder="Search case number or surgeon"
            value="{{ request('search') }}"
        >

        <select name="status">
            <option value="">All statuses</option>

            @foreach ([
                'Draft', 'Planning', 'In Review',
                'Approved', 'Completed', 'Cancelled'
            ] as $status)
                <option value="{{ $status }}"
                    @selected(request('status') === $status)>
                    {{ $status }}
                </option>
            @endforeach
        </select>

        <button type="submit">Search / Filter</button>

        <a class="reset-link" href="{{ route('cases.index') }}">
            Reset
        </a>
    </form>

    <!-- Medical Cases Table -->
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Case Number</th>
                    <th>Patient Reference</th>
                    <th>Surgeon</th>
                    <th>Implant Type</th>
                    <th>Status</th>
                    <th>Priority</th>
                    <th>Details</th>

                    @if (auth()->user()->role === 'Admin')
                        <th>Actions</th>
                    @endif
                </tr>
            </thead>

            <tbody>
                @forelse ($cases as $case)
                    <tr>
                        <td>{{ $case->case_number }}</td>
                        <td>{{ $case->patient_reference }}</td>
                        <td>{{ $case->surgeon_name }}</td>
                        <td>{{ $case->implant_type }}</td>
                        <td>{{ $case->status }}</td>
                        <td>{{ $case->priority }}</td>

                        <td>
                            <a href="{{ route('cases.show', $case) }}">
                                View
                            </a>
                        </td>

                        @if (auth()->user()->role === 'Admin')
                            <td>
                                <div class="actions">
                                    <a class="btn"
                                       href="{{ route('cases.edit', $case) }}">
                                        Edit
                                    </a>

                                    <form
                                        method="POST"
                                        action="{{ route('cases.destroy', $case) }}"
                                        onsubmit="return confirm('Are you sure you want to delete this case?');"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="delete-btn">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ auth()->user()->role === 'Admin' ? 8 : 7 }}">
                            No medical cases found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination">
        {{ $cases->links() }}
    </div>

</div>
</body>
</html>
