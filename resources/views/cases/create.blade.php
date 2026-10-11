<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Medical Case</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 30px;
        }
        .container {
            max-width: 650px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
        }
        h1 { margin-top: 0; }
        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }
        input, select {
            width: 100%;
            box-sizing: border-box;
            padding: 10px;
            margin-top: 6px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button, .back {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 16px;
            border: none;
            border-radius: 5px;
            background: #1769aa;
            color: white;
            text-decoration: none;
            cursor: pointer;
        }
        .error { color: #c62828; margin-top: 5px; }
    </style>
</head>
<body>
<div class="container">
    <h1>Create Medical Case</h1>

    @if ($errors->any())
        <div class="error">
            <p>Please correct the following errors:</p>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('cases.store') }}">
        @csrf

        <label>Case Number</label>
        <input type="text" name="case_number"
               value="{{ old('case_number') }}" required>

        <label>Patient Reference</label>
        <input type="text" name="patient_reference"
               value="{{ old('patient_reference') }}" required>

        <label>Surgeon Name</label>
        <input type="text" name="surgeon_name"
               value="{{ old('surgeon_name') }}" required>

        <label>Implant Type</label>
        <input type="text" name="implant_type"
               value="{{ old('implant_type') }}" required>

        <label>Status</label>
        <select name="status" required>
            @foreach (['Draft', 'Planning', 'In Review', 'Approved', 'Completed', 'Cancelled'] as $status)
                <option value="{{ $status }}"
                    @selected(old('status', 'Draft') === $status)>
                    {{ $status }}
                </option>
            @endforeach
        </select>

        <label>Priority</label>
        <select name="priority" required>
            @foreach (['Low', 'Medium', 'High'] as $priority)
                <option value="{{ $priority }}"
                    @selected(old('priority', 'Medium') === $priority)>
                    {{ $priority }}
                </option>
            @endforeach
        </select>

        <label>Surgery Date (Optional)</label>
        <input type="date" name="surgery_date"
               value="{{ old('surgery_date') }}">

        <button type="submit">Create Case</button>
        <a class="back" href="{{ route('cases.index') }}">Cancel</a>
    </form>
</div>
</body>
</html>
