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
            <button onclick="filterState.species='${sp}'; advancedFilter(filterState)">
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
};

function toggleDetailedSearch() {
    document.querySelector(".query-search").classList.toggle("hidden");
    document.querySelector(".detailed-search").classList.toggle("hidden");
};

