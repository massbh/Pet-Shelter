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

// Initialize button styles - ya no es necesario porque los estilos están en CSS
function initializeButtonStyles() {
    // Los estilos ya están definidos en CSS, solo removemos la clase active si existe
    const filterButtons = document.querySelectorAll('.filter-btn[data-species]');
    filterButtons.forEach(btn => {
        btn.classList.remove('active');
    });
}

// Open/Close form element and deselect radio buttons
document.getElementById("detailed-search-btn").addEventListener("click", () => {toggleDetailedSearch(); deselectRadioButtons();});
document.getElementById("close-detailed-search").addEventListener("click", () => {toggleDetailedSearch(); deselectRadioButtons();});

function toggleDetailedSearch() {
    document.querySelector(".query-search").classList.toggle("hidden");
    document.querySelector(".detailed-search-form").classList.toggle("hidden");
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
        });
        filterState.species = null;
    } else {
        buttons.forEach(btn => {
            btn.classList.remove('active');
        });
        
        activeSpeciesFilter = species;
        const activeButton = document.querySelector(`.filter-btn[data-species="${species}"]`);
        activeButton.classList.add('active');
        filterState.species = species;
        
        const detailedSearchForm = document.getElementById('advanced-filter-form');
        if (detailedSearchForm && !detailedSearchForm.classList.contains('hidden')) {
            detailedSearchForm.classList.add('hidden');
            document.querySelector(".query-search").classList.remove('hidden');
        }
    }

    advancedFilter(filterState);
}

// Reset filters functionality
document.getElementById("reset-search-btn").addEventListener("click", resetFilters);

function resetFilters() {
    const buttons = document.querySelectorAll('.filter-btn[data-species]');
    activeSpeciesFilter = null;
    
    buttons.forEach(btn => {
        btn.classList.remove('active');
    });

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

// Pet details
function openPetDetail(petId) {
    window.location.href = `/pet/${petId}`;
}

// Animal displayal uses absolute URLs for images
function displayAnimals(animals) {
    let output = "";

    for (let animal of animals) {
    output += `
    <div class="card">
        <img src="${animal.imageUrl}" alt="${animal.name}" onclick="openPetDetail(${animal.id})" style="cursor: pointer;">
        <p>${animal.name}</p>
        <div>
            <span class="badge">${animal.age} ${animal.age == 1 ? "year" : "years"}</span>
            <span>${animal.sex}</span>
            <a href="/contact?petId=${animal.id}">Adopt</a>
        </div>
    </div>
    `;
    }
    document.getElementById("cards").innerHTML = output;
};

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