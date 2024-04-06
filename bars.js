document.getElementById('barsTableContainer').innerHTML = ''; // Clear previous content
data.forEach(bar => {
    const barContainer = document.createElement('div');
    barContainer.classList.add('card');

    const barName = document.createElement('div');
    barName.classList.add('bar-name');
    barName.textContent = bar.name;
    barContainer.appendChild(barName);

    // Add other bar details similarly

    document.getElementById('barsTableContainer').appendChild(barContainer);
});
