@extends('layouts.app')
@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
  body {
        max-height: 100vh;
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
    color: #333;
}

.dashboard {
    padding: 20px;
    max-height: 100vh;
}
.analyics{
    display: flex;
    justify-content: space-between;
}
.section-completion{
    width: 70%;
   
}
.section-completion, .user-activity, .most-active-sections {
    background-color: white;
    padding: 20px;
    margin-bottom: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

h1 {
    font-size: 24px;
    margin-bottom: 20px;
}

.section {
    margin-bottom: 15px;
}

.section h2 {
    font-size: 18px;
    margin-bottom: 5px;
}

.progress-bar {
    background-color: #e0e0e0;
    border-radius: 5px;
    overflow: hidden;
    height: 10px;
    width: 100%;
}

.progress {
    background-color: #3498db;
    height: 100%;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}

table th, table td {
    padding: 10px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

table th {
    background-color: #f8f8f8;
}

table tbody tr:hover {
    background-color: #f1f1f1;
}
    </style>
  
<div class="dashboard">
    <div class="analyics">
        <div class="section-completion">
            <h1>Section Completion Rates</h1>
            @foreach ($sectionCompletion as $section => $percentage)
                <div class="section">
                    <h2>{{ ucfirst(str_replace('_', ' ', $section)) }}</h2>
                    <div class="progress-bar">
                        <div class="progress" style="width: {{ $percentage }}%;"></div>
                    </div>
                    <span>{{ $percentage }}%</span>
                </div>
            @endforeach
        </div>
        <div class="user-activity">
            <h1>User Activity Trend</h1>
            <table>
                <thead>
                    <tr>
                        <th>Period</th>
                        <th>Active Users</th>
                        <th>%</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Last 24 Hours</td>
                        <td>{{ $userActivity['last_24_hours'] }}</td>
                        <td>{{ (($userActivity['last_24_hours'] / max($userActivity['last_30_days'], 1)) / 100) * 100 }}%</td>
                    </tr>
                    <tr>
                        <td>Last 7 Days</td>
                        <td>{{ $userActivity['last_7_days'] }}</td>
                        <td>{{ (($userActivity['last_7_days'] / max($userActivity['last_30_days'], 1)) / 100) * 100 }}%</td>
                    </tr>
                    <tr>
                        <td>Last 30 Days</td>
                        <td>{{ $userActivity['last_30_days'] }}</td>
                        <td>{{ (($userActivity['last_30_days'] / max($userActivity['last_30_days'], 1)) / 100) * 100 }}%</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="most-active-sections">
        <h1>Most Active Sections</h1>
        <table>
            <tbody>
                <tr>
                    @foreach ($mostActiveSections as $section => $count)
                        <td>{{ $count }} <span>{{ ucfirst(str_replace('_', ' ', $section)) }}</span></td>
                    @endforeach
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection