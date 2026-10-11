<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Case Details</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f7fb;
            color: #1f2937;
            margin: 0;
            padding: 30px;
        }

        .container {
            max-width: 800px;
            margin: auto;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px #00000010;
        }

        h1 {
            color: #1d4ed8;
        }

        .detail {
            padding: 12px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .label {
            font-weight: bold;
            display: inline-block;
            width: 180px;
        }

        a {
            text-decoration: none;
        }

        .back {
            display: inline-block;
            margin-bottom: 20px;
            color: #1d4ed8;
        }

        .actions {
            margin-top: 25px;
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-block;
            padding: 10px 16px;
            border: none;
            border-radius: 5px;
            color: white;
            cursor: pointer;
            font-size: 14px;
        }

        .edit-btn {
            background: #1769aa;
        }

        .delete-btn {
            background: #dc2626;
        }

        .success {
            padding: 12px;
            margin-bottom: 15px;
            background: #dcfce7;
            color: #166534;
            border-radius: 5px;
        }
    </style>
</head>

<body>
<div class="container">

    <a class="back" href="{{ route('cases.index') }}">
        &larr; Back to Cases
    </a>

    @if (session('success'))
        <div class="success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <h1>Medical Case Details</h1>

        <div class="detail">
            <span class="label">Case Number:</span>
            {{ $medicalCase->case_number }}
        </div>

        <div class="detail">
            <span class="label">Patient Reference:</span>
            {{ $medicalCase->patient_reference }}
        </div>

        <div class="detail">
            <span class="label">Surgeon Name:</span>
            {{ $medicalCase->surgeon_name }}
        </div>

        <div class="detail">
            <span class="label">Implant Type:</span>
            {{ $medicalCase->implant_type }}
        </div>

        <div class="detail">
            <span class="label">Status:</span>
            {{ $medicalCase->status }}
        </div>

        <div class="detail">
            <span class="label">Priority:</span>
            {{ $medicalCase->priority }}
        </div>

        <div class="detail">
            <span class="label">Surgery Date:</span>
            {{ $medicalCase->surgery_date?->format('d M Y') ?? 'Not scheduled' }}
        </div>

        <div class="detail">
            <span class="label">Created By:</span>
            {{ $medicalCase->creator?->name ?? 'Unknown' }}
        </div>

        <div class="detail">
            <span class="label">Created At:</span>
            {{ $medicalCase->created_at->format('d M Y, h:i A') }}
        </div>

        <div class="detail">
            <span class="label">Last Updated:</span>
            {{ $medicalCase->updated_at->format('d M Y, h:i A') }}
        </div>

        @if (auth()->user()->role === 'Admin')
            <div class="actions">

                <a class="btn edit-btn"
                   href="{{ route('cases.edit', $medicalCase) }}">
                    Edit Case
                </a>

                <form method="POST"
                      action="{{ route('cases.destroy', $medicalCase) }}"
                      onsubmit="return confirm('Are you sure you want to delete this case?');">

                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn delete-btn">
                        Delete Case
                    </button>
                </form>

            </div>
        @endif

    </div>
</div>
</body>
</html>
