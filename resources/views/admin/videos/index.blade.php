@extends('layouts.app')
@section('content')
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        .header {
            background-color: #333;
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        .navbar {
            display: flex;
            justify-content: space-around;
            background-color: #444;
            padding: 10px 0;
        }

        .navbar a {
            color: #fff;
            text-decoration: none;
            padding: 10px 20px;
        }

        .navbar a:hover {
            background-color: #555;
        }

        .container {
            /* margin: 20px; */
            /* display: flex; */
            padding: 20px;
            justify-content: flex-end;
            align-items: center;

        }



        .search-bar {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .search-bar input {
            padding: 10px;
            width: 80%;
            border: 1px solid #ddd;
        }

        .search-bar button {
            padding: 10px;
            background-color: #ac0c0c;
            color: white;
            border: none;
            cursor: pointer;
        }

        .search-bar button:hover {
            background-color: #a71212;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 16px;
            font-family: Arial, sans-serif;
            text-align: left;
        }

        thead tr {
            background-color: #980000;
            color: #ffffff;
            text-align: left;
            font-weight: bold;
        }

        th,
        td {
            padding: 12px 15px;
            border: 1px solid #ddd;
        }

        tbody tr {
            border-bottom: 1px solid #ddd;
        }

        tbody tr:nth-of-type(even) {
            background-color: #f3f3f3;
        }

        tbody tr:hover {
            background-color: #f1f1f1;
            cursor: pointer;
        }

        a {
            text-decoration: none;
            color: #009879;
            font-weight: bold;
            margin-right: 10px;
        }

        a:hover {
            text-decoration: underline;
            color: #005f56;
        }

        button {
            background-color: #009879;
            color: #ffffff;
            border: none;
            padding: 8px 12px;
            font-size: 14px;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #007961;
        }

        form {
            display: inline-block;
        }

        .alert {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            font-size: 14px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .modal .form-control {
            border-radius: 5px;
            box-shadow: none;
            border: 1px solid #ddd;
        }

        .modal .btn {
            border-radius: 5px;
        }

        .video-preview {
            width: 150px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
        }
    </style>

    <div class="container">


        <div class="search-bar">
            <input type="text" id="searchInput" placeholder="Search users..." onkeyup="searchTable()" />
            <button>
                <a href="{{ route('admin.videos.create') }}" style="color:white;">Add New Video</a>
            </button>
            <script>
                function searchTable() {
                    var input = document.getElementById("searchInput");
                    var filter = input.value.toLowerCase();
                    var table = document.querySelector("table");
                    var tr = table.getElementsByTagName("tr");

                    for (var i = 1; i < tr.length; i++) {
                        var td = tr[i].getElementsByTagName("td");
                        var found = false;
                        for (var j = 0; j < td.length; j++) {
                            if (td[j]) {
                                var txtValue = td[j].textContent || td[j].innerText;
                                if (txtValue.toLowerCase().indexOf(filter) > -1) {
                                    found = true;
                                    break;
                                }
                            }
                        }
                        tr[i].style.display = found ? "" : "none";
                    }
                }

                function exportTableToCSV(filename) {
                    var csv = [];
            </script>
        </div>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Video Preview</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($videos as $video)
                    <tr>
                        <td>{{ $video->title }}</td>
                        <td>{{ $video->description }}</td>
                        <td>
                            <!-- عرض الفيديو في مربع صغير -->
                            <video controls class="video-preview">
                                <source src="{{ asset($video->video_path) }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        </td>
                        <td>
                            <!-- View Icon -->
                            <a href="{{ route('admin.videos.show', $video->id) }}" title="View">
                                <i class="fas fa-eye" style="color: #009879; font-size: 18px;"></i>
                            </a>

                            <!-- Edit Icon -->
                            <a href="{{ route('admin.videos.edit', $video->id) }}" title="Edit">
                                <i class="fas fa-pen" style="color: #ff9800; font-size: 18px; margin-left: 8px;"></i>
                            </a>

                            <!-- Delete Icon -->
                            <form action="{{ route('admin.videos.destroy', $video->id) }}" method="POST"
                                style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" title="Delete"
                                    style="background: none; border: none; cursor: pointer;"
                                    onclick="return confirm('Are you sure you want to delete this video?')">
                                    <i class="fas fa-trash-alt"
                                        style="color: #ff4d4d; font-size: 18px; margin-left: 8px;"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
