<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Numbers to Words Converter</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap');
        body {
            font-family: Roboto;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
            display: flex;
            /* justify-content: center;
            align-items: center;
            min-height: 100vh; */
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 100%;
        }
        h2 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 30px;
            /* font-weight: bold; */
        }
        form {
            text-align: center;
            margin-bottom: 30px;
        }
        label {
            font-size: 16px;
            color: #34495e;
        }
        input[type="text"] {
            width: 300px;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #bdc3c7;
            border-radius: 5px;
        }
        input[type="submit"] {
            background-color: #3498db;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        input[type="submit"]:hover {
            background-color: #2980b9;
        }
        .results {
            margin-top: 20px;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 5px;
            color: red;
        }
        .results h3 {
            color: black;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 style="color: Red">Numbers to Words Calculator</h2>
        <form action="NumToWords.php" method="POST">
            <label for="number">Please input your data (in Khmer Riel):</label><br>
            <input type="text" id="number" name="number" required placeholder="Enter amount in Riel"><br><br>
            <input type="submit" value="Convert">
        </form>

        <?php
        // Function to convert number to words in English
        function numberToWords($num) {
            $ones = array("", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eighteen", "Nineteen");
            $tens = array("", "", "Twenty", "Thirty", "Forty", "Fifty", "Sixty", "Seventy", "Eighty", "Ninety");
            $thousands = array("", "Thousand", "Million", "Billion");

            if ($num == 0) return "Zero";

            $words = "";
            $partCount = 0;

            while ($num > 0) {
                $currentPart = $num % 1000;
                if ($currentPart > 0) {
                    $words = convertPart($currentPart, $ones, $tens) . " " . $thousands[$partCount] . " " . $words;
                }
                $num = floor($num / 1000);
                $partCount++;
            }

            return trim($words);
        }

        function convertPart($num, $ones, $tens) {
            $result = "";

            if ($num >= 100) {
                $result .= $ones[floor($num / 100)] . " Hundred ";
                $num = $num % 100;
            }

            if ($num >= 20) {
                $result .= $tens[floor($num / 10)] . " ";
                $num = $num % 10;
            }

            if ($num > 0) {
                $result .= $ones[$num] . " ";
            }

            return trim($result);
        }

        // Convert number to Khmer words
        function numToKhmerWords($num) {
            $khmer_ones = array("សូន្យ", "មួយ", "ពីរ", "បី", "បួន", "ប្រាំ", "ប្រាំមួយ", "ប្រាំពីរ", "ប្រាំបី", "ប្រាំបួន");
            $khmer_tens = array("", "ដប់", "ម្ភៃ", "សាមសិប", "សែសិប", "ហាសិប", "ហុកសិប", "ចិតសិប", "ប៉ែតសិប", "កៅសិប");
            $khmer_hundred = "រយ";
            $khmer_thousand = "ពាន់";
            $khmer_Tenthousand = "មុឺន";
            $khmer_HundredThousand = "សែន";
            $khmer_Million = "លាន";

            if ($num == 0) return $khmer_ones[0];

            $words = "";
            
            // Handle millions (1,000,000+)
            if ($num >= 1000000) {
                $million_part = floor($num / 1000000);
                $words .= ($million_part == 1 ? "មួយ" : $khmer_ones[$million_part]) . $khmer_Million;
                if ($num % 1000000 > 0) {
                    $words .= " ";
                }
                $num %= 1000000;
            }
            
            // Handle hundred thousands (100,000-999,999)
            if ($num >= 100000) {
                $hundred_thousand_part = floor($num / 100000);
                if ($hundred_thousand_part == 1) {
                    $words .= "ដប់" . $khmer_Tenthousand;
                } else if ($hundred_thousand_part < 10) {
                    $words .= $khmer_ones[$hundred_thousand_part] . $khmer_Tenthousand;
                } else {
                    // Handle cases like 200,000, 300,000, etc.
                    $words .= numToKhmerWords($hundred_thousand_part) . $khmer_Tenthousand;
                }
                if ($num % 100000 > 0) {
                    $words .= " ";
                }
                $num %= 100000;
            }
            
            if ($num >= 10000) {
                $tenThousand_part = floor($num / 10000);
                if ($tenThousand_part == 1) {
                    $words .= "មួយ" . $khmer_Tenthousand;
                } else {
                    $words .= $khmer_ones[$tenThousand_part] . $khmer_Tenthousand;
                }
                if ($num % 10000 > 0) {
                    $words .= " ";
                }
                $num %= 10000;
            }

            if ($num >= 1000) {
                $thousand_part = floor($num / 1000);
                $words .= ($thousand_part == 1 ? "មួយ" : $khmer_ones[$thousand_part]) . $khmer_thousand;
                if ($num % 1000 > 0) {
                    $words .= " ";
                }
                $num %= 1000;
            }

            if ($num >= 100) {
                $hundred_part = floor($num / 100);
                $words .= ($hundred_part == 1 ? "មួយ" : $khmer_ones[$hundred_part]) . $khmer_hundred;
                if ($num % 100 > 0) {
                    $words .= " ";
                }
                $num %= 100;
            }

            if ($num >= 10) {
                $tens_part = floor($num / 10);
                if ($tens_part == 1) {
                    if ($num == 10) {
                        $words .= "ដប់";
                    } else {
                        $words .= "ដប់" . $khmer_ones[$num % 10];
                    }
                } else {
                    $words .= $khmer_tens[$tens_part] . " ";
                    if ($num % 10 != 0) {
                        $words .= $khmer_ones[$num % 10];
                    }
                }
            } else if ($num > 0) {
                $words .= $khmer_ones[$num];
            }

            return trim($words) . " រៀល";
        }

        function saveToTextFile($data) {
            $file = fopen("calculations.txt", "a");
            fwrite($file, $data . "\n");
            fclose($file);
        }

        // Validate and process the form data
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $number = $_POST['number'];

            // Validate if the input is a valid number
            if (!is_numeric($number)) {
                echo "<div class='results'><p style='color: red;'>Please enter a valid number.</p></div>";
            } else {
                $number = intval($number);

                // Convert number to English words
                $englishWords = numberToWords($number) . " Riel";

                // Convert number to Khmer words
                $khmerWords = numToKhmerWords($number);

                // Convert number to USD
                $usd = number_format($number / 4000, 2);

                // Display results
                echo "<div class='results'>";
                echo "<h3>Results:</h3>";
                echo "<p>a. " . $englishWords . "</p>";
                echo "<p>b. " . $khmerWords . "</p>";
                echo "<p>c. " . $usd . " USD</p>";
                echo "</div>";

                // Save to text file
                $dataToSave = "Input: $number, English: $englishWords, Khmer: $khmerWords, USD: $usd";
                saveToTextFile($dataToSave);
            }
        }

        
        ?>
    </div>
</body>
</html>
