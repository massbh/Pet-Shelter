<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Happinest - Edit {{ $pet->name }}</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>

    <main>
        <section class="hero">
            <h1>Edit Pet: {{ $pet->name }}</h1>
            <p>Update the pet's information below.</p>
        </section>

        <div class="create-container">
            <form action="{{ route('pets.update', $pet) }}" method="POST" enctype="multipart/form-data" class="create-form">
                @csrf
                @method('PUT')

                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Name *</label>
                        <input id="name" name="name" type="text" value="{{ old('name', $pet->name) }}" required placeholder="Enter pet's name">
                    </div>

                    <div class="form-group">
                        <label for="species">Species *</label>
                        <select id="species" name="species" required>
                            <option value="">Choose...</option>
                            <option value="Bird" {{ old('species', $pet->species)=='Bird' ? 'selected' : '' }}>Bird</option>
                            <option value="Capybara" {{ old('species', $pet->species)=='Capybara' ? 'selected' : '' }}>Capybara</option>
                            <option value="Cat" {{ old('species', $pet->species)=='Cat' ? 'selected' : '' }}>Cat</option>
                            <option value="Dog" {{ old('species', $pet->species)=='Dog' ? 'selected' : '' }}>Dog</option>
                            <option value="Rabbit" {{ old('species', $pet->species)=='Rabbit' ? 'selected' : '' }}>Rabbit</option>
                            <option value="Reptile" {{ old('species', $pet->species)=='Reptile' ? 'selected' : '' }}>Reptile</option>
                            <option value="Turtle" {{ old('species', $pet->species)=='Turtle' ? 'selected' : '' }}>Turtle</option>  
                            <option value="Other" {{ old('species', $pet->species)=='Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="age">Age (years) *</label>
                        <input id="age" name="age" type="number" min="0" value="{{ old('age', $pet->age) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="sex">Gender *</label>
                        <select id="sex" name="sex" required>
                            <option value="">Choose...</option>
                            <option value="Male" {{ old('sex', $pet->sex)=='Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('sex', $pet->sex)=='Female' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="status">Status *</label>
                    <select id="status" name="status" required>
                        <option value="available" {{ old('status', $pet->status)=='available' ? 'selected' : '' }}>Available</option>
                        <option value="pending" {{ old('status', $pet->status)=='pending' ? 'selected' : '' }}>Pending</option>
                        <option value="adopted" {{ old('status', $pet->status)=='adopted' ? 'selected' : '' }}>Adopted</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="5" placeholder="Describe the pet (personality, needs, etc)">{{ old('description', $pet->description) }}</textarea>
                </div>

                <div class="form-group">
                    <label for="image">Photo</label>
                    @if($pet->image_url)
                        <div style="margin-bottom: 10px;">
                            <img src="{{ $pet->image_url }}" alt="{{ $pet->name }}" style="max-width: 200px; border-radius: 8px;">
                            <p style="font-size: 0.85rem; color: #666; margin-top: 5px;">Current photo</p>
                        </div>
                    @endif
                    <input id="image" name="image" type="file" accept="image/*">
                    <p class="file-hint">Leave empty to keep current photo. JPEG, PNG, JPG or GIF. Max 2MB.</p>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn">Update Pet</button>
                    <a href="{{ route('pets.gallery') }}" class="btn cancel-btn"><- Go Back To Gallery</a>
                </div>
            </form>
        </div>
    </main>
</body>
</html>