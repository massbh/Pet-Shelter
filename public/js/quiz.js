document.addEventListener('DOMContentLoaded', function() {
    const startBtn = document.getElementById('start-quiz');
    const restartBtn = document.getElementById('restart-quiz');
    const quizIntro = document.getElementById('quiz-intro');
    const quizContainer = document.getElementById('quiz-container');
    const resultsContainer = document.getElementById('results-container');
    const questionContainer = document.getElementById('question-container');
    const progressBar = document.getElementById('bar');
    const progress = document.getElementById('progress')

    let questions = [];
    let currentQuestion = 0;
    let answers = [];

    startBtn.addEventListener('click', loadData);

    function loadData() {
        quizIntro.style.display = "none";
            fetch('/api/quiz/questions')
            .then(res => res.json())
            .then(data => {
                questions = data;
                answers = new Array(questions.length).fill(null);
                displayQuestions();
            })
            .catch(error => {
                console.error('Error loading quiz questions:', error);
                alert('Error loading quiz. Please try again.');
                quizIntro.style.display = "flex";
            });
    }

    function displayQuestions(data) {
        if(questions.length == 0 || !questions) {
            window.location.replace('/dashboard');
            return;
        }

        quizContainer.classList.remove('hidden');
        showQuestion(currentQuestion);
    }

    function showQuestion(index) {
        //If we reach the last question, result next
        if(index >= questions.length) {
            showResults();
            return;
        }

        const progressCalc = ((currentQuestion + 1)/(questions.length)) * 100
        progress.style.width = `${progressCalc}%`

        if(index == 0) {
            questionContainer.innerHTML = `
                <h3 class="question-title">${questions[index].question}</h3>
                <div class="answers">
                    ${questions[index].options.map((option, i) => `
                        <button id="answer-btn" class="answer-btn" data-index="${i}">${option.text}</button>
                    `).join('')}
                </div>
                <button style="margin-bottom: 50px;" id="next-question" class="next">Next</button>
            `;
        }
        else {
            questionContainer.innerHTML = `
                <h3 class="question-title">${questions[index].question}</h3>
                <div class="answers">
                    ${questions[index].options.map((option, i) => `
                        <button id="answer-btn" class="answer-btn" data-index="${i}">${option.text}</button>
                    `).join('')}
                </div>
                <div class="next-previous-container">
                    <button id="prev-question" class="prev">Previous</button>
                    <button id="next-question" class="next">Next</button>
                </div>
            `;
        }

        const answerBtns = document.querySelectorAll('.answer-btn');
        answerBtns.forEach(button => {
            button.addEventListener('click', function() {
                //First remove 'selected' from every button
                answerBtns.forEach(b => b.classList.remove('selected'));
                
                //Then add 'selected' just to the clicked one
                button.classList.add('selected');

                //And finally add the selected button to answers[]
                const answerIndex = parseInt(button.getAttribute('data-index'));
                answers[currentQuestion] = questions[currentQuestion].options[answerIndex];
            });
        });

        if(answers[index] !== null) {
            // Find the index of the saved option
            const selectedIndex = questions[index].options.findIndex(opt => 
                JSON.stringify(opt) === JSON.stringify(answers[index])
            );
            //The -1 is bc apparently findIndex returns -1 if it doesn't find any match
            if(selectedIndex !== -1) {
                answerBtns[selectedIndex].classList.add('selected');
            }
        }

        const nextBtn = document.getElementById("next-question");
        nextBtn.addEventListener('click', function() {
            currentQuestion++;
            showQuestion(currentQuestion);
        });

        const prevBtn = document.getElementById("prev-question");
        if(prevBtn) {
            prevBtn.addEventListener('click', function() {
                currentQuestion--;
                showQuestion(currentQuestion);
            });
        }
    }

    //Ok here goes the messy part, basically I needed to somehow give animals characteristics or traits based on
    //their species, ages ans descriptions. So this method takes the animal's traits and process them to match them
    //with the traits that appear in our quiz based on the questions.
    function analyzeAnimalTraits(animal) {
        const traits = {};
        const desc = animal.description.toLowerCase();
        
        // Traits based on the animal's species
        if(animal.species.toLowerCase() === 'dog') {
            traits.species_dog = 15;
            traits.social = 2;
        } else if(animal.species.toLowerCase() === 'cat') {
            traits.species_cat = 15;
            traits.independent = 1;
        } else {
            traits.species_exotic = 15;
        }

        // Traits based on the animal's age
        if(animal.age <= 3) {
            traits.age_young = 3;
            traits.energy = 3;
            traits.playful = 2;
        } else if(animal.age <= 8) {
            traits.age_adult = 3;
            traits.energy = 2;
        } else {
            traits.age_senior = 3;
            traits.calm = 2;
            traits.energy = 1;
        }

        // Traits based on description keywords
        const energyWords = [
            'energy', 'energetic', 'active', 'playful', 'adventur', 'excels', 
            'loves playing', 'fetch', 'learning', 'smart', 'bubbly', 'hopping',
            'exploring', 'chasing', 'outdoor', 'hiking', 'flying'
        ];
        
        const calmWords = [
            'calm', 'quiet', 'gentle', 'peaceful', 'relax', 'wise', 'wisdom', 
            'mature', 'patient', 'steady', 'chill', 'tranquil', 'serene',
            'cozy', 'naps', 'cuddles', 'leisurely', 'slow', 'peaceful',
            'observant', 'ancient soul'
        ];
        
        const playfulWords = [
            'playful', 'mischievous', 'curious', 'tricks', 'games', 'toy',
            'interactive', 'puzzle', 'learning', 'fetch', 'feather',
            'laser pointer', 'exploring', 'wonder', 'binky', 'meow back'
        ];
        
        const socialWords = [
            'friendly', 'social', 'companion', 'family', 'everyone', 'gets along',
            'meeting', 'loyal', 'great with', 'butterfly', 'conversation',
            'group activities', 'attention', 'people', 'other pets', 'other dogs',
            'cheerful', 'bright', 'sweet'
        ];
        
        const independentWords = [
            'independent', 'alone time', 'her terms', 'his terms', 'own', 
            'diva', 'regal', 'confident', 'mysterious', 'cool customer',
            'not like to share', 'spirit', 'graceful'
        ];
        
        const maintenanceWords = [
            'low maintenance', 'easy', 'house-trained', 'commands', 'training',
            'stimulation', 'routine', 'safe', 'proper', 'enclosed'
        ];
    
        // Count matches for energy
        let energyScore = 0;
        energyWords.forEach(word => {
            if(desc.includes(word)) energyScore++;
        });
        if(energyScore > 0) traits.energy = (traits.energy || 0) + energyScore;
        
        // Count matches for calm
        let calmScore = 0;
        calmWords.forEach(word => {
            if(desc.includes(word)) calmScore++;
        });
        if(calmScore > 0) traits.calm = (traits.calm || 0) + calmScore;
        
        // Count matches for playful
        let playfulScore = 0;
        playfulWords.forEach(word => {
            if(desc.includes(word)) playfulScore++;
        });
        if(playfulScore > 0) traits.playful = (traits.playful || 0) + playfulScore;
        
        // Count matches for social
        let socialScore = 0;
        socialWords.forEach(word => {
            if(desc.includes(word)) socialScore++;
        });
        if(socialScore > 0) traits.social = (traits.social || 0) + socialScore;
        
        // Count matches for independent
        let independentScore = 0;
        independentWords.forEach(word => {
            if(desc.includes(word)) independentScore++;
        });
        if(independentScore > 0) traits.independent = (traits.independent || 0) + independentScore;
        
        // Count matches for maintenance
        let maintenanceScore = 0;
        maintenanceWords.forEach(word => {
            if(desc.includes(word)) maintenanceScore++;
        });
        if(maintenanceScore > 0) traits.maintenance = (traits.maintenance || 0) + maintenanceScore;
        
        return traits;
    }

    function calculateMatch(userAnswers, animals) {
        const userProfile = {};
        
        // Sum all traits from user answers
        userAnswers.forEach(answer => {
            if(answer && answer.traits) {
                for (let trait in answer.traits) {
                    userProfile[trait] = (userProfile[trait] || 0) + answer.traits[trait];
                }
            }
        });

        // Calculate compatibility with each animal comparing the traits the user is looking for with what the animals have
        const matches = animals.map(animal => {
            // Infer traits automatically from animal data
            const animalTraits = analyzeAnimalTraits(animal);
            
            let score = 0;
            let maxScore = 0;
            
            for (let trait in userProfile) {
                const userValue = userProfile[trait];
                const animalValue = animalTraits[trait] || 0;
                
                maxScore += Math.abs(userValue);
                
                // Calculate similarity
                if(userValue > 0 && animalValue > 0) {
                    score += Math.min(Math.abs(userValue), Math.abs(animalValue));
                } else if(userValue < 0 && animalValue < 0) {
                    score += Math.min(Math.abs(userValue), Math.abs(animalValue));
                }
            }
            
            const compatibility = maxScore > 0 ? (score / maxScore) * 100 : 50;
            
            return {
                animal: animal,
                score: compatibility,
                inferredTraits: animalTraits
            };
        });

        // Sort by compatibility descending
        matches.sort((a, b) => b.score - a.score);
        
        return matches[0];
    }


function showResults() {
    quizContainer.classList.add('hidden');
    
    fetch('./assets/animals.json')
        .then(res => res.json())
        .then(animals => {
            const bestMatch = calculateMatch(answers, animals);
            
            resultsContainer.innerHTML = `
                <h2 class="results-title">Your Perfect Match!</h2>
                <div class="match-card">
                    <img src="${bestMatch.animal.imageUrl}" alt="${bestMatch.animal.name}" class="match-image">
                    <h3 class="match-name">${bestMatch.animal.name}</h3>
                    <p class="compatibility">${Math.round(bestMatch.score)}% Compatible</p>
                    <p class="species-info">${bestMatch.animal.species} • ${bestMatch.animal.age} years old • ${bestMatch.animal.sex}</p>
                    <p class="match-description">${bestMatch.animal.description}</p>
                    <a href="/pet/${bestMatch.animal.id}" class="adopt-btn">Meet ${bestMatch.animal.name}</a>
                    <button id="restart-quiz" class="restart-btn">Take Quiz Again</button>
                </div>
            `;
            
            resultsContainer.classList.remove('hidden');

            // Add restart functionality
            document.getElementById('restart-quiz').addEventListener('click', function() {
                currentQuestion = 0;
                answers = [];
                resultsContainer.classList.add('hidden');
                quizIntro.style.display = "flex";
            });
        });
    }

});