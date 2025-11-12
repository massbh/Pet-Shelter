<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Happinest - Add New Pet</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
    <main>
        <section class="hero">
            <h1>Add New Pet</h1>
            <p>Fill in the pet's information to add it to the gallery.</p>
        </section>

        <div class="create-container">
            <form action="{{ route('pets.store') }}" method="POST" enctype="multipart/form-data" class="create-form">
                @csrf

                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Name *</label>
                        <input id="name" name="name" type="text" value="{{ old('name') }}" required placeholder="Enter pet's name">
                    </div>

                    <div class="form-group">
                        <label for="species">Species *</label>
                        <select id="species" name="species" required>
                            <option value="">Choose...</option>
                            <option value="Bird" {{ old('species')=='Bird' ? 'selected' : '' }}>Bird</option>
                            <option value="Capybara" {{ old('species')=='Capybara' ? 'selected' : '' }}>Capybara</option>
                            <option value="Cat" {{ old('species')=='Cat' ? 'selected' : '' }}>Cat</option>
                            <option value="Dog" {{ old('species')=='Dog' ? 'selected' : '' }}>Dog</option>
                            <option value="Rabbit" {{ old('species')=='Rabbit' ? 'selected' : '' }}>Rabbit</option>
                            <option value="Reptile" {{ old('species')=='Reptile' ? 'selected' : '' }}>Reptile</option>
                            <option value="Turtle" {{ old('species')=='Turtle' ? 'selected' : '' }}>Turtle</option>  
                            <option value="Other" {{ old('species')=='Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="age">Age (years) *</label>
                        <input id="age" name="age" type="number" min="0" value="{{ old('age', 1) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="sex">Gender *</label>
                        <select id="sex" name="sex" required>
                            <option value="">Choose...</option>
                            <option value="Male" {{ old('sex')=='Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('sex')=='Female' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="status">Status *</label>
                    <select id="status" name="status" required>
                        <option value="available" {{ old('status','available')=='available' ? 'selected' : '' }}>Available</option>
                        <option value="pending" {{ old('status')=='pending' ? 'selected' : '' }}>Pending</option>
                        <option value="adopted" {{ old('status')=='adopted' ? 'selected' : '' }}>Adopted</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="5" placeholder="Describe the pet (personality, needs, etc)">{{ old('description') }}</textarea>
                </div>

                <div class="form-group">
                    <label for="image">Photo *</label>
                    <input id="image" name="image" type="file" accept="image/*" required>
                    <p class="file-hint">JPEG, PNG, JPG or GIF. Max 2MB.</p>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn">Add Pet</button>
                    <a href="{{ route('pets.gallery') }}" class="btn cancel-btn"><- Go Back To Gallery</a>
                </div>
            </form>
        </div>
    </main>
</body>
</html>