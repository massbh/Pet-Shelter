let allAnimals = [];

fetch("../assets/animals.json")
    .then(response => response.json())
    .then(animals => {
    allAnimals = animals;
    displayAnimals(allAnimals);
    })
    .catch(err => console.error("Error loading animals.json", err));

function displayAnimals(animals) {
    let output = "";
    for (let animal of animals) {
        output += `
    <div class="card">
        <img src="${animal.imageUrl}">
        <p>${animal.name}</p>
        <p>${animal.age}</p>
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

