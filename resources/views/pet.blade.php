<!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8" />
        <title>{{ $pet->name ?? 'Pet' }} - Adopt Me</title>
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
        <link rel="stylesheet" href="{{ asset('css/header.css') }}">
        <link rel="stylesheet" href="{{ asset('css/pet.css') }}">

    </head>
    <body>
        <main>
            <section class="pet-detail-hero">
                <div class="pet-image-container">
                    <img src="{{ $pet->imageUrl ?? '' }}" alt="{{ $pet->name ?? 'Pet' }}" class="pet-main-image">
                </div>
                
                <div class="pet-info-container">
                    <div class="pet-info-content">
                        <h1 class="pet-name">{{ $pet->name ?? 'Unknown Pet' }}</h1>
                        
                        <div class="pet-basic-info">
                            <div class="info-badges">
                                <span class="age-badge">{{ $pet->age ?? '?' }}{{ isset($pet->age) && $pet->age == 1 ? ' year' : ' years' }}</span>
                                <span class="gender-badge {{ isset($pet->sex) ? strtolower($pet->sex) : '' }}">
                                    @if(isset($pet->sex))
                                        @if($pet->sex === 'Male')
                                            ♂
                                        @else
                                            ♀
                                        @endif
                                    @else
                                        ?
                                    @endif
                                </span>
                                <span class="species-badge">{{ $pet->species ?? 'Unknown' }}</span>
                            </div>
                        </div>

                        <div class="pet-description">
                            <p>{{ $pet->description ?? 'No description available for this pet.' }}</p>
                        </div>

                        <div class="pet-actions">
                            @auth
                                @if(!Auth::user()->isAdmin())
                                    <a href="/contact?petId={{ $pet->id ?? '' }}" class="adopt-btn">Adopt Me</a>
                                @else
                                    <p style="color: #666; font-style: italic;">Administrators cannot submit adoption requests. Please register as a user to adopt pets.</p>
                                @endif
                            @else
                                <a href="/contact?petId={{ $pet->id ?? '' }}" class="adopt-btn">Adopt Me</a>
                            @endauth
                            <button onclick="window.history.back()" class="back-btn">← Back</button>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </body>
</html>