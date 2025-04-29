document.addEventListener('DOMContentLoaded', () => {
    const themeSwitch = document.getElementById('theme-switch');
    
    // Apply the initial theme based on user preference
    const currentTheme = document.body.classList.contains('dark-mode') ? 'dark mode' : 'light mode';
    themeSwitch.checked = currentTheme === 'dark mode';

    // Toggle theme when the user interacts with the switch
    themeSwitch.addEventListener('change', () => {
        const newTheme = themeSwitch.checked ? 'dark mode' : 'light mode';

        // Update the body class immediately
        document.body.classList.toggle('dark-mode', newTheme === 'dark mode');
        document.body.classList.toggle('light-mode', newTheme === 'light mode');

        // Save preference via AJAX
        fetch('update_theme.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ theme: newTheme })
        })
        .then(res => {
            if (!res.ok) {
                console.error('Failed to save theme preference');
            }
        })
        .catch(err => console.error('AJAX error:', err));
    });
});