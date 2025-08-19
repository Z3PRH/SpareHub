function searchParts() {
    const brand = document.getElementById('brand').value;
    const model = document.getElementById('model').value;
    const year = document.getElementById('year').value;

    if (brand && model && year) {
        const queryString = `brand=${encodeURIComponent(brand)}&model=${encodeURIComponent(model)}&year=${encodeURIComponent(year)}`;
        window.location.href = `parts.html?${queryString}`;
    } else {
        alert('Please select a brand, model, and year.');
    }

}
function searchAllParts() {
    window.location.href = 'results.html';
}