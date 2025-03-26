// Function to automatically fetch the budget when a category is selected
function submitForm() {
    const categorySelect = document.getElementById('category');
    const categoryId = categorySelect.value;
	
    // If a category is selected, fetch its budget using AJAX
    if (categoryId) {
        fetchCategoryBudget(categoryId);
    }
}

// Function to fetch the category budget via AJAX and update the UI
function fetchCategoryBudget(categoryId) {
    const url = `BudgetScreen.php?category=${categoryId}`;
    
    // Create a new XMLHttpRequest object
    const xhr = new XMLHttpRequest();
    
    // Set up the AJAX request
    xhr.open('GET', url, true);
    
    // Set up the callback function to handle the response
    xhr.onload = function () {
        if (xhr.status === 200) {
            try {
                // Parse the JSON response
                const response = JSON.parse(xhr.responseText);
                
                // Ensure the response contains the 'budget' field
                if (response && response.budget) {
                    // Update the category budget label with the fetched budget
                    document.getElementById('category-budget').innerText = `$${response.budget}`;
                } else {
                    // If no valid budget is returned, set to $0.00
                    document.getElementById('category-budget').innerText = '$0.00';
                }
            } catch (e) {
                // Log error if JSON parsing fails
                console.error('Error parsing JSON response:', e);
            }
        } else {
            // If there's an error with the AJAX request, log the error
            console.error('AJAX Request failed. Status:', xhr.status);
        }
    };

    // Send the AJAX request
    xhr.send();
}