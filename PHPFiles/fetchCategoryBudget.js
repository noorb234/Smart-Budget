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

// Function to clear the budget via AJAX
function clearBudget() {
    const categoryId = document.getElementById('category').value;

    // Check if a category is selected
    if (!categoryId) {
        alert('Please select a category first.');
        return;
    }

    if (confirm("Are you sure you want to clear the budget for this category?")) {
        // Prepare data for submission (clear budget is set to 0.00)
        const formData = new FormData();
        formData.append('category', categoryId);
        formData.append('ajax', '1');  // Indicate this is an AJAX request

        // Send AJAX request to clear the budget
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'clear_budget.php', true);
        xhr.onload = function () {
            if (xhr.status === 200) {
                try {
                    const response = JSON.parse(xhr.responseText);

                    if (response.status === 'success') {
                        // Update the category budget UI after clearing
                        document.getElementById('category-budget').innerText = `$0.00`; // Update budget label
                        alert('Budget cleared successfully!');

                        // Update the table and total budget sum
                        updateBudgetTableAndSum();
                    } else {
                        alert(response.message || 'Failed to clear the budget.');
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

                    // Call the function to update the budget table and sum label
                    updateBudgetTableAndSum();
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

// Function to update the budget table and sum label after an action
function updateBudgetTableAndSum() {
    const categoryId = document.getElementById('category').value;

    // Make an AJAX request to fetch the updated budgets
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'get_budgets.php?category=' + categoryId, true);

    xhr.onload = function () {
        if (xhr.status === 200) {
            try {
                const response = JSON.parse(xhr.responseText);

                // Check for errors in the response
                if (response.status === 'error') {
                    console.error('Error: ', response.message);
                    return;
                }

                // Update the table with the new budget data
                const budgetTable = document.getElementById('budget-table');
                budgetTable.innerHTML = ''; // Clear the current table
				
				// Add header row
				const headerRow = document.createElement('tr');
				headerRow.innerHTML = `
					<th>Category</th>
					<th>Budget Amount</th>
				`;
				budgetTable.appendChild(headerRow);

                // Check if the response contains a 'budgets' array
                if (response.budgets && Array.isArray(response.budgets)) {
                    response.budgets.forEach(budget => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${budget.category_name}</td>
                            <td>$${parseFloat(budget.monthly_limit).toFixed(2)}</td>
                        `;
                        budgetTable.appendChild(row);
                    });
                } else {
                    console.error('Invalid budgets data received');
                }

                // Update the sum label (e.g., total budget for the user)
                const sumLabel = document.getElementById('total-budget');
                if (response.total_budget) {
                    sumLabel.innerText = `$${response.total_budget.toFixed(2)}`;
                } else {
                    console.error('Total budget sum not found in the response');
                }
            } catch (e) {
                console.error('Error parsing budget response:', e);
            }
        } else {
            console.error('Error fetching budget data:', xhr.status);
        }
    };

    xhr.send();
}