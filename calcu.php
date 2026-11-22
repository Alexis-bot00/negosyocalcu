
<?php
$result = "";
$message = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate required fields
    if (
        !isset($_POST['num1'], $_POST['num2'], $_POST['operation'], $_POST['business']) ||
        $_POST['num1'] === "" ||
        $_POST['num2'] === "" ||
        $_POST['operation'] === "" ||
        $_POST['business'] === ""
    ) {
        $error = "Please fill in all fields.";

    } elseif (
        !is_numeric($_POST['num1']) ||
        !is_numeric($_POST['num2'])
    ) {
        $error = "Please enter valid numbers.";

    } else {

        // Type casting
        $num1 = (float) $_POST['num1'];
        $num2 = (float) $_POST['num2'];

        $operation = $_POST['operation'];
        $business = $_POST['business'];

        // Validate operation
        $valid_operations = ['add', 'subtract', 'multiply', 'divide'];

        if (!in_array($operation, $valid_operations, true)) {
            $error = "Invalid operation selected.";

        // Validate business type
        } elseif (!in_array($business, ['Palengke', 'Canteen', 'Sari-sari Store'], true)) {
            $error = "Invalid business type selected.";

        } else {

            // Perform calculation
            switch ($operation) {

                case 'add':
                    $answer = $num1 + $num2;
                    break;

                case 'subtract':
                    $answer = $num1 - $num2;
                    break;

                case 'multiply':
                    $answer = $num1 * $num2;
                    break;

                case 'divide':
                    if ($num2 == 0) {
                        $error = "Dili pwede ang division by zero!";
                    } else {
                        $answer = $num1 / $num2;
                    }
                    break;
            }

            // Display result
            if ($error == "") {

                $formatted = number_format($answer, 2);

                if ($business == "Palengke") {
                    $message = "Ang total para sa imong palit:";
                    $result = "₱" . $formatted;

                } elseif ($business == "Canteen") {
                    $message = "Total para sa imong order:";
                    $result = "₱" . $formatted;

                } elseif ($business == "Sari-sari Store") {

                    if ($operation == "subtract") {
                        $message = "Ang sukli gikan sa ₱" . number_format($num1, 2) . ":";
                        $result = "₱" . $formatted;
                    } else {
                        $message = "Total sa imong tindahan:";
                        $result = "₱" . $formatted;
                    }
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boholano Business Helper</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            background: #090909;
            color: #ffffff;
            min-height: 100vh;
            padding: 40px 20px;
        }

        .container {
            max-width: 950px;
            margin: auto;
        }

        .header {
            text-align: center;
            margin-bottom: 35px;
        }

        .logo {
            width: 55px;
            height: 55px;
            margin: 0 auto 15px;
            background: linear-gradient(135deg, #d4af37, #f5df91);
            color: #111;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            font-weight: bold;
        }

        .header h1 {
            font-size: 30px;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }

        .header h1 span {
            color: #d4af37;
        }

        .header p {
            color: #888;
            font-size: 14px;
        }

        .calculator {
            background: #141414;
            border: 1px solid #292929;
            border-radius: 18px;
            padding: 35px;
            box-shadow: 0 15px 45px rgba(0,0,0,0.4);
        }

        .section-title {
            font-size: 17px;
            margin-bottom: 22px;
            color: #e5c76b;
        }

        .input-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 25px;
        }

        .field label {
            display: block;
            color: #aaa;
            font-size: 13px;
            margin-bottom: 10px;
        }

        input[type="number"], select {
            width: 100%;
            padding: 14px;
            background: #202020;
            color: white;
            border: 1px solid #363636;
            border-radius: 9px;
            outline: none;
            font-size: 14px;
        }

        input:focus, select:focus {
            border-color: #d4af37;
        }

        select {
            cursor: pointer;
        }

        select option {
            background: #202020;
            color: white;
        }

        .business-options {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-bottom: 28px;
        }

        .business-option {
            position: relative;
        }

        .business-option input {
            position: absolute;
            opacity: 0;
        }

        .business-option label {
            display: block;
            text-align: center;
            padding: 16px 5px;
            background: #202020;
            border: 1px solid #363636;
            border-radius: 9px;
            color: #aaa;
            cursor: pointer;
            font-size: 13px;
            transition: 0.2s;
        }

        .business-option input:checked + label {
            border-color: #d4af37;
            background: #332b16;
            color: #f5d778;
        }

        .business-option label:hover {
            border-color: #d4af37;
        }

        .calculate-btn {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #d4af37, #f2d779);
            color: #111;
            border: none;
            border-radius: 9px;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        .calculate-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(212,175,55,0.2);
        }

        .result-box {
            margin-top: 25px;
            padding: 25px;
            background: #1b1b1b;
            border: 1px solid #55471f;
            border-radius: 12px;
            text-align: center;
        }

        .result-box p {
            color: #aaa;
            font-size: 14px;
            margin-bottom: 12px;
        }

        .result-box h2 {
            color: #e5c76b;
            font-size: 32px;
        }

        .error-box {
            margin-top: 20px;
            padding: 15px;
            border: 1px solid #813838;
            background: #291818;
            color: #ff9999;
            border-radius: 9px;
            text-align: center;
        }

        .footer {
            text-align: center;
            color: #666;
            font-size: 12px;
            margin-top: 25px;
        }

        @media (max-width: 600px) {
            body {
                padding: 25px 14px;
            }

            .calculator {
                padding: 22px;
            }

            .header h1 {
                font-size: 23px;
            }

            .input-grid {
                grid-template-columns: 1fr;
            }

            .business-options {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

<div class="container">

    <div class="header">
        <div class="logo">₱</div>
        <h1>NEGOSYO<span> CALCULATOR</span> HELPER</h1>
        <p>A simple and reliable calculator for your everyday business.</p>
    </div>

    <div class="calculator">

        <form method="POST">

            <h3 class="section-title">1. Enter Your Numbers</h3>

            <div class="input-grid">

                <div class="field">
                    <label for="num1">First Number</label>
                    <input
                        type="number"
                        step="any"
                        name="num1"
                        id="num1"
                        placeholder="Enter first number"
                        required
                        value="<?php echo isset($_POST['num1']) ? htmlspecialchars($_POST['num1']) : ''; ?>"
                    >
                </div>

                <div class="field">
                    <label for="num2">Second Number</label>
                    <input
                        type="number"
                        step="any"
                        name="num2"
                        id="num2"
                        placeholder="Enter second number"
                        required
                        value="<?php echo isset($_POST['num2']) ? htmlspecialchars($_POST['num2']) : ''; ?>"
                    >
                </div>

            </div>

            <h3 class="section-title">2. Choose Operation</h3>

            <div class="field" style="margin-bottom: 28px;">

                <label for="operation">Select Operation</label>

                <select name="operation" id="operation" required>

                    <option value="">-- Choose Operation --</option>

                    <option value="add"
                    <?php echo (isset($_POST['operation']) && $_POST['operation'] == 'add') ? 'selected' : ''; ?>>
                        + Addition
                    </option>

                    <option value="subtract"
                    <?php echo (isset($_POST['operation']) && $_POST['operation'] == 'subtract') ? 'selected' : ''; ?>>
                        − Subtraction
                    </option>

                    <option value="multiply"
                    <?php echo (isset($_POST['operation']) && $_POST['operation'] == 'multiply') ? 'selected' : ''; ?>>
                        × Multiplication
                    </option>

                    <option value="divide"
                    <?php echo (isset($_POST['operation']) && $_POST['operation'] == 'divide') ? 'selected' : ''; ?>>
                        ÷ Division
                    </option>

                </select>

            </div>

            <h3 class="section-title">03. Select Business Type</h3>

            <div class="business-options">

                <div class="business-option">
                    <input
                        type="radio"
                        name="business"
                        id="palengke"
                        value="Palengke"
                        <?php echo (!isset($_POST['business']) || $_POST['business'] == 'Palengke') ? 'checked' : ''; ?>
                        required
                    >
                    <label for="palengke">Palengke</label>
                </div>

                <div class="business-option">
                    <input
                        type="radio"
                        name="business"
                        id="canteen"
                        value="Canteen"
                        <?php echo (isset($_POST['business']) && $_POST['business'] == 'Canteen') ? 'checked' : ''; ?>
                    >
                    <label for="canteen">Canteen</label>
                </div>

                <div class="business-option">
                    <input
                        type="radio"
                        name="business"
                        id="store"
                        value="Sari-sari Store"
                        <?php echo (isset($_POST['business']) && $_POST['business'] == 'Sari-sari Store') ? 'checked' : ''; ?>
                    >
                    <label for="store">Sari-sari Store</label>
                </div>

            </div>

            <button type="submit" class="calculate-btn">
                CALCULATE NOW →
            </button>

        </form>

        <?php if ($error != ""): ?>

            <div class="error-box">
                <?php echo htmlspecialchars($error); ?>
            </div>

        <?php endif; ?>

        <?php if ($result != ""): ?>

            <div class="result-box">
                <p><?php echo htmlspecialchars($message); ?></p>
                <h2><?php echo htmlspecialchars($result); ?></h2>
            </div>

        <?php endif; ?>

    </div>

    <div class="footer">
        NEGOSYO CALCULATOR HELPER
    </div>

</div>

</body>
</html>
