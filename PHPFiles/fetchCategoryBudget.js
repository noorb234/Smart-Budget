// Function to automatically fetch the budget when a category is selected
function submitForm() {
    const categorySelect = document.getElementById('category');
    const categoryId = categorySelect.value;
    
    // DEBUG: Log the categoryId to make sure it's being passed
    console.log('Category ID selected:', categoryId);
    
    // If a category is selected, fetch its budget using AJAX
    if (categoryId) {
        fetchCategoryBudget(categoryId);
    }
}

// Function to fetch the category budget via AJAX and update the UI
function fetchCategoryBudget(categoryId) {
    const url = `BudgetScreen.php?category=${categoryId}`;
    
    const xhr = new XMLHttpRequest();
    
    xhr.open('GET', url, true);
    
    xhr.onload = function () {
        if (xhr.status === 200) {
            try {
                // Parse the JSON response
                const response = JSON.parse(xhr.responseText);
                
                // Ensure the response contains the 'category_budget' and 'prev_month_budget' fields
                if (response && response.category_budget && response.prev_month_budget) {
                    // Update the category budget label with the fetched budget
                    document.getElementById('category-budget').innerText = `$${response.category_budget}`;
                    
                    // Update the previous month's budget label with the fetched budget
                    document.getElementById('prev-month-budget').innerText = `$${response.prev_month_budget}`;
                    
                    // Make the previous month's budget visible
                    document.getElementById('prev-month-budget').style.display = 'inline';
                    document.getElementById('prev-month-budget-label').style.display = 'inline';
                } else {
                    // If no valid budget is returned, set both to $0.00
                    document.getElementById('category-budget').innerText = '$0.00';
                    document.getElementById('prev-month-budget').innerText = '$0.00';
                    document.getElementById('prev-month-budget').style.display = 'none';
                    document.getElementById('prev-month-budget-label').style.display = 'none';
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

    xhr.send();
}