@extends('layouts.app')
@section('content')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Websites List') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="flex justify-between items-center p-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-800">All Websites</h3>
                    <a href="{{ route('admin.websites.analysis') }}" style="color: blue">
                        <i class="fas fa-chart-bar mr-2"></i>
                        View Analysis
                    </a>
                </div>

                <div class="p-6 overflow-x-auto">
                    <table class="table-auto w-full text-sm">
                        <thead>
                            <tr class="bg-gray-100 text-left">
                                <th class="px-4 py-2">ID</th>
                                <th class="px-4 py-2">User</th>
                                <th class="px-4 py-2">Business</th>
                                <th class="px-4 py-2">Business Name</th>
                                <th class="px-4 py-2">Description</th>
                                <th class="px-4 py-2">About Us</th>
                                <th class="px-4 py-2">Color</th>
                                <th class="px-4 py-2">Logo Style</th>
                                <th class="px-4 py-2">Social Proof</th>
                                <th class="px-4 py-2">Contact Info</th>
                                <th class="px-4 py-2">Services</th>
                                <th class="px-4 py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($websites as $website)
                                <tr class="bg-white border-b">
                                    <td class="border px-4 py-2">{{ $website->id }}</td>
                                    <td class="border px-4 py-2">{{ $website->user->name ?? '-' }}</td>
                                    <td class="border px-4 py-2">{{ $website->business->businessname ?? '-' }}</td>
                                    <td class="border px-4 py-2">{{ $website->business_name }}</td>
                                    <td class="border px-4 py-2">{{ $website->business_description }}</td>
                                    <td class="border px-4 py-2">{{ $website->about_us }}</td>
                                    <td class="border px-4 py-2">{{ $website->colour_choice }}</td>
                                    <td class="border px-4 py-2">{{ $website->logo_style_choice }}</td>
                                    <td class="border px-4 py-2">{{ $website->social_proof }}</td>
                                    <td class="border px-4 py-2">
                                        <ul class="list-disc pl-4">
                                            @foreach ((array) $website->contact_info as $info)
                                                <li>{{ $info }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td class="border px-4 py-2">
                                        @if ($website->services->count())
                                            <table class="table-auto w-full border text-sm">
                                                <thead class="bg-gray-100">
                                                    <tr>
                                                        <th class="px-3 py-2 border">#</th>
                                                        <th class="px-3 py-2 border">Service Name</th>
                                                        <th class="px-3 py-2 border">Description</th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($website->services as $index => $service)
                                                        <tr>
                                                            <td class="px-3 py-2 border">{{ $index + 1 }}</td>
                                                            <td class="px-3 py-2 border">{{ $service->name }}</td>
                                                            <td class="px-3 py-2 border">{{ $service->description ?? '-' }}
                                                            </td>

                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        @else
                                            <p class="text-gray-500">No services available for this website.</p>
                                        @endif
                                    </td>
                                    <td class="border px-4 py-2 flex gap-2">
                                        <a href="{{ route('admin.websites.show', $website->id) }}">
                                            <i class="fas fa-eye icon-blue"></i>
                                        </a>
                                        <form action="{{ route('admin.websites.destroy', $website->id) }}" method="POST"
                                            onsubmit="return confirm('Are you sure?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700">
                                                <i class="fas fa-trash icon-red"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
@endsection
