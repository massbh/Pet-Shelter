let allAnimals = [];

fetch("../assets/animals.json")
    .then(response => response.json())
    .then(animals => {
    allAnimals = animals;
    displayAnimals(allAnimals);
    populateSpecies(allAnimals);
    })
    .catch(err => console.error("Error loading animals.json", err));


function populateSpecies(animals) {
    let species = [...new Set(animals.map(a => a.species))];

    let speciesButtons = "";
    species.forEach(sp => {
        speciesButtons += `
            <button onclick="filterState.species='${sp}'; setAnimalText(1, '${sp}')">
                ${sp}
            </button>
        `;
    });
    document.getElementById("speciesButtons").innerHTML = speciesButtons;
};

function displayAnimals(animals) {
    let output = "";

    for (let animal of animals) {
    output += `
    <div class="card">
        <img src="${animal.imageUrl}">
        <p>${animal.name}</p>
        <div>
            <span class="badge">${animal.age} ${animal.age == 1 ? "year" : "years"}</span>
            <span>${animal.sex}</span>
        </div>
    </div>
    `;
    }
    document.getElementById("cards").innerHTML = output;
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

    // closes the detailed search if it's open after filtering
    if (document.querySelector(".query-search").classList.contains("hidden")) 
        toggleDetailedSearch(); 
    

    // Reset filter
    filterState.species = filterState.sex = filterState.age.minAge = filterState.age.maxAge = null; 
    setAnimalText(0, 'Any');
};

function toggleDetailedSearch() {
    document.querySelector(".query-search").classList.toggle("hidden");
    document.querySelector(".detailed-search").classList.toggle("hidden");
};

function setAnimalText(number, text) {
    if (number == 0) {
       document.getElementById("animalTextSpecies").textContent = text; 
       document.getElementById("animalTextGender").textContent = text;
    }
    else if (number == 1) {
        document.getElementById("animalTextSpecies").textContent = text;
    }
    else if (number == 2) {
        document.getElementById("animalTextGender").textContent = text;
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