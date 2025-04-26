@extends('layouts.app')
@section('content')
    <style>
        .custom-button {
            background-color: #4CAF50; 
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }
    
        .custom-button:hover {
            background-color: #45a049; 
            transform: scale(1.05);
        }
    
        .custom-button:active {
            transform: scale(0.95);
        }
    </style>
    <div class="container mx-auto px-4 py-8">

        <form action="{{ route('admin.videos.update', $video->id) }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-md">
            @csrf
            @method('PUT')
            <!-- Title Field -->
            <div class="mb-4">
                <label for="title" class="block text-gray-700 font-medium mb-2">Title</label>
                <select name="title" id="title" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="{{ $video->title }}" selected>{{ $video->title }}</option>
                    <option value="Business Idea">Business Idea</option>
                    <option value="Market Research">Market Research</option>
                    <option value="MVP Development">MVP Development</option>
                    <option value="Marketing">Marketing</option>
                    <option value="Sales Strategy">Sales Strategy</option>
                    <option value="Business Setup">Business Setup</option>
                    <option value="Financial Planning">Financial Planning</option>
                    <option value="Launch Preparation">Launch Preparation</option>
                </select>
            </div>

            <!-- Video Upload Field -->
            <div class="mb-4">
                <label for="video" class="block text-gray-700 font-medium mb-2">Upload New Video (Optional)</label>
                <input type="file" name="video" id="video" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" accept="video/*">
                <p class="text-sm text-gray-500 mt-1">Supported formats: MP4, MOV, AVI, WMV. Max size: 100MB.</p>
                <!-- Current Video -->
                <div class="mt-4">
                    <p class="text-gray-600">Current Video:</p>
                    <div class="video-preview">
                        <video controls class="w-48 h-32 rounded-lg shadow-md">
                            <source src="{{ asset($video->video_path) }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                </div>
            </div>

            <!-- Description Field -->
            <div class="mb-4">
                <label for="description" class="block text-gray-700 font-medium mb-2">Description</label>
                <textarea name="description" id="description" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" rows="4">{{ $video->description }}</textarea>
            </div>

            <!-- Submit Button -->
            <div class="mt-6">
                <button type="submit" class="w-full custom-button">
                    Save
                </button>
            </div>
        </form>
    </div>
@endsection