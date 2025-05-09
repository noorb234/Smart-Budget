<header>
    <div id = "logo-container">
        <img src = "logo.png">
    </div>
    <nav class="nav">
        <ul>
            <li> <a class="navTab" href ="dashboard.php">Home</a></li>
            <!-- <li> <a class="navTab" href ="dashboard.php">Dashboard</a></li> -->
            <li> <a class="navTab" href = "BudgetScreen.php">Budgeting</a></li>
            <li> <a class="navTab" href = "futurePlanning.php">Future Planning</a></li>
        </ul>

        <div class = "dropdown">
            <div class = "icon"> 
                <i class="fa-solid fa-user"></i>
            </div>
            <!-- <script src="toggleTheme.js"></script> -->

            <div class = "dropdown-content">
                <div class="theme-toggle">
                    <label class="switch">
                        <input type="checkbox" id="theme-switch" <?php if ($theme_preference === 'dark mode') echo 'checked'; ?>>
                        <span class="slider-icon">
                        <span class="icon sun"><i class="fas fa-sun"></i></span>
                        <span class="icon moon"><i class="fas fa-moon"></i></span>
                        </span>
                    </label>
                </div>

                <a href="settings.php">Settings</a>
                <a href="#">Notifications</a>
                <a href="logout.php">Log Out</a>
            </div>
        </div>

        
    </nav>

</header>
