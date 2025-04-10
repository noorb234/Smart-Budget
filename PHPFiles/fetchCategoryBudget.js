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
    const url = `BudgetScreen.php?category=${categoryId}&ajax=1`;
    
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

// Function to save the budget via AJAX
function saveBudget() {
    const budgetValue = document.getElementById('edit-budget-input').value;
    const categoryId = document.getElementById('category').value;

    // Check if budget value is provided
    if (!budgetValue || !categoryId) {
        alert('Please enter a valid budget and select a category.');
        return;
    }

    // Prepare data for submission
    const formData = new FormData();
    formData.append('category', categoryId);
    formData.append('budget', budgetValue);
    formData.append('ajax', '1');  // Indicate this is an AJAX request

    // Send AJAX request to update the budget
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'BudgetScreen.php', true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            try {
                const response = JSON.parse(xhr.responseText);

                if (response.status === 'success') {
                    // Update the category budget UI after saving
                    document.getElementById('category-budget').innerText = `$${response.category_budget}`;
                    alert('Budget saved successfully!');

                    // Redirect the user back to the main BudgetScreen.php page without any query parameters
                    window.location.href = 'BudgetScreen.php';  // Redirect to BudgetScreen.php without any query parameters
                } else {
                    alert('Failed to save the budget.');
                }
            } catch (e) {
                console.error('Error parsing the response:', e);
            }
        } else {
            console.error('Error in AJAX request:', xhr.status);
        }
    };

    xhr.send(formData);
}