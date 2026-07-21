@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">

    <div class="mb-6">
        <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 flex items-center gap-3">
            👤 My Profile
        </h2>

        <p class="text-gray-500 mt-1 text-lg">
            Your account details and profile photo.
        </p>
    </div>

    @if(session('status') === 'photo-updated')
        <div class="bg-green-500 text-white p-4 mb-6 rounded-lg text-lg">
            Profile photo updated successfully.
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-500 text-white p-4 mb-6 rounded-lg text-lg">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-500 text-white p-4 mb-6 rounded-lg text-lg">
            <ul class="list-disc ml-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- ACCOUNT INFO -->
        <div class="border rounded-lg p-5">

            <h3 class="font-semibold text-lg text-gray-800 mb-4">Account</h3>

            <div class="space-y-3">

                <div>
                    <p class="text-gray-500 text-sm">Name</p>
                    <p class="font-semibold">{{ $user->fullname ?? $user->username }}</p>
                </div>

                <div>
                    <p class="text-gray-500 text-sm">Username</p>
                    <p class="font-semibold">{{ $user->username }}</p>
                </div>

                <div>
                    <p class="text-gray-500 text-sm">Email</p>
                    <p class="font-semibold">{{ $user->email ?: '-' }}</p>
                </div>

                @if($user->systemRole)
                <div>
                    <p class="text-gray-500 text-sm">Role</p>
                    <p class="font-semibold">{{ $user->systemRole->name }}</p>
                </div>
                @endif

            </div>

        </div>

        <!-- PROFILE PHOTO -->
        <div class="border rounded-lg p-5">

            <h3 class="font-semibold text-lg text-gray-800 mb-1">Profile Photo</h3>

            <p class="text-gray-500 text-sm mb-4">
                This photo appears when you scan your QR code for attendance, so it can be visually verified.
            </p>

            @if($personnel)

                <form method="POST" action="{{ route('profile.photo.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    <div class="flex items-center gap-4">

                        <img
                            id="photo-preview"
                            src="{{ $personnel->photo_url ?? '' }}"
                            class="{{ $personnel->photo_url ? '' : 'hidden' }} w-24 h-24 object-cover rounded-full border"
                            alt="Preview">

                        <div class="flex-1">

                            <input type="file"
                                   name="photo"
                                   accept="image/*"
                                   onchange="previewProfilePhoto(this)"
                                   class="w-full border rounded-xl p-3 focus:ring-2 focus:ring-blue-400">

                            @if($personnel->photo_url)
                                <label class="inline-flex items-center gap-2 mt-2 text-sm text-red-600">
                                    <input type="checkbox" name="remove_photo" value="1">
                                    Remove current photo
                                </label>
                            @endif

                        </div>

                    </div>

                    <button type="submit" class="mt-4 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                        Save Photo
                    </button>

                </form>

            @else

                <p class="text-gray-500 italic">
                    No employee record is linked to this account, so a profile photo can't be set.
                </p>

            @endif

        </div>

    </div>

</div>

<script>

    function previewProfilePhoto(input)
    {
        const preview = document.getElementById('photo-preview');

        if (!input.files || !input.files[0]) {
            preview.classList.add('hidden');
            preview.src = '';
            return;
        }

        preview.src = URL.createObjectURL(input.files[0]);
        preview.classList.remove('hidden');
    }

</script>

@endsection
