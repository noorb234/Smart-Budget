<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>About SmartBudget</title>
  <link rel="stylesheet" href="styles.css" />
  <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background-color: #ffffff;
      color: #0A599D;
    }

    .about-container {
      max-width: 1000px;
      margin: auto;
      padding: 40px 20px;
    }

    .header {
      background-color: #0A599D;
      color: whitesmoke;
      text-align: center;
      padding: 30px 20px;
      border-radius: 30px;
      font-size: 36px;
      font-weight: bold;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .section {
      background-color: #E2F1FD;
      margin: 60px 0;
      padding: 40px 30px;
      border-radius: 20px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
    }

    .section h2 {
      font-size: 28px;
      margin-bottom: 20px;
    }

    .section p {
      font-size: 18px;
      line-height: 1.7;
      max-width: 800px;
      margin-bottom: 15px;
    }

    .cta-button {
      display: inline-block;
      margin-top: 30px;
      padding: 15px 40px;
      background-color: #0A599D;
      color: white;
      text-decoration: none;
      font-weight: bold;
      font-size: 18px;
      border-radius: 30px;
      transition: background 0.3s ease, transform 0.2s;
    }

    .cta-button:hover {
      background-color: #084776;
      transform: translateY(-3px);
    }

    @media (max-width: 768px) {
      .header {
        font-size: 28px;
      }

      .section h2 {
        font-size: 24px;
      }

      .section p {
        font-size: 16px;
      }

      .cta-button {
        font-size: 16px;
        padding: 12px 30px;
      }
    }
  </style>
</head>
<body>

  <div class="about-container">
    <div class="header" data-aos="fade-down">SmartBudget</div>

    <div class="section" data-aos="fade-right">
      <h2>Make Your Money Make Sense</h2>
      <p>
        SmartBudget helps you see exactly where your money is going, in real-time. Whether you're budgeting for coffee, rent, or your next big dream, we've got you covered.
      </p>
      <p>
        No spreadsheets. No confusion. Just crystal-clear tracking, smart categorization, and full control — all in one place.
      </p>
    </div>

    <div class="section" data-aos="fade-left">
      <h2>Set Goals That Stick</h2>
      <p>
        Whether you're saving for that dream trip, emergency fund, or new laptop, SmartBudget turns those goals into reality. Set a target, track your progress, and let our system gently push you to the finish line.
      </p>
      <p>
        Visual motivation keeps you moving forward — and notifications make sure you stay on track.
      </p>
    </div>

    <div class="section" data-aos="fade-up">
      <h2>Understand Your Habits</h2>
      <p>
        With beautifully designed charts and real-time reports, SmartBudget shows you how you spend, where you overspend, and where you can save. 
      </p>
      <p>
        It's like having a personal finance coach — minus the fees.
      </p>
    </div>

    <div class="section" data-aos="fade-right">
      <h2>Peace of Mind, Built In</h2>
      <p>
        Your financial data is encrypted and stored with the highest standards of security. We never sell or share your information. You’re in control — always.
      </p>
    </div>

    <div class="section" data-aos="zoom-in">
      <h2>Ready to Get Smart With Your Budget?</h2>
      <p>
        Join the SmartBudget community and start taking charge of your finances today. It’s simple, free to start, and designed to make managing money feel easy.
      </p>
      <a href="Startup.php" class="cta-button">Try for Free</a>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
  <script>
    AOS.init({
      duration: 1000,
      once: true,
    });
  </script>
</body>
</html>
