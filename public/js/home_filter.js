let allAnimals = [];
let activeSpeciesFilter = null;

// Load animals from database API
fetch("/api/pets")
    .then(response => response.json())
    .then(animals => {
    // Transform database fields to match expected format
    allAnimals = animals.map(pet => ({
        id: pet.id,
        name: pet.name,
        species: pet.species,
        age: pet.age,
        sex: pet.sex,
        imageUrl: pet.image_url,
        description: pet.description
    }));
    displayAnimals(allAnimals);
    populateSpecies(allAnimals);
    initializeButtonStyles();
    })
    .catch(err => console.error("Error loading pets from database", err));

// Initialize button styles
function initializeButtonStyles() {
    const filterButtons = document.querySelectorAll('.filter-btn[data-species]');
    filterButtons.forEach(btn => {
        btn.classList.remove('active');
    });
}

// Open/Close form element and deselect radio buttons
document.querySelector(".detailed-search-btn").addEventListener("click", () => {toggleDetailedSearch(); deselectRadioButtons();});
document.querySelector(".close-detailed-search").addEventListener("click", () => {toggleDetailedSearch(); deselectRadioButtons();});

function toggleDetailedSearch() {
    document.querySelector(".query-search").classList.toggle("hidden");
    document.querySelector(".detailed-search-form").classList.toggle("hidden");
    document.querySelector(".reset-search-container").classList.toggle("hidden");
    document.querySelector(".animal-adoption").classList.toggle("filter-open");
};

// Advanced filtering
document.getElementById("advanced-filter-form").addEventListener("submit", (e) => {
    e.preventDefault();
    
    const selectedSpecies = document.querySelector('input[name="animal"]:checked');
    const selectedSex = document.querySelector('input[name="sex"]:checked');

    filterState.species = selectedSpecies ? selectedSpecies.value : null;
    filterState.sex = selectedSex ? selectedSex.value : null;
    filterState.age.minAge = +document.getElementById("sliderMinValue").value;
    filterState.age.maxAge = +document.getElementById("sliderMaxValue").value;

    advancedFilter(filterState);
    toggleDetailedSearch();
});

// Basic filtering with toggle functionality
document.querySelectorAll(".filter-btn[data-species]").forEach(button => {
    button.addEventListener("click", () => {
        const species = button.dataset.species;
        toggleSpeciesFilter(species);
    });
});



// Toggle species filter with visual feedback
function toggleSpeciesFilter(species) {
    const buttons = document.querySelectorAll('.filter-btn[data-species]');
    
    if (activeSpeciesFilter === species) {
        activeSpeciesFilter = null;
        buttons.forEach(btn => {
            btn.classList.remove('active');
            document.querySelector(".detailed-search-btn").classList.remove("active");
        });
        filterState.species = null;
    } else {
        buttons.forEach(btn => {
            btn.classList.remove('active');
            document.querySelector(".detailed-search-btn").classList.remove("active");
        });
        
        activeSpeciesFilter = species;
        const activeButton = document.querySelector(`.filter-btn[data-species="${species}"]`);
        activeButton.classList.add('active');
        filterState.species = species;
    }

    advancedFilter(filterState);
}
// include to work with advanced filtering button
document.querySelector(".advanced-filter-btn").addEventListener('click', () => {
    document.querySelector(".detailed-search-btn").classList.add("active");
    document.querySelectorAll('.filter-btn[data-species]').forEach(btn => {
        btn.classList.remove("active")});

});

// Reset filters functionality
document.getElementById("reset-search-btn").addEventListener("click", resetFilters);

function resetFilters() {
    const buttons = document.querySelectorAll('.filter-btn[data-species]');
    activeSpeciesFilter = null;
    
    buttons.forEach(btn => {
        btn.classList.remove('active');
    });
    document.querySelector(".detailed-search-btn").classList.remove("active");

    filterState.species = null;
    filterState.sex = null;
    filterState.age.minAge = null;
    filterState.age.maxAge = null;
    
    advancedFilter(filterState);
    
    const detailedSearchForm = document.getElementById('advanced-filter-form');
    const querySearch = document.querySelector('.query-search');
    
    // Reset UI state - ensure query search is visible and detailed form is hidden
    if (detailedSearchForm && !detailedSearchForm.classList.contains('hidden')) {
        detailedSearchForm.classList.add('hidden');
        if (querySearch) {
            querySearch.classList.remove('hidden');
        }
    }
    
    // Reset form elements
    deselectRadioButtons();
    document.getElementById("sliderMinValue").value = 1;
    document.getElementById("sliderMaxValue").value = 20;
    
    // Update slider display
    const ranges = document.querySelectorAll(".drange input[type=range]");
    const dmin = document.querySelector(".dmin");
    const dmax = document.querySelector(".dmax");
    if (dmin) dmin.innerHTML = ranges[0].value;
    if (dmax) dmax.innerHTML = ranges[1].value;
}

function deselectRadioButtons() {
  const radios = document.querySelectorAll('.detailed-search-form input[type="radio"]');
  radios.forEach(radio => radio.checked = false);
};

function populateSpecies(animals) {
    let species = [...new Set(animals.map(a => a.species))];

    let speciesButtons = "";
    species.forEach(sp => {
        speciesButtons += `
            <input type="radio" id="animal-${sp}" name="animal" value="${sp}">
            <label for="animal-${sp}">${sp}</label>
            `;
    });
    document.getElementById("speciesButtons").innerHTML = speciesButtons;
};

const filterState = {
    species: null,
    sex: null,
    age: { minAge: null, maxAge: null }
};

function advancedFilter(filterState) {
    let filteredAnimals = allAnimals;
    
    if (filterState.species != null)
        if (filterState.species == "Other")
            filteredAnimals = filteredAnimals.filter(a => a.species != "Dog" && a.species != "Cat");
        else
            filteredAnimals = filteredAnimals.filter(a => a.species == filterState.species);

    if (filterState.sex != null)
        filteredAnimals = filteredAnimals.filter(a => a.sex == filterState.sex);

    if (filterState.age.minAge != null && filterState.age.maxAge != null)
        filteredAnimals = filteredAnimals.filter(a => a.age >= filterState.age.minAge && a.age <= filterState.age.maxAge);

    displayAnimals(filteredAnimals);
};

// These two functions return the route to pet detail view based on its id either for users or admins
function openPetDetail(petId) {
    window.location.href = `/pet/${petId}`;
}

function openPetAdminEdit(petId) {
    window.location.href = `/pet/${petId}/edit`;
}

// Animal displayal uses absolute URLs for images
function displayAnimals(animals) {
    let output = "";

    const isAdminGallery = window.location.pathname.startsWith('/admin/pet-gallery');

    for (let animal of animals) {
        // Determine where to go when the image is clicked based on if it´s admin gallery or not
        const imgOnClick = isAdminGallery
            ? `openPetAdminEdit(${animal.id})`
            : `openPetDetail(${animal.id})`;

        // Create the card HTML, with admin buttons if in admin gallery
        output += `
        <div class="card">
            <img src="${animal.imageUrl}" alt="${animal.name}" onclick="${imgOnClick}" style="cursor: pointer;">
            <div class="card-name-row">
                <p>${animal.name}</p>
                ${isAdminGallery ? `
                <div style="display: flex; justify-content: flex-end; gap: 6px;">
                    <button class="view-pet-btn" onclick="event.stopPropagation(); openPetDetail(${animal.id})" title="View ${animal.name}'s adoption page">
                        View
                    </button>
                    <button class="delete-pet-btn" onclick="event.stopPropagation(); deletePet(${animal.id}, '${animal.name}')" title="Delete ${animal.name}">
                        Delete
                    </button>
                </div>
                ` : ''}
            </div>
            <div class="card-info-row">
                <span class="badge">${animal.age} ${animal.age == 1 ? "year" : "years"}</span>
                <span>${animal.sex}</span>
            </div>
        </div>
        `;
    }
    document.getElementById("cards").innerHTML = output;
}

// Delete pet function
function deletePet(petId, petName) {
    if (!confirm(`Are you sure you want to delete ${petName}? This action cannot be undone.`)) {
        return;
    }

    fetch(`/pets/${petId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Reload the animals from the API after deleting one
            fetch("/api/pets")
                .then(response => response.json())
                .then(animals => {
                    allAnimals = animals.map(pet => ({
                        id: pet.id,
                        name: pet.name,
                        species: pet.species,
                        age: pet.age,
                        sex: pet.sex,
                        imageUrl: pet.image_url,
                        description: pet.description
                    }));
                    displayAnimals(allAnimals);
                    populateSpecies(allAnimals);
                    
                    // Show success message
                    showFlashMessage('success', `${petName} has been deleted successfully!`);
                })
                .catch(err => console.error("Error reloading pets", err));
        } else {
            showFlashMessage('error', 'Failed to delete pet. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showFlashMessage('error', 'An error occurred while deleting the pet.');
    });
}

// Show flash message function
function showFlashMessage(type, message) {
    // Remove any existing flash messages
    const existingFlash = document.querySelector('.flash-message');
    if (existingFlash) {
        existingFlash.remove();
    }

    // Create new flash message
    const flashDiv = document.createElement('div');
    flashDiv.className = `flash-message ${type}`;
    flashDiv.textContent = message;
    
    // Insert before the cards section
    const cardsSection = document.querySelector('.cards');
    if (cardsSection) {
        cardsSection.parentNode.insertBefore(flashDiv, cardsSection);
    }
}

// Slider
window.addEventListener("load", () => {
  // (PART A) LOOP THROUGH DUAL RANGE SLIDERS
  document.querySelectorAll(".drange").forEach(drange => {
    // (PART B) GET RANGE PICKERS & VALUE DISPLAY
    let ranges = drange.querySelectorAll("input[type=range]"),
    dmin = drange.querySelector(".dmin"),
    dmax = drange.querySelector(".dmax");

    // (PART C) MIN CANNOT BE MORE THAN MAX
    ranges[0].addEventListener("input", e => {
      if (+ranges[0].value >= +ranges[1].value) {
        ranges[0].value = +ranges[1].value - 1;
      }
      dmin.innerHTML = ranges[0].value;
    });

    // (PART D) MAX CANNOT BE LESS THAN MIN
    ranges[1].addEventListener("input", e => {
      if (+ranges[1].value <= +ranges[0].value) {
        ranges[1].value = +ranges[0].value + 1;
      }
      dmax.innerHTML = ranges[1].value;
    });

    // (PART E) INIT VALUE DISPLAY
    dmin.innerHTML = ranges[0].value;
    dmax.innerHTML = ranges[1].value;
  });
});