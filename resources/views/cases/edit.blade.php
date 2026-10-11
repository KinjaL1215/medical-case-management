<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Medical Case</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            padding: 30px;
        }
        .container {
            max-width: 650px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
        }
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
        .error { color: #c62828; }
    </style>
</head>
<body>
<div class="container">
    <h1>Edit Medical Case: {{ $medicalCase->case_number }}</h1>

    @if ($errors->any())
        <div class="error">
            <p>Please correct these errors:</p>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('cases.update', $medicalCase) }}">
        @csrf
        @method('PUT')

        <label>Case Number</label>
        <input name="case_number" value="{{ old('case_number', $medicalCase->case_number) }}" required>

        <label>Patient Reference</label>
        <input name="patient_reference" value="{{ old('patient_reference', $medicalCase->patient_reference) }}" required>

        <label>Surgeon Name</label>
        <input name="surgeon_name" value="{{ old('surgeon_name', $medicalCase->surgeon_name) }}" required>

        <label>Implant Type</label>
        <input name="implant_type" value="{{ old('implant_type', $medicalCase->implant_type) }}" required>

        <label>Status</label>
        <select name="status" required>
            @foreach (['Draft', 'Planning', 'In Review', 'Approved', 'Completed', 'Cancelled'] as $status)
                <option value="{{ $status }}" @selected(old('status', $medicalCase->status) === $status)>
                    {{ $status }}
                </option>
            @endforeach
        </select>

        <label>Priority</label>
        <select name="priority" required>
            @foreach (['Low', 'Medium', 'High'] as $priority)
                <option value="{{ $priority }}" @selected(old('priority', $medicalCase->priority) === $priority)>
                    {{ $priority }}
                </option>
            @endforeach
        </select>

        <label>Surgery Date (Optional)</label>
        <input type="date" name="surgery_date"
               value="{{ old('surgery_date', $medicalCase->surgery_date?->format('Y-m-d')) }}">

        <button type="submit">Update Case</button>
        <a class="back" href="{{ route('cases.show', $medicalCase) }}">Cancel</a>
    </form>
</div>
</body>
</html>
