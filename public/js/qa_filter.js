function toggleAnswer(element) {
    const qaItem = element.parentNode;
    const answer = qaItem.querySelector('.answer');
    const icon = element.querySelector('.toggle-icon');

    if (answer.style.display === 'none' || answer.style.display === '') {
        answer.style.display = 'block';
        icon.textContent = '-';
        qaItem.classList.add('active');
    } else {
        answer.style.display = 'none';
        icon.textContent = '+';
        qaItem.classList.remove('active');
    }
}

function filterByCategory(category) {
    const items = document.querySelectorAll('.qa-item');
    const buttons = document.querySelectorAll('.category-btn');

    // Update active button
    buttons.forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');

    // Filter items
    items.forEach(item => {
        if (category === 'all' || item.dataset.category === category) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
}

function filterQuestions() {
    const searchTerm = document.getElementById('qaSearchInput').value.toLowerCase();
    const items = document.querySelectorAll('.qa-item');

    items.forEach(item => {
        const question = item.querySelector('.question h3').textContent.toLowerCase();
        const answer = item.querySelector('.answer').textContent.toLowerCase();

        if (question.includes(searchTerm) || answer.includes(searchTerm) || searchTerm === '') {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
}

// Hide all answers by default
document.addEventListener('DOMContentLoaded', function () {
    const answers = document.querySelectorAll('.answer');
    answers.forEach(answer => {
        answer.style.display = 'none';
    });
});
